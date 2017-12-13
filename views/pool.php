<h1><?= htmlspecialchars($pool['title']) ?></h1>
<?= htmlspecialchars($pool['description']) ?>
<p>
Share - <a href="<?= $pool_url ?>"><?= $pool_url ?></a>
</p>
<hr>

<?php if (!$user_has_joined): ?>
<form action="/pools/<?= $pool['slug'] ?>" method="post" class="mb-2">
    <input type="submit" name="join" class="btn btn-primary" value="Join">
</form>
<?php endif ?>

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
        <tr>
            <td>1</td>
            <td>zulu#1799</td>
            <td>2</td>
            <td>2-0</td>
        </tr>
        <tr>
            <td>1</td>
            <td>lightster#1173</td>
            <td>2</td>
            <td>1-1</td>
        </tr>
    </tbody>
</table>
