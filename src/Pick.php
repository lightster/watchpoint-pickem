<?php

class Pick extends Model
{
    protected static $table_name = 'picks';
    protected static $primary_key = 'pick_id';
    protected $data = [
        'pick_id'      => Model::DEFAULT,
        'pool_user_id' => null,
        'match_id'     => null,
        'team_id'      => null,
        'created_at'   => Model::DEFAULT,
        'updated_at'   => Model::DEFAULT,
    ];

    public static function fetchMatches(PoolUser $pool_user, int $week_number): array
    {
        $sql = <<<SQL
SELECT
    game_time AS match_time,
    a.team_id AS away_team_id,
    a.name AS away_team_name,
    h.team_id AS home_team_id,
    h.name AS home_team_name,
    p.team_id AS pick_team_id
FROM matches m
LEFT JOIN picks p ON p.match_id = m.match_id AND p.pool_user_id = $1
WHERE match_id IN (
    SELECT match_id
    FROM match_weeks
    WHERE week_number = $2
)
ORDER BY game_time
SQL;
    }
}
