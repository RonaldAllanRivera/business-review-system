<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Business;
use App\Models\Review;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(AdminUserSeeder::class);
        // Create users
        $users = User::factory(5)->create();
        if ($users->count() > 0) {
            $adminRole = Role::where('name', 'admin')->first();
            $moderatorRole = Role::where('name', 'moderator')->first();
            $users[0]->assignRole($adminRole);
            if ($users->count() > 1) {
                $users[1]->assignRole($moderatorRole);
            }
        }

        // Create businesses
        $businesses = Business::factory(10)->create();

        // Create reviews
        Review::factory(20)->create([
            'business_id' => fn() => $businesses->random()->id,
            'user_id' => fn() => $users->random()->id,
        ]);
    }
}
