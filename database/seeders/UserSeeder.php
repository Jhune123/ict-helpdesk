<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist
        $role = Role::firstOrCreate(['name' => 'it_staff']);

        // Create example IT Staff users
        $it_staff = [
            ['name' => 'Bryan', 'email' => 'bryan@ksu.edu', 'password' => bcrypt('password')],
            ['name' => 'Jhune', 'email' => 'jhune@ksu.edu', 'password' => bcrypt('password')],
            ['name' => 'Reymar', 'email' => 'reymar@ksu.edu', 'password' => bcrypt('password')],
            ['name' => 'Walid', 'email' => 'walid@ksu.edu', 'password' => bcrypt('password')],
        ];

        foreach ($it_staff as $staff) {
            $user = User::firstOrCreate(['email' => $staff['email']], $staff);
            $user->assignRole('it_staff');
        }

        $this->command->info('IT Staff users seeded.');
    }
}
