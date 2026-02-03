<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HistoryPoint;
use App\Models\Candidates;
use App\Models\Applications;
use Faker\Factory as Faker;

class HistoryPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            $candidate = Candidates::inRandomOrder()->first();
            $application = Applications::inRandomOrder()->first();

            // Check if candidate and application are not null
            if ($candidate && $application) {
                $oldPoint = $faker->numberBetween(0, 100);
                $newPoint = $faker->numberBetween($oldPoint, 100);
                HistoryPoint::create([
                    'candidates_id' => $candidate->id,
                    'application_id' => $application->id,
                    'old_point' => $oldPoint,
                    'new_point' => $newPoint,
                ]);
            } else {
                // Optionally log an error or just skip if no candidate or application found
                \Log::warning('No candidate or application found for seeding.');
            }
        }
    }
}
