<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobPostings;
use App\Models\Days;
use App\Models\JobDates;
use Faker\Factory as Faker;

class JobDatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            $jobPosting = JobPostings::inRandomOrder()->first();
            $day = Days::inRandomOrder()->first();
            $startTime = $faker->time('H:i');
            $endTime = $faker->time('H:i');
            while (strtotime($endTime) <= strtotime($startTime)) {
                $endTime = $faker->time('H:i');
            }
            JobDates::create([
                'date' => $faker->date(),
                'start_time' => $startTime,
                'end_time' => $endTime,
                'job_posting_id' => $jobPosting->id, // correct column name
                'days_id' => $day->id, // correct column name
            ]);
        }
    }
}
