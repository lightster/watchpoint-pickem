<?php

// Create pools schema

if ($rollback === true) {
    $app->db()->query("DROP TABLE pools");
    return true;
}

$sql = <<<SQL
CREATE TABLE pools (
    pool_id serial PRIMARY KEY,
    user_id integer NOT NULL,
    slug varchar NOT NULL UNIQUE,
    title varchar NOT NULL,
    description varchar,
    is_featured boolean DEFAULT FALSE NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    CONSTRAINT pools_user_id_fkey FOREIGN KEY (user_id)
        REFERENCES users (user_id) ON UPDATE CASCADE ON DELETE RESTRICT
);
SQL;

$app->db()->query($sql);
