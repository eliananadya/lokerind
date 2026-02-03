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
        Schema::create('job_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedBigInteger('job_posting_id');
            $table->unsignedBigInteger('days_id');
            $table->timestamps();
            $table->foreign('job_posting_id')->references('id')->on('job_postings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('days_id')->references('id')->on('days')->onDelete('cascade')->onUpdate('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_dates');
    }
};
