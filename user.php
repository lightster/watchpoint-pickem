<?php

$app = require_once __DIR__ . '/app.php';
$app->run();

$user = User::find($_SESSION['user']);

$app->set('btag', $user->getData('bnet_tag'));
echo $app->render(__DIR__. '/views/user.php', __DIR__ . '/views/layout.php');
