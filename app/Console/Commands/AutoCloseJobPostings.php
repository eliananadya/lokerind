<?php

namespace App\Console\Commands;

use App\Models\JobPostings;
use App\Models\JobDates;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AutoCloseJobPostings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:auto-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close job postings after H+1 from last job date';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔄 Starting auto-close job postings...');

        // Ambil semua job posting yang masih Open
        $openJobs = JobPostings::where('status', 'Open')
            ->with('jobDatess')
            ->get();

        $closedCount = 0;
        $today = Carbon::today();

        foreach ($openJobs as $job) {
            // Cek apakah job memiliki job dates
            if ($job->jobDatess->isEmpty()) {
                continue;
            }

            // Ambil tanggal terakhir dari job dates
            $lastJobDate = $job->jobDatess()
                ->orderBy('date', 'desc')
                ->first();

            if (!$lastJobDate) {
                continue;
            }

            // Parse tanggal terakhir
            $lastDate = Carbon::parse($lastJobDate->date);

            // Hitung H+1 (hari berikutnya setelah tanggal terakhir)
            $closeDate = $lastDate->addDay();

            // Jika hari ini >= H+1, maka close job posting
            if ($today->gte($closeDate)) {
                $job->update([
                    'status' => 'Closed',
                    'close_recruitment' => now()
                ]);

                $closedCount++;

                $this->info("✅ Closed: {$job->title} (ID: {$job->id}) - Last date: {$lastDate->format('Y-m-d')}");

                Log::info('Job posting auto-closed', [
                    'job_id' => $job->id,
                    'job_title' => $job->title,
                    'last_job_date' => $lastDate->format('Y-m-d'),
                    'closed_at' => now()
                ]);
            }
        }

        $this->info("🎉 Done! Closed {$closedCount} job posting(s).");

        return Command::SUCCESS;
    }
}
