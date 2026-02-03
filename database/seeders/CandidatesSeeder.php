<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Candidates;
use Illuminate\Support\Facades\DB;

class CandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('id_ID'); // Indonesian locale

        // Get all user IDs once to avoid repeated queries
        $userIds = User::pluck('id')->toArray();

        if (empty($userIds)) {
            $this->command->error('No users found!  Please seed users table first.');
            return;
        }

        $this->command->info('Seeding 10,000 candidates...');

        // Use chunking for better performance
        $chunkSize = 50;
        $totalRecords = 100;

        for ($i = 0; $i < $totalRecords; $i += $chunkSize) {
            $candidates = [];
            $limit = min($chunkSize, $totalRecords - $i);

            for ($j = 0; $j < $limit; $j++) {
                $candidates[] = [
                    'name' => $faker->name,
                    'gender' => $faker->randomElement(['Male', 'Female']),
                    'description' => $faker->paragraph(3), // More realistic description
                    'birth_date' => $faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
                    'phone_number' => $faker->numerify('08##########'), // Indonesian phone format
                    'level_english' => $faker->randomElement(['beginner', 'intermediate', 'expert']),
                    'level_mandarin' => $faker->randomElement(['beginner', 'intermediate', 'expert']),
                    'point' => $faker->numberBetween(0, 100),
                    'avg_rating' => $faker->numberBetween(1, 5),
                    'min_height' => $faker->numberBetween(150, 190),
                    'min_weight' => $faker->numberBetween(40, 100),
                    'min_salary' => $faker->numberBetween(3000000, 15000000), // Rupiah format
                    'percentase_acc' => $faker->numberBetween(50, 100),
                    'user_id' => $faker->randomElement($userIds),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Bulk insert for better performance
            DB::table('candidates')->insert($candidates);

            $this->command->info('Inserted ' . ($i + $limit) . ' / ' . $totalRecords . ' candidates');
        }

        $this->command->info('Successfully seeded 10,000 candidates!');
    }
}
