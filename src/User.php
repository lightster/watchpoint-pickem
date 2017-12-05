<?php

class User extends Model
{
    protected static $table_name = 'users';
    protected static $primary_key = 'user_id';
    protected $data = [
        'user_id'         => Model::DEFAULT,
        'bnet_account_id' => null,
        'bnet_tag'        => null,
        'email'           => null,
        'created_at'      => Model::DEFAULT,
        'updated_at'      => Model::DEFAULT,
    ];

    public static function findByBnetAccountId(string $bnet_account_id): ?User
    {
        $sql = <<<SQL
SELECT *
FROM users
WHERE bnet_account_id = $1
LIMIT 1
SQL;
        $row = self::db()->fetchRow($sql, [$bnet_account_id]);
        if (!$row) {
            return null;
        }

        $user = new self($row, false);

        return $user;
    }
}
