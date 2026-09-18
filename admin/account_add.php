<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$admin = getCurrentAdmin();
if (!$admin) {
    logoutAdmin();
    redirect('login.php');
}

$pageTitle = 'Yeni Hesap Oluştur';
$error = null;
$success = null;
$username = '';
$adminAccounts = getAdminAccounts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['password_confirm'] ?? '';

    if ($username === '' || $password === '' || $confirmPassword === '') {
        $error = 'Lütfen tüm alanları doldurun.';
    } else {
        $result = createAdminAccount($username, $password, $confirmPassword);

        if ($result['success']) {
            $success = $result['message'];
            $username = '';
            $adminAccounts = getAdminAccounts();
        } else {
            $error = $result['message'];
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="admin-toolbar">
  <h2>Yeni Hesap Oluştur</h2>
  <a href="dashboard.php" class="btn btn--outline">← Geri</a>
</div>

<?php if ($success): ?>
  <div class="alert alert--success"><?= e($success) ?></div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert--error"><?= e($error) ?></div>
<?php endif; ?>

<form method="post" class="admin-form">
  <div class="form-group">
    <label for="username">Kullanıcı Adı *</label>
    <input
      type="text"
      id="username"
      name="username"
      required
      minlength="3"
      maxlength="50"
      pattern="[A-Za-z0-9._-]+"
      autocomplete="off"
      value="<?= e($username) ?>"
      placeholder="Örn: yonetici"
    >
  </div>
  <div class="form-group">
    <label for="password">Şifre *</label>
    <input type="password" id="password" name="password" required minlength="6" autocomplete="new-password">
  </div>
  <div class="form-group">
    <label for="password_confirm">Şifre (Tekrar) *</label>
    <input type="password" id="password_confirm" name="password_confirm" required minlength="6" autocomplete="new-password">
  </div>
  <button type="submit" class="btn btn--dark">Hesap Oluştur</button>
</form>

<?php if ($adminAccounts): ?>
  <div class="profile-accounts profile-accounts--page">
    <h3 class="profile-accounts__title">Mevcut Yöneticiler</h3>
    <ul class="profile-accounts__list">
      <?php foreach ($adminAccounts as $account): ?>
        <li class="profile-accounts__item">
          <span class="profile-accounts__avatar" aria-hidden="true">
            <?= e(mb_strtoupper(mb_substr($account['username'], 0, 1))) ?>
          </span>
          <span class="profile-accounts__name"><?= e($account['username']) ?></span>
          <?php if ((int) $account['id'] === (int) $admin['id']): ?>
            <span class="profile-accounts__badge">Siz</span>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
