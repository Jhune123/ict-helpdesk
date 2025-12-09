<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Define all permissions your system needs
        $permissions = [
            // Tickets
            'ticket.create', 'ticket.view', 'ticket.edit', 'ticket.delete',
            // Task schedules
            'task.create', 'task.view', 'task.edit', 'task.delete',
            // Meeting schedules
            'meeting.create', 'meeting.view', 'meeting.edit', 'meeting.delete',
            // Any other system-wide permissions
        ];

        // Create permissions if not exists
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Assign all permissions to admin role
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions);
    }
}
