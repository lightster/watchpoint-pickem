<h1>Make your Pick</h1>
<?php foreach ($matches as $match): ?>
<div class="container p-0">
    <div class="row">
        <div class="col">
        <span class="text-muted"><?= date('F j', strtotime($match['match_time'])) ?></span>
        <?= date('l g:i A', strtotime($match['match_time'])) ?>
        </div>
    </div>
    <div class="row mt-3 mb-3 align-items-center">
        <div class="col-5 text-center align-middle">
            <img src="/logo/<?= $match['away_team_id'] ?>.svg" width="50"><br>
            <div class="d-block d-sm-none">
                <?= $match['away_team_abbr'] ?>
            </div>
            <div class="d-none d-sm-block">
                <?= $match['away_team_name'] ?>
            </div>
        </div>
        <div class="col-2 text-center align-middle">at</div>
        <div class="col-5 text-center align-middle">
            <img src="/logo/<?= $match['home_team_id'] ?>.svg" width="50"><br>
            <div class="d-block d-sm-none">
                <?= $match['home_team_abbr'] ?>
            </div>
            <div class="d-none d-sm-block">
                <?= $match['home_team_name'] ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach ?>
