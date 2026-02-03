<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Benefit;
use Faker\Factory as Faker;

class BenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $benefits = [
            'Health Insurance',
            'Dental Insurance',
            'Vision Insurance',
            'Paid Time Off',
            'Bonuses',
            'Retirement Plan',
            'Life Insurance',
            'Paid Parental Leave',
            'Stock Options',
            'Flexible Working Hours',
            'Work from Home Option',
            'Gym Membership',
            'Transportation Allowance',
            'Meal Allowance',
            'Professional Development',
            'Company Car',
            'Performance Bonuses',
            'Employee Discounts',
            'Job Training',
        ];

        foreach ($benefits as $benefit) {
            Benefit::create([
                'name' => $benefit,
            ]);
        }
    }
}
