<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Portofolios;
use App\Models\Candidates;
use Faker\Factory as Faker;

class PortofoliosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            $candidate = Candidates::inRandomOrder()->first();
            $fileName = 'portfolio_' . $faker->unique()->word . '.jpg';
            $caption = $faker->sentence;
            Portofolios::create([
                'file' => $fileName,
                'caption' => $caption,
                'candidates_id' => $candidate->id,
            ]);
        }
    }
}
