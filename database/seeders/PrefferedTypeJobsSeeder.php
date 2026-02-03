<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Candidates;
use App\Models\PrefferedTypeJobs;
use App\Models\TypeJobs;
use Faker\Factory as Faker;

class PrefferedTypeJobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach (range(1, 100) as $index) {
            PrefferedTypeJobs::create([
                'candidates_id' => Candidates::inRandomOrder()->first()->id,
                'type_jobs_id' => TypeJobs::inRandomOrder()->first()->id,
            ]);
        }
    }
}
