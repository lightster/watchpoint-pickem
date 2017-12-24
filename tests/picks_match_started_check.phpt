--TEST--
picks matches started check works
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

$db->query('BEGIN');

$user = User::create([
    'bnet_account_id' => 1234,
    'bnet_tag'        => 'test#1234',
]);
$pool = Pool::create([
    'user_id' => $user->getId(),
    'title'   => 'Testing Pool',
]);

$team = Team::create([
    'blizz_id'          => 999999,
    'blizz_division_id' => 1234,
    'division'          => 'Testing',
    'name'              => 'Testing',
    'abbreviation'      => 'Testing',
    'location'          => 'CA',
    'handle'            => 'team#win',
    'colors'            => ['#000000', '#000000'],
    'logo'              => '<svg>',
]);
$team2 = Team::create([
    'blizz_id'          => 999998,
    'blizz_division_id' => 1234,
    'division'          => 'Testing',
    'name'              => 'Testing 2',
    'abbreviation'      => 'Testing2',
    'location'          => 'CA',
    'handle'            => 'team#win',
    'colors'            => ['#000000', '#000000'],
    'logo'              => '<svg>',
]);
$match = Match::create([
    'blizz_id'     => 999999,
    'away_team_id' => $team->getId(),
    'home_team_id' => $team2->getId(),
    'game_time'    => new DbExpr("now()"),
]);

$match2 = Match::create([
    'blizz_id'     => 999998,
    'away_team_id' => $team->getId(),
    'home_team_id' => $team2->getId(),
    'game_time'    => new DbExpr("now() + '1 day'"),
]);

$pool_user = $pool->getPoolUser($user);

$db->query('SAVEPOINT fail_pick');
try {
    @$pool_user->pick($match->getId(), $team->getId());
} catch (DbException $e) {
    var_dump((bool) preg_match("/picks_match_started_check/", $e->getLastError()));
}
$db->query("ROLLBACK TO SAVEPOINT fail_pick;");

var_dump(get_class($pool_user->pick($match2->getId(), $team->getId())));

// test updating with data check
$match2->setData(['game_time' => new DbExpr("now() - INTERVAL '1 day'")]);
$match2->save();

$db->query('SAVEPOINT fail_update_pick;');
try {
    @$pool_user->pick($match2->getId(), $team2->getId());
} catch (DbException $e) {
    var_dump((bool) preg_match("/picks_match_started_check/", $e->getLastError()));
}
$db->query("ROLLBACK TO SAVEPOINT fail_update_pick;");

$db->query('ROLLBACK');

?>
--EXPECTF--
bool(true)
string(%d) "Pick"
bool(true)
