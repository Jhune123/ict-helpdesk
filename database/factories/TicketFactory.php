<?php

namespace Database\Factories;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['open', 'in_progress', 'closed']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'client_name' => $this->faker->name,
            'department' => $this->faker->word,
            'date_submitted' => $this->faker->date,
            'date_finished' => $this->faker->date,
            'contact_number' => $this->faker->phoneNumber,
            'created_by' => $this->faker->numberBetween(1, 4),
            'assigned_to' => $this->faker->randomElement([null, $this->faker->numberBetween(1, 4)]),
        ];
    }
}