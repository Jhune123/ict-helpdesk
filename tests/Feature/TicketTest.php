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
                    'property_no' => 'PROPERTY-A',
                    'acquisition_date' => '2020-01-15',
                    'acquisition_cost' => '25000',
                    'last_repair_date' => '2025-05-20',
                    'last_repair_nature' => 'Replaced hard drive',
                ],
                'expected' => ['Repair Ticket Title', 'Repair Requestor', 'Repair Office', 'repair@example.test', 'PROPERTY-A', '2020-01-15', '25000', '2025-05-20', 'Replaced hard drive', 'Repair issue description'],
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
                    'request_type' => ['Bug Report', 'Others'],
                    'other_request_type' => 'Custom system change',
                    'requested_start_date' => '2026-10-14',
                    'requested_completion_date' => '2026-10-20',
                ],
                'expected' => ['System Ticket Title', 'System Office', 'system@example.test', 'October 10, 2026', 'Custom system change', '2026-10-14', '2026-10-20', 'System request details'],
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
                    'request_type' => ['Videography', 'Others'],
                    'other_request_type' => 'Stage recording',
                    'date_time' => 'October 25, 2026 at 10:00 AM',
                    'duration' => 'Two hours',
                    'requested_start_date' => '2026-10-22',
                    'requested_completion_date' => '2026-10-26',
                ],
                'expected' => ['Multimedia Ticket Title', 'Multimedia Office', 'multimedia@example.test', 'October 11, 2026', 'October 25, 2026 at 10:00 AM', 'Stage recording', '2026-10-22', '2026-10-26', 'Multimedia request details'],
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
                    'employee_name_signature' => 'Prepared by Account',
                    'supervisor_name' => 'Supervisor Name',
                    'client_signature_acknowledged' => 'Yes',
                ],
                'expected' => ['Incident Ticket Title', 'Incident Office / Staff', 'incident@example.test', '2026-10-13', '09:30', 'Computer Lab', 'Desktop', '2', 'INC-SERIAL', 'Damaged casing', 'Incident narrative', 'Power disconnected', 'Prepared by Account', 'Supervisor Name', 'Digital signature acknowledged'],
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
                    'agreed_to_terms' => 'Yes',
                ],
                'expected' => ['Borrower Ticket Title', 'Borrower purpose description', 'Borrower Requestor', 'Borrower Office', 'borrower@example.test', 'Projector', '3', 'BORROW-SERIAL', '2026-10-13', '2026-10-20', 'Agreed to Terms:', 'Yes'],
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

            $this->get(route('tickets.jobOrderPdf', $ticket))
                ->assertOk()
                ->assertHeader('content-type', 'application/pdf');

            $detailsPage = $this->get(route('tickets.show', $ticket))->assertOk();
            $submittedValues = array_merge($case['fields'], $case['form_data']);
            if ($case['form_type'] === 'multimedia_request') {
                unset($submittedValues['description']);
            }
            foreach ($submittedValues as $value) {
                foreach (is_array($value) ? $value : [$value] as $submittedValue) {
                    if (is_scalar($submittedValue) && $submittedValue !== '') {
                        $detailsPage->assertSeeText((string) $submittedValue);
                    }
                }
            }
        }
    }

    public function test_generic_ticket_job_order_uses_saved_contact_and_form_values()
    {
        $creator = User::factory()->create();

        $this->actingAs($creator)->post(route('tickets.store'), [
            'form_type' => 'generic',
            'title' => 'General Support Request',
            'client_name' => 'General Requestor',
            'contact_info' => '09123456789 / general@example.test',
            'department' => 'General Office',
            'description' => 'General request details',
            'meta' => [
                'equipment_type' => 'Laptop',
                'brand_model' => 'Model G',
                'serial_no' => 'GENERAL-SERIAL',
            ],
            'status' => 'Open',
        ])->assertRedirect();

        $ticket = Ticket::where('title', 'General Support Request')->firstOrFail();
        $this->assertSame('09123456789 / general@example.test', $ticket->contact_number);

        $jobOrder = view('tickets.print-general', ['ticket' => $ticket])->render();
        foreach ([
            'General Support Request',
            'General Requestor',
            'General Office',
            '09123456789 / general@example.test',
            'Laptop',
            'Model G',
            'GENERAL-SERIAL',
            'General request details',
        ] as $value) {
            $this->assertStringContainsString($value, $jobOrder);
        }

        $this->get(route('tickets.jobOrderPdf', $ticket))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_edit_forms_load_and_save_all_submitted_service_form_values()
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'it_staff', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $cases = [
            ['form_type' => 'equipment_repair', 'data' => [
                'full_name' => 'Repair Requestor', 'date_requested' => '2026-10-01',
                'office_address' => 'Repair Office', 'email_address' => 'repair@example.test',
                'property_no' => 'REPAIR-PROPERTY', 'acquisition_date' => '2020-01-02',
                'acquisition_cost' => '20000', 'last_repair_date' => '2025-01-03',
                'last_repair_nature' => 'Replaced screen',
            ]],
            ['form_type' => 'information_system_request', 'data' => [
                'date_requested' => '2026-10-02', 'full_name' => 'System Requestor',
                'office_address' => 'System Office', 'email_address' => 'system@example.test',
                'request_type' => ['Bug Report', 'Others'], 'other_request_type' => 'Custom feature',
                'requested_start_date' => '2026-10-10', 'requested_completion_date' => '2026-10-15',
            ]],
            ['form_type' => 'multimedia_request', 'data' => [
                'date_requested' => '2026-10-03', 'full_name' => 'Media Requestor',
                'office_address' => 'Media Office', 'email_address' => 'media@example.test',
                'request_type' => ['Videography'], 'other_request_type' => 'Stage recording',
                'location' => 'Main Hall', 'date_time' => 'October 25 at 10 AM',
                'duration' => 'Two hours', 'requested_start_date' => '2026-10-20',
                'requested_completion_date' => '2026-10-26',
            ]],
            ['form_type' => 'network_request', 'data' => [
                'date_requested' => '2026-10-04', 'full_name' => 'Network Requestor',
                'office_address' => 'Network Office', 'email_address' => 'network@example.test',
                'request_type' => ['VPN Access'], 'request_type_other' => 'Firewall access',
                'location' => 'Room 12', 'device_type' => ['Laptop'],
                'device_other' => 'Network appliance', 'mac_address' => '00:11:22:33:44:55',
                'requested_start_date' => '2026-10-21', 'requested_completion_date' => '2026-10-28',
            ]],
            ['form_type' => 'incident_report', 'data' => [
                'full_name' => 'Incident Requestor', 'office_name_position' => 'ICTO / Staff',
                'email_address' => 'incident@example.test', 'incident_date' => '2026-10-05',
                'incident_time' => '09:30', 'location' => 'Computer Lab',
                'equipment_type' => 'Desktop', 'equipment_qty' => '2',
                'equipment_serial' => 'INC-SERIAL', 'equipment_remarks' => 'Damaged case',
                'actions_taken' => 'Disconnected power', 'client_signature_acknowledged' => 'Yes',
                'employee_name_signature' => 'Prepared By', 'supervisor_name' => 'Supervisor',
            ]],
            ['form_type' => 'equipment_borrower', 'data' => [
                'full_name' => 'Borrower Requestor', 'office_name' => 'Borrower Office',
                'email_address' => 'borrower@example.test', 'equipment_type' => 'Projector',
                'quantity' => '3', 'serial_no' => 'BORROW-SERIAL',
                'date_borrowed' => '2026-10-06', 'expected_return_date' => '2026-10-12',
                'agreed_to_terms' => 'Yes',
            ]],
        ];

        foreach ($cases as $index => $case) {
            $title = "Edit form ticket {$index}";
            $this->actingAs($admin)->post(route('tickets.store'), [
                'form_type' => $case['form_type'],
                'title' => $title,
                'description' => "Request details {$index}",
                'client_name' => $case['data']['full_name'],
                'contact_number' => '09123456789',
                'meta' => $case['data'],
                'status' => 'Open',
            ])->assertRedirect();

            $ticket = Ticket::where('title', $title)->firstOrFail();
            $editPage = $this->get(route('tickets.edit', $ticket))->assertOk();
            foreach ($case['data'] as $value) {
                foreach (is_array($value) ? $value : [$value] as $submittedValue) {
                    $editPage->assertSee((string) $submittedValue);
                }
            }

            $changedName = "Updated Requestor {$index}";
            $changedField = $case['form_type'] === 'multimedia_request'
                ? 'requested_start_date'
                : (array_key_exists('office_address', $case['data']) ? 'office_address' : 'office_name');
            $changedFieldValue = $case['form_type'] === 'multimedia_request'
                ? '2026-10-22'
                : "Updated Office {$index}";
            $this->put(route('tickets.update', $ticket), [
                'title' => $title,
                'description' => $ticket->description ?: "Request details {$index}",
                'client_name' => $changedName,
                'contact_number' => '09123456789',
                'status' => 'Open',
                'form_data' => [
                    'full_name' => $changedName,
                    $changedField => $changedFieldValue,
                ],
            ])->assertRedirect();

            $ticket->refresh();
            $this->assertSame($changedName, $ticket->form_data['full_name']);
            $this->assertSame($changedFieldValue, $ticket->form_data[$changedField]);
            if ($case['form_type'] === 'multimedia_request') {
                $this->assertStringContainsString('Requested Start Date: October 22, 2026', $ticket->description);
            }
            $this->assertSame(
                $changedField === 'office_address' || $changedField === 'office_name'
                    ? $changedFieldValue
                    : ($case['data']['office_address'] ?? $case['data']['office_name'] ?? null),
                $ticket->form_data['office_address'] ?? $ticket->form_data['office_name'] ?? null
            );
            $this->assertArrayHasKey('original_form_type', $ticket->form_data);
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

    public function test_equipment_borrower_job_order_prints_borrower_and_assigned_it_personnel_names()
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'it_staff', 'guard_name' => 'web']);
        $admin = User::factory()->create(['name' => 'Ticket Administrator']);
        $itPersonnel = User::factory()->create(['name' => 'Assigned IT Personnel']);
        $admin->assignRole('admin');
        $itPersonnel->assignRole('it_staff');

        $this->actingAs($admin)->post(route('tickets.store'), [
            'form_type' => 'equipment_borrower',
            'title' => 'Borrow a projector',
            'description' => 'For classroom presentation',
            'client_name' => 'Form Borrower Name',
            'contact_number' => '09123456789',
            'assigned_to' => $itPersonnel->id,
            'meta' => [
                'full_name' => 'Form Borrower Name',
                'equipment_type' => 'Projector',
                'quantity' => '1',
                'agreed_to_terms' => 'Yes',
            ],
            'status' => 'Open',
        ])->assertRedirect();

        $ticket = Ticket::where('title', 'Borrow a projector')->firstOrFail();
        $jobOrder = view('tickets.borrower-form', ['ticket' => $ticket])->render();

        $this->assertSame($itPersonnel->id, $ticket->assigned_to);
        $this->assertSame('Assigned IT Personnel', $ticket->assignee->name);
        $this->assertSame(2, substr_count($jobOrder, 'Form Borrower Name'));
        $this->assertSame(2, substr_count($jobOrder, 'Assigned IT Personnel'));
        $this->assertDoesNotMatchRegularExpression('/Borrower:\s*Form Borrower Name/', $jobOrder);
        $this->assertMatchesRegularExpression('/border-bottom: 1px solid black; min-width: 220px; padding: 0 20px; display: inline-block;">Assigned IT Personnel<\/span>/', $jobOrder);

        $this->get(route('tickets.jobOrderPdf', $ticket))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_multimedia_ticket_description_includes_location_and_schedule_dates()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('tickets.store'), [
            'form_type' => 'multimedia_request',
            'title' => 'Graduation Ceremony Coverage',
            'description' => 'Please provide event coverage. Keep this remark out of the description.',
            'meta' => [
                'location' => 'Main Auditorium',
                'date_time' => 'October 25, 2026 at 10:00 AM',
                'requested_start_date' => '2026-10-22',
                'requested_completion_date' => '2026-10-26',
            ],
            'status' => 'Open',
        ])->assertRedirect();

        $ticket = Ticket::where('title', 'Graduation Ceremony Coverage')->firstOrFail();
        $this->assertSame(
            "Location: Main Auditorium\nDate and Time: October 25, 2026 at 10:00 AM\nRequested Start Date: October 22, 2026\nRequested Completion Date: October 26, 2026",
            $ticket->description
        );
        $this->assertStringNotContainsString('Please provide event coverage.', $ticket->description);
        $this->assertStringNotContainsString('Keep this remark out of the description.', $ticket->description);
        $this->assertSame('Please provide event coverage. Keep this remark out of the description.', $ticket->form_data['multimedia_request_details']);

        $this->get(route('tickets.show', $ticket))
            ->assertOk()
            ->assertDontSeeText('Please provide event coverage.')
            ->assertDontSeeText('Keep this remark out of the description.')
            ->assertSeeText('Location: Main Auditorium')
            ->assertSeeText('Date and Time: October 25, 2026 at 10:00 AM')
            ->assertSeeText('Requested Start Date: October 22, 2026')
            ->assertSeeText('Requested Completion Date: October 26, 2026');

        $jobOrder = view('tickets.print-multimedia', ['ticket' => $ticket])->render();
        $this->assertStringContainsString('Please provide event coverage. Keep this remark out of the description.', $jobOrder);
    }

}