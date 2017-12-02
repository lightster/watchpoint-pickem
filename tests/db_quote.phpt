--TEST--
db quote escapes values for postgres
--FILE--
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Db('postgres://watchpoint:@localhost/watchpoint');
var_dump($db->quote('value'));
var_dump($db->quote(1));
var_dump($db->quote("you're"));

?>
--EXPECTF--
string(%d) "'value'"
string(%d) "'1'"
string(%d) "'you''re'"
