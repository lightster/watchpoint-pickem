<?php

class Model
{
    const DEFAULT = 0;

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

    public static function find($id)
    {
        $sql = <<<SQL
SELECT *
FROM %s
WHERE %s = $1
LIMIT 1
SQL;
        $sql = sprintf($sql, static::$table_name, static::$primary_key);
        $row = $this->db()->fetchRow($sql, [$id]);
        if (!$row) {
            return false;
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
        if ($this->getData(static::$primary_key) !== Model::DEFAULT) {
            // update
            $changed_data = array_intersect_key($this->data, $this->changed);
            if (empty($changed_data)) {
                return;
            }
            $row = $this->db()->update(
                static::$table_name,
                $changed_data,
                $this->db()->quoteCol(static::$primary_key) . ' = $1',
                [$this->getId()]
            );
        } else {
            // insert
            $data = array_filter($this->getData(), function($val) {
                return $val !== Model::DEFAULT;
            });
            $row = $this->db()->insert(static::$table_name, $data);
        }

        if ($row === false) {
            throw new \Exception('Failed to save model');
        }

        $this->setData($row);
        $this->changed = [];
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
}
