<?php

$app = require_once __DIR__ . '/app.php';
$app->run();
$app->requireLogin();

if ($_POST) {
    $pool = Pool::create([
        'user_id'     => $app->option('user')->getId(),
        'title'       => $_POST['title'],
        'description' => $_POST['description'],
    ]);
    $app->flash("Pool created");
    $app->redirect('/pools/' . $pool->getData('slug'));
}

echo $app->render(__DIR__. '/views/pools.php', __DIR__ . '/views/layout.php');
