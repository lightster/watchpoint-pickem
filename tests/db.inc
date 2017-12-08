<?php

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Db('postgres://watchpoint:@localhost/watchpoint');
Model::setDb(function() use ($db) {
    return $db;
});

return $db;
