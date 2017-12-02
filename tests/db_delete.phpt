--TEST--
db delete()
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

$db->query("CREATE TEMPORARY TABLE t (id serial, letter varchar)");
$db->query("INSERT INTO t (letter) VALUES ('a'), ('a'), ('b'), ('c'), ('d')");

$countLetter = function ($letter) use ($db) {
    return $db->fetchOne("SELECT COUNT(*) FROM t WHERE letter = $1", [$letter]);
};

// test without parameters
var_dump($countLetter('a'));
$db->delete('t', "letter = 'a'");
var_dump($countLetter('a'));

// test with parameters
var_dump($countLetter('b'));
$db->delete('t', "letter = $1", ['b']);
var_dump($countLetter('b'));

try {
    @$db->delete('t', ' ');
} catch (DbException $e) {
    var_dump($e->getMessage());
}

?>
--EXPECTF--
string(%d) "2"
string(%d) "0"
string(%d) "1"
string(%d) "0"
string(%d) "No $where condition passed when trying to delete from "t""
