<?php

$app = require_once __DIR__ . '/app.php';
$app->run();

$app->set('btag', $_SESSION['user']['battletag']);
echo $app->render(__DIR__. '/views/user.php', __DIR__ . '/views/layout.php');
