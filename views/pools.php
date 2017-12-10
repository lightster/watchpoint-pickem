<main role="main">
<div class="container">
<div class="row">
<div class="col-6">

<h3>Create Pool</h3>

<form action="/pools" method="post">
    <div class="form-group">
        <input type="text" class="form-control" placeholder="Super awesome pool" name="title">
        <textarea name="description" class="form-control"></textarea>
    </div>
    <input type="submit" class="btn btn-primary" value="Create Pool">
</form>

<ul>
<?php foreach($pools as $pool): ?>
<li><a href="/pools/<?= $pool->getData('slug') ?>">
    <?= htmlspecialchars($pool->getData('title')) ?></a>
</li>
<?php endforeach ?>
</ul>

</div>
</div>
</div>
</main>