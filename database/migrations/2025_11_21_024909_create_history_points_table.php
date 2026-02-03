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
        Schema::dropIfExists('history_points');

        Schema::create('history_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidates_id');
            $table->unsignedBigInteger('application_id')->nullable(); // ✅ NULLABLE untuk registrasi

            $table->integer('old_point')->default(0)->comment('Point sebelum perubahan');
            $table->integer('new_point')->default(0)->comment('Point setelah perubahan');

            $table->string('reason')->nullable()->comment('Alasan perubahan point (registration, application, etc)');

            $table->timestamps();

            // ✅ Foreign key constraints
            $table->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('candidates_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // ✅ Indexes untuk performa query
            $table->index('candidates_id');
            $table->index('application_id');
            $table->index('created_at');
            $table->index(['candidates_id', 'application_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_points');
    }
};
