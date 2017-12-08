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
        'logo'              => null,
        'created_at'        => Model::DEFAULT,
        'updated_at'        => Model::DEFAULT,
    ];

    public static function findByBlizzId(int $blizz_id): ?Team
    {
        return self::findWhere("blizz_id = $1", [$blizz_id]);
    }

    public function getColors(): array
    {
        $colors = $this->getData('colors');
        $colors_arr = str_getcsv(trim($colors, '{}'), ',', '"', '\\');

        return $colors_arr;
    }

    public function setLogo(string $logo_url)
    {
        $logo = file_get_contents($logo_url);
        if (!$logo) {
            throw new ModelException("Failed to retrieve logo '{$logo_url}'");
        }
        $this->setData(['logo' => $logo]);
    }
}
