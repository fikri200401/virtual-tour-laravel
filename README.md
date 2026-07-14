# Virtual Tour UNPAM

Aplikasi Laravel untuk menampilkan lingkungan Program Studi Sistem Informasi Universitas Pamulang melalui panorama 360 derajat dan hasil ekspor virtual tour.

## Fitur

Fitur untuk pengunjung:

- Beranda, informasi fasilitas, profil program studi, dan lokasi kampus.
- Virtual tour umum serta virtual tour yang terpisah untuk setiap fasilitas.
- Formulir kritik dan saran dengan notifikasi Telegram opsional.
- Tampilan responsif untuk desktop dan perangkat seluler.

Fitur untuk admin:

- Statistik pengunjung.
- Pengelolaan konten teks, URL, dan gambar website.
- Pengelolaan fasilitas, akun admin, kritik, dan saran.
- Galeri gambar yang dapat dipakai kembali pada formulir konten.
- Unggah virtual tour dalam format ZIP, RAR, PNG, JPG, atau JPEG.

## Kebutuhan sistem

- PHP 8.2 atau lebih baru.
- Composer.
- MySQL atau MariaDB.
- Node.js dan npm.
- Ekstensi PHP `zip` untuk arsip ZIP.
- Ekstensi PHP `rar` atau `bsdtar` untuk arsip RAR.

## Instalasi

```bash
git clone https://github.com/ghilmanfz/Virtual-Tour-University.git
cd Virtual-Tour-University
composer install
npm install
```

Salin `.env.example` menjadi `.env`, lalu sesuaikan koneksi database:

```env
APP_NAME="Virtual Tour UNPAM"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=virtual_tour
DB_USERNAME=root
DB_PASSWORD=
```

Siapkan aplikasi dan database:

```bash
php artisan key:generate
php artisan migrate --seed
npm run build
```

Seeder membuat akun awal dengan username `admin` dan password `admin123`. Ganti password tersebut melalui menu Kelola User setelah login pertama.

## Menjalankan aplikasi

```bash
php artisan serve
```

Website tersedia di `http://localhost:8000` dan halaman login admin di `http://localhost:8000/login`.

Untuk pengembangan aset frontend, jalankan:

```bash
npm run dev
```

## Struktur utama

- `app/Http/Controllers`: alur request untuk halaman publik dan admin.
- `app/Services`: pengelolaan konten website dan pemrosesan berkas virtual tour.
- `resources/views`: template Blade.
- `public/asset`: gambar yang diunggah admin.
- `public/virtual-tours`: virtual tour umum yang sudah diproses.
- `public/facility-tours`: virtual tour khusus fasilitas.
- `database/migrations`: riwayat skema database.
