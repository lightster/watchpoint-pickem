<?php

// Create matches view

if ($rollback === true) {
    $app->db()->query("DROP VIEW match_weeks");
    return true;
}

$sql = <<<SQL
CREATE VIEW match_weeks AS
SELECT
    match_id,
    date_trunc('week', game_time) :: date AS start_of_week,
    (date_trunc('week', game_time) + '6 days') :: date AS end_of_week,
    dense_rank() OVER (ORDER BY date_trunc('week', game_time)) AS week_number
FROM matches
SQL;

$app->db()->query($sql);
