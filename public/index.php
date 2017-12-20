<?php

// ignore static files for PHP built-in webserver
if (PHP_SAPI == 'cli-server') {
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false;
    }
}

/**
 * Checks if the request uri matches a route regex pattern
 * Example: if (route('#/user/(\d+)#, $matches))
 * This strips trailing slashes
 */
function route(string $pattern, array &$matches = null)
{
    $req_uri = $_SERVER['REQUEST_URI'];
    if(strpos($req_uri, '?') !== false) {
        $req_uri = substr($req_uri, 0, strpos($req_uri, '?'));
    }
    $req_uri = rtrim($req_uri, '/') ?: '/';

    if (preg_match($pattern, $req_uri, $matches)) {
        return true;
    }

    return false;
}

if (route('#^/logo/(?P<team_id>.+).svg$#', $matches)) {
    include __DIR__ . '/../team_logo.php';
    exit(0);
}

if (route('#^/user$#')) {
    include __DIR__ . '/../user.php';
    exit(0);
}

if (route('#^/auth$#')) {
    include __DIR__ . '/../auth.php';
    exit(0);
}

if (route('#^/auth/logout$#')) {
    include __DIR__ . '/../auth_logout.php';
    exit(0);
}

if (route('#^/pools/(?P<slug>.+)/picks$#', $matches)) {
    include __DIR__ . '/../picks.php';
    exit(0);
}

if (route('#^/pools/(?P<slug>.+)$#', $matches)) {
    include __DIR__ . '/../pool.php';
    exit(0);
}

if (route('#^/pools$#')) {
    include __DIR__ . '/../pools.php';
    exit(0);
}

if (route('#^/home$#')) {
    include __DIR__ . '/../user_home.php';
    exit(0);
}

if (route('#^/$#')) {
    include __DIR__ . '/../home.php';
    exit(0);
}

include __DIR__ . '/../not_found.php';
exit(0);
