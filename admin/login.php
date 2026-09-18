<?php
require_once __DIR__ . '/includes/auth.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Kullanıcı adı ve şifre gereklidir.';
    } elseif (loginAdmin($username, $password)) {
        redirect('dashboard.php');
    } else {
        $error = 'Geçersiz kullanıcı adı veya şifre.';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Giriş — <?= e(SITE_NAME) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-login">
  <div class="login-card">
    <a href="../index.php" class="admin-logo admin-logo--login">
      <span class="admin-logo__text">
        <span class="admin-logo__name">TURAN</span>
        <span class="admin-logo__line">
          <span class="admin-logo__line-bar" aria-hidden="true"></span>
          <span class="admin-logo__sub">MOBİLYA</span>
          <span class="admin-logo__line-bar" aria-hidden="true"></span>
        </span>
      </span>
    </a>
    <p class="login-card__subtitle">Admin Paneli</p>

    <?php if ($error): ?>
      <div class="alert alert--error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" class="login-form">
      <div class="form-group">
        <label for="username">Kullanıcı Adı</label>
        <input type="text" id="username" name="username" required autofocus
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label for="password">Şifre</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn--dark btn--full">Giriş Yap</button>
    </form>

    <a href="../index.php" class="login-card__back">← Siteye Dön</a>
  </div>
</body>
</html>
