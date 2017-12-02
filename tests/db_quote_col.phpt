--TEST--
db quoteCol escapes postgres column
--FILE--
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Db('postgres://watchpoint:@localhost/watchpoint');
var_dump($db->quoteCol('id'));
var_dump($db->quoteCol('someColName'));

?>
--EXPECTF--
string(%d) ""id""
string(%d) ""someColName""
