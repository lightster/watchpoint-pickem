<?php

// Create matches schema

if ($rollback === true) {
    $app->db()->query("DROP TABLE matches");
    return true;
}

$sql = <<<SQL
CREATE TABLE matches (
    match_id serial PRIMARY KEY,
    blizz_id integer NOT NULL UNIQUE,
    away_team_id integer NOT NULL,
    home_team_id integer NOT NULL,
    game_time timestamp with time zone NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    CONSTRAINT matches_away_team_id_fkey FOREIGN KEY (away_team_id)
        REFERENCES teams (team_id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT matches_home_team_id_fkey FOREIGN KEY (home_team_id)
        REFERENCES teams (team_id) ON UPDATE CASCADE ON DELETE RESTRICT
);
SQL;

$app->db()->query($sql);
