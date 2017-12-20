<?php

// Create match winners table

if ($rollback === true) {
    $app->db()->query("DROP TABLE match_winners");
    return true;
}

$sql = <<<SQL
CREATE TABLE match_winners (
    match_winner_id serial PRIMARY KEY,
    match_id integer NOT NULL,
    team_id integer NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    UNIQUE(match_id),
    CONSTRAINT match_winners_match_id_fkey FOREIGN KEY (match_id)
        REFERENCES matches (match_id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT match_winners_team_id_fkey FOREIGN KEY (team_id)
        REFERENCES teams (team_id) ON UPDATE CASCADE ON DELETE CASCADE
);
SQL;

$app->db()->query($sql);
