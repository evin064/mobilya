<?php
session_start();

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/contact-form.php';

$pageTitle = 'İletişim — ' . SITE_NAME;
$subjects = contactSubjectOptions();
$success = false;
$error = '';
$form = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'subject' => 'genel',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'subject' => trim($_POST['subject'] ?? 'genel'),
        'message' => trim($_POST['message'] ?? ''),
    ];

    try {
        if (!verifyCsrf($_POST['csrf_token'] ?? null)) {
            throw new InvalidArgumentException('Oturum doğrulaması başarısız. Lütfen tekrar deneyin.');
        }

        saveContactMessage($form);
        $success = true;
        $form = [
            'name' => '',
            'email' => '',
            'phone' => '',
            'subject' => 'genel',
            'message' => '',
        ];
    } catch (InvalidArgumentException $e) {
        $error = $e->getMessage();
    } catch (Throwable $e) {
        $error = 'Mesajınız gönderilemedi. Lütfen daha sonra tekrar deneyin.';
    }
}

require __DIR__ . '/includes/header.php';
?>

  <section class="contact-page">
    <div class="contact-hero">
      <img
        src="assets/images/hero-banner.png?v=<?= filemtime(__DIR__ . '/assets/images/hero-banner.png') ?>"
        alt=""
        class="contact-hero__bg"
        aria-hidden="true"
      >
      <div class="contact-hero__overlay"></div>
      <div class="container contact-hero__inner reveal">
        <span class="about__tag">İletişim</span>
        <h1 class="contact-hero__title">İletişime Geçin</h1>
        <p class="contact-hero__desc">Özel ölçü talepleriniz, keşif randevusu veya koleksiyon hakkında sorularınız için bize ulaşın.</p>
      </div>
    </div>

    <div class="container">
      <div class="contact-quick reveal">
        <a href="<?= contactTelUrl() ?>" class="contact-quick__card">
          <span class="contact-quick__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
          </span>
          <span class="contact-quick__label">Telefon</span>
          <span class="contact-quick__value"><?= e(CONTACT_PHONE_DISPLAY) ?></span>
        </a>
        <a href="<?= contactMailtoUrl() ?>" class="contact-quick__card">
          <span class="contact-quick__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
          </span>
          <span class="contact-quick__label">E-posta</span>
          <span class="contact-quick__value"><?= e(CONTACT_EMAIL) ?></span>
        </a>
        <a href="<?= e(contactWhatsAppUrl()) ?>" class="contact-quick__card" target="_blank" rel="noopener noreferrer">
          <span class="contact-quick__icon" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C6.48 2 2 6.15 2 11.25c0 1.98.56 3.84 1.53 5.42L2 21l4.47-1.17A9.86 9.86 0 0 0 12 20.5c5.52 0 10-4.15 10-9.25S17.52 2 12 2z"/><path d="M9.2 9.6c.35 1.05 1.35 2.55 2.85 3.35.95.48 1.75.65 2.45.7"/></svg>
          </span>
          <span class="contact-quick__label">WhatsApp</span>
          <span class="contact-quick__value">Hemen Yazın</span>
        </a>
      </div>

      <div class="contact-page__layout">
        <aside class="contact-page__aside reveal">
          <div class="contact-info-card contact-map-card">
            <span class="about__tag">Konum</span>
            <div class="contact-map">
              <iframe
                src="<?= e(contactMapEmbedUrl()) ?>"
                title="Turan Mobilya konum haritası — <?= e(CONTACT_ADDRESS) ?>"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen
              ></iframe>
            </div>
            <a href="<?= e(contactMapsUrl()) ?>" class="contact-map__link" target="_blank" rel="noopener noreferrer">
              Haritada Aç
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M7 17L17 7M17 7H8M17 7v9"/></svg>
            </a>
          </div>

          <div class="contact-hours-card">
            <div class="contact-hours-card__head">
              <span class="contact-hours-card__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
              </span>
              <h3>Çalışma Saatleri</h3>
            </div>
            <ul class="contact-hours-list">
              <li class="contact-hours-list__item">
                <span class="contact-hours-list__day">Pzt – Cmt</span>
                <span class="contact-hours-list__time contact-hours-list__time--open">09:00 – 18:00</span>
              </li>
              <li class="contact-hours-list__item">
                <span class="contact-hours-list__day">Pazar</span>
                <span class="contact-hours-list__time contact-hours-list__time--closed">Kapalı</span>
              </li>
            </ul>
          </div>

          <p class="contact-aside__note">Ücretsiz keşif ve ölçü danışmanlığı için formu doldurun veya yukarıdaki iletişim kanallarından bize ulaşın.</p>
        </aside>

        <div class="contact-page__form-panel reveal">
          <div class="contact-form__head">
            <h2>Mesaj Gönderin</h2>
            <p>Talebinizi iletin; en kısa sürede size dönüş yapalım.</p>
          </div>

          <?php if ($success): ?>
            <div class="contact-alert contact-alert--success" role="status">
              Mesajınız başarıyla iletildi. En kısa sürede sizinle iletişime geçeceğiz.
            </div>
          <?php endif; ?>

          <?php if ($error): ?>
            <div class="contact-alert contact-alert--error" role="alert">
              <?= e($error) ?>
            </div>
          <?php endif; ?>

          <form class="contact-form" method="post" action="contact.php">
            <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">

            <div class="contact-form__row">
              <div class="contact-form__field">
                <label for="contact-name">Ad Soyad *</label>
                <input
                  type="text"
                  id="contact-name"
                  name="name"
                  value="<?= e($form['name']) ?>"
                  required
                  autocomplete="name"
                  placeholder="Adınız ve soyadınız"
                >
              </div>
              <div class="contact-form__field">
                <label for="contact-phone">Telefon</label>
                <input
                  type="tel"
                  id="contact-phone"
                  name="phone"
                  value="<?= e($form['phone']) ?>"
                  autocomplete="tel"
                  placeholder="+90 5XX XXX XX XX"
                >
              </div>
            </div>

            <div class="contact-form__field">
              <label for="contact-email">E-posta *</label>
              <input
                type="email"
                id="contact-email"
                name="email"
                value="<?= e($form['email']) ?>"
                required
                autocomplete="email"
                placeholder="ornek@email.com"
              >
            </div>

            <div class="contact-form__field">
              <label for="contact-subject">Konu</label>
              <select id="contact-subject" name="subject">
                <?php foreach ($subjects as $value => $label): ?>
                  <option value="<?= e($value) ?>" <?= $form['subject'] === $value ? 'selected' : '' ?>>
                    <?= e($label) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="contact-form__field">
              <label for="contact-message">Mesajınız *</label>
              <textarea
                id="contact-message"
                name="message"
                rows="6"
                required
                minlength="3"
                placeholder="Talebinizi veya sorunuzu kısaca yazın..."
              ><?= e($form['message']) ?></textarea>
            </div>

            <button type="submit" class="btn btn--brand contact-form__submit">Mesaj Gönder</button>
          </form>
        </div>
      </div>
    </div>
  </section>

<?php require __DIR__ . '/includes/footer.php'; ?>
