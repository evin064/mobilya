<?php

/**
 * Görsel içeriğe göre doğrulanmış ürün kataloğu.
 * Anahtar: product-XX.jpg dosya adı
 */
function getProductCatalog(): array
{
    return [
        'product-02.jpg' => ['Modern Mutfak Dolabı', 'Mutfak', 'Beyaz lake mutfak dolabı takımı.'],
        'product-03.jpg' => ['Klasik Aynalı Gardrop', 'Yatak Odası', 'Altın detaylı aynalı gardrop.'],
        'product-04.jpg' => ['Modern Aynalı Gardrop', 'Yatak Odası', 'LED aydınlatmalı modern gardrop.'],
        'product-05.jpg' => ['Modern Oturma Grubu', 'Oturma Odası', 'Modüler kanepe ve sehpa takımı.'],
        'product-06.jpg' => ['Mermer Ayaklı Yemek Masası', 'Yemek Odası', 'Mermer tabla ve bouclé sandalye seti.'],
        'product-07.jpg' => ['Bahçe Yemek Takımı', 'Bahçe', 'Ahşap bahçe masası ve sandalye seti.'],
        'product-08.jpg' => ['Baza ve Yatak Seti', 'Yatak Odası', 'Kapitone baza, başlık ve yatak.'],
        'product-09.jpg' => ['Kırmızı Mindersli Balkon Takımı', 'Balkon', 'Kilim minderli teras oturma grubu.'],
        'product-10.jpg' => ['Kapitone Oturma Grubu', 'Oturma Odası', 'Turuncu-krem kapitone koltuk takımı.'],
        'product-11.jpg' => ['Klasik Mutfak Dolabı', 'Mutfak', 'Klasik kapaklı mutfak dolabı seti.'],
        'product-12.jpg' => ['Çalışma Masası ve Kitaplık', 'Yatak Odası', 'Kitaplıklı çalışma masası seti.'],
        'product-13.jpg' => ['Havuz Başı Kamelya', 'Bahçe', 'Ahşap kamelya ve oturma alanı.'],
        'product-14.jpg' => ['Şifonyer ve Ayna', 'Yatak Odası', 'Aynalı şifonyer ünitesi.'],
        'product-15.jpg' => ['Kapitone Yatak Takımı', 'Yatak Odası', 'Kapitone başlıklı yatak seti.'],
        'product-16.jpg' => ['Cam Yemek Masası Seti', 'Yemek Odası', 'Cam tabla, bench ve sandalye seti.'],
        'product-17.jpg' => ['Gardrop ve Şifonyer', 'Yatak Odası', 'Aynalı gardrop ve şifonyer takımı.'],
        'product-18.jpg' => ['Kent Mobilyası Bank Seti', 'Bahçe', 'Pergola altı piknik masası seti.'],
        'product-19.jpg' => ['6 Kapaklı Gardrop', 'Yatak Odası', 'Aynalı kapaklı geniş gardrop.'],
        'product-20.jpg' => ['Gardrop Vitrin Seti', 'Yatak Odası', 'Çok renkli gardrop koleksiyonu.'],
        'product-21.jpg' => ['Hasır Bahçe Oturma Takımı', 'Bahçe', 'Hasır dış mekan oturma seti.'],
        'product-22.jpg' => ['Konsol Ünitesi', 'Oturma Odası', 'Ahşap detaylı konsol ve depolama.'],
        'product-23.jpg' => ['Oval Ahşap Yemek Takımı', 'Yemek Odası', 'Oval masa ve sandalye seti.'],
        'product-24.jpg' => ['Kilim Mindersli Şark Köşesi', 'Balkon', 'Geleneksel kilim minderli şark köşesi.'],
        'product-25.jpg' => ['Yatak Odası Mobilya Seti', 'Yatak Odası', 'Gardrop, şifonyer ve komodin seti.'],
        'product-26.jpg' => ['Ahşap Bahçe Kamelyası', 'Bahçe', 'Bahçe kamelyası yapısı.'],
        'product-27.jpg' => ['Ranza Yatak Seti', 'Yatak Odası', 'Merdivenli ranza ve depolama.'],
        'product-28.jpg' => ['Oval Bouclé Yemek Takımı', 'Yemek Odası', 'Oval masa ve bouclé sandalye seti.'],
        'product-29.jpg' => ['Mermer Yemek Masası Seti', 'Yemek Odası', 'Mermer tabla ve metal ayaklı sandalyeler.'],
        'product-30.jpg' => ['Lüks Altın Detaylı Gardrop', 'Yatak Odası', 'Altın aksesuarlı lüks gardrop.'],
    ];
}

function syncProductCatalog(): int
{
    $catalog = getProductCatalog();
    $db = getDB();
    $update = $db->prepare('UPDATE products SET title = ?, category = ?, description = ? WHERE image = ?');
    $updated = 0;

    foreach ($catalog as $image => [$title, $category, $description]) {
        $update->execute([$title, $category, $description, $image]);
        $updated += $update->rowCount();
    }

    return $updated;
}
