--TEST--
creating a new team
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

$db->query('BEGIN');

$team_colors = ['#ff0000', '#000000'];
$team = Team::create([
    'blizz_id'          => 8912,
    'blizz_division_id' => 79,
    'division'          => 'Pacific',
    'name'              => 'Testing Fuel',
    'abbreviation'      => 'TF',
    'location'          => 'Los Angeles, CA',
    'handle'            => 'testing.1234',
    'colors'            => $team_colors,
]);
var_dump($team->getColors() === $team_colors);

// test saving a logo
$team->saveLogo(__DIR__ . '/logo.svg');
var_dump(file_exists($team->getLogoPath()));

unlink($team->getLogoPath());
$db->query('ROLLBACK');

?>
--EXPECTF--
bool(true)
bool(true)
