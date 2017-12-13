<!doctype html>
<html lang="en">
  <head>
    <title>watchpoint.io</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/main.css">
  </head>
  <body>
    <header>
      <div class="navbar navbar-expand bg-dark navbar-dark">
        <div class="container">
            <a href="<?= (isset($user) ? '/home' : '/') ?>" class="navbar-brand"><img src="/img/logo.svg" width="180"></a>
          <ul class="navbar-nav ml-auto">
            <?php if (isset($user)): ?>
            <li class="nav-item">
              <a href="/user" class="nav-link"><?= htmlspecialchars($user->getData('bnet_tag')) ?></a>
            </li>
            <li class="nav-item">
              <a href="/auth/logout" class="nav-link">Sign out</a>
            </li>
            <?php else: ?>
            <li class="nav-item">
              <a href="/auth" class="nav-link">Sign in</a>
            </li>
            <?php endif ?>
          </ul>
        </div>
      </div>
    </header>
    <main role="main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 mt-3">
                <?php if ($flash_msgs = $flash_messages()): ?>
                    <?php foreach ($flash_msgs as $msg): ?>
                        <?php list($msg, $status) = $msg; ?>
                        <div><?= htmlspecialchars($msg) ?></div>
                    <?php endforeach ?>
                <?php endif ?>

                <?= $content ?>
            </div>
        </div>
    </div>
    </main>
    <footer class="text-muted">
      <div class="container">
        <p class="float-right">
          <a href="#">Back to top</a>
        </p>
        <p>&copy; watchpoint.io</p>
      </div>
    </footer>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script src="/js/holder.min.js"></script>
  </body>
</html>
