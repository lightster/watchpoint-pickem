<?php

$app = require_once __DIR__ . '/app.php';
$app->run();
$app->requireLogin();

$pool = Pool::findBySlug($matches['slug']);
if (!$pool) {
    $app->redirect('/pools');
}
$pool_user = $pool->getPoolUser($app->option('user'));

$app->set('pool', $pool->getData());
$app->set('matches', $pool_user->getMatches($_GET['w'] ?? 1));
echo $app->render(__DIR__. '/views/picks.php', __DIR__ . '/views/layout.php');
