<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/products.php';

$id = (int) ($_GET['id'] ?? 0);
$product = getProduct($id);

if (!$product) {
    redirect('index.php#products');
}

$pageTitle = $product['title'] . ' — ' . SITE_NAME;
$imageUrl = productImageUrl($product['image'] ?? '');

require __DIR__ . '/includes/header.php';
?>

  <section class="product-detail">
    <div class="container product-detail__inner">
      <a href="index.php#products" class="product-detail__back">← Koleksiyona Dön</a>

      <div class="product-detail__layout">
        <div class="product-detail__gallery">
          <?php if ($imageUrl): ?>
            <button
              type="button"
              class="product-detail__gallery-trigger"
              id="product-image-trigger"
              aria-label="<?= e($product['title']) ?> — görseli tam ekran aç"
            >
              <img
                src="<?= e($imageUrl) ?>"
                alt="<?= e($product['title']) ?>"
                class="product-detail__image"
              >
              <span class="product-detail__zoom-hint" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M15 3h6v6M14 10l7-7M9 21H3v-6M10 14l-7 7"/></svg>
                Tam ekran
              </span>
            </button>
          <?php else: ?>
            <div class="product-detail__placeholder"></div>
          <?php endif; ?>
        </div>

        <div class="product-detail__content">
          <span class="product-detail__category"><?= e($product['category']) ?></span>
          <h1 class="product-detail__title"><?= e($product['title']) ?></h1>

          <?php if (!empty($product['description'])): ?>
            <div class="product-detail__desc">
              <p><?= nl2br(e($product['description'])) ?></p>
            </div>
          <?php endif; ?>

          <div class="product-detail__actions">
            <a href="<?= pageAnchor('contact') ?>" class="btn btn--brand">Bilgi Al</a>
            <a href="index.php#products" class="btn btn--outline">Diğer Ürünler</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php
  $related = array_values(array_filter(getProducts(), static fn($item) => (int) $item['id'] !== $id));
  $related = array_slice($related, 0, 4);
  ?>

  <?php if ($related): ?>
    <section class="product-related">
      <div class="container">
        <h2 class="section-title">Benzer Ürünler</h2>
        <div class="products__grid">
          <?php foreach ($related as $item): ?>
            <a href="product.php?id=<?= (int) $item['id'] ?>" class="product-card">
              <div class="product-card__image">
                <?php if (!empty($item['image']) && productImageUrl($item['image'])): ?>
                  <img src="<?= e(productImageUrl($item['image'])) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
                <?php else: ?>
                  <div class="product-card__placeholder"></div>
                <?php endif; ?>
              </div>
              <div class="product-card__body">
                <span class="product-card__category"><?= e($item['category']) ?></span>
                <h3 class="product-card__title"><?= e($item['title']) ?></h3>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <?php if ($imageUrl): ?>
    <div class="image-lightbox" id="product-image-lightbox" role="dialog" aria-modal="true" aria-label="Ürün görseli" hidden>
      <button type="button" class="image-lightbox__close" aria-label="Kapat">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
      </button>
      <img
        src="<?= e($imageUrl) ?>"
        alt="<?= e($product['title']) ?>"
        class="image-lightbox__image"
      >
    </div>
  <?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
