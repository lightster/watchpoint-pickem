--TEST--
pools model
--FILE--
<?php

$db = require_once __DIR__ . '/db.inc';

$db->query('BEGIN');

$test_user = User::create([
    'bnet_account_id' => 1234,
    'bnet_tag'        => 'test#1234',
]);
$test_user2 = User::create([
    'bnet_account_id' => 5678,
    'bnet_tag'        => 'test#5678',
]);

$pool = Pool::create([
    'user_id' => $test_user->getId(),
    'title'   => 'Testing Pool',
]);
$pool_user = $pool->join($test_user2);

var_dump($pool_user->getData('user_id') == $test_user2->getId());
var_dump($pool_user->getData('pool_id') == $pool->getId());

var_dump(count($pool->getUsers()));

var_dump($db->fetchOne("SELECT COUNT(*) FROM pool_users WHERE pool_id = $1", [$pool->getId()]));

$db->query('ROLLBACK');

?>
--EXPECTF--
bool(true)
bool(true)
int(2)
string(%d) "2"
