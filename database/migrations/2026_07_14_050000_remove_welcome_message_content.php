<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tb_content')->where('content_key', 'welcome_message')->delete();
    }

    public function down(): void
    {
        DB::table('tb_content')->updateOrInsert(
            ['content_key' => 'welcome_message'],
            [
                'section' => 'home_hero',
                'content_value' => 'Selamat datang di website resmi Virtual Tour Prodi Sistem Informasi UNPAM',
                'content_type' => 'text',
                'updated_at' => now(),
            ]
        );
    }
};
