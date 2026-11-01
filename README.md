# Pengelolaan Jadwal Kegiatan

Aplikasi PHP native untuk menambah, melihat, mengedit, dan menghapus kegiatan. Daftar diurutkan berdasarkan tanggal dan waktu paling awal secara kronologis. Satu tabel `kegiatan` menyimpan `id`, `nama_kegiatan`, `tanggal`, `waktu`, dan `tempat`.

Antarmuka **Ruang Kegiatan** memakai HTML dan CSS tanpa framework atau JavaScript. Penghapusan melalui halaman konfirmasi dan hanya dilaksanakan oleh request POST. Tidak ada login, notifikasi, integrasi kalender, atau layanan tambahan.

## Mulai menjalankan

1. Ekstrak folder `jadwal-kegiatan` ke `C:\xampp\htdocs\jadwal-kegiatan`.
2. Jalankan Apache dan MySQL melalui XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`, lalu impor `database/schema.sql` dari tab Import pada tingkat server.
4. Salin `config/local.example.php` menjadi `config/local.php`, lalu isi konfigurasi sesuai MySQL lokal.
5. Buka `http://localhost/jadwal-kegiatan/` dan tambahkan kegiatan pertama.

Paket tidak mengisi data contoh otomatis; database awal kosong. Panduan XAMPP lengkap disimpan di folder `docs/` pada laptop dan tidak disertakan dalam repository.

## Fitur dan aturan data

| Operasi | Halaman | Perilaku |
| --- | --- | --- |
| Lihat | `index.php` | Menampilkan kegiatan berurutan menurut tanggal, waktu, lalu ID |
| Tambah | `tambah.php` | Formulir dengan nama kegiatan, tanggal, waktu, dan tempat |
| Edit | `edit.php?id=ID` | Memuat data yang dipilih dan menyimpan perubahannya |
| Hapus | `hapus.php?id=ID` | GET menampilkan konfirmasi; POST dengan token valid menghapus |
| Kesiapan | `health.php` | Memeriksa kesiapan aplikasi dan akses tabel database |

Semua kolom formulir wajib terisi. Nama kegiatan dan tempat maksimal 150 karakter Unicode; tanggal harus valid dan waktu menggunakan format jam serta menit. Tanggal lampau tetap diperbolehkan untuk pencatatan kegiatan yang sudah berlangsung. ID tidak ditulis pengguna pada formulir dan dibuat otomatis oleh database.

Penyimpanan dan perubahan memakai prepared statement PDO. Output di-escape ketika ditampilkan. Token CSRF melindungi perubahan data; redirect setelah request berhasil mencegah pengiriman ulang saat refresh. Pesan koneksi gagal tidak menampilkan password atau rincian kueri kepada pengunjung.

## Struktur proyek

```text
jadwal-kegiatan/
├── index.php
├── tambah.php
├── edit.php
├── hapus.php
├── health.php
├── .env.example
├── .gitignore
├── .htaccess
├── assets/                  # CSS dan ikon lokal
├── src/                     # Konfigurasi, koneksi PDO, validasi, dan helper
├── views/                   # Template halaman
├── config/
│   ├── local.example.php    # Contoh konfigurasi XAMPP
│   └── local.php            # Dibuat sendiri, tidak dilacak Git
├── database/
│   ├── schema.sql           # Membuat database lokal dan tabel
│   └── table.sql            # Membuat tabel dalam database Azure yang dipilih
├── azure/
│   ├── startup.sh           # Memasang konfigurasi NGINX saat App Service mulai
│   └── default.conf
└── certs/
    ├── .htaccess
    └── azure-mysql-ca.pem   # Ditambahkan setelah bundle CA publik dibuat
```

Folder `src`, `views`, `config`, `database`, `certs`, dan `azure` tidak ditujukan untuk akses browser. Apache memakai aturan `.htaccess`; Azure PHP menggunakan konfigurasi NGINX melalui startup script. Pertahankan `.htaccess` di root dan setiap folder internal. Mengabaikan file pada Git saja tidak menggantikan pembatasan akses web.

Folder `docs/`, screenshot, data pengujian lokal, ZIP, dan log tidak disertakan dalam repository. Simpan dokumentasi dan bukti praktikum di laptop.

## Konfigurasi

Aplikasi membaca environment variable yang disediakan proses PHP/Apache atau App Service. Untuk XAMPP, `config/local.php` menyediakan nilai lokal bila environment variable terkait belum ditetapkan. **File `.env.example` hanya referensi nama pengaturan; menyalinnya menjadi `.env` tidak otomatis memuat nilainya.** Tidak ada library dotenv.

| Nama | Contoh lokal | Keterangan Azure |
| --- | --- | --- |
| `APP_ENV` | `local` | Wajib `production` untuk deployment ini |
| `DB_HOST` | `127.0.0.1` | Hostname MySQL eksternal, bukan `localhost` |
| `DB_PORT` | `3306` | Port database eksternal |
| `DB_DATABASE` | `jadwal_kegiatan` | Database yang sudah dibuat pada server |
| `DB_USERNAME` | `root` | Akun aplikasi dengan izin CRUD |
| `DB_PASSWORD` | Kosong hanya pada XAMPP default | Password nonkosong melalui App Service settings |
| `DB_SSL_CA` | Kosong untuk lokal | Path absolut berkas CA PEM untuk TLS |

Mode `production` mewajibkan password nonkosong dan berkas CA yang dapat dibaca. Koneksi menggunakan verifikasi sertifikat TLS. Jangan mengunggah `config/local.php`, `.env`, atau kredensial asli ke repository. Sertifikat CA publik boleh disertakan karena bukan private key.

## Deployment GitHub ke Azure

Panduan Azure lengkap disimpan di `docs/AZURE.md` pada laptop. Siapkan MySQL eksternal dengan TLS dan App Service Linux/PHP, buat tabel dari `database/table.sql`, lalu isi environment variables sesuai tabel konfigurasi di atas. Aplikasi tidak membutuhkan Composer, Node.js, Docker, atau build frontend.

Setelah bundle CA publik dibuat, commit dan push `certs/azure-mysql-ca.pem`. Atur `DB_SSL_CA` menjadi `/home/site/wwwroot/certs/azure-mysql-ca.pem` dan startup command menjadi `bash /home/site/wwwroot/azure/startup.sh`. Hubungkan Deployment Center ke repository GitHub dan branch `main`; artefak deployment harus memuat `index.php` langsung di root.

Deployment Azure **belum dinyatakan berhasil** oleh paket ini. Setelah deployment, periksa `/health.php` dan jalankan uji tambah, lihat, edit, serta hapus. Catatan hasil dan panduan pengujian lengkap disimpan di folder `docs/` lokal.

## Cara menggunakan

1. Pada daftar kegiatan, pilih **Tambah kegiatan**.
2. Lengkapi empat kolom, lalu pilih **Simpan kegiatan**.
3. Pilih **Edit** pada kegiatan untuk mengubah data, lalu simpan perubahan.
4. Pilih **Hapus** untuk membuka konfirmasi. Pilih **Batal** bila ingin mempertahankan data atau **Ya, hapus kegiatan** untuk menghapusnya.
5. Muat ulang daftar setelah pengujian untuk memastikan hasil berasal dari database.

Untuk laporan, ambil screenshot sendiri ketika tambah, lihat, edit, dan hapus pada URL Azure. Daftar bukti serta tabel hasil yang dapat diisi tersedia pada panduan pengujian.
