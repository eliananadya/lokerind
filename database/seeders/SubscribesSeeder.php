<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subscribes;
use App\Models\Candidates;
use App\Models\Companies;

class SubscribesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach (range(1, 100) as $index) {
            Subscribes::create([
                'candidates_id' => Candidates::inRandomOrder()->first()->id,
                'companies_id' => Companies::inRandomOrder()->first()->id,
            ]);
        }
    }
}
