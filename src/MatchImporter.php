<?php

class MatchImporter
{
    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function import(int $stage): array
    {
        $matches_api = 'https://api.overwatchleague.com/schedule?locale=en_US';
        $data = file_get_contents($matches_api);
        if (!$data) {
            throw new Exception("Failed to read data from '{$matches_api}'");
        }
        $data = json_decode($data, true);
        $matches_data = $data['data']['stages'][$stage]['matches'] ?? [];
        $matches = [];
        foreach ($matches_data as $match_data) {
            $match = $this->importMatch($match_data);
            if (!$match) {
                continue;
            }

            $this->importWinner($match, $match_data);
            $matches[] = $match;
        }

        return $matches;
    }

    private function importMatch(array $match_data): ?Match
    {
        $blizz_match_id = (int) $match_data['id'];
        $match = Match::findByBlizzId($blizz_match_id);
        if ($match) {
            return $match;
        }

        $atm_blizz_id = (int) $match_data['competitors'][0]['id'];
        $htm_blizz_id = (int) $match_data['competitors'][1]['id'];

        if (!$atm_blizz_id || ! $htm_blizz_id) {
            return null;
        }

        $away_team = Team::findByBlizzId($atm_blizz_id);
        $home_team = Team::findByBlizzId($htm_blizz_id);
        $match = Match::create([
            'blizz_id'     => $blizz_match_id,
            'away_team_id' => $away_team->getId(),
            'home_team_id' => $home_team->getId(),
            'game_time'    => $match_data['endDate'],
        ]);

        return $match;
    }

    private function importWinner(Match $match, array $match_data)
    {
        if (empty($match_data['winner']['id'])) {
            return;
        }

        $winning_team = Team::findByBlizzId($match_data['winner']['id']);
        $sql = <<<SQL
INSERT INTO match_winners (match_id, team_id) VALUES ($1, $2)
ON CONFLICT (match_id)
DO UPDATE SET team_id = $2, updated_at = now()
RETURNING *
SQL;
        $this->db->query($sql, [$match->getId(), $winning_team->getId()]);
    }
}

