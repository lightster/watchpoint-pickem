--TEST--
db quoteCol escapes postgres column
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

var_dump($db->quoteCol('id'));
var_dump($db->quoteCol('someColName'));

?>
--EXPECTF--
string(%d) ""id""
string(%d) ""someColName""
