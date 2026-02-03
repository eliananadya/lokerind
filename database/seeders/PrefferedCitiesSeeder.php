<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Candidates;
use App\Models\Cities;
use App\Models\PrefferedCities;
use Faker\Factory as Faker;

class PrefferedCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach (range(1, 100) as $index) {
            PrefferedCities::create([
                'candidates_id' => Candidates::inRandomOrder()->first()->id,
                'cities_id' => Cities::inRandomOrder()->first()->id,
            ]);
        }
    }
}
