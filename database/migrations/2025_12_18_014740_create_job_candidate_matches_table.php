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
        Schema::create('job_candidate_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidates_id')->constrained('candidates')->onDelete('cascade');
            $table->foreignId('job_posting_id')->constrained('job_postings')->onDelete('cascade');
            $table->boolean('city_match')->default(0);
            $table->boolean('type_job_match')->default(0);
            $table->boolean('industry_match')->default(0);
            $table->boolean('salary_match')->default(0);
            $table->boolean('skill_match')->default(0);
            $table->boolean('day_match')->default(0);
            $table->decimal('match_percentage', 5, 2)->default(0);
            $table->timestamps();
            $table->unique(['candidates_id', 'job_posting_id'], 'unique_candidate_job');
            $table->index('match_percentage');
            $table->index('day_match');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_candidate_matches');
    }
};
