<?php

require_once __DIR__ . '/../config/config.php';

function migrateDatabase(PDO $pdo): void
{
    $columns = array_column(
        $pdo->query('PRAGMA table_info(products)')->fetchAll(),
        'name'
    );

    if (!in_array('image', $columns, true)) {
        $pdo->exec("ALTER TABLE products ADD COLUMN image TEXT DEFAULT ''");
    }

    require_once __DIR__ . '/contact-form.php';
    ensureContactMessagesTable($pdo);
}

function ensureUploadDir(): void
{
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }
}

function productImageUrl(?string $filename): string
{
    if (!$filename) {
        return '';
    }

    $path = UPLOAD_DIR . $filename;
    if (!is_file($path)) {
        return '';
    }

    $version = filemtime($path) ?: time();
    return UPLOAD_URL . rawurlencode($filename) . '?v=' . $version;
}

function deleteProductImage(?string $filename): void
{
    if (!$filename) {
        return;
    }

    $path = UPLOAD_DIR . $filename;
    if (is_file($path)) {
        unlink($path);
    }
}

function storeProductImage(array $file): ?string
{
    ensureUploadDir();

    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Görsel yüklenirken bir hata oluştu.');
    }

    if (($file['size'] ?? 0) > MAX_UPLOAD_SIZE) {
        throw new RuntimeException('Görsel boyutu en fazla 5 MB olabilir.');
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Yalnızca JPG, PNG veya WEBP yükleyebilirsiniz.');
    }

    $filename = 'product-' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    $destination = UPLOAD_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Görsel kaydedilemedi.');
    }

    return $filename;
}
