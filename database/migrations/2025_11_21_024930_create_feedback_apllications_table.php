<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('feedback_id');
            $table->unsignedBigInteger('application_id');
            $table->string('given_by');
            $table->timestamps();

            $table->foreign('application_id')
                ->references('id')
                ->on('applications')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('feedback_id')
                ->references('id')
                ->on('feedback')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedback_applications');
    }
};
