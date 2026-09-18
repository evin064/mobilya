<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/../includes/contact-form.php';
requireLogin();

markContactMessagesAsRead();
$messages = getContactMessages();
$pageTitle = 'İletişim Mesajları';

require __DIR__ . '/includes/header.php';
?>

<div class="admin-toolbar">
  <h2>Gelen Mesajlar</h2>
</div>

<?php if (empty($messages)): ?>
  <div class="empty-state">
    <p>Henüz iletişim formundan mesaj gelmemiş.</p>
  </div>
<?php else: ?>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Ad Soyad</th>
          <th>E-posta</th>
          <th>Telefon</th>
          <th>Konu</th>
          <th>Mesaj</th>
          <th>Tarih</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($messages as $message): ?>
          <tr>
            <td><?= (int) $message['id'] ?></td>
            <td><strong><?= e($message['name']) ?></strong></td>
            <td><a href="mailto:<?= e($message['email']) ?>"><?= e($message['email']) ?></a></td>
            <td><?= e($message['phone'] ?: '—') ?></td>
            <td><?= e(contactSubjectOptions()[$message['subject']] ?? $message['subject'] ?: '—') ?></td>
            <td class="desc-cell"><?= e($message['message']) ?></td>
            <td><?= date('d.m.Y H:i', strtotime($message['created_at'])) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
