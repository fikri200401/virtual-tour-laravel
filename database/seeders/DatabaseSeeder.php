<?php

namespace Database\Seeders;

use App\Services\WebsiteContentService;
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

        $contents = collect(WebsiteContentService::definitions())
            ->map(fn (array $definition, string $key) => [
                'section' => $definition['section'],
                'content_key' => $key,
                'content_value' => $definition['value'],
                'content_type' => $definition['type'],
            ])
            ->values()
            ->all();
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
