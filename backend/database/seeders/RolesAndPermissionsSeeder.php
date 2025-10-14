<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $moderate = Permission::firstOrCreate(['name' => 'review.moderate']);
        $view = Permission::firstOrCreate(['name' => 'review.view']);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $moderator = Role::firstOrCreate(['name' => 'moderator']);
        $user = Role::firstOrCreate(['name' => 'user']);

        $admin->syncPermissions(Permission::all());
        $moderator->syncPermissions([$moderate, $view]);
        $user->syncPermissions([$view]);
    }
}
