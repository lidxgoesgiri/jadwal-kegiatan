<?php

declare(strict_types=1);
require __DIR__ . '/src/bootstrap.php';
allow_methods(['GET', 'POST']);
$values = ['nama_kegiatan' => '', 'tanggal' => '', 'waktu' => '', 'tempat' => ''];
$errors = [];
$status = 200;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = form_values();
    $errors = validate_kegiatan($values);
    if (!csrf_valid()) {
        $errors['_form'] = 'Formulir sudah tidak berlaku. Muat ulang halaman, lalu coba lagi.';
        $status = 400;
    } elseif ($errors !== []) {
        $status = 422;
    } else {
        try {
            $query = db()->prepare('INSERT INTO kegiatan (nama_kegiatan, tanggal, waktu, tempat) VALUES (:nama_kegiatan, :tanggal, :waktu, :tempat)');
            $query->execute($values);
            redirect_home('Kegiatan berhasil ditambahkan.');
        } catch (Throwable $error) {
            error_log('Tambah kegiatan: ' . $error->getMessage());
            $errors['_form'] = 'Kegiatan belum tersimpan. Periksa koneksi database, lalu coba lagi. Isian Anda tetap tersedia.';
            $status = 503;
        }
    }
}
render('form', ['title' => 'Tambah kegiatan', 'activePage' => 'tambah', 'mode' => 'tambah',
    'values' => $values, 'errors' => $errors], $status);
