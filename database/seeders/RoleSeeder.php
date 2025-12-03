<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define default roles
        $roles = ['admin', 'it_staff', 'user'];

        // Create each role if it doesn't already exist
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Info message in console
        $this->command->info('✅ Default roles seeded successfully.');
    }
}
