<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const OLD_DESCRIPTION = 'Pilih lokasi di bawah untuk memulai tur virtual interaktif. Gunakan mouse atau sentuh layar untuk melihat ke segala arah.';

    private const NEW_DESCRIPTION = 'Gunakan mouse atau sentuh layar untuk melihat ke segala arah. Gunakan tombol navigasi di dalam tur untuk berpindah lokasi.';

    public function up(): void
    {
        DB::table('tb_content')
            ->where('content_key', 'vr_section_description')
            ->where('content_value', self::OLD_DESCRIPTION)
            ->update([
                'content_value' => self::NEW_DESCRIPTION,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('tb_content')
            ->where('content_key', 'vr_section_description')
            ->where('content_value', self::NEW_DESCRIPTION)
            ->update([
                'content_value' => self::OLD_DESCRIPTION,
                'updated_at' => now(),
            ]);
    }
};
