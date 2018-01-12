<?php

class Pool extends Model
{
    protected static $table_name = 'pools';
    protected static $primary_key = 'pool_id';
    protected $data = [
        'pool_id'     => Model::DEFAULT,
        'user_id'     => null,
        'slug'        => null,
        'title'       => null,
        'description' => null,
        'is_featured' => Model::DEFAULT,
        'created_at'  => Model::DEFAULT,
        'updated_at'  => Model::DEFAULT,
    ];

    public static function findBySlug(string $slug): ?Pool
    {
        return self::findWhere("slug = $1", [$slug]);
    }

    public static function fetchAllByUser(User $user): array
    {
        $where = <<<SQL
pool_id IN (
    SELECT pool_id
    FROM pool_users
    WHERE user_id = $1
)
SQL;
        return self::fetchAllWhere($where, [$user->getId()]);
    }

    public function getUser(): User
    {
        return User::find($this->getData('user_id'));
    }

    public function join(User $user): PoolUser
    {
        $pool_user = PoolUser::create([
            'pool_id' => $this->getId(),
            'user_id' => $user->getId(),
        ]);

        return $pool_user;
    }

    public function userHasJoined(User $user): bool
    {
        $sql = <<<SQL
SELECT 1
FROM pool_users
WHERE pool_id = $1
    AND user_id = $2
SQL;
        return $this->db()->exists($sql, [$this->getId(), $user->getId()]);
    }

    public function getUsers(): array
    {
       return PoolUser::fetchAllByPoolId($this->getId());
    }

    public function getPoolUser(User $user): ?PoolUser
    {
        return PoolUser::findByPoolIdUserId($this->getId(), $user->getId());
    }

    public function getLeaderboard(): array
    {
        $sql = <<<SQL
WITH lboard AS (
    SELECT pool_user_id, COUNT(*) AS score
    FROM picks
    JOIN match_winners USING (match_id, team_id)
    GROUP BY pool_user_id
)
SELECT
    users.bnet_tag AS user_display_name,
    COALESCE(lboard.score, 0) AS score,
    (
        SELECT COUNT(*) AS total
        FROM match_winners
        JOIN matches USING (match_id)
        WHERE matches.game_time >= $2
    ) AS total
FROM pool_users
LEFT JOIN lboard USING (pool_user_id)
JOIN users USING (user_id)
WHERE pool_users.pool_user_id IN (
    SELECT pool_user_id
    FROM pool_users
    WHERE pool_id = $1
)
ORDER BY score DESC
SQL;
        $res = $this->db()->query($sql, [$this->getId(), $this->getData('created_at')]);
        $lboard = [];
        while ($row = $res->fetchRow()) {
            $lboard[] = $row;
        }

        return $lboard;
    }

    protected function beforeCreate()
    {
        $this->setData(['slug' => $this->generateSlug()]);
    }

    protected function afterCreate()
    {
        $this->join($this->getUser());
    }

    private function generateSlug(): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($chars, 6)), 0, 9);
    }
}
