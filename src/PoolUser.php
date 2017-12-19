<?php

class PoolUser extends Model
{
    protected static $table_name = 'pool_users';
    protected static $primary_key = 'pool_user_id';
    protected $data = [
        'pool_user_id' => Model::DEFAULT,
        'pool_id'      => null,
        'user_id'      => null,
        'created_at'   => Model::DEFAULT,
        'updated_at'   => Model::DEFAULT,
    ];

    public static function fetchAllByPoolId(int $pool_id): array
    {
        return self::fetchAllWhere("pool_id = $1", [$pool_id]);
    }

    public static function findByPoolIdUserId(int $pool_id, int $user_id): ?PoolUser
    {
        return self::findWhere("pool_id = $1 AND user_id = $2", [$pool_id, $user_id]);
    }

    public function getUser(): User
    {
        return User::find($this->getData('user_id'));
    }

    public function getDisplayName(): string
    {
        return $this->getUser()->getDisplayName();
    }

    public function getMatches(int $week_number): array
    {
        $sql = <<<SQL
SELECT
    m.match_id,
    m.game_time AS match_time,
    a.team_id AS away_team_id,
    a.name AS away_team_name,
    a.abbreviation AS away_team_abbr,
    h.team_id AS home_team_id,
    h.name AS home_team_name,
    h.abbreviation AS home_team_abbr,
    p.team_id AS pick_team_id
FROM matches m
JOIN teams a ON a.team_id = m.away_team_id
JOIN teams h ON h.team_id = m.home_team_id
LEFT JOIN picks p ON p.match_id = m.match_id AND p.pool_user_id = $1
WHERE m.match_id IN (
    SELECT match_id
    FROM match_weeks
    WHERE week_number = $2
)
ORDER BY m.game_time
SQL;
        $res = $this->db()->query($sql, [$this->getId(), $week_number]);
        $matches = [];
        while ($row = $res->fetchRow()) {
            $matches[] = $row;
        }

        return $matches;
    }

    public function pick(int $match_id, int $team_id): Pick
    {
        $sql = <<<SQL
INSERT INTO picks (pool_user_id, match_id, team_id) VALUES ($1, $2, $3)
ON CONFLICT (pool_user_id, match_id)
DO UPDATE SET team_id = $3, updated_at = now()
RETURNING *
SQL;
        $row = $this->db()->fetchRow($sql, [$this->getId(), $match_id, $team_id]);
        $pick = new Pick($row, false);

        return $pick;
    }
}
