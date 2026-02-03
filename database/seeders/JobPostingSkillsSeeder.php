<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPostings;
use App\Models\JobPostingSkills;
use App\Models\Skills;
use Faker\Factory as Faker;

class JobPostingSkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            $jobPosting = JobPostings::inRandomOrder()->first();
            $skill = Skills::inRandomOrder()->first();
            JobPostingSkills::create([
                'job_posting_id' => $jobPosting->id,
                'skills_id' => $skill->id,
            ]);
        }
    }
}
