<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist
        $role = Role::firstOrCreate(['name' => 'it_staff']);

        // Create example IT Staff users with @ksu.edu.ph
        $it_staff = [
            ['name' => 'Bryan', 'email' => 'bryan@ksu.edu.ph', 'password' => Hash::make('password123')],
            ['name' => 'Jhune', 'email' => 'jhune@ksu.edu.ph', 'password' => Hash::make('password123')],
            ['name' => 'Reymar', 'email' => 'reymar@ksu.edu.ph', 'password' => Hash::make('password123')],
            ['name' => 'Walid', 'email' => 'walid@ksu.edu.ph', 'password' => Hash::make('password123')],
        ];

        foreach ($it_staff as $staff) {
            $user = User::updateOrCreate(['email' => $staff['email']], $staff);
            $user->assignRole('it_staff');
        }

        $this->command->info('IT Staff users seeded with password: password123');
    }
}