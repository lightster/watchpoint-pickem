<?php

$app = require_once 'app.php';
$app->run(function () {
    $this->option('session_read_only', false);
});

// Already logged in
if (!empty($_SESSION['user'])) {
    $app->redirect('/user');
}

$scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'https';
$host = $_SERVER['HTTP_HOST'];
$redirect_uri = "{$scheme}://{$host}/auth";

$bnet_auth = new BnetAuth([
    'key'          => getenv('BNET_API_KEY'),
    'secret'       => getenv('BNET_API_SECRET'),
    'redirect_uri' => $redirect_uri,
    'code'         => $_GET['code'] ?? '',
    'state'        => $_GET['state'] ?? '',
    'auth_handler' => function($auth_url) use ($app) {
        return $app->redirect($auth_url);
    },
]);

try {
    $user = $bnet_auth->getUser();
    $_SESSION['user'] = $user;
    $app->redirect('/user');
} catch (BnetAuthException $e) {
    $app->redirect('/auth/error');
}
