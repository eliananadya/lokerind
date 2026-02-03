<?php

namespace Database\Seeders;

use App\Models\Benefit;
use Illuminate\Database\Seeder;
use App\Models\JobPostingBenefit;
use App\Models\JobPostings;
use Faker\Factory as Faker;

class JobPostingBenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            $jobPosting = JobPostings::inRandomOrder()->first();
            $benefit = Benefit::inRandomOrder()->first();

            // ✅ UBAH: Gunakan 'cash' atau 'in kind' sesuai enum di migration
            $benefitType = $faker->randomElement(['cash', 'in kind']);

            // ✅ UBAH: Sesuaikan amount dengan benefit_type
            if ($benefitType == 'cash') {
                // Untuk cash: nominal uang
                $amount = 'Rp ' . number_format($faker->numberBetween(100000, 5000000), 0, ',', '.');
            } else {
                // Untuk in kind: nama barang/benefit
                $inKindBenefits = [
                    'Laptop',
                    'Smartphone',
                    'Meal Voucher',
                    'Transportation Allowance',
                    'Health Insurance',
                    'Gym Membership',
                    'Training Program',
                    'Company Car',
                    'Housing Allowance',
                    'Uniform'
                ];
                $amount = $faker->randomElement($inKindBenefits);
            }

            JobPostingBenefit::create([
                'job_posting_id' => $jobPosting->id,
                'benefit_id' => $benefit->id,
                'benefit_type' => $benefitType,
                'amount' => $amount,
            ]);
        }
    }
}
