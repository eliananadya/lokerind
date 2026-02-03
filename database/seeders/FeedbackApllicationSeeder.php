<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feedback;
use App\Models\Applications;
use Faker\Factory as Faker;

class FeedbackApllicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            $feedback = Feedback::inRandomOrder()->first();
            $application = Applications::inRandomOrder()->first();

            // Check if feedback and application are not null
            if ($feedback && $application) {
                $givenBy = $faker->randomElement(['Candidate', 'Company']);

                $feedback->applications()->create([
                    'feedback_id' => $feedback->id,
                    'application_id' => $application->id,
                    'given_by' => $givenBy,
                ]);
            } else {
                // Optionally log an error or just skip if no feedback or application found
                \Log::warning('No feedback or application found for seeding.');
            }
        }
    }
}
