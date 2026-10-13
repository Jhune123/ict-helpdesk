<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'admin',
            'it_staff',
            'user',
            'ojt',
            'supervisor',     // 👈 Added to fix RoleDoesNotExist for 'supervisor'
            'ojt_supervisor', // 👈 Added OJT Supervisor
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }

        $this->command->info('✅ Roles seeded: admin, it_staff, user, ojt, supervisor, ojt_supervisor');
    }
}