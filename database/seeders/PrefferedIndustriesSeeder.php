<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Candidates;
use App\Models\Industries;

class PrefferedIndustriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $candidates = Candidates::all();

        if ($candidates->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada candidates di database. Jalankan CandidatesSeeder terlebih dahulu!');
            return;
        }

        $industries = Industries::all();

        if ($industries->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada industries di database. Jalankan IndustriesSeeder terlebih dahulu!');
            return;
        }

        foreach ($candidates as $candidate) {
            // ✅ Attach 1-3 random industries per candidate
            $randomIndustries = $industries->random(rand(1, min(3, $industries->count())));

            foreach ($randomIndustries as $industry) {
                // ✅ Gunakan preferredIndustries() sesuai relasi di model
                $candidate->preferredIndustries()->attach($industry->id);
            }
        }

        $this->command->info('✅ Preferred industries berhasil dibuat!');
    }
}
