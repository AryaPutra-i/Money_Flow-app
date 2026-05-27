# MoneyFlow App

Aplikasi manajemen keuangan berbasis Laravel + Filament.

## Persyaratan Sistem

Untuk menjalankan proyek ini, siapkan lingkungan berikut:

- PHP 8.3 atau lebih tinggi
- Composer 2.x
- Node.js 18.x atau lebih tinggi
- npm 10.x / 12.x / 16.x atau lebih tinggi
- Database:
  - Default menggunakan SQLite
  - Atau MySQL / MariaDB / PostgreSQL / SQL Server jika ingin menggunakan koneksi database lain
- Ekstensi PHP yang diperlukan:
  - `pdo`
  - `pdo_sqlite` (untuk SQLite)
  - `pdo_mysql` (jika menggunakan MySQL/MariaDB)
  - `openssl`
  - `tokenizer`
  - `xml`
  - `ctype`
  - `json`
  - `bcmath`

## Dependensi Utama

Proyek ini menggunakan library dan plugin berikut:

- Laravel Framework `^13.0`
- Filament Admin `^5.0`
- Laravel Tinker `^3.0`
- Tailwind CSS `^4.0.0`
- Vite `^8.0.0`
- laravel-vite-plugin `^3.0.0`
- concurrently `^9.0.1`

## Instalasi

Ikuti langkah berikut agar proyek dapat dijalankan oleh orang lain:

1. Clone repositori:
   ```bash
   git clone <url-repo-anda> moneyflow-app
   cd moneyflow-app
   ```

2. Pasang dependensi PHP:
   ```bash
   composer install
   ```

3. Buat salinan file lingkungan:
   ```bash
   cp .env.example .env
   ```

4. Konfigurasi database di `.env`:
   - Untuk SQLite (direkomendasikan):
     ```env
     DB_CONNECTION=sqlite
     DB_DATABASE=/full/path/to/project/database/database.sqlite
     ```
     Jika belum ada file `database/database.sqlite`, buat file kosong:
     ```bash
     touch database/database.sqlite
     ```

   - Untuk MySQL/MariaDB:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=nama_database
     DB_USERNAME=nama_user
     DB_PASSWORD=password
     ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Jalankan migrasi database:
   ```bash
   php artisan migrate
   ```

7. Pasang dependensi frontend:
   ```bash
   npm install
   ```

8. Bangun aset frontend:
   ```bash
   npm run build
   ```

## Menjalankan Aplikasi

Setelah semua langkah di atas selesai, jalankan server lokal:

```bash
php artisan serve
```

Buka browser dan akses:

```text
http://127.0.0.1:8000
```

Jika ingin menjalankan mode development dengan Vite live reload:

```bash
npm run dev
```

## Akses Plugin / Library

Proyek ini menggunakan Filament sebagai antarmuka admin. Akses panel Filament biasanya tersedia di:

```text
http://127.0.0.1:8000/filament
```

Jika aplikasi ini menggunakan autentikasi Filament, pastikan membuat akun pengguna admin terlebih dahulu.

## Catatan Tambahan

- Jika menggunakan SQLite, pastikan file `database/database.sqlite` dapat ditulis oleh sistem.
- Jika menggunakan MySQL/MariaDB, pastikan server database sudah berjalan dan kredensial di `.env` sudah benar.
- Jika mengalami error saat `composer install`, periksa kembali versi PHP dan ekstensi yang terpasang.
- Jika proyek tidak otomatis menemukan `.env.example`, buat `.env` secara manual dengan menyalin isinya.
