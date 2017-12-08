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
            $blizz_match_id = (int) $match_data['id'];
            if (Match::findByBlizzId($blizz_match_id)) {
                continue;
            }
            $away_team = Team::findByBlizzId((int) $match_data['competitors'][0]['id']);
            $home_team = Team::findByBlizzId((int) $match_data['competitors'][1]['id']);
            if (!$away_team || !$home_team) {
                continue;
            }
            $match = Match::create([
                'blizz_id'     => $blizz_match_id,
                'away_team_id' => $away_team->getId(),
                'home_team_id' => $home_team->getId(),
                'game_time'    => $match_data['endDate'],
            ]);
            $matches[] = $match;
        }

        return $matches;
    }
}

