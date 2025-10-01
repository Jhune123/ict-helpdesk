<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ITStaffSeeder extends Seeder
{
    public function run(): void
    {
        $staff = [
            ['name' => 'Bryan', 'email' => 'bryan@ksu.edu.ph'],
            ['name' => 'Jhune', 'email' => 'jhune@ksu.edu.ph'],
            ['name' => 'Reymar', 'email' => 'reymar@ksu.edu.ph'],
            ['name' => 'Walid', 'email' => 'walid@ksu.edu.ph'],
        ];

        foreach ($staff as $person) {
            User::firstOrCreate(
                ['email' => $person['email']],
                [
                    'name' => $person['name'],
                    'password' => bcrypt('password'), // default password
                ]
            );
        }
    }
}
