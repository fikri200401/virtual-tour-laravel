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
        Schema::create('tb_content', function (Blueprint $table) {
            $table->id();
            $table->string('section', 50)->default('');
            $table->string('content_key', 100);
            $table->text('content_value');
            $table->enum('content_type', ['text', 'image', 'url'])->default('text');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_content');
    }
};
