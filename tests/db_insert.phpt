--TEST--
db insert()
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

$db->query("CREATE TEMPORARY TABLE t (id serial, a varchar, b int, c bool)");
var_dump($db->insert('t', [
    'a' => 'hello',
    'b' => 2,
    'c' => true,
]));
var_dump($db->insert('t', [
    'b' => 999,
]));

?>
--EXPECTF--
array(%d) {
  ["id"]=>
  string(%d) "1"
  ["a"]=>
  string(%d) "hello"
  ["b"]=>
  string(%d) "2"
  ["c"]=>
  string(%d) "t"
}
array(%d) {
  ["id"]=>
  string(%d) "2"
  ["a"]=>
  NULL
  ["b"]=>
  string(%d) "999"
  ["c"]=>
  NULL
}
