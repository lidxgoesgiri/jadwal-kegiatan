<?php

declare(strict_types=1);

function form_values(): array
{
    $values = [];
    foreach (['nama_kegiatan', 'tanggal', 'waktu', 'tempat'] as $field) {
        // Pangkas whitespace biasa saja. Byte NUL tetap ditolak oleh validasi.
        $values[$field] = is_string($_POST[$field] ?? null) ? trim($_POST[$field], " \t\n\r\v") : '';
    }
    return $values;
}

function validate_kegiatan(array $values): array
{
    $errors = [];
    foreach (['nama_kegiatan' => 'Nama kegiatan', 'tempat' => 'Tempat'] as $field => $label) {
        $text = $values[$field];
        $length = preg_match_all('/./us', $text);
        if ($text === '') {
            $errors[$field] = $label . ' wajib diisi.';
        } elseif ($length === false || $length > 150) {
            $errors[$field] = $label . ' maksimal 150 karakter dengan teks UTF-8 yang valid.';
        } elseif (preg_match('/[\x00-\x1F\x7F]/', $text)) {
            $errors[$field] = $label . ' harus berupa satu baris teks.';
        }
    }
    $date = $values['tanggal'];
    $parsed = preg_match('/^[1-9][0-9]{3}-[0-9]{2}-[0-9]{2}$/D', $date)
        ? DateTimeImmutable::createFromFormat('!Y-m-d', $date) : false;
    // MySQL DATE mendukung tahun 1000 sampai 9999. Tanggal lampau tetap diperbolehkan.
    if (!preg_match('/^[1-9][0-9]{3}-[0-9]{2}-[0-9]{2}$/D', $date)
        || !$parsed || $parsed->format('Y-m-d') !== $date) {
        $errors['tanggal'] = 'Masukkan tanggal yang valid (tahun 1000–9999).';
    }
    if (!preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/D', $values['waktu'])) {
        $errors['waktu'] = 'Masukkan waktu yang valid dalam format jam:menit.';
    }
    return $errors;
}
