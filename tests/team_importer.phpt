--TEST--
team importer from blizzard api
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

$db->query("BEGIN");
$db->query("DELETE FROM teams");

$team_imp = new TeamImporter($db);
$teams = $team_imp->import();

$num_teams = (int) $db->fetchOne("SELECT COUNT(*) FROM teams");
var_dump($num_teams == count($teams));

foreach($teams as $team) {
    unlink($team->getLogoPath());
}

$db->query("ROLLBACK");

?>
--EXPECTF--
bool(true)
