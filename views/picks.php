<a href="/pools/<?= $pool['slug'] ?>">&larr; Back to Pool</a>
<h3>Make your Picks</h3>
<form action="/pools/<?= $pool['slug'] ?>/picks" method="get">
    <div class="form-group col-3 col-md-2">
    <label for="week">Week</label>
    <select name="w" class="form-control week-selector-js" id="week">
    <?php foreach(range(1, $number_of_weeks) as $week): ?>
    <option value="<?= $week ?>"
        <?= ($selected_week == $week ? ' selected="selected"' : '') ?>><?= $week ?></option>
    <?php endforeach ?>
    </select>
    </div>
</form>

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
            <a href="javascript:;" class="team-pick-js"
                data-team_id="<?= $match['away_team_id'] ?>"
                data-match_id="<?= $match['match_id'] ?>">
            <?php if ($match['away_team_id'] === $match['pick_team_id']): ?>
            Picked
            <?php else: ?>
            Pick
            <?php endif ?>
            </a>
            <?php if ($match['away_team_id'] == $match['winning_team_id']): ?>
            <div class="bg-success text-white">Winner</div>
            <?php endif ?>
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
            <a href="javascript:;" class="team-pick-js"
                data-team_id="<?= $match['home_team_id'] ?>"
                data-match_id="<?= $match['match_id'] ?>">
            <?php if ($match['home_team_id'] === $match['pick_team_id']): ?>
            Picked
            <?php else: ?>
            Pick
            <?php endif ?>
            </a>
            <?php if ($match['home_team_id'] == $match['winning_team_id']): ?>
            <div class="bg-success text-white">Winner</div>
            <?php endif ?>
        </div>
    </div>
</div>
<?php endforeach ?>

<script type="text/javascript">
$(function() {
    $('.team-pick-js').on('click', function(e) {
        e.preventDefault();
        var team_id = $(this).data('team_id');
        var match_id = $(this).data('match_id');

        $.post('/pools/<?= $pool['slug'] ?>/picks', {
            'match_id': match_id,
            'team_id': team_id
        }, function(res) {
            $('.team-pick-js[data-match_id=' + match_id + ']').each(function() {
                var e_team_id = $(this).data('team_id');
                if (e_team_id == team_id) {
                    $(this).html('Picked');
                } else {
                    $(this).html('Pick');
                }
            });
        });
    });
    $('.week-selector-js').on('change', function(e) {
        this.form.submit();
    });
});
</script>
