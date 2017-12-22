<?php

$app = require_once __DIR__ . '/app.php';
$app->run();
$app->requireLogin();

$pool = Pool::findBySlug($matches['slug']);
if (!$pool) {
    $app->redirect('/pools');
}
$pool_user = $pool->getPoolUser($app->option('user'));

if ($_POST) {
    if (empty($_POST['team_id']) || empty($_POST['match_id'])) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode('fail');
        exit(1);
    }

    $pool_user->pick($_POST['match_id'], $_POST['team_id']);
    header('Content-Type: application/json');
    echo json_encode('success');
    exit;
}

$week = $_GET['w'] ?? Match::getNearestWeek();

$app->set('selected_week', $week);
$app->set('number_of_weeks', Match::getNumberOfWeeks());
$app->set('pool', $pool->getData());
$app->set('matches', $pool_user->getMatches($week));
echo $app->render(__DIR__. '/views/picks.php', __DIR__ . '/views/layout.php');
