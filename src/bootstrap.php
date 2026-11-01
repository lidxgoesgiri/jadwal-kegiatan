<?php

declare(strict_types=1);

// File konfigurasi lokal hanya untuk XAMPP. Environment server selalu diutamakan.
$localConfig = is_file(__DIR__ . '/../config/local.php')
    ? require __DIR__ . '/../config/local.php'
    : [];
if (!is_array($localConfig)) {
    $localConfig = [];
}

function env_value(string $key, string $default = ''): string
{
    global $localConfig;
    $value = getenv($key);
    if ($value !== false) {
        return $value;
    }
    return (string) ($_SERVER[$key] ?? $localConfig[$key] ?? $default);
}

date_default_timezone_set('Asia/Jakarta');
ini_set('display_errors', '0');
ini_set('log_errors', '1');
header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');
header("Content-Security-Policy: default-src 'self'; style-src 'self'; img-src 'self'; script-src 'none'; base-uri 'none'; form-action 'self'; frame-ancestors 'none'");

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = 'index.php'): string
{
    $directory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    return rtrim($directory, '/.') . '/' . ltrim($path, '/');
}

$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
session_name('jadwal_session');
session_start([
    'use_strict_mode' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_secure' => $https,
    'cookie_path' => rtrim(url(''), '/') . '/',
]);
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e($_SESSION['csrf']) . '">';
}

function csrf_valid(): bool
{
    return is_string($_POST['csrf_token'] ?? null)
        && hash_equals($_SESSION['csrf'], $_POST['csrf_token']);
}

function render(string $view, array $data = [], int $status = 200): void
{
    http_response_code($status);
    $title = (string) ($data['title'] ?? 'Pengelolaan Jadwal Kegiatan');
    $activePage = (string) ($data['activePage'] ?? $view);
    $flash = (string) ($_SESSION['flash'] ?? '');
    unset($_SESSION['flash']);
    extract($data, EXTR_SKIP);
    require __DIR__ . '/../views/header.php';
    require __DIR__ . '/../views/' . $view . '.php';
    require __DIR__ . '/../views/footer.php';
}

function fail(int $status, string $message): never
{
    $titles = [400 => 'Permintaan tidak valid', 404 => 'Kegiatan tidak ditemukan',
        405 => 'Metode tidak didukung', 503 => 'Layanan belum tersedia'];
    render('error', ['title' => $titles[$status] ?? 'Terjadi kesalahan',
        'message' => $message, 'status' => $status], $status);
    exit;
}

set_exception_handler(function (Throwable $error): void {
    error_log('Jadwal Kegiatan: ' . $error->getMessage());
    // Pesan halaman tidak membocorkan DSN, SQL, lokasi file, atau password.
    fail(503, 'Layanan belum dapat diakses. Periksa konfigurasi dan koneksi database, lalu coba kembali.');
});

function allow_methods(array $methods): void
{
    if (!in_array($_SERVER['REQUEST_METHOD'], $methods, true)) {
        header('Allow: ' . implode(', ', $methods));
        fail(405, 'Metode permintaan tidak didukung pada halaman ini.');
    }
}

function requested_id(): int
{
    $raw = $_GET['id'] ?? null;
    $id = is_string($raw) ? filter_var($raw, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 2147483647],
    ]) : false;
    if ($id === false) {
        fail(400, 'ID kegiatan tidak valid.');
    }
    return $id;
}

function redirect_home(string $message): never
{
    $_SESSION['flash'] = $message;
    header('Location: ' . url(), true, 303);
    exit;
}

function format_tanggal(string $date): string
{
    $months = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $parsed = new DateTimeImmutable($date);
    return $parsed->format('j') . ' ' . $months[(int) $parsed->format('n')] . ' ' . $parsed->format('Y');
}

function format_waktu(string $time): string
{
    return substr($time, 0, 5);
}

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/validation.php';
