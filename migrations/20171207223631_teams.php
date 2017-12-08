<?php

// Create teams schema

if ($rollback === true) {
    $app->db()->query("DROP TABLE teams");
    return true;
}

$sql = <<<SQL
CREATE TABLE teams (
    team_id serial PRIMARY KEY,
    blizz_id integer NOT NULL UNIQUE,
    blizz_division_id integer NOT NULL,
    division varchar NOT NULL,
    name varchar NOT NULL,
    abbreviation varchar NOT NULL,
    location varchar NOT NULL,
    handle varchar NOT NULL,
    colors varchar[] NOT NULL,
    logo varchar NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);
SQL;

$app->db()->query($sql);
