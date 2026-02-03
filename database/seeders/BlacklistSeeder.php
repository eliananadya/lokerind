<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blacklist;
use App\Models\User;
use Faker\Factory as Faker;

class BlacklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            $user = User::inRandomOrder()->first();
            $blockedUser = User::where('id', '!=', $user->id)->inRandomOrder()->first();
            $reason = $faker->sentence;
            Blacklist::create([
                'user_id' => $user->id,
                'blocked_user_id' => $blockedUser->id,
                'reason' => $reason,
            ]);
        }
    }
}
