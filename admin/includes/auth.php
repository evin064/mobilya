<?php

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/products.php';
require_once __DIR__ . '/../../includes/contact-form.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool
{
    return !empty($_SESSION['admin_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        redirect('login.php');
    }
}

function loginAdmin(string $username, string $password): bool
{
    $db = getDB();
    $stmt = $db->prepare('SELECT * FROM admins WHERE username = ?');
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_user'] = $admin['username'];
        return true;
    }

    return false;
}

function logoutAdmin(): void
{
    $_SESSION = [];
    session_destroy();
}

function getCurrentAdmin(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }

    $db = getDB();
    $stmt = $db->prepare('SELECT id, username FROM admins WHERE id = ?');
    $stmt->execute([(int) $_SESSION['admin_id']]);
    $admin = $stmt->fetch();

    return $admin ?: null;
}

function changeAdminPassword(int $adminId, string $currentPassword, string $newPassword, string $confirmPassword): array
{
    if ($newPassword !== $confirmPassword) {
        return ['success' => false, 'message' => 'Yeni şifreler eşleşmiyor.'];
    }

    if (strlen($newPassword) < 6) {
        return ['success' => false, 'message' => 'Yeni şifre en az 6 karakter olmalıdır.'];
    }

    $db = getDB();
    $stmt = $db->prepare('SELECT password FROM admins WHERE id = ?');
    $stmt->execute([$adminId]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($currentPassword, $admin['password'])) {
        return ['success' => false, 'message' => 'Mevcut şifre hatalı.'];
    }

    $stmt = $db->prepare('UPDATE admins SET password = ? WHERE id = ?');
    $stmt->execute([
        password_hash($newPassword, PASSWORD_DEFAULT),
        $adminId,
    ]);

    return ['success' => true, 'message' => 'Şifreniz başarıyla güncellendi.'];
}

function getAdminAccounts(): array
{
    $db = getDB();

    return $db->query('SELECT id, username FROM admins ORDER BY username ASC')->fetchAll();
}

function createAdminAccount(string $username, string $password, string $confirmPassword): array
{
    $username = trim($username);

    if ($username === '') {
        return ['success' => false, 'message' => 'Kullanıcı adı boş olamaz.'];
    }

    if (strlen($username) < 3) {
        return ['success' => false, 'message' => 'Kullanıcı adı en az 3 karakter olmalıdır.'];
    }

    if (!preg_match('/^[a-zA-Z0-9._-]+$/', $username)) {
        return ['success' => false, 'message' => 'Kullanıcı adı yalnızca harf, rakam, nokta, tire ve alt çizgi içerebilir.'];
    }

    if ($password !== $confirmPassword) {
        return ['success' => false, 'message' => 'Şifreler eşleşmiyor.'];
    }

    if (strlen($password) < 6) {
        return ['success' => false, 'message' => 'Şifre en az 6 karakter olmalıdır.'];
    }

    $db = getDB();
    $stmt = $db->prepare('SELECT id FROM admins WHERE username = ?');
    $stmt->execute([$username]);

    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Bu kullanıcı adı zaten kullanılıyor.'];
    }

    $stmt = $db->prepare('INSERT INTO admins (username, password) VALUES (?, ?)');
    $stmt->execute([
        $username,
        password_hash($password, PASSWORD_DEFAULT),
    ]);

    return ['success' => true, 'message' => '"' . $username . '" hesabı başarıyla oluşturuldu.'];
}
