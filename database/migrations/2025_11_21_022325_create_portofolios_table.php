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
        Schema::create('portofolios', function (Blueprint $table) {
            $table->id();
            $table->string('file')->nullable();
            $table->string('caption')->nullable();
            $table->unsignedBigInteger('candidates_id');
            $table->timestamps();
            $table->foreign('candidates_id')->references('id')->on('candidates')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('industries_id')->references('id')->on('jon_postings')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portofolios');
    }
};
