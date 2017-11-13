<?php

$app = require_once __DIR__ . '/app.php';

$user_id = $matches[1] ?? 'fail';
?>

<?php include __DIR__ . '/header.php'; ?>

Hello user <?= $user_id ?>

<?php include __DIR__ . '/footer.php'; ?>
