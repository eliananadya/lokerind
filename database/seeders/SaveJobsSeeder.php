<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Candidates;
use App\Models\JobPostings;

class SaveJobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 100) as $index) {
            // Get random candidate and job posting
            $candidate = Candidates::inRandomOrder()->first();
            $jobPosting = JobPostings::inRandomOrder()->first();

            // Directly insert into the pivot table using DB facade
            DB::table('save_jobs')->insert([
                'candidates_id' => $candidate->id,
                'job_posting_id' => $jobPosting->id,
            ]);
        }
    }
}
