<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Days;

class DaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // An array of days to seed into the 'days' table
        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        ];

        // Loop through the days array and create records for each day
        foreach ($days as $day) {
            Days::create([
                'name' => $day,
            ]);
        }
    }
}
