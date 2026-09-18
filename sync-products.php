<?php

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/product-catalog.php';

$count = syncProductCatalog();
echo "Katalog güncellendi: {$count} ürün\n";
