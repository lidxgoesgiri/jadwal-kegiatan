<?php

declare(strict_types=1);
require __DIR__ . '/src/bootstrap.php';
allow_methods(['GET', 'POST']);
$id = requested_id();
$item = find_kegiatan($id);
$values = [
    'nama_kegiatan' => $item['nama_kegiatan'], 'tanggal' => $item['tanggal'],
    'waktu' => format_waktu($item['waktu']), 'tempat' => $item['tempat'],
];
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
            $query = db()->prepare('UPDATE kegiatan SET nama_kegiatan = :nama_kegiatan, tanggal = :tanggal, waktu = :waktu, tempat = :tempat WHERE id = :id');
            $query->execute($values + ['id' => $id]);
            redirect_home('Perubahan kegiatan berhasil disimpan.');
        } catch (Throwable $error) {
            error_log('Edit kegiatan: ' . $error->getMessage());
            $errors['_form'] = 'Perubahan belum tersimpan. Periksa koneksi database, lalu coba lagi. Isian Anda tetap tersedia.';
            $status = 503;
        }
    }
}
render('form', ['title' => 'Edit kegiatan', 'activePage' => 'edit', 'mode' => 'edit',
    'id' => $id, 'values' => $values, 'errors' => $errors], $status);
