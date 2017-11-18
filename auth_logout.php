<?php

$app = require_once 'app.php';
$app->run(function () {
    $this->option('session_read_only', false);
});

unset($_SESSION['user']);
$app->redirect('/');
