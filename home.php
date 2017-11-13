<?php

$app = require_once __DIR__ . '/app.php';

?>

<?php include __DIR__ . '/header.php'; ?>

<main role="main">

  <section class="jumbotron text-center">
    <div class="container">
      <h1 class="jumbotron-heading">Overwatch League Fantasy Pick'Em</h1>
      <p class="lead text-muted">Come have some fun with us in our fantasy Overwatch league. Create your own pool or join one of ours and win some fun prizes.</p>
      <p>
        <a href="#" class="btn btn-primary">Sign up</a>
      </p>
    </div>
  </section>

  <div class="album text-muted">
    <div class="container">

      <div class="row">
        <div class="card">
          <img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">
          <p class="card-text"><strong>Crazy Wild Pool Party</strong> Example featured pool.</p>
        </div>
        <div class="card">
          <img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">
          <p class="card-text"><strong>ASUS Sponsored Pool</strong> Weekly winners get a new ASUS monitor.</p>
        </div>
        <div class="card">
          <img data-src="holder.js/100px280?theme=thumb" alt="Card image cap">
          <p class="card-text"><strong>We Rock</strong> Special pool for us only.</p>
        </div>
      </div>

    </div>
  </div>

</main>

<?php include __DIR__ . '/footer.php'; ?>
