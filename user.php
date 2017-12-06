<?php

$app = require_once __DIR__ . '/app.php';
$app->run();
$app->requireLogin();

$user = $app->option('user');

if (isset($_POST['email'])) {
    $user->setData(['email' => $_POST['email']]);
    $user->save();
    $app->redirect('/user');
}

echo $app->render(__DIR__. '/views/user.php', __DIR__ . '/views/layout.php');
