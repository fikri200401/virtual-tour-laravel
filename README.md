# 🏫 Virtual Tour University

<p align="center">
  <img src="public/asset/logo-unpam-300x291.png" width="120" alt="Logo Universitas">
</p>

<p align="center">
  Aplikasi Virtual Tour berbasis web untuk menampilkan lingkungan kampus secara interaktif menggunakan teknologi 360°.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-red?style=for-the-badge&logo=laravel" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/TailwindCSS-3-06B6D4?style=for-the-badge&logo=tailwindcss" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

---

## 📋 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Fitur](#-fitur)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Struktur Proyek](#-struktur-proyek)
- [Cara Instalasi](#-cara-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Akses Admin](#-akses-admin)
- [Lisensi](#-lisensi)

---

## 🎯 Tentang Proyek

**Virtual Tour University** adalah aplikasi web interaktif yang memungkinkan pengguna untuk menjelajahi lingkungan kampus secara virtual menggunakan teknologi panorama 360°. Aplikasi ini dibangun di atas framework Laravel 11 dengan antarmuka yang modern dan responsif menggunakan Tailwind CSS.

---

## ✨ Fitur

### 👥 Fitur Publik
- **🏠 Halaman Beranda** — Informasi umum dan sambutan kampus
- **🔭 Virtual Tour 360°** — Jelajahi kampus secara interaktif dengan hotspot navigasi
- **🏛️ Fasilitas** — Galeri dan informasi fasilitas kampus
- **ℹ️ Tentang** — Profil dan sejarah universitas
- **📩 Kritik & Saran** — Form pengiriman masukan dari pengunjung
- **📊 Tracking Pengunjung** — Statistik kunjungan otomatis

### 🔐 Fitur Admin
- **Dashboard** — Statistik pengunjung dan ringkasan data
- **Manajemen Konten** — Update konten halaman secara dinamis
- **Manajemen Fasilitas** — Tambah, edit, hapus data fasilitas
- **Manajemen Scene VR** — Kelola scene panorama 360° (tambah/edit/hapus)
- **Manajemen Hotspot** — Tambah dan hapus titik navigasi antar scene
- **Manajemen Pengguna** — Kelola akun admin
- **Upload Gambar** — Upload dan kelola aset gambar
- **Kritik & Saran** — Lihat dan hapus masukan dari pengunjung

---

## 🛠️ Teknologi yang Digunakan

| Teknologi | Versi | Keterangan |
|-----------|-------|------------|
| **PHP** | ^8.2 | Backend language |
| **Laravel** | ^11.31 | PHP Framework |
| **MySQL** | - | Database |
| **Tailwind CSS** | ^3 | CSS Framework |
| **Vite** | - | Asset Bundler |
| **Laravel Tinker** | ^2.9 | REPL Tool |

---

## 📁 Struktur Proyek

```
virtual-tour-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controller aplikasi
│   │   └── Middleware/        # AdminAuth & TrackVisitor
│   ├── Models/                # Eloquent Models
│   └── Providers/
├── database/
│   ├── migrations/            # Skema database
│   └── seeders/
├── public/
│   └── asset/                 # Gambar & aset publik
├── resources/
│   ├── css/                   # Stylesheet
│   ├── js/                    # JavaScript
│   └── views/                 # Blade templates
│       ├── admin/             # Halaman admin
│       ├── layouts/           # Layout utama
│       └── partials/          # Komponen partial
└── routes/
    └── web.php                # Definisi route
```

---

## 🚀 Cara Instalasi

### Prasyarat
Pastikan sudah terinstall:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/ghilmanfz/Virtual-Tour-University.git
cd Virtual-Tour-University
git checkout withlaravel
```

**2. Install dependensi PHP**
```bash
composer install
```

**3. Install dependensi Node.js**
```bash
npm install
```

**4. Salin file environment**
```bash
cp .env.example .env
```

**5. Generate application key**
```bash
php artisan key:generate
```

---

## ⚙️ Konfigurasi

Edit file `.env` sesuaikan dengan konfigurasi lokal Anda:

```env
APP_NAME="Virtual Tour University"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=virtual_tour
DB_USERNAME=root
DB_PASSWORD=
```

**Jalankan migrasi database:**
```bash
php artisan migrate
```

**Jalankan seeder (opsional):**
```bash
php artisan db:seed
```

---

## ▶️ Menjalankan Aplikasi

**Build assets (development):**
```bash
npm run dev
```

**Build assets (production):**
```bash
npm run build
```

**Jalankan server lokal:**
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

---

## 🔑 Akses Admin

Halaman admin dapat diakses melalui:
```
http://localhost:8000/login
```

> Buat akun admin melalui seeder atau langsung di database pada tabel `admins`.

Panel admin tersedia di:
```
http://localhost:8000/admin/dashboard
```

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

---

<p align="center">
  Dibuat dengan ❤️ menggunakan <a href="https://laravel.com">Laravel</a>
</p>

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
