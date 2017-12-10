<?= htmlspecialchars($pool->getData('title')) ?><br>
Description: <?= htmlspecialchars($pool->getData('description')) ?>

<form action="/pools/<?= $pool->getData('slug') ?>" method="post">
    <input type="submit" name="join" class="btn btn-primary" value="Join">
</form>
