<?php

$app = require_once __DIR__ . '/app.php';
$app->run();
$app->requireLogin();

echo $app->render(__DIR__. '/views/user_home.php', __DIR__ . '/views/layout.php');
