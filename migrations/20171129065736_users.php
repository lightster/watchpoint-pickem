<?php

if ($rollback === true) {
    $app->db()->query("DROP TABLE users;");
    return true;
}

// create users table
$sql = <<<SQL
CREATE TABLE users (
    user_id serial PRIMARY KEY,
    bnet_account_id varchar NOT NULL,
    bnet_tag varchar NOT NULL,
    email varchar,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);
SQL;

$app->db()->query($sql);
