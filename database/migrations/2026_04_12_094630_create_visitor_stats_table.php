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
        Schema::create('tb_visitor_stats', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('page_visited', 255)->nullable();
            $table->date('visit_date')->nullable();
            $table->timestamp('visit_time')->useCurrent();
            $table->boolean('is_admin')->default(false);
            $table->index('visit_date', 'idx_date');
            $table->index('page_visited', 'idx_page');
            $table->index('is_admin', 'idx_admin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_visitor_stats');
    }
};
