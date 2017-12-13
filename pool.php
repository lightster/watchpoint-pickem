<?php

$app = require_once __DIR__ . '/app.php';
$app->run();
$app->requireLogin();

$pool = Pool::findBySlug($matches['slug']);
if (!$pool) {
    $app->redirect('/pools');
}

if (isset($_POST['join'])) {
    $pool->join($app->option('user'));
    $app->flash(sprintf("You have joined %s", $pool->getData('title')), 0);
    $app->redirect('/pools/' . $pool->getData('slug'));
}

$scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'https';
$host = $_SERVER['HTTP_HOST'];
$pool_url = "{$scheme}://{$host}/pools/{$pool->getData('slug')}";

$app->set('pool_url', $pool_url);
$app->set('pool', $pool->getData());
$app->set('user_has_joined', $pool->userHasJoined($app->option('user')));

$members = array_map(function (PoolUser $pool_user) {
    return $pool_user->getDisplayName();
}, $pool->getUsers());
sort($members);
$app->set('members', $members);
echo $app->render(__DIR__. '/views/pool.php', __DIR__ . '/views/layout.php');
