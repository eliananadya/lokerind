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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->enum('status', [
                'Pending',      // Lamaran baru masuk
                'Selection',    // Dalam proses seleksi (dulu: interview)
                'Invited',      // Diundang untuk interview/seleksi
                'Accepted',     // Diterima
                'Rejected',     // Ditolak
                'Withdrawn',    // Kandidat menarik lamaran
                'Finished'      // Proses selesai (kontrak selesai)
            ])->default('pending');
            $table->text('message')->nullable();
            $table->date('applied_at');
            $table->integer('rating_candidates')->nullable()->comment('Rating dari company ke candidate (1-5)');
            $table->integer('rating_company')->nullable()->comment('Rating dari candidate ke company (1-5)');
            $table->text('review_candidate')->nullable()->comment('Review dari company ke candidate');
            $table->text('review_company')->nullable()->comment('Review dari candidate ke company');
            $table->unsignedBigInteger('candidates_id');
            $table->unsignedBigInteger('job_posting_id');
            $table->boolean('invited_by_company')->default(false)->comment('Apakah diundang langsung oleh company');
            $table->timestamp('invited_at')->nullable()->comment('Waktu diundang');
            $table->timestamp('withdrawn_at')->nullable()->comment('Waktu kandidat menarik lamaran');
            $table->text('withdraw_reason')->nullable()->comment('Alasan kandidat menarik lamaran');
            $table->timestamps();
            $table->foreign('job_posting_id')
                ->references('id')
                ->on('job_postings')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('candidates_id')
                ->references('id')
                ->on('candidates')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->index('status');
            $table->index('applied_at');
            $table->index(['candidates_id', 'status']);
            $table->index(['job_posting_id', 'status']);
            $table->index(['candidates_id', 'job_posting_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
