<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Candidates;
use App\Models\Skills;
use Faker\Factory as Faker;

class CandidatesSkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed 200 random records for candidates_skills
        foreach (range(1, 100) as $index) {
            // Randomly pick a candidate
            $candidate = Candidates::inRandomOrder()->first();

            // Randomly pick a skill (you can adjust the number of skills here)
            $skill = Skills::inRandomOrder()->first();

            // Attach the skill to the candidate (creating a new entry in the pivot table)
            $candidate->skills()->attach($skill->id);
        }
    }
}
