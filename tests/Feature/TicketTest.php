<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_ticket()
    {
        $user = User::factory()->create();

        $ticket = Ticket::factory()->create([
            'created_by' => $user->id,
        ]);

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'created_by' => $user->id,
        ]);
    }

    public function test_ticket_creation_client_name_is_empty_and_editable()
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'it_staff', 'guard_name' => 'web']);
        $user = User::factory()->create(['name' => 'Default Client Name']);

        $response = $this->actingAs($user)->get(route('tickets.create'));

        $response->assertOk()
            ->assertSee('name="client_name" id="client_name" value=""', false)
            ->assertDontSee('name="client_name" id="client_name" value="Default Client Name"', false);
    }

    public function test_ticket_client_name_uses_requestor_full_name()
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'it_staff', 'guard_name' => 'web']);
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('tickets.store'), [
            'form_type' => 'generic',
            'title' => 'Test request',
            'description' => 'Test details',
            'meta' => ['full_name' => 'Requestor Full Name'],
            'client_name' => 'Different Client Name',
            'status' => 'Open',
        ])->assertRedirect();

        $this->assertDatabaseHas('tickets', [
            'created_by' => $user->id,
            'title' => 'Test request',
            'client_name' => 'Requestor Full Name',
        ]);
    }

    public function test_long_multiline_description_is_saved_and_fully_shown_in_ticket_management()
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'it_staff', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $description = "First line with a detailed description beyond the old limit.\nSecond line with additional information that must remain visible.";

        $this->actingAs($user)->post(route('tickets.store'), [
            'form_type' => 'generic',
            'title' => 'Description display test',
            'description' => $description,
            'status' => 'Open',
        ])->assertRedirect();

        $ticket = Ticket::where('title', 'Description display test')->firstOrFail();
        $this->assertSame($description, $ticket->description);

        $this->get(route('tickets.index'))
            ->assertOk()
            ->assertSeeText('First line with a detailed description beyond the old limit.')
            ->assertSeeText('Second line with additional information that must remain visible.');

        $this->get(route('tickets.show', $ticket))
            ->assertOk()
            ->assertSeeText('First line with a detailed description beyond the old limit.')
            ->assertSeeText('Second line with additional information that must remain visible.');
    }
}