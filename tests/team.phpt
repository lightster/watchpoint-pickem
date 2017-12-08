--TEST--
creating a new team
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

$db->query('BEGIN');

$team_colors = ['#ff0000', '#000000'];
$team = new Team([
    'blizz_id'          => 8912,
    'blizz_division_id' => 79,
    'division'          => 'Pacific',
    'name'              => 'Testing Fuel',
    'abbreviation'      => 'TF',
    'location'          => 'Los Angeles, CA',
    'handle'            => 'testing.1234',
    'colors'            => $team_colors,
]);

// test saving a logo
$team->setLogo(__DIR__ . '/logo.svg');
$team->save();

var_dump($team->getColors() === $team_colors);
var_dump($team->getData('logo') == file_get_contents(__DIR__ . '/logo.svg'));

$db->query('ROLLBACK');

?>
--EXPECTF--
bool(true)
bool(true)
