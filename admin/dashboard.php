<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$search = trim($_GET['q'] ?? '');
$products = searchProducts($search);
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$pageTitle = 'Ürün Yönetimi';
require __DIR__ . '/includes/header.php';
?>

<?php if ($flash): ?>
  <div class="alert alert--success"><?= e($flash) ?></div>
<?php endif; ?>

<div class="admin-toolbar">
  <h2>
    Ürünler
    <span class="badge"><?= count($products) ?></span>
    <?php if ($search !== ''): ?>
      <span class="admin-toolbar__hint">"<?= e($search) ?>" için sonuçlar</span>
    <?php endif; ?>
  </h2>
  <div class="admin-toolbar__actions">
    <form class="admin-search" method="get" action="dashboard.php" role="search">
      <input
        type="search"
        name="q"
        value="<?= e($search) ?>"
        placeholder="Ürün ara..."
        aria-label="Ürün ara"
      >
      <?php if ($search !== ''): ?>
        <a href="dashboard.php" class="btn btn--outline btn--sm">Temizle</a>
      <?php endif; ?>
    </form>
    <a href="product_add.php" class="btn btn--dark">+ Yeni Ürün</a>
  </div>
</div>

<?php if (empty($products)): ?>
  <div class="empty-state">
    <?php if ($search !== ''): ?>
      <p>"<?= e($search) ?>" için ürün bulunamadı.</p>
      <a href="dashboard.php" class="btn btn--dark">Tüm Ürünleri Göster</a>
    <?php else: ?>
      <p>Henüz ürün eklenmemiş.</p>
      <a href="product_add.php" class="btn btn--dark">İlk Ürünü Ekle</a>
    <?php endif; ?>
  </div>
<?php else: ?>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Görsel</th>
          <th>Ürün Adı</th>
          <th>Kategori</th>
          <th>Açıklama</th>
          <th>Eklenme</th>
          <th>İşlemler</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product): ?>
          <tr>
            <td><?= (int) $product['id'] ?></td>
            <td>
              <?php if (!empty($product['image']) && productImageUrl($product['image'])): ?>
                <img class="admin-thumb" src="../<?= e(productImageUrl($product['image'])) ?>" alt="">
              <?php else: ?>
                <span class="admin-thumb admin-thumb--empty">—</span>
              <?php endif; ?>
            </td>
            <td><strong><?= e($product['title']) ?></strong></td>
            <td><span class="tag"><?= e($product['category']) ?></span></td>
            <td class="desc-cell"><?= e($product['description'] ?: '—') ?></td>
            <td><?= date('d.m.Y', strtotime($product['created_at'])) ?></td>
            <td class="actions">
              <a href="product_edit.php?id=<?= (int) $product['id'] ?>" class="btn btn--outline btn--sm">Düzenle</a>
              <a href="product_delete.php?id=<?= (int) $product['id'] ?>"
                 class="btn btn--danger btn--sm"
                 onclick="return confirm('Bu ürünü silmek istediğinize emin misiniz?')">Sil</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
