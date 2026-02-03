<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reports;
use App\Models\Applications;
use App\Models\User;
use Faker\Factory as Faker;

class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            $user = User::inRandomOrder()->first();
            $application = Applications::inRandomOrder()->first();

            // Check if both $user and $application are not null
            if ($user && $application) {
                $reason = $faker->sentence;
                $status = $faker->randomElement(['Pending', 'Applied', 'Withdrawn', 'Reviewed', 'Accepted']);

                Reports::create([
                    'reason' => $reason,
                    'status' => $status,
                    'application_id' => $application->id,
                    'user_id' => $user->id,
                ]);
            } else {
                // Optionally log an error or just skip if no user or application found
                \Log::warning('No user or application found for seeding.');
            }
        }
    }
}
