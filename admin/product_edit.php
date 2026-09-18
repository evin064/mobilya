<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$id = (int) ($_GET['id'] ?? 0);
$product = getProduct($id);

if (!$product) {
    redirect('dashboard.php');
}

$errors = [];
$data = [
    'title' => $product['title'],
    'category' => $product['category'],
    'description' => $product['description'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['title'] = trim($_POST['title'] ?? '');
    $data['category'] = trim($_POST['category'] ?? '');
    $data['description'] = trim($_POST['description'] ?? '');

    if ($data['title'] === '') {
        $errors[] = 'Ürün adı gereklidir.';
    }
    if ($data['category'] === '') {
        $errors[] = 'Kategori gereklidir.';
    }

    if (empty($errors)) {
        try {
            $image = $product['image'] ?? '';

            if (!empty($_FILES['image']['name'])) {
                $newImage = storeProductImage($_FILES['image']);
                if ($newImage) {
                    deleteProductImage($image);
                    $image = $newImage;
                }
            }

            $db = getDB();
            $stmt = $db->prepare('UPDATE products SET title = ?, category = ?, description = ?, image = ? WHERE id = ?');
            $stmt->execute([$data['title'], $data['category'], $data['description'], $image, $id]);
            $_SESSION['flash'] = 'Ürün güncellendi.';
            redirect('dashboard.php');
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        }
    }
}

$pageTitle = 'Ürün Düzenle';
require __DIR__ . '/includes/header.php';
?>

<div class="admin-toolbar">
  <h2>Ürün Düzenle</h2>
  <a href="dashboard.php" class="btn btn--outline">← Geri</a>
</div>

<?php if ($errors): ?>
  <div class="alert alert--error">
    <?php foreach ($errors as $err): ?>
      <p><?= e($err) ?></p>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="admin-form">
  <?php if (!empty($product['image']) && productImageUrl($product['image'])): ?>
    <div class="form-preview">
      <img src="../<?= e(productImageUrl($product['image'])) ?>" alt="<?= e($product['title']) ?>">
    </div>
  <?php endif; ?>

  <div class="form-group">
    <label for="title">Ürün Adı *</label>
    <input type="text" id="title" name="title" required value="<?= e($data['title']) ?>">
  </div>
  <div class="form-group">
    <label for="category">Kategori *</label>
    <input type="text" id="category" name="category" required value="<?= e($data['category']) ?>"
           list="categories">
    <datalist id="categories">
      <option value="Oturma Odası">
      <option value="Yemek Odası">
      <option value="Yatak Odası">
      <option value="Mutfak">
      <option value="Balkon">
      <option value="Bahçe">
    </datalist>
  </div>
  <div class="form-group">
    <label for="description">Açıklama</label>
    <textarea id="description" name="description" rows="4"><?= e($data['description']) ?></textarea>
  </div>
  <div class="form-group">
    <label for="image">Yeni Görsel Yükle</label>
    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
    <small class="form-hint">Boş bırakırsanız mevcut görsel korunur.</small>
  </div>
  <button type="submit" class="btn btn--dark">Değişiklikleri Kaydet</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>
