<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <title><?= e($pageTitle ?? SITE_NAME) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body>

  <header class="header">
    <div class="container header__inner">
      <a href="index.php" class="logo">
        <span class="logo__text">
          <span class="logo__name">TURAN</span>
          <span class="logo__line">
            <span class="logo__line-bar" aria-hidden="true"></span>
            <span class="logo__sub">MOBİLYA</span>
            <span class="logo__line-bar" aria-hidden="true"></span>
          </span>
        </span>
      </a>

      <nav class="nav" id="site-nav">
        <div class="nav__inner">
          <a href="<?= pageAnchor('home') ?>">Ana Sayfa</a>
          <a href="<?= pageAnchor('products') ?>">Koleksiyon</a>
          <?php foreach (getNavCategories() as $label => $slug): ?>
            <a href="<?= pageCategoryAnchor($label) ?>"><?= e($label) ?></a>
          <?php endforeach; ?>
          <a href="<?= pageAnchor('services') ?>">Hizmetler</a>
          <a href="<?= pageAnchor('about') ?>">Hakkımızda</a>
          <a href="<?= pageAnchor('contact') ?>" class="nav__contact">İletişim</a>
        </div>
      </nav>

      <div class="header__actions">
        <a href="<?= pageAnchor('contact') ?>" class="btn btn--brand btn--sm">İletişim</a>
        <button class="menu-toggle" aria-label="Menü" aria-expanded="false" aria-controls="site-nav">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </header>
