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

$app->set('pool', $pool);
echo $app->render(__DIR__. '/views/pool.php', __DIR__ . '/views/layout.php');
