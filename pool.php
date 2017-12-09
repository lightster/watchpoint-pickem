<?php

$app = require_once __DIR__ . '/app.php';
$app->run();
$app->requireLogin();

$pool = Pool::findBySlug($matches['slug']);
if (!$pool) {
    $app->redirect('/pools');
}

$app->set('pool', $pool);
echo $app->render(__DIR__. '/views/pool.php', __DIR__ . '/views/layout.php');
