--TEST--
db exists()
--FILE--
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Db('postgres://watchpoint:@localhost/watchpoint');
var_dump($db->exists('SELECT 1'));
var_dump($db->exists('SELECT NULL'));

$db->query("CREATE TEMPORARY TABLE t (num int)");
var_dump($db->exists("SELECT * FROM t WHERE num = 1"));

?>
--EXPECTF--
bool(true)
bool(true)
bool(false)
