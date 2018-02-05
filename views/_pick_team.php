<div class="col-5 text-center align-middle">
    <img src="/logo/<?= $team_id ?>.svg" width="50" height="50"><br>
    <div class="d-block d-sm-none">
        <?= $abbr ?>
    </div>
    <div class="d-none d-sm-block">
        <?= $name ?>
    </div>
    <div class="team-pick-container-js">
        <?php if ($match_started == 'f'): ?>
        <a href="javascript:;"
            class="team-pick-js<?= $team_id == $pick_team_id
                ? " bg-primary text-white" : "" ?>"
            data-team_id="<?= $team_id ?>"
            data-match_id="<?= $match_id ?>"><?= $team_id == $pick_team_id ?
            "Picked" : "Pick" ?></a>
        <?php elseif ($team_id == $pick_team_id): ?>
        <div class="bg-primary text-white">Picked</div>
        <?php endif ?>
    </div>
    <?php if ($team_id == $winning_team_id): ?>
    <div class="bg-success text-white">Winner</div>
    <?php endif ?>
</div>
