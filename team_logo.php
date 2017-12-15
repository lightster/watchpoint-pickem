<?php

$app = require_once 'app.php';
$app->run();

$team = Team::find($matches['team_id']);
if (!$team) {
    http_response_code(404);
    exit;
}

header("Content-Type: image/svg+xml");
echo $team->getData('logo');
