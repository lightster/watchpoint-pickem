<?php

class PoolUser extends Model
{
    protected static $table_name = 'pool_users';
    protected static $primary_key = 'pool_user_id';
    protected $data = [
        'pool_user_id' => Model::DEFAULT,
        'pool_id'      => null,
        'user_id'      => null,
        'created_at'   => Model::DEFAULT,
        'updated_at'   => Model::DEFAULT,
    ];

    public static function fetchAllByPoolId(int $pool_id): array
    {
        return self::fetchAllWhere("pool_id = $1", [$pool_id]);
    }

    public function getUser(): User
    {
        return User::find($this->getData('user_id'));
    }

    public function getDisplayName(): string
    {
        return $this->getUser()->getDisplayName();
    }
}
