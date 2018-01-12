<h1><?= htmlspecialchars($pool['title']) ?></h1>
<?= htmlspecialchars($pool['description']) ?>
<p>
Share - <a href="<?= $pool_url ?>"><?= $pool_url ?></a>
</p>
<hr>

<h3>Members</h3>

<ul>
    <?php foreach ($members as $member): ?>
    <li><?= htmlspecialchars($member) ?></li>
    <?php endforeach ?>
</ul>

<h3>Leaderboard</h3>

<table class="table table-bordered">
    <thead class="thead-default">
        <tr>
            <th>Rank</th>
            <th>Member</th>
            <th>Score</th>
            <th>Record</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($leaderboard as $user_score): ?>
        <tr>
            <td><?= $user_score['lb_rank'] ?: '-' ?></td>
            <td><?= $user_score['user_display_name'] ?></td>
            <td><?= $user_score['score'] ?: '-' ?></td>
            <?php if ($user_score['score'] !== null): ?>
            <td><?= $user_score['score'] ?>-<?= $user_score['total'] ?></td>
            <?php else: ?>
            <td>-</td>
            <?php endif ?>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?php if ($user_has_joined): ?>

<h3>Picks</h3>

<a href="/pools/<?= $pool['slug'] ?>/picks">Make your picks</a>

<?php else: ?>

<form action="/pools/<?= $pool['slug'] ?>" method="post" class="mb-2">
    <input type="submit" name="join" class="btn btn-primary" value="Join">
</form>

<?php endif ?>
