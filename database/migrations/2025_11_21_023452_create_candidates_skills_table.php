<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates_skills', function (Blueprint $table) {
            // ❌ HAPUS: $table->id();
            $table->unsignedBigInteger('candidates_id');
            $table->unsignedBigInteger('skills_id');
            $table->timestamps();

            $table->foreign('candidates_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('skills_id')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates_skills');
    }
};
