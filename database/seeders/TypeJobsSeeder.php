<?php

namespace Database\Seeders;

use App\Models\TypeJobs;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeJobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 100) as $index) {
            TypeJobs::create([
                'name' => $faker->word,
            ]);
        }
    }
}
