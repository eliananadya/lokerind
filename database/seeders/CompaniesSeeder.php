<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Companies;
use App\Models\Industries;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CompaniesSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // ✅ Ambil role 'company'
        $companyRole = Role::where('name', 'company')->first();

        if (!$companyRole) {
            $this->command->error('❌ Role "company" tidak ditemukan! Jalankan RoleSeeder terlebih dahulu.');
            return;
        }

        foreach (range(1, 100) as $index) {
            // ✅ Buat user dengan role company
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'id_roles' => $companyRole->id,
            ]);

            $user->assignRole('company');

            // ✅ Buat company
            Companies::create([
                'name' => $faker->company,
                'phone_number' => $faker->phoneNumber,
                'location' => $faker->address,
                'description' => $faker->text,
                'avg_rating' => $faker->randomFloat(2, 0, 5),
                'user_id' => $user->id,
                'industries_id' => Industries::inRandomOrder()->first()->id,
            ]);
        }

        $this->command->info('✅ 100 companies dengan user role "company" berhasil dibuat!');
    }
}
