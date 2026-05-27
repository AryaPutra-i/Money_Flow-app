# Setup Panel Admin/Arya dengan Model user_account

## ✅ Selesai! Filament sudah dikonfigurasi menggunakan `user_account`

Panel admin/arya sekarang menggunakan data dari table `user_accounts` untuk autentikasi login dan register.

---

## 📝 File yang Diubah

### 1. **[app/Models/user_account.php](app/Models/user_account.php)**
- Ditambahkan trait `HasFactory` dan `Notifiable`
- Extend dari `Authenticatable` (bukan hanya Model)
- Ditambahkan casting untuk password: `'password' => 'hashed'`
- Sekarang model ini bisa digunakan untuk authentication

```php
class user_account extends Authenticatable
{
    use HasFactory, Notifiable;
    // ... rest of code
}
```

### 2. **[config/auth.php](config/auth.php)**
- Changed import dari `User` ke `user_account`
- Changed provider model ke `user_account::class`
- Changed password broker dari `'users'` ke `'user_accounts'`

```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => env('AUTH_MODEL', user_account::class),
    ],
],

'passwords' => [
    'user_accounts' => [
        'provider' => 'users',
        'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
        'expire' => 60,
        'throttle' => 60,
    ],
],
```

### 3. **[app/Providers/Filament/AryaPanelProvider.php](app/Providers/Filament/AryaPanelProvider.php)**
- Ditambahkan import `use App\Models\user_account;`
- Panel sudah otomatis menggunakan config auth.php yang telah diubah

### 4. **[database/seeders/DatabaseSeeder.php](database/seeders/DatabaseSeeder.php)**
- Diubah dari menggunakan `User::factory()` ke `user_account::create()`
- Membuat 2 user test di table `user_accounts`:
  - Admin User (admin@moneyflow.test)
  - Test User (test@example.com)

---

## 🔐 Akun Test untuk Login

Gunakan kredensial berikut untuk test login di `/arya/login`:

```
Email: admin@moneyflow.test
Password: password

atau

Email: test@example.com  
Password: password
```

---

## 🔗 URL Penting

| Fitur | URL |
|-------|-----|
| Login | `http://localhost:8000/arya/login` |
| Register | `http://localhost:8000/arya/register` |
| Dashboard | `http://localhost:8000/arya` |

---

## 🔄 Alur Kerja Authentication

1. **User Register** → Datanya disimpan di table `user_accounts`
2. **User Login** → Laravel check di table `user_accounts` sesuai config `auth.php`
3. **Session Created** → User dapat mengakses `/arya` dashboard

---

## 📊 Database Structure

### Table `user_accounts`
```
id (Primary Key)
name (string)
email (string, unique)
password (string, hashed)
created_at (timestamp)
updated_at (timestamp)
```

Data yang sudah ada:
```
✅ Admin User | admin@moneyflow.test
✅ Test User  | test@example.com
```

---

## 🛠️ Perintah Berguna

### Fresh Database dengan Seed
```bash
php artisan migrate:fresh --seed
```

### Cek Data User_Account
```bash
php artisan tinker
\App\Models\user_account::all()
exit
```

### Clear Cache (jika ada masalah)
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Jalankan Development Server
```bash
php artisan serve
```

Kemudian akses: `http://localhost:8000/arya`

---

## 🎯 Fitur yang Tersedia

### Login
✅ Email & password validation  
✅ Remember me checkbox  
✅ Error handling  
✅ Session management  
✅ CSRF protection  

### Register
✅ Form validation (name, email, password)  
✅ Email unique checking  
✅ Password confirmation  
✅ Password hashing  
✅ Auto-login setelah register (configurable)  

### Dashboard
✅ Protected dengan authentication middleware  
✅ Hanya user yang login bisa akses  

---

## 🔧 Customization

### Mengubah Warna Primary Panel
Edit di [app/Providers/Filament/AryaPanelProvider.php](app/Providers/Filament/AryaPanelProvider.php):

```php
->colors([
    'primary' => Color::Blue, // Ubah warna
])
```

Available colors:
- `Color::Blue`, `Color::Red`, `Color::Green`, `Color::Yellow`
- `Color::Amber`, `Color::Pink`, `Color::Purple`, `Color::Cyan`
- dll

### Mengubah Path Panel
Edit di [app/Providers/Filament/AryaPanelProvider.php](app/Providers/Filament/AryaPanelProvider.php):

```php
->path('dashboard') // Ubah dari 'arya' ke 'dashboard'
```

Maka URL akan jadi: `http://localhost:8000/dashboard/login`

### Menonaktifkan Registration
Edit di [app/Providers/Filament/AryaPanelProvider.php](app/Providers/Filament/AryaPanelProvider.php):

```php
// Hapus baris ini:
->registration()
```

---

## ⚡ Troubleshooting

### ❌ Login tidak berfungsi
1. Pastikan migrations dijalankan: `php artisan migrate`
2. Pastikan seeder dijalankan: `php artisan db:seed`
3. Cek email & password di table `user_accounts`

### ❌ Registration gagal
1. Pastikan email belum terdaftar
2. Cek password confirmation cocok
3. Cek column table `user_accounts` sudah ada

### ❌ Halaman blank/error
1. Jalankan: `php artisan cache:clear`
2. Jalankan: `php artisan config:clear`
3. Refresh browser

### ❌ 419 Page Expired (CSRF Error)
1. Buka browser cache/cookies settings
2. Clear cookies untuk localhost
3. Coba lagi

---

## 📚 Relasi Model

`user_account` memiliki relationship:

```php
public function workspaces(): HasMany
{
    return $this->hasMany(workspace::class);
}
```

Jadi setiap user bisa membuat multiple workspaces.

---

## 🚀 Next Steps (Optional)

1. **Add Email Verification**
   - Verifikasi email saat register

2. **Add Two Factor Authentication**
   - Setup 2FA untuk security lebih

3. **Add User Profile Page**
   - Edit profile, upload avatar, dll

4. **Add Role-Based Access Control**
   - Admin, User roles & permissions

5. **Add Password Reset**
   - Lupa password functionality

6. **Add Audit Log**
   - Track user activities

---

## ✨ Kesimpulan

Panel Admin/Arya sekarang:
- ✅ Menggunakan table `user_accounts` untuk auth
- ✅ Memiliki login & register bawaan Filament
- ✅ Sudah ada 2 user test untuk demo
- ✅ Siap untuk production (dengan customization lebih lanjut)

**Silakan test di `http://localhost:8000/arya/login`** 🎉
