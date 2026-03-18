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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf = (string)($_POST['csrf'] ?? '');
    if (!hash_equals((string)($_SESSION['csrf'] ?? ''), $csrf)) {
        header('Location: dashboard.php');
        exit;
    }

    $comm = trim((string)($_POST['comm'] ?? ''));
    $type = (string)($_POST['type'] ?? '');

    $map = [
        'agenda' => 'forAgenda.php',
        'ldr' => 'Legaslative.php',
        'ao' => 'Administrative.php',
        'action' => 'Action.php',
    ];

    if ($comm !== '' && isset($map[$type])) {
        $_SESSION['items'][$type] ??= [];
        $_SESSION['items'][$type][] = [
            'id' => 'u-' . bin2hex(random_bytes(8)),
            'comm' => $comm,
            'dt' => date('M j, Y • g:i A'),
        ];

        header('Location: ' . $map[$type]);
        exit;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard</title>
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
      <?php $active = 'receive'; require __DIR__ . '/partials/sidebar.php'; ?>

      <section class="content" aria-label="Main content">
        <div class="panel">
          <form action="dashboard.php" method="post">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars((string)($_SESSION['csrf'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" />
            <div class="formgrid">
              <label class="field">
                <span class="label">Communication #:</span>
                <input class="input" type="text" name="comm" placeholder="" required />
              </label>

              <label class="field">
                <span class="label">Type:</span>
                <select class="select" name="type">
                  <option value="" selected>Select type</option>
                  <option value="agenda">For Agenda</option>
                  <option value="ldr">Legislative Document Request</option>
                  <option value="ao">Administrative Order</option>
                  <option value="action">For Action</option>
                </select>
              </label>
            </div>

            <div class="submitrow">
              <button class="btn" type="submit">SUBMIT</button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>
</body>
</html>
  