<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;
use App\Models\User;

class TicketSeeder extends Seeder
{
    public function run()
    {
        $user = User::first(); // now we are sure at least one user exists

        Ticket::create([
            'title' => 'Sample Ticket',
            'description' => 'This is a sample ticket.',
            'status' => 'Open',
            'category' => 'Hardware',
            'department' => 'ICT Office',
            'client_name' => 'John Doe',
            'assigned_to' => $user->id,
            'created_by' => $user->id,
        ]);
    }
}
