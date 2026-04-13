<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tb_vr_hotspots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scene_id')->constrained('tb_vr_scenes')->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('target_scene', 100);
            $table->decimal('position_x', 10, 2);
            $table->decimal('position_y', 10, 2);
            $table->decimal('position_z', 10, 2);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_vr_hotspots');
    }
};
