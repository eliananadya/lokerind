<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preffered_days', function (Blueprint $table) {
            $table->unsignedBigInteger('candidates_id');
            $table->unsignedBigInteger('days_id');
            $table->timestamps();

            $table->foreign('candidates_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('days_id')
                ->references('id')
                ->on('days')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preffered_days');
    }
};
