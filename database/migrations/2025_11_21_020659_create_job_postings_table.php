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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('salary')->nullable();
            $table->enum('type_salary', ['total', 'shift'])->nullable();
            $table->text('address')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->integer('min_height')->nullable();
            $table->integer('min_weight')->nullable();
            $table->enum('verification_status', ['Approved', 'Pending', 'Rejected'])->default('Pending');
            $table->enum('status', ['Open', 'Closed', 'Draft'])->default('Draft');
            $table->enum('gender', ['Male', 'Female', 'Both'])->nullable();
            $table->date('open_recruitment')->nullable();
            $table->date('close_recruitment')->nullable();
            $table->integer('slot')->default(1);
            $table->enum('level_mandarin', ['beginner', 'intermediate', 'expert'])->nullable();
            $table->enum('level_english', ['beginner', 'intermediate', 'expert'])->nullable();
            $table->boolean('has_interview')->default(false);

            $table->unsignedBigInteger('industries_id')->nullable();
            $table->unsignedBigInteger('companies_id')->nullable();
            $table->unsignedBigInteger('type_jobs_id')->nullable();
            $table->unsignedBigInteger('cities_id')->nullable();

            $table->timestamps();

            $table->foreign('industries_id')
                ->references('id')
                ->on('industries')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('companies_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('type_jobs_id')
                ->references('id')
                ->on('type_jobs')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('cities_id')
                ->references('id')
                ->on('cities')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
