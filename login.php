<?php
declare(strict_types=1);

session_start();

// If already logged in, go to dashboard.
if (!empty($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter your username and password.';
    } else {
        // Demo credentials (replace with DB lookup / password_hash in real use).
        $validUser = 'admin';
        $validPass = 'admin123';

        if (hash_equals($validUser, $username) && hash_equals($validPass, $password)) {
            $_SESSION['user'] = [
                'username' => $username,
                'logged_in_at' => time(),
            ];
            session_regenerate_id(true);
            header('Location: dashboard.php');
            exit;
        }

        $error = 'Invalid username or password.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="login.css" />
</head>
<body>
  <main class="page">
    <section class="brand" aria-label="Brand panel">
      <div class="brand__top">
        <div class="brand__seal" aria-hidden="true"></div>
      </div>

      <div class="brand__center">
        <div class="brand__title">
          <div class="brand__titleLine">SANGGUNIANG</div>
          <div class="brand__titleLine">PANGLUNGSOD</div>
        </div>
      </div>

      <div class="brand__bottom">
        <div class="brand__subtitle">SECRETARIAT<br/>OFFICE</div>
      </div>
    </section>

    <section class="hero" aria-label="Login panel">
      <div class="hero__bg" role="img" aria-label="Background image"></div>
      <div class="hero__soverlay" aria-hidden="true"></div>

      <div class="card" role="region" aria-label="Login form">
        <h1 class="card__title">Login to your account</h1>

        <?php if ($error !== ''): ?>
          <div class="alert" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <form method="post" action="login.php" class="form" novalidate>
          <label class="field">
            <span class="field__label">Username</span>
            <input
              class="field__input"
              type="text"
              name="username"
              placeholder="Input username"
              autocomplete="username"
              value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
              required
            />
          </label>

          <label class="field">
            <span class="field__label">Password</span>
            <input
              class="field__input"
              type="password"
              name="password"
              placeholder="Input your password"
              autocomplete="current-password"
              required
            />
          </label>

          <button class="btn" type="submit">Login</button>

          <div class="form__meta">
            <span class="form__metaText">Don’t have an Account?</span>
            <a class="form__link" href="#" onclick="return false;">Signup</a>
          </div>
        </form>

        <div class="hint">
          Demo login: <strong>admin</strong> / <strong>admin123</strong>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
