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

        $this->assertSame(7, substr_count($response->getContent(), 'name="title"'));
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

    public function test_service_category_ticket_titles_are_saved_and_displayed_as_entered()
    {
        $user = User::factory()->create();
        $formTypes = [
            'equipment_repair',
            'information_system_request',
            'multimedia_request',
            'network_request',
            'incident_report',
            'equipment_borrower',
        ];

        foreach ($formTypes as $formType) {
            $title = "Custom {$formType} Request";

            $this->actingAs($user)->post(route('tickets.store'), [
                'form_type' => $formType,
                'title' => $title,
                'description' => 'Ticket description',
                'status' => 'Open',
            ])->assertRedirect();

            $this->assertDatabaseHas('tickets', [
                'created_by' => $user->id,
                'title' => $title,
            ]);
        }

        $this->get(route('tickets.index'))
            ->assertOk()
            ->assertSeeText('Custom equipment_repair Request')
            ->assertSeeText('Custom information_system_request Request')
            ->assertSeeText('Custom multimedia_request Request')
            ->assertSeeText('Custom network_request Request')
            ->assertSeeText('Custom incident_report Request')
            ->assertSeeText('Custom equipment_borrower Request');
    }

    public function test_job_order_templates_render_values_using_the_create_form_field_names()
    {
        $creator = User::factory()->create([
            'name' => 'Account Name',
            'email' => 'account@example.test',
        ]);
        $cases = [
            'tickets.equipment-repair' => [
                'form_type' => 'equipment_repair',
                'fields' => [
                    'title' => 'Repair Ticket Title',
                    'client_name' => 'Repair Requestor',
                    'equipment_type' => 'Laptop',
                    'brand_model' => 'Model A',
                    'serial_no' => 'SERIAL-A',
                    'description' => 'Repair issue description',
                ],
                'form_data' => [
                    'full_name' => 'Repair Requestor',
                    'office_address' => 'Repair Office',
                    'email_address' => 'repair@example.test',
                ],
                'expected' => ['Repair Ticket Title', 'Repair Requestor', 'Repair Office', 'repair@example.test', 'Repair issue description'],
            ],
            'tickets.print-is' => [
                'form_type' => 'information_system_request',
                'fields' => [
                    'title' => 'System Ticket Title',
                    'client_name' => 'System Requestor',
                    'description' => 'System request details',
                ],
                'form_data' => [
                    'office_address' => 'System Office',
                    'email_address' => 'system@example.test',
                    'date_requested' => 'October 10, 2026',
                    'request_type' => ['Bug Report'],
                    'requested_start_date' => '2026-10-14',
                    'requested_completion_date' => '2026-10-20',
                ],
                'expected' => ['System Ticket Title', 'System Office', 'system@example.test', 'October 10, 2026', '2026-10-14', '2026-10-20', 'System request details'],
            ],
            'tickets.print-multimedia' => [
                'form_type' => 'multimedia_request',
                'fields' => [
                    'title' => 'Multimedia Ticket Title',
                    'client_name' => 'Multimedia Requestor',
                    'description' => 'Multimedia request details',
                ],
                'form_data' => [
                    'office_address' => 'Multimedia Office',
                    'email_address' => 'multimedia@example.test',
                    'date_requested' => 'October 11, 2026',
                    'request_type' => ['Videography'],
                    'date_time' => 'October 25, 2026 at 10:00 AM',
                    'duration' => 'Two hours',
                    'requested_start_date' => '2026-10-22',
                    'requested_completion_date' => '2026-10-26',
                ],
                'expected' => ['Multimedia Ticket Title', 'Multimedia Office', 'multimedia@example.test', 'October 11, 2026', 'October 25, 2026 at 10:00 AM', '2026-10-22', '2026-10-26', 'Multimedia request details'],
            ],
            'tickets.print-network' => [
                'form_type' => 'network_request',
                'fields' => [
                    'title' => 'Network Ticket Title',
                    'client_name' => 'Network Requestor',
                    'description' => 'Network request details',
                ],
                'form_data' => [
                    'office_address' => 'Network Office',
                    'email_address' => 'network@example.test',
                    'date_requested' => 'October 12, 2026',
                    'request_type' => ['VPN Access'],
                    'request_type_other' => 'Firewall access',
                    'device_type' => ['Laptop'],
                    'device_other' => 'Network appliance',
                    'location' => 'Room 12',
                    'mac_address' => '00:11:22:33:44:55',
                    'requested_start_date' => '2026-10-23',
                    'requested_completion_date' => '2026-10-30',
                ],
                'expected' => ['Network Ticket Title', 'Network Office', 'network@example.test', 'October 12, 2026', 'Firewall access', 'Network appliance', 'Room 12', '00:11:22:33:44:55', 'October 23, 2026', 'October 30, 2026', 'Network request details'],
            ],
            'tickets.incident-report.job-order' => [
                'form_type' => 'incident_report',
                'fields' => [
                    'title' => 'Incident Ticket Title',
                    'client_name' => 'Incident Requestor',
                    'description' => 'Incident narrative',
                ],
                'form_data' => [
                    'office_name_position' => 'Incident Office / Staff',
                    'email_address' => 'incident@example.test',
                    'incident_date' => '2026-10-13',
                    'incident_time' => '09:30',
                    'location' => 'Computer Lab',
                    'equipment_type' => 'Desktop',
                    'equipment_qty' => '2',
                    'equipment_serial' => 'INC-SERIAL',
                    'equipment_remarks' => 'Damaged casing',
                    'actions_taken' => 'Power disconnected',
                ],
                'expected' => ['Incident Ticket Title', 'Incident Office / Staff', 'incident@example.test', '2026-10-13', '09:30', 'Computer Lab', 'Desktop', '2', 'INC-SERIAL', 'Damaged casing', 'Incident narrative', 'Power disconnected'],
            ],
            'tickets.borrower-form' => [
                'form_type' => 'equipment_borrower',
                'fields' => [
                    'title' => 'Borrower Ticket Title',
                    'client_name' => 'Borrower Requestor',
                    'contact_number' => '09123456789',
                    'equipment_type' => 'Projector',
                    'serial_no' => 'BORROW-SERIAL',
                    'description' => 'Borrower purpose description',
                ],
                'form_data' => [
                    'full_name' => 'Borrower Requestor',
                    'office_name' => 'Borrower Office',
                    'email_address' => 'borrower@example.test',
                    'equipment_type' => 'Projector',
                    'quantity' => '3',
                    'serial_no' => 'BORROW-SERIAL',
                    'date_borrowed' => '2026-10-13',
                    'expected_return_date' => '2026-10-20',
                ],
                'expected' => ['Borrower Ticket Title', 'Borrower purpose description', 'Borrower Requestor', 'Borrower Office', 'borrower@example.test', 'Projector', '3', 'BORROW-SERIAL', '2026-10-13', '2026-10-20'],
            ],
        ];

        foreach ($cases as $viewName => $case) {
            $this->actingAs($creator)->post(route('tickets.store'), array_merge($case['fields'], [
                'form_type' => $case['form_type'],
                'meta' => $case['form_data'],
                'status' => 'Open',
            ]))->assertRedirect();

            $ticket = Ticket::where('title', $case['fields']['title'])->firstOrFail();
            foreach ($case['form_data'] as $key => $value) {
                $this->assertSame($value, $ticket->form_data[$key], "{$viewName} should persist {$key}");
            }
            if ($case['form_type'] === 'network_request') {
                $this->assertSame('KSU-ICTO-QF-04', $ticket->form_data['original_form_type']);
                $this->assertSame('Network Office', $ticket->networkRequest->office);
                $this->assertSame('VPN Access', $ticket->networkRequest->request_type);
                $this->assertSame('Firewall access', $ticket->networkRequest->request_type_others);
                $this->assertSame('Network appliance', $ticket->networkRequest->device_others);
                $this->assertSame('2026-10-23', $ticket->networkRequest->start_date);
                $this->assertSame('2026-10-30', $ticket->networkRequest->completion_date);
            }

            $html = view($viewName, ['ticket' => $ticket])->render();

            foreach ($case['expected'] as $value) {
                $this->assertStringContainsString($value, $html, "{$viewName} should render {$value}");
            }
        }
    }

    public function test_equipment_borrower_creation_marks_ticket_for_borrower_job_order()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('tickets.store'), [
            'form_type' => 'equipment_borrower',
            'title' => 'Borrow a projector',
            'description' => 'Needed for a presentation',
            'meta' => [
                'full_name' => 'Borrower Name',
                'equipment_type' => 'Projector',
                'expected_return_date' => '2026-10-20',
            ],
            'status' => 'Open',
        ])->assertRedirect();

        $ticket = Ticket::where('title', 'Borrow a projector')->firstOrFail();

        $this->assertSame('KSU-ICTO-QF-09', $ticket->form_data['original_form_type']);
        $this->assertSame('equipment_borrower', $ticket->form_data['form_type']);
        $this->assertSame('Equipment Borrowing', $ticket->category->name);
    }
}