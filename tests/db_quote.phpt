--TEST--
db quote escapes values for postgres
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

var_dump($db->quote('value'));
var_dump($db->quote(1));
var_dump($db->quote("you're"));
var_dump($db->quote(null));
var_dump($db->quote(false));
var_dump($db->quote(['a', 'b']));

?>
--EXPECTF--
string(%d) "'value'"
string(%d) "1"
string(%d) "'you''re'"
string(%d) "NULL"
string(%d) "FALSE"
string(%d) "ARRAY['a', 'b']"
