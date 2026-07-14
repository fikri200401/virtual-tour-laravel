<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('tb_content')
            ->where('content_key', 'campus_map_title')
            ->where('content_value', 'Denah Kampus')
            ->update(['content_value' => 'Kampus UNPAM Viktor']);

        DB::table('tb_content')
            ->where('content_key', 'campus_map_description')
            ->where('content_value', 'Lihat denah kampus untuk orientasi lebih lanjut.')
            ->update(['content_value' => 'Lihat lokasi kampus untuk orientasi lebih lanjut.']);
    }

    public function down(): void
    {
        DB::table('tb_content')
            ->where('content_key', 'campus_map_title')
            ->where('content_value', 'Kampus UNPAM Viktor')
            ->update(['content_value' => 'Denah Kampus']);

        DB::table('tb_content')
            ->where('content_key', 'campus_map_description')
            ->where('content_value', 'Lihat lokasi kampus untuk orientasi lebih lanjut.')
            ->update(['content_value' => 'Lihat denah kampus untuk orientasi lebih lanjut.']);
    }
};
