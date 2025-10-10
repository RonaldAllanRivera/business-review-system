<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Business;
use App\Models\Review;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create users
        $users = User::factory(5)->create();

        // Create businesses
        $businesses = Business::factory(10)->create();

        // Create reviews
        Review::factory(20)->create([
            'business_id' => fn() => $businesses->random()->id,
            'user_id' => fn() => $users->random()->id,
        ]);
    }
}
