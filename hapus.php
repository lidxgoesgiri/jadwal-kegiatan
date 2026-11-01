<?php

declare(strict_types=1);
require __DIR__ . '/src/bootstrap.php';
allow_methods(['GET', 'POST']);
$id = requested_id();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !csrf_valid()) {
    fail(400, 'Konfirmasi tidak berlaku. Buka kembali halaman konfirmasi penghapusan.');
}
$item = find_kegiatan($id);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query = db()->prepare('DELETE FROM kegiatan WHERE id = :id');
    $query->execute(['id' => $id]);
    redirect_home('Kegiatan berhasil dihapus.');
}
// GET hanya menampilkan konfirmasi. Data baru dihapus setelah POST dengan token valid.
render('hapus', ['title' => 'Hapus kegiatan', 'item' => $item, 'activePage' => 'hapus']);
