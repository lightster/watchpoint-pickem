<?php

class Model
{
    /**
     * Default a columns value to whatever the default is in postgres.
     * Essentially this will exclude the column when doing a db->insert()
     */
    const DEFAULT = 'PG-DEFAULT';

    private static $db;
    private static $db_loaded = false;

    protected $data = [];
    private $changed = [];

    public static function setDb(Closure $db)
    {
        self::$db = $db;
    }

    protected static function db(): Db
    {
        if (self::$db_loaded === true) {
            return self::$db;
        }

        self::$db = call_user_func(self::$db);
        self::$db_loaded = true;

        return self::$db;
    }

    public static function create(array $data)
    {
        $model = new static($data);
        $model->save();

        return $model;
    }

    public static function find($id): ?Model
    {
        $sql = <<<SQL
SELECT *
FROM %s
WHERE %s = $1
LIMIT 1
SQL;
        $sql = sprintf($sql, static::$table_name, static::$primary_key);
        $row = self::db()->fetchRow($sql, [$id]);
        if (!$row) {
            return null;
        }
        $model = new static($row, false);

        return $model;
    }

    public static function findWhere(string $where, array $params = []): ?Model
    {
        if (empty(trim($where))) {
            throw new ModelException(sprintf(
                "No \$where condition passed when trying to findWhere '%s'",
                static::$table_name
            ));
        }

        $sql = <<<SQL
SELECT *
FROM %s
WHERE %s
LIMIT 1
SQL;
        $sql = sprintf($sql, static::$table_name, $where);
        $row = self::db()->fetchRow($sql, $params);
        if (!$row) {
            return null;
        }
        $model = new static($row, false);

        return $model;
    }

    public function __construct(array $data = [], $dirty = true)
    {
        $this->setData($data);
        if ($dirty === false) {
            $this->changed = [];
        }
    }

    public function getId()
    {
        return $this->getData(static::$primary_key);
    }

    public function getData($field = '')
    {
        if ($field !== '') {
            return $this->data[$field];
        }

        return $this->data;
    }

    public function setData(array $data = [])
    {
        foreach ($data as $field => $value) {
            if (array_key_exists($field, $this->data)) {
                $this->data[$field] = $value;
                $this->changed[$field] = true;
            }
        }
    }

    public function save()
    {
        if ($this->getId() !== Model::DEFAULT) {
            return $this->update();
        }

        return $this->insert();
    }

    public function delete()
    {
        $this->db()->delete(
            static::$table_name,
            $this->db()->quoteCol(static::$primary_key) . ' = $1',
            [$this->getId()]
        );
        $this->setData([static::$primary_key => self::DEFAULT]);
    }

    private function update()
    {
        if (empty($this->changed)) {
            return;
        }

        if (is_callable([$this, 'beforeUpdate'])) {
            $this->beforeUpdate();
        }

        // pre-update hook to set the updated_at column
        // this is magical, but I think it might be better than
        // adding it to every Model
        $this->setData([
            'updated_at' => new DbExpr('now()'),
        ]);
        $changed_data = array_intersect_key($this->data, $this->changed);
        $row = $this->db()->update(
            static::$table_name,
            $changed_data,
            $this->db()->quoteCol(static::$primary_key) . ' = $1',
            [$this->getId()]
        );
        $this->setData($row);
        $this->changed = [];

        if (is_callable([$this, 'afterUpdate'])) {
            $this->afterUpdate();
        }
    }

    private function insert()
    {
        if (is_callable([$this, 'beforeCreate'])) {
            $this->beforeCreate();
        }

        $data = array_filter($this->getData(), function($val) {
            return $val !== Model::DEFAULT;
        });
        $row = $this->db()->insert(static::$table_name, $data);
        $this->setData($row);
        $this->changed = [];

        if (is_callable([$this, 'afterCreate'])) {
            $this->afterCreate();
        }
    }
}
