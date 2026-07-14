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
        if (! DB::table('tb_admin')->where('username', 'admin')->exists()) {
            DB::table('tb_admin')->insert([
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'created_at' => now(),
            ]);
        }

        $contents = collect(WebsiteContentService::definitions())
            ->map(fn (array $definition, string $key) => [
                'section' => $definition['section'],
                'content_key' => $key,
                'content_value' => $definition['value'],
                'content_type' => $definition['type'],
            ])
            ->values()
            ->all();

        $existingContentKeys = DB::table('tb_content')
            ->whereIn('content_key', array_column($contents, 'content_key'))
            ->pluck('content_key');
        $missingContents = collect($contents)
            ->reject(fn (array $content) => $existingContentKeys->contains($content['content_key']))
            ->values()
            ->all();

        if ($missingContents !== []) {
            DB::table('tb_content')->insert($missingContents);
        }

        $facilities = [
            ['name' => 'Perpustakaan', 'description' => 'Ribuan koleksi buku dan jurnal di Perpustakaan Universitas Pamulang.', 'image' => 'asset/perpustakaan 2.webp', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ruang Kelas', 'description' => 'Ruang Proses Kelas dengan AC', 'image' => 'asset/kelas.jpg', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($facilities as $facility) {
            if (! DB::table('tb_facilities')->where('name', $facility['name'])->exists()) {
                DB::table('tb_facilities')->insert($facility);
            }
        }

        $feedback = [
            'nama' => 'Budi Santoso',
            'kontak' => 'budi@email.com',
            'pesan' => 'Website virtual tour sangat menarik! Saya tertarik untuk mendaftar kuliah di UNPAM.',
            'created_at' => now(),
        ];

        if (! DB::table('tb_kritik_saran')->where('kontak', $feedback['kontak'])->where('pesan', $feedback['pesan'])->exists()) {
            DB::table('tb_kritik_saran')->insert($feedback);
        }
    }
}
