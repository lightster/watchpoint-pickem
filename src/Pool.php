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

    public static function generateSlug(): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($chars, 6)), 0, 9);
    }

    public static function findBySlug(string $slug): ?Pool
    {
        return self::findWhere("slug = $1", [$slug]);
    }
}
