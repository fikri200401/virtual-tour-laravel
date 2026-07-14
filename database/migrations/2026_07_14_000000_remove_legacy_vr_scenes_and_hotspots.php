<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('tb_vr_hotspots');
        Schema::dropIfExists('tb_vr_scenes');
    }

    public function down(): void
    {
        Schema::create('tb_vr_scenes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('scene_key', 100)->unique();
            $table->text('image_360');
            $table->string('icon', 100)->default('fas fa-door-open');
            $table->timestamp('created_at')->useCurrent();
        });

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
};
