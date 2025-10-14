<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'jaeron.rivera@gmail.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('123456789'),
            ]
        );

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$user->hasRole($adminRole)) {
            $user->assignRole($adminRole);
        }
    }
}
