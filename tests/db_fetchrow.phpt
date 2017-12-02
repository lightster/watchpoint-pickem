--TEST--
db fetchRow()
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

var_dump($db->fetchRow('SELECT 1 AS t'));

$db->query("CREATE TEMPORARY TABLE data (data_id int, data varchar);");
$db->query("INSERT INTO data VALUES (1, 'hello'), (2, 'world');");

var_dump($db->fetchRow("SELECT * FROM data WHERE data_id = 2 LIMIT 1"));

?>
--EXPECTF--
array(%d) {
  ["t"]=>
  string(%d) "1"
}
array(%d) {
  ["data_id"]=>
  string(%d) "2"
  ["data"]=>
  string(%d) "world"
}
