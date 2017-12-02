--TEST--
db fetchOne()
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

var_dump($db->fetchOne("SELECT 1"));

$db->query("CREATE TEMPORARY TABLE t (id int)");

var_dump($db->fetchOne("SELECT * FROM t LIMIT 1"));

?>
--EXPECTF--
string(%d) "1"
NULL
