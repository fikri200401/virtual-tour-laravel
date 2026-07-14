<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_admin')->insert([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'created_at' => now(),
        ]);

        $contents = [
            ['section' => '', 'content_key' => 'hero_title', 'content_value' => 'SELAMAT DATANG DI', 'content_type' => 'text'],
            ['section' => '', 'content_key' => 'hero_subtitle', 'content_value' => 'VIRTUAL TOUR PRODI SISTEM INFORMASI', 'content_type' => 'text'],
            ['section' => '', 'content_key' => 'hero_description', 'content_value' => 'Jelajahi fasilitas dan lingkungan kampus Universitas Pamulang secara virtual', 'content_type' => 'text'],
            ['section' => '', 'content_key' => 'facilities_title', 'content_value' => 'FASILITAS UNPAM', 'content_type' => 'text'],
            ['section' => '', 'content_key' => 'facilities_description', 'content_value' => 'Temukan berbagai fasilitas modern yang mendukung proses belajar mengajar di Program Studi Sistem Informasi', 'content_type' => 'text'],
            ['section' => '', 'content_key' => 'about_title', 'content_value' => 'Tentang Program Studi Sistem Informasi', 'content_type' => 'text'],
            ['section' => '', 'content_key' => 'about_description', 'content_value' => 'Program Studi Sistem Informasi UNPAM menghasilkan lulusan yang kompeten di bidang teknologi informasi dan sistem informasi.', 'content_type' => 'text'],
            ['section' => '', 'content_key' => 'contact_title', 'content_value' => 'Hubungi Kami', 'content_type' => 'text'],
            ['section' => '', 'content_key' => 'contact_description', 'content_value' => 'Dapatkan informasi lebih lanjut tentang program studi dan fasilitas kampus.', 'content_type' => 'text'],
            ['section' => '', 'content_key' => 'footer_text', 'content_value' => 'Universitas Pamulang - Program Studi Sistem Informasi', 'content_type' => 'text'],
            ['section' => '', 'content_key' => 'welcome_message', 'content_value' => 'Selamat datang di website resmi Virtual Tour Prodi Sistem Informasi UNPAM', 'content_type' => 'text'],
        ];
        DB::table('tb_content')->insert($contents);

        DB::table('tb_facilities')->insert([
            ['name' => 'Perpustakaan', 'description' => 'Ribuan koleksi buku dan jurnal di Perpustakaan Universitas Pamulang.', 'image' => 'asset/perpustakaan 2.webp', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ruang Kelas', 'description' => 'Ruang Proses Kelas dengan AC', 'image' => 'asset/kelas.jpg', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('tb_kritik_saran')->insert([
            ['nama' => 'Budi Santoso', 'kontak' => 'budi@email.com', 'pesan' => 'Website virtual tour sangat menarik! Saya tertarik untuk mendaftar kuliah di UNPAM.', 'created_at' => now()],
        ]);
    }
}
