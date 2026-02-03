<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles
        $roles = [
            'super_admin' => 'super_admin',
            'user' => 'user',
            'company' => 'company',
        ];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }
        $superAdminRoleId = Role::where('name', 'super_admin')->first()->id;
        $userRoleId = Role::where('name', 'user')->first()->id;
        $companyRoleId = Role::where('name', 'company')->first()->id;
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            $role = null;
            if ($index % 3 == 0) {
                $role = $superAdminRoleId;
                $roleName = 'super_admin';
            } elseif ($index % 3 == 1) {
                $role = $userRoleId;
                $roleName = 'user';
            } else {
                $role = $companyRoleId;
                $roleName = 'company';
            }

            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                // 'phone_number' => $faker->phoneNumber,
                'id_roles' => $role,
                'password' => Hash::make('password123'),
            ]);
            $user->assignRole($roleName);
        }
    }
}
