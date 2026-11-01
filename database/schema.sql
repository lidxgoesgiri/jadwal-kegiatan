-- Impor melalui phpMyAdmin untuk XAMPP. Tidak memuat data contoh.
CREATE DATABASE IF NOT EXISTS jadwal_kegiatan
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE jadwal_kegiatan;

CREATE TABLE IF NOT EXISTS kegiatan (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nama_kegiatan VARCHAR(150) NOT NULL,
    tanggal DATE NOT NULL,
    waktu TIME NOT NULL,
    tempat VARCHAR(150) NOT NULL,
    INDEX urutan_jadwal (tanggal, waktu, id)
) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
