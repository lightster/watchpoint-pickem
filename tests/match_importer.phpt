--TEST--
match importer from blizzard api
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

$db->query("BEGIN");
$db->query("DELETE FROM picks");
$db->query("DELETE FROM matches");

$match_importer = new MatchImporter($db);
$matches = $match_importer->import(0);

$num_matches = (int) $db->fetchOne("SELECT COUNT(*) FROM matches");

var_dump($matches > 0 && count($matches) == $num_matches);

// import again to make sure we skip ones that already exist
$match_importer->import(0);

$db->query("ROLLBACK");

?>
--EXPECTF--
bool(true)
