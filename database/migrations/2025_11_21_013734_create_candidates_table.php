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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('photo')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('description')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone_number')->nullable();
            $table->enum('level_english', ['beginner', 'intermediate', 'expert'])->nullable();
            $table->enum('level_mandarin', ['beginner', 'intermediate', 'expert'])->nullable();
            $table->integer('point')->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0.00)->nullable();
            $table->integer('min_height')->nullable();
            $table->integer('min_weight')->nullable();
            $table->integer('min_salary')->nullable();
            $table->decimal('percentase_acc', 5, 2)->default(0.00)->nullable();

            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
