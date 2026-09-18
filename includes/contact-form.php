<?php

function ensureContactMessagesTable(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS contact_messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT DEFAULT '',
            subject TEXT DEFAULT '',
            message TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            read_at DATETIME DEFAULT NULL
        )
    ");

    $columns = array_column(
        $pdo->query('PRAGMA table_info(contact_messages)')->fetchAll(),
        'name'
    );

    if (!in_array('read_at', $columns, true)) {
        $pdo->exec('ALTER TABLE contact_messages ADD COLUMN read_at DATETIME DEFAULT NULL');
    }
}

function csrfToken(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrf(?string $token): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

function saveContactMessage(array $input): void
{
    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    $phone = trim($input['phone'] ?? '');
    $subject = trim($input['subject'] ?? '');
    $message = trim($input['message'] ?? '');

    if ($name === '' || mb_strlen($name) < 2) {
        throw new InvalidArgumentException('Lütfen geçerli bir ad soyad girin.');
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Lütfen geçerli bir e-posta adresi girin.');
    }

    if ($message === '' || mb_strlen($message) < 3) {
        throw new InvalidArgumentException('Lütfen mesajınızı yazın (en az 3 karakter).');
    }

    $allowedSubjects = contactSubjectOptions();
    if ($subject !== '' && !isset($allowedSubjects[$subject])) {
        throw new InvalidArgumentException('Lütfen geçerli bir konu seçin.');
    }

    $db = getDB();
    $stmt = $db->prepare('
        INSERT INTO contact_messages (name, email, phone, subject, message)
        VALUES (?, ?, ?, ?, ?)
    ');
    $stmt->execute([$name, $email, $phone, $subject, $message]);
}

function contactSubjectOptions(): array
{
    return [
        'genel' => 'Genel Bilgi',
        'urun' => 'Ürün Hakkında',
        'ozel-olcu' => 'Özel Ölçü Talebi',
        'montaj' => 'Montaj & Teslimat',
    ];
}

function getContactMessages(): array
{
    $db = getDB();
    return $db->query('SELECT * FROM contact_messages ORDER BY created_at DESC')->fetchAll();
}

function getContactMessageCount(): int
{
    $db = getDB();
    return (int) $db->query('SELECT COUNT(*) FROM contact_messages WHERE read_at IS NULL')->fetchColumn();
}

function markContactMessagesAsRead(): void
{
    $db = getDB();
    $db->exec('UPDATE contact_messages SET read_at = CURRENT_TIMESTAMP WHERE read_at IS NULL');
}
