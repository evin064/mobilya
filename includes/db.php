<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/products.php';

function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dir = dirname(DB_PATH);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        initDatabase($pdo);
        migrateDatabase($pdo);
    }

    return $pdo;
}

function initDatabase(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            category TEXT NOT NULL,
            description TEXT DEFAULT '',
            image TEXT DEFAULT '',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL
        )
    ");

    $count = (int) $pdo->query('SELECT COUNT(*) FROM admins')->fetchColumn();
    if ($count === 0) {
        $stmt = $pdo->prepare('INSERT INTO admins (username, password) VALUES (?, ?)');
        $stmt->execute([
            DEFAULT_ADMIN_USER,
            password_hash(DEFAULT_ADMIN_PASS, PASSWORD_DEFAULT),
        ]);
    }

    $productCount = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    if ($productCount === 0) {
        $samples = [
            ['Minimalist Meşe Kanepe', 'Oturma Odası', 'Doğal meşe ahşap detaylı, modern silüet.'],
            ['Ceviz Yemek Masası', 'Yemek Odası', 'Masif ceviz, 6 kişilik geniş yüzey.'],
            ['Kapitone Başlıklı Yatak', 'Yatak Odası', 'Zarif kapitone detay, yüksek konfor.'],
            ['Deri Berjer Koltuk', 'Oturma Odası', 'Premium deri kaplama, ergonomik oturum.'],
            ['Balkon Oturma Takımı', 'Balkon', 'Kilim minderli balkon ve teras oturma grubu.'],
            ['Mermer Sehpa Seti', 'Oturma Odası', 'Doğal mermer üst, pirinç ayak detayı.'],
        ];

        $stmt = $pdo->prepare('INSERT INTO products (title, category, description, image) VALUES (?, ?, ?, ?)');
        foreach ($samples as [$title, $category, $description]) {
            $stmt->execute([$title, $category, $description, '']);
        }
    }
}
