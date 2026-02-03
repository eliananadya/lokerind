<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('save_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('candidates_id');
            $table->unsignedBigInteger('job_posting_id');
            $table->timestamps();

            $table->foreign('candidates_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('job_posting_id')
                ->references('id')
                ->on('job_postings')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('save_jobs');
    }
};
