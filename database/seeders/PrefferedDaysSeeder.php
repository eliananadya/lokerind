<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Candidates;
use App\Models\Days;

class PrefferedDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 100) as $index) {
            $candidate = Candidates::inRandomOrder()->first();
            $day = Days::inRandomOrder()->first();
            $candidate->days()->attach($day->id);
        }
    }
}
