<?php

class TeamImporter
{
    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function import(): array
    {
        $teams_api = 'https://overwatchleague.com/en-us/api/teams';
        $data = file_get_contents($teams_api);
        if (!$data) {
            throw new Exception("Failed to read data from '{$teams_api}'");
        }
        $data = json_decode($data, true);
        $teams_data = $data['data'];
        $meta = $data['meta'];
        $teams = [];
        foreach ($teams_data as $team_data) {
            $blizz_id = $team_data['id'];
            if (Team::findByBlizzId($blizz_id)) {
                continue;
            }
            $division_id = $team_data['divisionId'];
            $division_str = $meta['divisions'][$division_id];
            $division = $meta['strings'][$division_str];
            $team = new Team([
                'blizz_id'          => $blizz_id,
                'blizz_division_id' => $division_id,
                'division'          => $division,
                'name'              => $team_data['name'],
                'abbreviation'      => $team_data['abbreviation'],
                'location'          => $team_data['location'],
                'handle'            => $team_data['handle'],
                'colors'            => [
                    $team_data['colors']['primary'],
                    $team_data['colors']['secondary'],
                ],
            ]);
            $team->setLogo($team_data['logo']);
            $team->save();
            $teams[] = $team;
        }

        return $teams;
    }
}
