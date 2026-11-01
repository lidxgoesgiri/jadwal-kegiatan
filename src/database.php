<?php

declare(strict_types=1);

function db(): PDO
{
    static $connection = null;
    if ($connection instanceof PDO) {
        return $connection;
    }
    $environment = env_value('APP_ENV', 'production');
    $host = env_value('DB_HOST', '127.0.0.1');
    $port = filter_var(env_value('DB_PORT', '3306'), FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => 65535]]);
    $database = env_value('DB_DATABASE', 'jadwal_kegiatan');
    $username = env_value('DB_USERNAME', '');
    $password = env_value('DB_PASSWORD');
    $ca = env_value('DB_SSL_CA');
    if (!in_array($environment, ['local', 'production'], true)
        || !preg_match('/^[a-zA-Z0-9.:-]+$/D', $host)
        || !preg_match('/^[a-zA-Z0-9_]+$/D', $database)
        || $port === false || $username === '') {
        throw new RuntimeException('Konfigurasi environment database tidak valid.');
    }
    if ($environment === 'production' && ($password === '' || $ca === '')) {
        throw new RuntimeException('Production memerlukan password dan DB_SSL_CA.');
    }
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 5,
    ];
    if ($ca !== '') {
        if (!is_readable($ca)) {
            throw new RuntimeException('Berkas CA database tidak dapat dibaca.');
        }
        $options[PDO::MYSQL_ATTR_SSL_CA] = $ca;
        $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
    }
    $connection = new PDO(
        "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4",
        $username, $password, $options
    );
    return $connection;
}

function find_kegiatan(int $id): array
{
    $query = db()->prepare('SELECT id, nama_kegiatan, tanggal, waktu, tempat FROM kegiatan WHERE id = :id');
    $query->execute(['id' => $id]);
    $item = $query->fetch();
    if ($item === false) {
        fail(404, 'Kegiatan tidak ditemukan. Data mungkin sudah dihapus.');
    }
    return $item;
}
