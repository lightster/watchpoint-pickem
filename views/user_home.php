<a href="/pools" class="btn btn-secondary">New Pool</a>
<hr>
<?php foreach($pools as $pool): ?>
<li><a href="/pools/<?= $pool->getData('slug') ?>">
    <?= htmlspecialchars($pool->getData('title')) ?></a>
    <small class="text-muted"><?= htmlspecialchars($pool->getData('description')) ?></small>
</li>
<?php endforeach ?>
</ul>
