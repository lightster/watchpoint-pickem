<?php

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

if (route('#^/user/(\d+)$#', $matches)) {
    include __DIR__ . '/../user.php';
    exit(0);
}

if (route('#^/$#')) {
    include __DIR__ . '/../home.php';
    exit(0);
}

include __DIR__ . '/../not_found.php';
exit(0);
