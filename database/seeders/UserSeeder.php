<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ Admin account
        User::updateOrCreate(
            ['email' => 'admin@ksu.edu.ph'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // ✅ IT Personnel accounts
        $itPersonnels = [
            ['name' => 'Bryan', 'email' => 'bryan@ksu.edu.ph'],
            ['name' => 'Jhune', 'email' => 'jhune@ksu.edu.ph'],
            ['name' => 'Reymar', 'email' => 'reymar@ksu.edu.ph'],
            ['name' => 'Walid', 'email' => 'walid@ksu.edu.ph'],
        ];

        foreach ($itPersonnels as $person) {
            User::updateOrCreate(
                ['email' => $person['email']],
                [
                    'name' => $person['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'it_staff',
                ]
            );
        }

        $this->command->info('✅ Admin and IT Personnel users updated/created successfully.');
    }
}
