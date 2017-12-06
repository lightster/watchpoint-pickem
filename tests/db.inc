<?php

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Db('postgres://watchpoint:@localhost/watchpoint');

return $db;
