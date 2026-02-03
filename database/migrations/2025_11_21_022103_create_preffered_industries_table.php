<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preffered_industries', function (Blueprint $table) {
            $table->unsignedBigInteger('candidates_id');
            $table->unsignedBigInteger('industries_id');
            $table->timestamps();

            $table->foreign('candidates_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('industries_id')
                ->references('id')
                ->on('industries')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preffered_industries');
    }
};
