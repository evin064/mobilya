  <footer class="footer">
    <div id="contact" class="footer__cta">
      <img
        src="assets/products/product-05.jpg"
        alt=""
        class="footer__cta-bg"
        aria-hidden="true"
      >
      <div class="footer__cta-overlay"></div>
      <div class="container footer__cta-inner reveal">
        <span class="footer__cta-tag">İletişim</span>
        <h2 class="footer__cta-title">Hayalinizdeki Mekânı Birlikte Tasarlayalım</h2>
        <p>Ücretsiz keşif ve danışmanlık için bize ulaşın.</p>
        <div class="footer__cta-actions">
          <a href="contact.php" class="btn btn--light">İletişime Geçin</a>
          <a href="<?= contactTelUrl() ?>" class="btn btn--outline-light"><?= e(CONTACT_PHONE_DISPLAY) ?></a>
        </div>
      </div>
    </div>

    <div class="footer__body">
      <div class="container footer__grid">
        <div class="footer__brand">
          <a href="index.php" class="footer__logo">
            <span class="footer__logo-name">TURAN</span>
            <span class="footer__logo-sub">— MOBİLYA —</span>
          </a>
          <p class="footer__brand-desc">Doğal malzemeler ve usta işçilikle yaşam alanlarınıza değer katıyoruz.</p>
          <div class="footer__social">
            <a href="<?= e(SOCIAL_INSTAGRAM) ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
            </a>
            <a href="<?= e(SOCIAL_FACEBOOK) ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
              </svg>
            </a>
            <a href="<?= e(contactWhatsAppUrl()) ?>" target="_blank" rel="noopener noreferrer" class="footer__social-wp" aria-label="WhatsApp">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 2C6.48 2 2 6.15 2 11.25c0 1.98.56 3.84 1.53 5.42L2 21l4.47-1.17A9.86 9.86 0 0 0 12 20.5c5.52 0 10-4.15 10-9.25S17.52 2 12 2z"/>
                <path d="M9.2 9.6c.35 1.05 1.35 2.55 2.85 3.35.95.48 1.75.65 2.45.7"/>
              </svg>
            </a>
          </div>
        </div>

        <div class="footer__col">
          <h4>Sayfalar</h4>
          <a href="<?= pageAnchor('home') ?>">Ana Sayfa</a>
          <a href="<?= pageAnchor('products') ?>">Koleksiyon</a>
          <?php foreach (getNavCategories() as $label => $slug): ?>
            <a href="<?= pageCategoryAnchor($label) ?>"><?= e($label) ?></a>
          <?php endforeach; ?>
          <a href="<?= pageAnchor('services') ?>">Hizmetler</a>
          <a href="<?= pageAnchor('about') ?>">Hakkımızda</a>
          <a href="<?= pageAnchor('contact') ?>">İletişim</a>
        </div>

        <div class="footer__col footer__col--contact">
          <h4>İletişim</h4>
          <a href="<?= contactMailtoUrl() ?>"><?= e(CONTACT_EMAIL) ?></a>
          <a href="<?= contactTelUrl() ?>"><?= e(CONTACT_PHONE_DISPLAY) ?></a>
          <span><?= e(CONTACT_ADDRESS) ?></span>
        </div>
      </div>

      <div class="container footer__newsletter">
        <form class="newsletter-form" onsubmit="return false;">
          <input type="email" placeholder="E-posta adresiniz" aria-label="E-posta">
          <button type="submit" class="newsletter-form__btn" aria-label="Abone ol">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </button>
        </form>
      </div>
    </div>

    <div class="container footer__bottom">
      <span>© <?= date('Y') ?> TURAN MOBİLYA. Tüm hakları saklıdır.</span>
      <a href="#">Gizlilik Politikası</a>
    </div>

    <div class="container footer__credit">
      <p class="footer__credit-inner">
        Web Tasarım &amp; Yazılım —
        <a href="https://evnasoft.com/" target="_blank" rel="noopener noreferrer" class="footer__credit-brand">EvnaSoft</a>
        <span class="footer__credit-text">tarafından geliştirilmiştir.</span>
      </p>
    </div>
  </footer>

  <script src="script.js"></script>
</body>
</html>
