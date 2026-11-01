<?php

declare(strict_types=1);
require __DIR__ . '/src/bootstrap.php';
allow_methods(['GET', 'HEAD']);
$kegiatan = db()->query(
    'SELECT id, nama_kegiatan, tanggal, waktu, tempat FROM kegiatan ORDER BY tanggal ASC, waktu ASC, id ASC'
)->fetchAll();
render('daftar', ['kegiatan' => $kegiatan, 'activePage' => 'daftar']);
