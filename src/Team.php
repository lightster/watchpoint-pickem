<?php

class Team extends Model
{
    protected static $table_name = 'teams';
    protected static $primary_key = 'team_id';
    protected $data = [
        'team_id'           => Model::DEFAULT,
        'blizz_id'          => null,
        'blizz_division_id' => null,
        'division'          => null,
        'name'              => null,
        'abbreviation'      => null,
        'location'          => null,
        'handle'            => null,
        'colors'            => null,
        'created_at'        => Model::DEFAULT,
        'updated_at'        => Model::DEFAULT,
    ];

    public function getColors(): array
    {
        $colors = $this->getData('colors');
        $colors_arr = str_getcsv(trim($colors, '{}'), ',', '"', '\\');

        return $colors_arr;
    }

    public function getLogoPath(): string
    {
        return __DIR__ . '/../public/img/teams/' . $this->getId() . '.svg';
    }

    public function saveLogo(string $logo_url)
    {
        $cp = copy($logo_url, $this->getLogoPath());
        if (!$cp) {
            throw new Exception("Failed to save logo '{$logo_url}'");
        }
    }
}
