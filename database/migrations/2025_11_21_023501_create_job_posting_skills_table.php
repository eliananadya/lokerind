<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_posting_skills', function (Blueprint $table) {
            $table->unsignedBigInteger('job_posting_id');
            $table->unsignedBigInteger('skills_id');
            $table->timestamps();

            $table->foreign('job_posting_id')
                ->references('id')
                ->on('job_postings')
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
        Schema::dropIfExists('job_posting_skills');
    }
};
