<?php

use App\Mail\LeaveApprovedMail;
use App\Models\LeaveApplication;
use App\Models\LeaveBalance;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    foreach (['admin', 'it_staff', 'ojt'] as $role) {
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
    }
});

test('IT staff can submit a leave application with working days calculated automatically', function () {
    $staff = User::factory()->create();
    $staff->assignRole('it_staff');

    $this->actingAs($staff)
        ->post(route('staff.leaves.store'), [
            'applicant_last_name' => 'Staff',
            'applicant_first_name' => 'Test',
            'applicant_middle_name' => 'A',
            'applicant_signature' => 'Test A Staff',
            'filed_at' => '2026-11-02',
            'leave_type' => 'Vacation Leave',
            'vacation_within_philippines' => '1',
            'vacation_within_destination' => 'Tabuk City',
            'start_date' => '2026-11-09',
            'end_date' => '2026-11-11',
            'commutation_requested' => '0',
        ])
        ->assertRedirect(route('staff.leaves.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('leave_applications', [
        'user_id' => $staff->id,
        'applicant_last_name' => 'Staff',
        'applicant_first_name' => 'Test',
        'applicant_middle_name' => 'A',
        'applicant_signature' => 'Test A Staff',
        'filed_at' => '2026-11-02 00:00:00',
        'leave_type' => 'Vacation Leave',
        'details' => 'Within the Philippines: Tabuk City',
        'vacation_within_philippines' => 1,
        'vacation_within_destination' => 'Tabuk City',
        'working_days' => 3,
        'status' => 'pending',
        'commutation_requested' => 0,
    ]);
});

test('staff cannot submit overlapping pending leave applications', function () {
    $staff = User::factory()->create();
    $staff->assignRole('it_staff');
    LeaveApplication::create([
        'user_id' => $staff->id,
        'filed_at' => '2026-11-01',
        'leave_type' => 'Vacation Leave',
        'details' => 'Existing request.',
        'start_date' => '2026-11-09',
        'end_date' => '2026-11-11',
        'working_days' => 3,
        'commutation_requested' => false,
        'status' => 'pending',
    ]);

    $this->actingAs($staff)
        ->from(route('staff.leaves.index'))
        ->post(route('staff.leaves.store'), [
            'applicant_last_name' => 'Staff',
            'applicant_first_name' => 'Test',
            'applicant_signature' => 'Test Staff',
            'filed_at' => '2026-11-02',
            'leave_type' => 'Sick Leave',
            'sick_out_patient' => '1',
            'sick_out_patient_illness' => 'Checkup',
            'start_date' => '2026-11-10',
            'end_date' => '2026-11-10',
            'commutation_requested' => '0',
        ])
        ->assertRedirect(route('staff.leaves.index'))
        ->assertSessionHasErrors('start_date');

    expect(LeaveApplication::where('user_id', $staff->id)->count())->toBe(1);
});

test('leave form captures the form-six fields and validates leave-specific details', function () {
    $staff = User::factory()->create();
    $staff->assignRole('it_staff');

    $this->actingAs($staff)
        ->get(route('staff.leaves.index'))
        ->assertOk()
        ->assertSee('Civil Service Form No. 6')
        ->assertSee('Apply for Leave')
        ->assertSee('applicant_signature')
        ->assertSee('id="filed_at" name="filed_at" type="date" required', false)
        ->assertSee('@click="open = true"', false)
        ->assertSee('x-show="open"', false)
        ->assertSee('2. Name (Last, First and Middle)')
        ->assertSee('Specify the type of leave you are applying for')
        ->assertSee('6.A Type of leave to be availed of')
        ->assertSee('In case of Vacation/Special Privilege Leave:')
        ->assertSee('Within the Philippines')
        ->assertSee('Abroad')
        ->assertSee('In case of Sick Leave:')
        ->assertSee('In case of Special Leave Benefits for Women:')
        ->assertSee('In case of Study Leave:')
        ->assertSee('Monetization of Leave Credits')
        ->assertSee('Terminal Leave')
        ->assertDontSee('7. Details of Action on Application')
        ->assertDontSee('7.A Certification of Leave Credits')
        ->assertDontSee('Signature over printed name of Authorized Officer');

    $this->actingAs($staff)
        ->from(route('staff.leaves.index'))
        ->post(route('staff.leaves.store'), [
            'applicant_last_name' => 'Staff',
            'applicant_first_name' => 'Test',
            'filed_at' => '2026-11-02',
            'leave_type' => 'Sick Leave',
            'sick_in_hospital' => '1',
            'start_date' => '2026-11-09',
            'end_date' => '2026-11-09',
            'commutation_requested' => '0',
        ])
        ->assertRedirect(route('staff.leaves.index'))
        ->assertSessionHasErrors('sick_in_hospital_illness');
});

test('staff can select and submit the Section 6.B hospital and outpatient details', function () {
    $staff = User::factory()->create();
    $staff->assignRole('it_staff');

    $this->actingAs($staff)
        ->post(route('staff.leaves.store'), [
            'applicant_last_name' => 'Staff',
            'applicant_first_name' => 'Test',
            'applicant_signature' => 'Test Staff',
            'filed_at' => '2026-11-02',
            'leave_type' => 'Sick Leave',
            'sick_in_hospital' => '1',
            'sick_in_hospital_illness' => 'Flu',
            'sick_out_patient' => '1',
            'sick_out_patient_illness' => 'Checkup',
            'sick_additional_details' => 'Follow-up appointment.',
            'start_date' => '2026-11-09',
            'end_date' => '2026-11-09',
            'commutation_requested' => '0',
        ])
        ->assertRedirect(route('staff.leaves.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('leave_applications', [
        'user_id' => $staff->id,
        'sick_in_hospital' => 1,
        'sick_in_hospital_illness' => 'Flu',
        'sick_out_patient' => 1,
        'sick_out_patient_illness' => 'Checkup',
        'sick_additional_details' => 'Follow-up appointment.',
        'details' => "In Hospital — Flu\nOut Patient — Checkup\nFollow-up appointment.",
    ]);
});

test('staff can submit a domestic vacation leave without specifying a destination', function () {
    $staff = User::factory()->create();
    $staff->assignRole('it_staff');

    $this->actingAs($staff)
        ->post(route('staff.leaves.store'), [
            'applicant_last_name' => 'Staff',
            'applicant_first_name' => 'Test',
            'applicant_signature' => 'Test Staff',
            'filed_at' => '2026-11-02',
            'leave_type' => 'Vacation Leave',
            'vacation_within_philippines' => '1',
            'start_date' => '2026-11-09',
            'end_date' => '2026-11-09',
            'commutation_requested' => '0',
        ])
        ->assertRedirect(route('staff.leaves.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('leave_applications', [
        'user_id' => $staff->id,
        'leave_type' => 'Vacation Leave',
        'vacation_within_philippines' => 1,
        'vacation_within_destination' => null,
    ]);
});

test('admin can approve leave and approved days reduce the staff balance', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $staff = User::factory()->create();
    $staff->assignRole('it_staff');
    LeaveBalance::create([
        'user_id' => $staff->id,
        'mandatory_days' => 12,
        'sick_days' => 8,
        'special_privilege_days' => 5,
        'wellness_break_days' => 4,
    ]);
    $leave = LeaveApplication::create([
        'user_id' => $staff->id,
        'filed_at' => '2026-11-01',
        'leave_type' => 'Mandatory/Forced Leave',
        'details' => 'Family vacation.',
        'start_date' => '2026-11-09',
        'end_date' => '2026-11-11',
        'working_days' => 3,
        'commutation_requested' => false,
        'status' => 'pending',
    ]);
    foreach ([
        ['Sick Leave', '2026-11-12', '2026-11-12', 1, null],
        ['Special Privilege Leave', '2026-11-13', '2026-11-16', 2, null],
        ['Others', '2026-11-17', '2026-11-17', 1, 'Wellness Break'],
    ] as [$type, $startDate, $endDate, $days, $otherType]) {
        LeaveApplication::create([
            'user_id' => $staff->id,
            'filed_at' => '2026-11-01',
            'leave_type' => $type,
            'other_leave_type' => $otherType,
            'details' => 'Approved time off.',
            'start_date' => $startDate,
            'end_date' => $endDate,
            'working_days' => $days,
            'commutation_requested' => false,
            'status' => 'approved',
        ]);
    }

    $this->actingAs($admin)
        ->get(route('staff.leaves.index'))
        ->assertOk()
        ->assertSee('Staff Leave Credits')
        ->assertSee('Approve')
        ->assertSee('Mandatory/Forced credits')
        ->assertSee('Special Privilege credits')
        ->assertSee('Wellness Break credits');

    $this->actingAs($admin)
        ->patch(route('staff.leaves.review', $leave), ['status' => 'approved'])
        ->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('leave_applications', [
        'id' => $leave->id,
        'status' => 'approved',
        'reviewed_by' => $admin->id,
    ]);

    $this->actingAs($admin)
        ->get(route('staff.leaves.index'))
        ->assertOk()
        ->assertSee('Staff Leave Credits')
        ->assertSee('Mandatory/Forced credits');

    $this->actingAs($staff)
        ->get(route('staff.leaves.index'))
        ->assertOk()
        ->assertSee('Your Leave Balance')
        ->assertSee('Mandatory/Forced Leave')
        ->assertSee('Sick Leave')
        ->assertSee('Special Privilege Leave')
        ->assertSee('Others (Wellness Break)')
        ->assertSee('9.00')
        ->assertSee('7.00')
        ->assertSee('3.00');
});

test('admin dashboard shows live leave totals and the weekly availability timeline', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $staff = User::factory()->create(['name' => 'ICT Test Staff']);
    $staff->assignRole('it_staff');
    LeaveApplication::create([
        'user_id' => $staff->id,
        'filed_at' => '2026-11-01',
        'leave_type' => 'Sick Leave',
        'details' => 'Medical leave.',
        'start_date' => '2026-11-04',
        'end_date' => '2026-11-06',
        'working_days' => 3,
        'commutation_requested' => false,
        'status' => 'approved',
    ]);

    $this->travelTo(\Carbon\Carbon::parse('2026-11-04 10:00:00'));
    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Staff Availability &amp; Leave Monitor', false)
        ->assertSee('Open')
        ->assertSee(route('staff.leaves.index'))
        ->assertDontSee('On Leave Today')
        ->assertDontSee('ICT Test Staff');
});

test('staff can view only their own leave applications while admin can manage applications', function () {
    $staff = User::factory()->create(['name' => 'Leave Applicant']);
    $staff->assignRole('it_staff');
    $otherStaff = User::factory()->create(['name' => 'Other Applicant']);
    $otherStaff->assignRole('it_staff');
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $leave = LeaveApplication::create([
        'user_id' => $staff->id,
        'applicant_last_name' => 'Applicant',
        'applicant_first_name' => 'Leave',
        'applicant_signature' => 'Leave Applicant',
        'filed_at' => '2026-11-01',
        'leave_type' => 'Vacation Leave',
        'details' => 'Within the Philippines: Tabuk',
        'start_date' => '2026-11-09',
        'end_date' => '2026-11-10',
        'working_days' => 2,
        'commutation_requested' => false,
        'status' => 'pending',
    ]);

    $this->actingAs($staff)
        ->get(route('staff.leaves.index'))
        ->assertOk()
        ->assertSee('Vacation Leave')
        ->assertSee('View')
        ->assertSee('Edit')
        ->assertDontSee('Other Applicant')
        ->assertDontSee('Delete');

    $this->actingAs($otherStaff)
        ->get(route('staff.leaves.show', $leave))
        ->assertForbidden();
    $this->actingAs($otherStaff)
        ->get(route('staff.leaves.edit', $leave))
        ->assertForbidden();
    $this->actingAs($otherStaff)
        ->put(route('staff.leaves.update', $leave), [])
        ->assertForbidden();
    $this->actingAs($staff)
        ->get(route('staff.leaves.edit', $leave))
        ->assertOk()
        ->assertSee('You can edit your application while it is pending Admin review.');
    $this->actingAs($staff)
        ->get(route('staff.leaves.print', $leave))
        ->assertOk();
    $this->actingAs($staff)
        ->delete(route('staff.leaves.destroy', $leave))
        ->assertForbidden();

    $this->actingAs($admin)
        ->get(route('staff.leaves.show', $leave))
        ->assertOk()
        ->assertSee('Within the Philippines: Tabuk');
    $this->actingAs($admin)
        ->get(route('staff.leaves.edit', $leave))
        ->assertOk()
        ->assertSee('Edit Leave Application');
    $this->actingAs($admin)
        ->get(route('staff.leaves.print', $leave))
        ->assertOk()
        ->assertSee('APPLICATION FOR LEAVE')
        ->assertSee('Leave Applicant');

    $this->actingAs($staff)
        ->put(route('staff.leaves.update', $leave), [
            'applicant_last_name' => 'Applicant',
            'applicant_first_name' => 'Staff Edited',
            'applicant_middle_name' => '',
            'applicant_signature' => 'Staff Edited Applicant',
            'office_department' => 'ICT Department',
            'filed_at' => '2026-11-02',
            'position' => '',
            'designation' => '',
            'monthly_basic_salary' => '',
            'leave_type' => 'Vacation Leave',
            'other_leave_type' => '',
            'details' => 'Updated request details.',
            'start_date' => '2026-11-09',
            'end_date' => '2026-11-10',
            'commutation_requested' => '0',
        ])
        ->assertRedirect(route('staff.leaves.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('leave_applications', [
        'id' => $leave->id,
        'applicant_first_name' => 'Staff Edited',
        'filed_at' => '2026-11-02 00:00:00',
        'details' => 'Updated request details.',
        'working_days' => 2,
    ]);

    $this->actingAs($admin)
        ->put(route('staff.leaves.update', $leave), [
            'applicant_last_name' => 'Applicant',
            'applicant_first_name' => 'Updated',
            'applicant_middle_name' => '',
            'applicant_signature' => 'Updated Applicant',
            'office_department' => 'ICT Department',
            'filed_at' => '2026-11-03',
            'position' => 'Technician',
            'designation' => '',
            'monthly_basic_salary' => '',
            'leave_type' => 'Vacation Leave',
            'other_leave_type' => '',
            'details' => 'Within the Philippines: Baguio',
            'start_date' => '2026-11-09',
            'end_date' => '2026-11-11',
            'commutation_requested' => '0',
        ])
        ->assertRedirect(route('staff.leaves.index'))
        ->assertSessionHas('success');

    $this->assertDatabaseHas('leave_applications', [
        'id' => $leave->id,
        'applicant_first_name' => 'Updated',
        'applicant_signature' => 'Updated Applicant',
        'filed_at' => '2026-11-03 00:00:00',
        'working_days' => 3,
    ]);

    $this->actingAs($admin)
        ->get(route('staff.leaves.print', $leave))
        ->assertOk()
        ->assertSee('Within the Philippines: Baguio')
        ->assertDontSee('Within the Philippines: Tabuk');

    $this->actingAs($admin)
        ->delete(route('staff.leaves.destroy', $leave))
        ->assertRedirect(route('staff.leaves.index'));
    $this->assertDatabaseMissing('leave_applications', ['id' => $leave->id]);
});

test('IT staff cannot edit their application after it has been approved', function () {
    $staff = User::factory()->create();
    $staff->assignRole('it_staff');
    $leave = LeaveApplication::create([
        'user_id' => $staff->id,
        'filed_at' => '2026-11-01',
        'leave_type' => 'Sick Leave',
        'details' => 'Medical leave.',
        'start_date' => '2026-11-09',
        'end_date' => '2026-11-09',
        'working_days' => 1,
        'commutation_requested' => false,
        'status' => 'approved',
    ]);

    $this->actingAs($staff)
        ->get(route('staff.leaves.index'))
        ->assertOk()
        ->assertDontSee(route('staff.leaves.edit', $leave));

    $this->actingAs($staff)
        ->get(route('staff.leaves.edit', $leave))
        ->assertForbidden();

    $this->actingAs($staff)
        ->put(route('staff.leaves.update', $leave), [])
        ->assertForbidden();
});

test('staff can print only their own leave application in A4 portrait format', function () {
    $staff = User::factory()->create(['email' => 'leave.staff@example.com']);
    $staff->assignRole('it_staff');
    $otherStaff = User::factory()->create();
    $otherStaff->assignRole('it_staff');

    $leave = LeaveApplication::create([
        'user_id' => $staff->id,
        'filed_at' => '2026-11-01',
        'leave_type' => 'Vacation Leave',
        'details' => 'Within the Philippines: Tabuk',
        'start_date' => '2026-11-09',
        'end_date' => '2026-11-09',
        'working_days' => 1,
        'commutation_requested' => false,
        'status' => 'pending',
    ]);

    $this->actingAs($staff)
        ->get(route('staff.leaves.index'))
        ->assertOk()
        ->assertSee(route('staff.leaves.print', $leave));

    $this->actingAs($staff)
        ->get(route('staff.leaves.print', $leave))
        ->assertOk()
        ->assertSee('@page { size: A4 portrait;', false)
        ->assertSee('Civil Service Form No. 6')
        ->assertSee('ANNEX A')
        ->assertSee('image/KSU-logo.png')
        ->assertSee('image/Bagong-Pilipinas.png')
        ->assertSee('.republic { font-size: 10px;', false)
        ->assertSee('KALINGA STATE UNIVERSITY')
        ->assertSee('APPLICATION FOR LEAVE');
    $this->actingAs($staff)
        ->get(route('staff.leaves.print', $leave))
        ->assertSee('7. DETAILS OF ACTION ON APPLICATION')
        ->assertSee('7.A CERTIFICATION OF LEAVE CREDITS')
        ->assertSee('7.B RECOMMENDATION')
        ->assertSee('7.C APPROVED FOR:')
        ->assertSee('7.D DISAPPROVED DUE TO:');
    $this->actingAs($staff)
        ->get(route('staff.leaves.index'))
        ->assertSee('image/KSU-logo.png')
        ->assertSee('image/Bagong-Pilipinas.png');

    $this->actingAs($otherStaff)
        ->get(route('staff.leaves.print', $leave))
        ->assertForbidden();
});

test('approval emails IT staff and admin with the approved leave dates', function () {
    Mail::fake();

    $admin = User::factory()->create(['email' => 'admin@example.com']);
    $admin->assignRole('admin');
    $staff = User::factory()->create([
        'name' => 'Leave Applicant',
        'email' => 'applicant@example.com',
    ]);
    $staff->assignRole('it_staff');
    User::factory()->create([
        'email' => 'colleague@example.com',
        'role' => 'it_staff',
    ]);
    $ojt = User::factory()->create(['email' => 'ojt@example.com']);
    $ojt->assignRole('ojt');

    $leave = LeaveApplication::create([
        'user_id' => $staff->id,
        'applicant_last_name' => 'Different',
        'applicant_first_name' => 'Typed',
        'filed_at' => '2026-11-01',
        'leave_type' => 'Vacation Leave',
        'details' => 'Family vacation.',
        'start_date' => '2026-11-09',
        'end_date' => '2026-11-11',
        'working_days' => 3,
        'commutation_requested' => false,
        'status' => 'pending',
    ]);

    $this->actingAs($admin)
        ->patch(route('staff.leaves.review', $leave), ['status' => 'approved'])
        ->assertRedirect()
        ->assertSessionHas('success');

    Mail::assertSent(LeaveApprovedMail::class, function (LeaveApprovedMail $mail) use ($leave) {
        return $mail->leave->is($leave)
            && $mail->hasTo('admin@example.com')
            && $mail->hasBcc('applicant@example.com')
            && $mail->hasBcc('colleague@example.com')
            && ! $mail->hasBcc('admin@example.com')
            && ! $mail->hasBcc('ojt@example.com')
            && str_contains($mail->render(), 'Leave Applicant')
            && ! str_contains($mail->render(), 'Typed Different')
            && str_contains($mail->render(), 'November 9, 2026 to November 11, 2026')
            && str_contains($mail->render(), 'will be on leave on the approved dates');
    });
});
