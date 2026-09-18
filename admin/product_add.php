<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$errors = [];
$data = ['title' => '', 'category' => '', 'description' => ''];

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
            $image = storeProductImage($_FILES['image'] ?? []);
            $db = getDB();
            $stmt = $db->prepare('INSERT INTO products (title, category, description, image) VALUES (?, ?, ?, ?)');
            $stmt->execute([$data['title'], $data['category'], $data['description'], $image ?? '']);
            $_SESSION['flash'] = 'Ürün başarıyla eklendi.';
            redirect('dashboard.php');
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        }
    }
}

$pageTitle = 'Yeni Ürün Ekle';
require __DIR__ . '/includes/header.php';
?>

<div class="admin-toolbar">
  <h2>Yeni Ürün Ekle</h2>
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
  <div class="form-group">
    <label for="title">Ürün Adı *</label>
    <input type="text" id="title" name="title" required value="<?= e($data['title']) ?>"
           placeholder="Örn: Minimalist Meşe Kanepe">
  </div>
  <div class="form-group">
    <label for="category">Kategori *</label>
    <input type="text" id="category" name="category" required value="<?= e($data['category']) ?>"
           placeholder="Örn: Oturma Odası" list="categories">
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
    <textarea id="description" name="description" rows="4"
              placeholder="Ürün hakkında kısa açıklama..."><?= e($data['description']) ?></textarea>
  </div>
  <div class="form-group">
    <label for="image">Ürün Görseli</label>
    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
    <small class="form-hint">JPG, PNG veya WEBP · En fazla 5 MB</small>
  </div>
  <button type="submit" class="btn btn--dark">Ürünü Kaydet</button>
</form>

<?php require __DIR__ . '/includes/footer.php'; ?>
