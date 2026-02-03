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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama feedback (contoh: Selling Bagus, Komunikasi Baik)');

            // ✅ KOLOM BARU: FOR (candidate atau company)
            $table->enum('for', ['candidate', 'company'])
                ->comment('Feedback untuk siapa: candidate atau company');

            $table->text('description')->nullable()->comment('Deskripsi detail feedback (optional)');
            $table->boolean('is_active')->default(true)->comment('Status aktif feedback');

            $table->timestamps();

            // ✅ Indexes
            $table->index('for');
            $table->index('is_active');
            $table->index(['for', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
