<?php

class Pool extends Model
{
    protected static $table_name = 'pools';
    protected static $primary_key = 'pool_id';
    protected $data = [
        'pool_id'     => Model::DEFAULT,
        'user_id'     => null,
        'slug'        => null,
        'title'       => null,
        'description' => null,
        'is_featured' => Model::DEFAULT,
        'created_at'  => Model::DEFAULT,
        'updated_at'  => Model::DEFAULT,
    ];

    public static function findBySlug(string $slug): ?Pool
    {
        return self::findWhere("slug = $1", [$slug]);
    }

    public static function fetchAllByUser(User $user): array
    {
        $sql = <<<SQL
SELECT *
FROM pools
WHERE pool_id IN (
    SELECT pool_id
    FROM pool_users
    WHERE user_id = $1
)
SQL;
        $res = self::db()->query($sql, [$user->getId()]);
        $pools = [];
        while ($row = $res->fetchRow()) {
            $pools[] = new Pool($row, false);
        }

        return $pools;
    }

    public function getUser(): User
    {
        return User::find($this->getData('user_id'));
    }

    public function join(User $user): PoolUser
    {
        $pool_user = PoolUser::create([
            'pool_id' => $this->getId(),
            'user_id' => $user->getId(),
        ]);

        return $pool_user;
    }

    protected function beforeCreate()
    {
        $this->setData(['slug' => $this->generateSlug()]);
    }

    protected function afterCreate()
    {
        $this->join($this->getUser());
    }

    private function generateSlug(): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($chars, 6)), 0, 9);
    }
}
