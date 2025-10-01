<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin account
        User::updateOrCreate(
            ['email' => 'admin@ksu.edu.ph'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // IT Personnel accounts
        $itPersonnel = [
            ['name' => 'Jhune Aga Doctor', 'email' => 'jhune@ksu.edu.ph'],
            ['name' => 'Bryan', 'email' => 'bryan@ksu.edu.ph'],
            ['name' => 'Reymar', 'email' => 'reymar@ksu.edu.ph'],
            ['name' => 'Walid', 'email' => 'walid@ksu.edu.ph'],
        ];

        foreach ($itPersonnel as $person) {
            User::updateOrCreate(
                ['email' => $person['email']],
                [
                    'name' => $person['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'it_staff',
                ]
            );
        }
    }
}
