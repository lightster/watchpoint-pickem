<?php

// Create pool_users schema

if ($rollback === true) {
    $app->db()->query("DROP TABLE pool_users");
    return true;
}

$sql = <<<SQL
CREATE TABLE pool_users (
    pool_user_id serial PRIMARY KEY,
    pool_id integer NOT NULL,
    user_id integer NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    UNIQUE(pool_id, user_id),
    CONSTRAINT pool_users_pool_id_fkey FOREIGN KEY (pool_id)
        REFERENCES pools (pool_id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT pool_users_user_id_fkey FOREIGN KEY (user_id)
        REFERENCES users (user_id) ON UPDATE CASCADE ON DELETE CASCADE
);
SQL;

$app->db()->query($sql);
