<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/products.php';

$products = getProducts();
$categories = getProductCategories();
$pageTitle = SITE_NAME . ' — Lüks Mobilya';

$categoryShowcase = getCategoryShowcase();

require __DIR__ . '/includes/header.php';
?>

  <!-- Hero -->
  <section id="home" class="hero">
    <div class="hero__slides" aria-hidden="true">
      <?php
      $heroSlides = [
          [
              'src' => 'assets/images/hero-banner.png',
              'alt' => 'Turan Mobilya modern oturma odası',
          ],
          [
              'src' => 'assets/products/product-05.jpg',
              'alt' => 'Turan Mobilya oturma odası koleksiyonu',
          ],
      ];
      foreach ($heroSlides as $index => $slide):
          $slidePath = __DIR__ . '/' . $slide['src'];
          $version = is_file($slidePath) ? filemtime($slidePath) : time();
      ?>
        <img
          src="<?= e($slide['src']) ?>?v=<?= $version ?>"
          alt="<?= e($slide['alt']) ?>"
          class="hero__slide<?= $index === 0 ? ' is-active hero__slide--brand' : '' ?>"
          loading="<?= $index === 0 ? 'eager' : 'lazy' ?>"
          decoding="async"
        >
      <?php endforeach; ?>
    </div>
    <div class="hero__overlay"></div>
    <div class="hero__content">
      <div class="hero__content-inner">
        <h1 class="hero__title">Yaşam Alanlarınıza<br>Sanat Katarız</h1>
        <p class="hero__desc">Özel tasarım mobilyalarla evinizi benzersiz bir deneyime dönüştürün.</p>
        <a href="#products" class="btn btn--dark hero__btn">Koleksiyonu Keşfet</a>
      </div>
    </div>
  </section>

  <!-- Categories -->
  <section class="categories">
    <div class="container">
      <div class="categories__header reveal">
        <span class="section-tag">Kategoriler</span>
        <h2 class="section-title section-title--sm">Her Oda İçin Özel Çözümler</h2>
      </div>
      <div class="categories__grid">
        <?php foreach ($categoryShowcase as $item): ?>
          <a href="#products" class="category-card reveal" data-category="<?= e($item[0]) ?>">
            <img src="assets/products/<?= e($item[1]) ?>" alt="<?= e($item[0]) ?>" loading="lazy">
            <div class="category-card__overlay"></div>
            <div class="category-card__content">
              <h3><?= e($item[0]) ?></h3>
              <p><?= e($item[2]) ?></p>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Products -->
  <section id="products" class="products">
    <div class="container">
      <div class="products__header reveal">
        <span class="section-tag">Koleksiyon</span>
        <h2 class="section-title section-title--sm">Seçkin Mobilya Koleksiyonu</h2>
      </div>

      <?php if (!empty($categories)): ?>
        <div class="products__filters reveal" role="tablist" aria-label="Ürün kategorileri">
          <button type="button" class="filter-btn is-active" data-filter="all">Tümü</button>
          <?php foreach ($categories as $cat): ?>
            <button type="button" class="filter-btn" data-filter="<?= e($cat) ?>"><?= e($cat) ?></button>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (empty($products)): ?>
        <p class="products__empty">Henüz ürün eklenmemiş.</p>
      <?php else: ?>
        <div class="products__grid">
          <?php foreach ($products as $product): ?>
            <a
              href="product.php?id=<?= (int) $product['id'] ?>"
              class="product-card reveal"
              data-category="<?= e($product['category']) ?>"
            >
              <div class="product-card__image">
                <?php if (!empty($product['image']) && productImageUrl($product['image'])): ?>
                  <img
                    src="<?= e(productImageUrl($product['image'])) ?>"
                    alt="<?= e($product['title']) ?>"
                    loading="lazy"
                  >
                <?php else: ?>
                  <div class="product-card__placeholder"></div>
                <?php endif; ?>
              </div>
              <div class="product-card__body">
                <span class="product-card__category"><?= e($product['category']) ?></span>
                <h3 class="product-card__title"><?= e($product['title']) ?></h3>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Showcase Banner -->
  <section class="showcase">
    <img src="assets/products/product-05.jpg" alt="Turan Mobilya oturma odası koleksiyonu" class="showcase__image">
    <div class="showcase__overlay"></div>
    <div class="container showcase__content reveal">
      <span class="showcase__tag">Öne Çıkan Koleksiyon</span>
      <h2 class="showcase__title">Konforun ve Estetiğin<br>Mükemmel Uyumu</h2>
      <p class="showcase__desc">Modern çizgiler, doğal dokular ve özenle seçilmiş kumaşlarla tasarlanmış oturma gruplarımızı keşfedin.</p>
      <a href="#products" class="btn btn--light">Koleksiyonu İncele</a>
    </div>
  </section>

  <!-- About -->
  <section id="about" class="about">
    <div class="container about__inner">
      <div class="about__media reveal">
        <img src="assets/products/product-22.jpg" alt="Turan Mobilya üretim ve tasarım" class="about__image">
        <div class="about__badge">
          <span>15+</span>
          <small>Yıl Deneyim</small>
        </div>
      </div>
      <div class="about__content reveal">
        <span class="about__tag">Hakkımızda</span>
        <h2 class="about__title">Fikirler Sanatla Buluşuyor</h2>
        <p>TURAN MOBİLYA olarak, her mobilyayı bir sanat eseri olarak görüyoruz. Doğal malzemeler, usta işçilik ve zamansız tasarım anlayışıyla yaşam alanlarınıza değer katıyoruz.</p>
        <p>Özel ölçü üretimden hazır koleksiyonlara kadar geniş bir yelpazede hizmet sunuyoruz.</p>
        <ul class="about__features">
          <li>Doğal ve kaliteli malzemeler</li>
          <li>Özel ölçü üretim imkânı</li>
          <li>Profesyonel montaj desteği</li>
        </ul>
        <a href="contact.php" class="btn btn--brand">Daha Fazla Bilgi</a>
      </div>
    </div>
  </section>

  <!-- Services -->
  <section id="services" class="services">
    <div class="container">
      <div class="services__header reveal">
        <span class="services__tag">Hizmetler</span>
        <h2 class="services__title">Size Özel İç Mekan Çözümleri</h2>
        <p class="services__intro">İhtiyacınıza göre tasarlanmış, uçtan uca mobilya ve iç mekân hizmetleri.</p>
      </div>
      <div class="services__grid">
        <a href="#products" class="service-card reveal" data-category="Oturma Odası">
          <img src="assets/products/product-05.jpg" alt="Konut iç mekan oturma odası" class="service-card__image">
          <div class="service-card__overlay"></div>
          <span class="service-card__label">Konut İç Mekan</span>
        </a>
        <a href="#products" class="service-card reveal" data-category="Yemek Odası">
          <img src="assets/products/product-23.jpg" alt="Modern yemek odası takımı" class="service-card__image">
          <div class="service-card__overlay"></div>
          <span class="service-card__label">Yemek Odası</span>
        </a>
        <a href="#products" class="service-card reveal" data-category="Yatak Odası">
          <img src="assets/products/product-04.jpg" alt="Modern yatak odası gardrop" class="service-card__image">
          <div class="service-card__overlay"></div>
          <span class="service-card__label">Yatak Odası</span>
        </a>
      </div>
      <div class="services__cta reveal">
        <a href="#products" class="btn btn--brand">Tüm Hizmetleri Keşfet</a>
      </div>
    </div>
  </section>

  <!-- Testimonial -->
  <section class="testimonial">
    <div class="container">
      <div class="testimonial__header reveal">
        <span class="testimonial__tag">Müşteri Yorumları</span>
        <h2 class="testimonial__title">Güvenin Sesi</h2>
        <p class="testimonial__intro">Binlerce mutlu müşterimizin deneyimlerinden ilham alın.</p>
      </div>
      <div class="testimonial__inner">
        <div class="testimonial__media reveal">
          <img
            src="assets/products/product-05.jpg"
            alt="Turan Mobilya ile döşenmiş modern oturma odası"
            class="testimonial__image"
          >
          <div class="testimonial__accent">
            <img src="assets/products/product-04.jpg" alt="" aria-hidden="true">
          </div>
        </div>
        <div class="testimonial__cards">
          <article class="testimonial__card reveal">
            <div class="testimonial__stars" aria-label="5 yıldız">★★★★★</div>
            <blockquote>
              "TURAN MOBİLYA ile çalışmak evimizi tamamen dönüştürdü. Her detay özenle düşünülmüş, kalite beklentilerimizi aştı."
            </blockquote>
            <div class="testimonial__author">
              <div class="testimonial__avatar" aria-hidden="true">ED</div>
              <div>
                <strong>Elif Demir</strong>
                <span>İç Mimar, Zara</span>
              </div>
            </div>
          </article>
          <article class="testimonial__card reveal">
            <div class="testimonial__stars" aria-label="5 yıldız">★★★★★</div>
            <blockquote>
              "Yemek odası takımımız tam istediğimiz gibi oldu. Montaj ekibi son derece profesyoneldi."
            </blockquote>
            <div class="testimonial__author">
              <div class="testimonial__avatar" aria-hidden="true">AK</div>
              <div>
                <strong>Ahmet Kaya</strong>
                <span>Ev Sahibi, Sivas</span>
              </div>
            </div>
          </article>
          <article class="testimonial__card reveal">
            <div class="testimonial__stars" aria-label="5 yıldız">★★★★★</div>
            <blockquote>
              "Balkon mobilyalarımızı TURAN MOBİLYA'dan aldık. Kırmızı minderler terasımıza çok yakıştı, hem şık hem konforlu."
            </blockquote>
            <div class="testimonial__author">
              <div class="testimonial__avatar" aria-hidden="true">SY</div>
              <div>
                <strong>Selin Yıldız</strong>
                <span>İK Müdürü, İzmir</span>
              </div>
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
