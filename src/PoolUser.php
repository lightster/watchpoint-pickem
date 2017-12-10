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
}
