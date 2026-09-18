<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$admin = getCurrentAdmin();
if (!$admin) {
    logoutAdmin();
    redirect('login.php');
}

$pageTitle = 'Profil';
$passwordError = null;
$passwordSuccess = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
        $passwordError = 'Lütfen tüm alanları doldurun.';
    } else {
        $result = changeAdminPassword((int) $admin['id'], $currentPassword, $newPassword, $confirmPassword);

        if ($result['success']) {
            $passwordSuccess = $result['message'];
        } else {
            $passwordError = $result['message'];
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="profile-page">
  <div class="profile-card">
    <div class="profile-card__header">
      <div class="profile-card__avatar" aria-hidden="true">
        <?= e(mb_strtoupper(mb_substr($admin['username'], 0, 1))) ?>
      </div>
      <div>
        <h2 class="profile-card__name"><?= e($admin['username']) ?></h2>
        <p class="profile-card__role">Yönetici hesabı</p>
      </div>
    </div>

    <section class="profile-section">
      <h3 class="profile-section__title">Şifre Değiştir</h3>

      <?php if ($passwordSuccess): ?>
        <div class="alert alert--success"><?= e($passwordSuccess) ?></div>
      <?php endif; ?>

      <?php if ($passwordError): ?>
        <div class="alert alert--error"><?= e($passwordError) ?></div>
      <?php endif; ?>

      <form method="post" class="admin-form profile-form">
        <div class="form-group">
          <label for="current_password">Mevcut Şifre</label>
          <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
        </div>
        <div class="form-group">
          <label for="new_password">Yeni Şifre</label>
          <input type="password" id="new_password" name="new_password" required minlength="6" autocomplete="new-password">
        </div>
        <div class="form-group">
          <label for="confirm_password">Yeni Şifre (Tekrar)</label>
          <input type="password" id="confirm_password" name="confirm_password" required minlength="6" autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn--dark">Şifreyi Güncelle</button>
      </form>
    </section>

    <section class="profile-section profile-section--danger">
      <h3 class="profile-section__title">Oturum</h3>
      <p class="profile-section__text">Hesabınızdan güvenli şekilde çıkış yapabilirsiniz.</p>
      <a href="logout.php" class="btn btn--outline btn--danger-outline">Çıkış Yap</a>
    </section>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
