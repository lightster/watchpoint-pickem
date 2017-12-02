--TEST--
db exists()
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

var_dump($db->exists('SELECT 1'));
var_dump($db->exists('SELECT NULL'));

$db->query("CREATE TEMPORARY TABLE t (num int)");
var_dump($db->exists("SELECT * FROM t WHERE num = 1"));
$db->query("INSERT INTO t(num) VALUES (1), (2), (3);");
var_dump($db->exists("SELECT * FROM t WHERE num = 2"));

?>
--EXPECTF--
bool(true)
bool(true)
bool(false)
bool(true)
