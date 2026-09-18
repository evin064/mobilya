<?php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function getProducts(): array
{
    $db = getDB();
    return $db->query('SELECT * FROM products ORDER BY title ASC')->fetchAll();
}

function searchProducts(string $query): array
{
    $query = trim($query);
    if ($query === '') {
        return getProducts();
    }

    $db = getDB();
    $term = '%' . $query . '%';
    $stmt = $db->prepare('
        SELECT * FROM products
        WHERE title LIKE ? OR category LIKE ? OR description LIKE ?
        ORDER BY title ASC
    ');
    $stmt->execute([$term, $term, $term]);

    return $stmt->fetchAll();
}

function getProductCategories(): array
{
    $db = getDB();
    return $db->query('SELECT DISTINCT category FROM products ORDER BY category ASC')->fetchAll(PDO::FETCH_COLUMN);
}

function pageAnchor(string $id): string
{
    $id = ltrim($id, '#');
    $script = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');

    if ($id === 'contact' && in_array($script, ['index.php', 'product.php'], true)) {
        return '#contact';
    }

    if ($id === 'contact') {
        return 'contact.php';
    }

    if ($script === 'index.php') {
        return '#' . $id;
    }

    return 'index.php#' . $id;
}

function getNavCategories(): array
{
    return [
        'Oturma Odası' => 'oturma-odasi',
        'Yatak Odası' => 'yatak-odasi',
        'Yemek Odası' => 'yemek-odasi',
    ];
}

function pageCategoryAnchor(string $category): string
{
    $categories = getNavCategories();

    if (!isset($categories[$category])) {
        return pageAnchor('products');
    }

    $anchor = 'products/' . $categories[$category];
    $script = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');

    if ($script === 'index.php') {
        return '#' . $anchor;
    }

    return 'index.php#' . $anchor;
}

function contactMailtoUrl(): string
{
    return 'mailto:' . CONTACT_EMAIL;
}

function contactTelUrl(): string
{
    return 'tel:' . CONTACT_PHONE;
}

function contactWhatsAppUrl(): string
{
    $phone = preg_replace('/\D/', '', CONTACT_PHONE);

    return 'https://wa.me/' . $phone;
}

function contactMapsUrl(): string
{
    return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(CONTACT_ADDRESS . ', Türkiye');
}

function contactMapEmbedUrl(): string
{
    $query = rawurlencode(CONTACT_ADDRESS . ', Türkiye');

    return 'https://maps.google.com/maps?q=' . $query . '&hl=tr&z=14&output=embed';
}

function getCategoryShowcase(): array
{
    $items = [
        ['Oturma Odası', 'Konfor ve şıklık bir arada'],
        ['Yemek Odası', 'Sofralarınıza zarafet katın'],
        ['Yatak Odası', 'Huzurlu dinlenme alanları'],
        ['Mutfak', 'Fonksiyonel mutfak çözümleri'],
        ['Balkon', 'Balkon keyfinize renk katın'],
        ['Bahçe', 'Dış mekân keyfi'],
    ];

    $db = getDB();
    $imageStmt = $db->prepare('SELECT image FROM products WHERE category = ? ORDER BY title ASC LIMIT 1');
    $result = [];

    foreach ($items as [$category, $description]) {
        $imageStmt->execute([$category]);
        $image = $imageStmt->fetchColumn();
        if ($image) {
            $result[] = [$category, $image, $description];
        }
    }

    return $result;
}

function getProduct(int $id): ?array
{
    $db = getDB();
    $stmt = $db->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
    return $product ?: null;
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}
