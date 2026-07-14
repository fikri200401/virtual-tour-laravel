<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicateKeys = DB::table('tb_content')
            ->select('content_key')
            ->groupBy('content_key')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('content_key');

        foreach ($duplicateKeys as $contentKey) {
            $duplicateIds = DB::table('tb_content')
                ->where('content_key', $contentKey)
                ->orderByDesc('updated_at')
                ->orderByDesc('id')
                ->pluck('id')
                ->slice(1);

            DB::table('tb_content')->whereIn('id', $duplicateIds)->delete();
        }

        Schema::table('tb_content', function (Blueprint $table) {
            $table->unique('content_key');
        });
    }

    public function down(): void
    {
        Schema::table('tb_content', function (Blueprint $table) {
            $table->dropUnique(['content_key']);
        });
    }
};
