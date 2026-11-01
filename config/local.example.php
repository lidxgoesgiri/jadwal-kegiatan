<?php
// Salin menjadi local.php hanya untuk XAMPP. Environment server mengalahkan nilai ini.
return [
    'APP_ENV' => 'local',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'jadwal_kegiatan',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '', // Default XAMPP lokal; sesuaikan jika MySQL Anda memakai password.
    'DB_SSL_CA' => '',  // Pada Azure production wajib diisi jalur CA bundle yang valid.
];
