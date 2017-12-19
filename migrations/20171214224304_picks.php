<?php

// Create picks schema

if ($rollback === true) {
    $app->db()->query("DROP TABLE picks");
    return true;
}

$sql = <<<SQL
CREATE TABLE picks (
    pick_id serial PRIMARY KEY,
    pool_user_id integer NOT NULL,
    match_id integer NOT NULL,
    team_id integer NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    UNIQUE (pool_user_id, match_id),
    CONSTRAINT picks_pool_user_id_fkey FOREIGN KEY (pool_user_id)
        REFERENCES pool_users (pool_user_id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT picks_match_id_fkey FOREIGN KEY (match_id)
        REFERENCES matches (match_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT picks_team_id_fkey FOREIGN KEY (team_id)
        REFERENCES teams (team_id) ON UPDATE CASCADE ON DELETE RESTRICT
);
SQL;

$app->db()->query($sql);
