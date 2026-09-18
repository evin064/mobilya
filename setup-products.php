<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/product-catalog.php';

$srcDir = 'C:/Users/evinaglamaz/.cursor/projects/c-Users-evinaglamaz-Desktop-MOB-LYA/assets/';
$files = glob($srcDir . '*a-*.png');

if (!$files) {
    fwrite(STDERR, "Kaynak görseller bulunamadı.\n");
    exit(1);
}

usort($files, static function (string $a, string $b): int {
    preg_match('/(\d+)a-/', basename($a), $matchA);
    preg_match('/(\d+)a-/', basename($b), $matchB);
    return ((int) ($matchA[1] ?? 0)) <=> ((int) ($matchB[1] ?? 0));
});

// İlk görsel hero/banner olduğu için ürün listesine alma
if (count($files) > 1) {
    array_shift($files);
}

$catalog = array_values(getProductCatalog());

ensureUploadDir();
$db = getDB();

foreach (glob(UPLOAD_DIR . 'product-*') as $oldFile) {
    if (is_file($oldFile)) {
        unlink($oldFile);
    }
}

$db->exec('DELETE FROM products');

$insert = $db->prepare('INSERT INTO products (title, category, description, image) VALUES (?, ?, ?, ?)');

foreach ($files as $index => $source) {
    $meta = $catalog[$index] ?? [
        'Koleksiyon Ürünü ' . ($index + 1),
        'Mobilya',
        'Turan Mobilya koleksiyonundan seçkin bir parça.',
    ];

    $ext = pathinfo($source, PATHINFO_EXTENSION) ?: 'jpg';
    $filename = sprintf('product-%02d.%s', $index + 1, $ext === 'png' ? 'jpg' : $ext);
    $destination = UPLOAD_DIR . $filename;

    if (!copy($source, $destination)) {
        fwrite(STDERR, "Kopyalanamadı: $source\n");
        continue;
    }

    $insert->execute([
        $meta[0],
        $meta[1],
        $meta[2],
        $filename,
    ]);
}

$count = (int) $db->query('SELECT COUNT(*) FROM products')->fetchColumn();
echo "OK: {$count} ürün görseli yüklendi.\n";
