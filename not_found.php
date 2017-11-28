<?php

$app = require_once __DIR__ . '/app.php';
$app->run();

echo $app->render(__DIR__ . '/views/not_found.php', __DIR__ . '/views/layout.php');
