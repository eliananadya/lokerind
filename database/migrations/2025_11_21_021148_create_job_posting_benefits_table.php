<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_posting_benefits', function (Blueprint $table) {
            $table->unsignedBigInteger('benefit_id');
            $table->unsignedBigInteger('job_posting_id');
            $table->enum('benefit_type', ['cash', 'in kind'])->nullable();
            $table->string('amount')->nullable();
            $table->timestamps();

            $table->foreign('benefit_id')
                ->references('id')
                ->on('benefits')
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
        Schema::dropIfExists('job_posting_benefits');
    }
};
