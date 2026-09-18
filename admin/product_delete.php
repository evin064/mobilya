<?php
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$id = (int) ($_GET['id'] ?? 0);
$product = getProduct($id);

if ($product) {
    deleteProductImage($product['image'] ?? '');
    $db = getDB();
    $stmt = $db->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $_SESSION['flash'] = '"' . $product['title'] . '" silindi.';
}

redirect('dashboard.php');
