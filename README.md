# Laravel 12 Starter Kit

Starter sederhana berbasis Laravel 12 untuk aplikasi internal dengan fokus pada empat modul inti: manajemen pengguna, role, menu navigasi, dan konfigurasi aplikasi. Paket ini menjaga struktur tetap ringan sehingga mudah dikembangkan ulang sesuai kebutuhan perusahaan.

## Fitur

- **Autentikasi & Profil** - login berbasis username, update profil, dan pencatatan aktivitas pengguna.
- **Role & Menu Management** - kelola role, atur hak akses menu, drag & drop urutan menu, dan assign per role.
- **Manajemen Pengguna** - CRUD pengguna dengan relasi role, dukungan pencarian cepat melalui DataTables.
- **Konfigurasi Aplikasi** - pengaturan nama aplikasi, deskripsi, dan logo langsung dari antarmuka.

## Prasyarat

- PHP 8.2 atau lebih baru
- PostgreSQL atau database yang kompatibel dengan default Laravel
- Composer 2.x
- Node.js 18+ dan npm 9+ (untuk asset pipeline)

## Instalasi Singkat

```bash
cp .env.example .env
composer install
npm install && npm run build
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Kredensial Awal

| Username | Password     | Role         |
|----------|--------------|--------------|
| `it`     | `superadmin` | Super Admin  |
| `user`   | `user`       | User         |

## Struktur Direktori Ringkas

- `app/Http/Controllers` - controller untuk Dashboard, User, Role-Menu, Menu, Configuration, dan Auth default.
- `app/Models` - model inti (`User`, `Role`, `Menu`, `RoleMenu`, `Configuration`, `LogUser`).
- `app/Helpers/myHelper.php` - helper untuk pengambilan menu dan log aktivitas.
- `database/migrations` - migrasi minimal (users, roles, menus, configurations, sessions, log_users).
- `database/seeders/ConfigSeeder.php` - seeder awal menu, role, dan pengguna.
- `resources/views` - Blade template untuk layout, dashboard, user, role-menu, menu, configuration, dan auth.
- `public/custom` - asset tema (CSS/JS) yang digunakan oleh tampilan bawaan.

## Rute Penting

- `/` - Dashboard ringkas berisi statistik jumlah user, role, menu, konfigurasi, serta daftar 5 pengguna terbaru.
- `/user` - Manajemen akun pengguna.
- `/role-menu` - Atur role dan hak akses menu.
- `/menu` - Kelola menu, termasuk urutan dan struktur parent-child.
- `/configuration` - Ubah konfigurasi aplikasi (nama, deskripsi, logo).

Seluruh rute administrasi berada di balik middleware `auth` dan `cekRole` untuk memastikan hak akses berbasis menu.

## Pengembangan

- Jalankan `php artisan serve` untuk server lokal dan `npm run dev` bila ingin hot reload asset.
- Gunakan `php artisan test` untuk menjalankan test bawaan Laravel.
- Lakukan commit teratur setelah menambahkan modul baru agar tetap mudah dilacak.

Selamat membangun aplikasi internal dengan fondasi yang bersih! Jika perlu menambah modul baru, tetap pertahankan pemisahan tanggung jawab yang jelas dan tambahkan dokumentasi singkat agar tim mudah mengikuti perubahan.
