<?php
// Ikon dekoratif dibuat sebagai SVG lokal agar aplikasi tidak bergantung pada CDN.
function ui_icon(string $name): string
{
    $paths = [
        'plus' => '<path d="M12 5v14M5 12h14"/>',
        'arrow' => '<path d="M5 12h14m-6-6 6 6-6 6"/>',
        'back' => '<path d="M19 12H5m6-6-6 6 6 6"/>',
        'edit' => '<path d="m16 4 4 4M4 20l4-1L20 7a2.8 2.8 0 0 0-4-4L4 15l-1 6 5-2"/>',
        'trash' => '<path d="M3 6h18M9 6V3h6v3M6 6l1 15h10l1-15M10 10v7m4-7v7"/>',
        'pin' => '<path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 1 1 14 0Z"/><circle cx="12" cy="10" r="2"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
        'check' => '<path d="m5 12 4 4L19 6"/>',
        'alert' => '<path d="m12 3 10 18H2L12 3Z"/><path d="M12 9v5m0 3v.1"/>',
    ];

    return '<svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'
        . ($paths[$name] ?? $paths['arrow']) . '</svg>';
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#182b37">
    <title><?= e($title) ?> · Ruang Kegiatan</title>
    <link rel="icon" type="image/svg+xml" href="<?= e(url('assets/favicon.svg')) ?>">
    <link rel="stylesheet" href="<?= e(url('assets/style.css')) ?>">
</head>
<body>
    <a class="skip-link" href="#konten">Lewati ke konten</a>
    <div class="site-shell">
        <header class="site-header">
            <a class="brand" href="<?= e(url()) ?>" aria-label="Ruang Kegiatan, halaman daftar kegiatan">
                <span class="brand-mark" aria-hidden="true"><span></span><span></span><span></span></span>
                <span class="brand-text">Ruang Kegiatan<span>Pengelolaan Jadwal Kegiatan</span></span>
            </a>
            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="<?= e(url()) ?>" <?= $activePage === 'daftar' ? 'aria-current="page"' : '' ?>>Daftar kegiatan</a>
            </nav>
        </header>
        <main id="konten">
            <?php if ($flash !== ''): ?>
                <div class="notice notice-success" role="status">
                    <?= ui_icon('check') ?><p><?= e($flash) ?></p>
                </div>
            <?php endif; ?>
