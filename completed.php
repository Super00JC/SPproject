<?php
declare(strict_types=1);

session_start();
require __DIR__ . '/partials/data.php';
seed_demo_if_missing();

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$username = (string)($_SESSION['user']['username'] ?? 'user');

$rows = list_completed();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Completed</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="dashboard.css" />
</head>
<body>
  <div class="bg">
    <header class="topbar" role="banner">
      <div class="brand" aria-label="Brand">
        <div class="brand__logo" aria-hidden="true"></div>
        <div class="brand__title">SANGGUNIANG PANLUNGSOD</div>
      </div>

      <div class="userpill" aria-label="User menu">
        <div class="userpill__name">Piolo Pascual | <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></div>
        <a class="userpill__logout" href="logout.php">logout</a>
      </div>
    </header>

    <div class="layout">
      <?php $active = 'completed'; require __DIR__ . '/partials/sidebar.php'; ?>

      <section class="content" aria-label="Main content">
        <div class="panel panel--table">
          <div class="table">
            <div class="table__head">
              <div class="table__th">Communication #</div>
              <div class="table__th">Date &amp; Time</div>
              <div class="table__th table__th--action"></div>
            </div>

            <?php foreach ($rows as $r): ?>
              <div class="table__row">
                <div class="table__td"><?= htmlspecialchars($r['comm'], ENT_QUOTES, 'UTF-8') ?></div>
                <div class="table__td"><?= htmlspecialchars($r['dt'], ENT_QUOTES, 'UTF-8') ?></div>
                <div class="table__td table__td--action">
                  <div class="rowactions">
                    <span class="pillbtn pillbtn--done">COMPLETED</span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    </div>
  </div>
</body>
</html>
