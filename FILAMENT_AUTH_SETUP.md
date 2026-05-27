# Setup Login & Register dengan Filament

## 📋 Ringkasan Implementasi

Fitur login dan register sudah berhasil dikonfigurasi menggunakan Filament bawaan (built-in authentication).

## 🔗 URL Akses

### Panel "Arya" (untuk testing)
- **Dashboard**: `http://localhost:8000/arya`
- **Login**: `http://localhost:8000/arya/login`
- **Register**: `http://localhost:8000/arya/register`

### Panel "Admin" (jika ada)
- **Dashboard**: `http://localhost:8000/admin`
- **Login**: `http://localhost:8000/admin/login`
- **Register**: `http://localhost:8000/admin/register`

## 🔐 Test Account

Gunakan akun berikut untuk testing:

```
Email: admin@moneyflow.test
Password: password

atau

Email: test@example.com
Password: password
```

## 📁 Struktur File yang Dibuat/Diubah

### 1. **AryaPanelProvider** 
File: `app/Providers/Filament/AryaPanelProvider.php`
- Dikonfigurasi dengan `.login()` untuk halaman login
- Dikonfigurasi dengan `.registration()` untuk halaman register

### 2. **DatabaseSeeder**
File: `database/seeders/DatabaseSeeder.php`
- Membuat 2 user test untuk akses:
  - Admin User (admin@moneyflow.test)
  - Test User (test@example.com)

### 3. **User Factory**
File: `database/factories/UserFactory.php`
- Password default: `password` (di-hash)

## ✨ Fitur yang Tersedia

### Login
- ✅ Form login dengan email & password
- ✅ Remember me checkbox
- ✅ Error validation
- ✅ Session management
- ✅ Middleware protection

### Register
- ✅ Form registrasi dengan name, email, password
- ✅ Email validation (unique)
- ✅ Password confirmation
- ✅ Password strength validation
- ✅ Auto-login setelah registrasi (bisa dikonfigurasi)

### Security
- ✅ CSRF protection
- ✅ Password hashing
- ✅ Session middleware
- ✅ Authentication middleware

## 🎨 Customization

### Mengubah Warna Primary
Edit di `app/Providers/Filament/AryaPanelProvider.php`:

```php
->colors([
    'primary' => Color::Amber, // Ganti dengan warna yang diinginkan
])
```

Warna yang tersedia: Blue, Red, Green, Yellow, Amber, Pink, Purple, dll.

### Mengubah Path Panel
Edit di `app/Providers/Filament/AryaPanelProvider.php`:

```php
->path('arya') // Ganti 'arya' dengan path yang diinginkan
```

### Menonaktifkan Registration
Edit di `app/Providers/Filament/AryaPanelProvider.php`:

```php
// Hapus atau comment baris ini:
->registration()
```

### Menambahkan Middleware Custom
Edit di `app/Providers/Filament/AryaPanelProvider.php`:

```php
->middleware([
    // ... middleware yang ada
    YourCustomMiddleware::class,
])
```

## 🔄 Alur Autentikasi

1. User mengakses `/arya/login` atau `/arya/register`
2. Jika belum punya akun → klik "Register"
3. Isi form registrasi → submit
4. Sistem membuat user baru dengan password ter-hash
5. User di-redirect ke login page
6. Login dengan email & password
7. Session dibuat & user di-redirect ke dashboard

## 🛡️ Middleware Protection

Routes yang dilindungi middleware:
- Dashboard (`/arya`) - Memerlukan authentication
- Hanya user yang login bisa akses

Routes yang publik:
- Login page (`/arya/login`) - Untuk guest user
- Register page (`/arya/register`) - Untuk guest user

## 📝 Model User

File: `app/Models/User.php`

Fillable fields:
- `name`
- `email`
- `password`

Hidden fields:
- `password`
- `remember_token`

## 🚀 Cara Menjalankan

### Development Server
```bash
php artisan serve
```

### Run Database Seeder (untuk create user test)
```bash
php artisan db:seed
```

### Fresh Migration & Seed
```bash
php artisan migrate:fresh --seed
```

## 🔧 Troubleshooting

### Login tidak berfungsi
1. Pastikan migrations sudah dijalankan: `php artisan migrate`
2. Pastikan seeder sudah dijalankan: `php artisan db:seed`
3. Cek apakah email dan password benar

### Register tidak berfungsi
1. Cek apakah email sudah terdaftar
2. Pastikan password confirmation cocok
3. Cek column table `users` sudah ada

### Halaman login tidak muncul
1. Pastikan `->login()` sudah ditambahkan di PanelProvider
2. Jalankan `php artisan cache:clear`
3. Jalankan `php artisan config:clear`

## 📚 Resources

- [Filament Auth Documentation](https://filamentphp.com/docs/3.x/panels/authentication)
- [Laravel Authentication](https://laravel.com/docs/authentication)
- [Filament Official](https://filamentphp.com)

## 🎯 Next Steps

Untuk implementasi lebih lanjut:

1. **Customize Login Page**: Buat custom pages di `app/Filament/Arya/Pages/Auth/`
2. **Add Email Verification**: Setup email verification
3. **Role-based Access**: Tambahkan roles & permissions
4. **Two Factor Authentication**: Setup 2FA
5. **Profile Management**: Buat halaman profile update user

---

**Setup selesai! Silakan akses aplikasi di `http://localhost:8000/arya`** 🎉
