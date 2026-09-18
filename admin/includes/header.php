<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

$messageCount = getContactMessageCount();
$currentAdmin = getCurrentAdmin();
$currentPage = basename($_SERVER['PHP_SELF']);
$adminInitial = mb_strtoupper(mb_substr($currentAdmin['username'] ?? 'A', 0, 1));
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? 'Admin') ?> — <?= e(SITE_NAME) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/admin.css">
</head>
<body class="admin-body">
  <aside class="admin-sidebar">
    <a href="dashboard.php" class="admin-logo admin-logo--sidebar">
      <span class="admin-logo__text">
        <span class="admin-logo__name">TURAN</span>
        <span class="admin-logo__line">
          <span class="admin-logo__line-bar" aria-hidden="true"></span>
          <span class="admin-logo__sub">MOBİLYA</span>
          <span class="admin-logo__line-bar" aria-hidden="true"></span>
        </span>
      </span>
    </a>
    <nav class="admin-sidebar__nav">
      <a href="dashboard.php" class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">Ürünler</a>
      <a href="messages.php" class="<?= $currentPage === 'messages.php' ? 'active' : '' ?>">
        <span>Mesajlar</span>
        <?php if ($messageCount > 0): ?>
          <span class="nav-badge"><?= $messageCount ?></span>
        <?php endif; ?>
      </a>
      <a href="product_add.php" class="<?= $currentPage === 'product_add.php' ? 'active' : '' ?>">Yeni Ürün</a>
      <a href="account_add.php" class="<?= $currentPage === 'account_add.php' ? 'active' : '' ?>">Yeni Hesap Oluştur</a>
      <a href="../index.php" target="_blank">Siteyi Görüntüle</a>
    </nav>
  </aside>
  <main class="admin-main">
    <header class="admin-header">
      <h1><?= e($pageTitle ?? 'Admin Panel') ?></h1>

      <?php if ($currentAdmin): ?>
        <div class="admin-user" id="admin-user-menu">
          <button
            type="button"
            class="admin-user__toggle"
            id="admin-user-toggle"
            aria-expanded="false"
            aria-haspopup="true"
            aria-controls="admin-user-dropdown"
          >
            <span class="admin-user__avatar" aria-hidden="true"><?= e($adminInitial) ?></span>
            <span class="admin-user__name"><?= e($currentAdmin['username']) ?></span>
            <svg class="admin-user__chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
              <path d="M6 9l6 6 6-6"/>
            </svg>
          </button>
          <div class="admin-user__dropdown" id="admin-user-dropdown" hidden>
            <a href="profile.php" class="admin-user__link <?= $currentPage === 'profile.php' ? 'is-active' : '' ?>">Profil</a>
            <a href="logout.php" class="admin-user__link admin-user__link--logout">Çıkış</a>
          </div>
        </div>
      <?php endif; ?>
    </header>
    <div class="admin-content">
