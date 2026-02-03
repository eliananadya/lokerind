<?php

namespace App\Console\Commands;

use App\Models\JobPostings;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CloseExpiredJobPostings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:close-expired 
                            {--dry-run : Run in dry-run mode without making changes}
                            {--details : Display detailed information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close job postings that have passed H+1 after their last job date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $isVerbose = $this->option('details');

        $this->info('🔍 Checking for expired job postings...');

        if ($isDryRun) {
            $this->warn('⚠️  DRY RUN MODE - No changes will be made');
        }

        // Get all open job postings
        $openJobs = JobPostings::where('status', 'Open')
            ->with(['jobDatess' => function ($query) {
                $query->orderBy('date', 'desc');
            }])
            ->get();

        $this->info("📊 Found {$openJobs->count()} open job postings");

        $closedCount = 0;
        $skippedCount = 0;
        $today = Carbon::today();

        foreach ($openJobs as $job) {
            // Get last job date
            $lastJobDate = $job->jobDatess->first();

            if (!$lastJobDate) {
                if ($isVerbose) {
                    $this->warn("⏭️  Skipped: Job #{$job->id} - {$job->title} (No job dates found)");
                }
                $skippedCount++;
                continue;
            }

            // Calculate H+1 date (one day after last job date)
            $lastDate = Carbon::parse($lastJobDate->date);
            $closeDateThreshold = $lastDate->copy()->addDay()->startOfDay();

            if ($isVerbose) {
                $this->line("   Job #{$job->id}: {$job->title}");
                $this->line("   Last Date: {$lastDate->format('Y-m-d')}");
                $this->line("   Close After: {$closeDateThreshold->format('Y-m-d')}");
                $this->line("   Today: {$today->format('Y-m-d')}");
            }

            // Check if today is >= close date threshold
            if ($today->gte($closeDateThreshold)) {
                if ($isDryRun) {
                    $this->info("✓ Would close: Job #{$job->id} - {$job->title}");
                } else {
                    try {
                        $job->update([
                            'status' => 'Close',
                            'close_recruitment' => now(),
                        ]);

                        $this->info("✅ Closed: Job #{$job->id} - {$job->title}");

                        Log::info('Job posting auto-closed', [
                            'job_id' => $job->id,
                            'job_title' => $job->title,
                            'last_job_date' => $lastDate->format('Y-m-d'),
                            'closed_at' => now()->format('Y-m-d H:i:s'),
                        ]);

                        $closedCount++;
                    } catch (\Exception $e) {
                        $this->error("❌ Failed to close Job #{$job->id}: {$e->getMessage()}");
                        Log::error('Failed to close job posting', [
                            'job_id' => $job->id,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            } else {
                if ($isVerbose) {
                    $daysRemaining = $today->diffInDays($closeDateThreshold, false);
                    $this->line("   ⏰ Will close in {$daysRemaining} day(s)");
                }
                $skippedCount++;
            }

            if ($isVerbose) {
                $this->line('');
            }
        }

        // Summary
        $this->newLine();
        $this->info('═══════════════════════════════════════');
        $this->info('📋 SUMMARY');
        $this->info('═══════════════════════════════════════');

        if ($isDryRun) {
            $this->line("Would close: {$closedCount} job(s)");
        } else {
            $this->line("✅ Closed: {$closedCount} job(s)");
        }

        $this->line("⏭️  Skipped: {$skippedCount} job(s)");
        $this->line("📊 Total checked: {$openJobs->count()} job(s)");
        $this->info('═══════════════════════════════════════');

        if ($closedCount > 0 && !$isDryRun) {
            Log::info('Job closing scheduler completed', [
                'closed_count' => $closedCount,
                'skipped_count' => $skippedCount,
                'total_checked' => $openJobs->count(),
            ]);
        }

        return Command::SUCCESS;
    }
}
