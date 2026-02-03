<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preffered_type_jobs', function (Blueprint $table) {
            $table->unsignedBigInteger('candidates_id');
            $table->unsignedBigInteger('type_jobs_id');
            $table->timestamps();

            $table->foreign('candidates_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('type_jobs_id')
                ->references('id')
                ->on('type_jobs')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preffered_type_jobs');
    }
};
