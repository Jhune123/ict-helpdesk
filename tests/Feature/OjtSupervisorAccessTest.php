<?php

use App\Models\Department;
use App\Models\OjtAttendance;
use App\Models\User;
use Spatie\Permission\Models\Role;

function createOjtRoleUser(string $role, ?int $departmentId = null): User
{
    Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);

    $user = User::factory()->create([
        'department_id' => $departmentId,
        'is_ojt' => $role === 'ojt',
    ]);
    $user->assignRole($role);

    return $user;
}

test('supervisors cannot submit attendance logs', function () {
    $department = Department::create(['name' => 'ICT']);
    $supervisor = createOjtRoleUser('ojt_supervisor', $department->id);

    $this->actingAs($supervisor)
        ->post(route('ojt.store-log'))
        ->assertForbidden();
});

test('supervisors only see their department and have no self check-in controls', function () {
    $ownDepartment = Department::create(['name' => 'ICT']);
    $otherDepartment = Department::create(['name' => 'Finance']);
    $supervisor = createOjtRoleUser('supervisor', $ownDepartment->id);
    $ownIntern = createOjtRoleUser('ojt', $ownDepartment->id);
    $otherIntern = createOjtRoleUser('ojt', $otherDepartment->id);

    $this->actingAs($supervisor)
        ->get(route('supervisor.dashboard', ['department_id' => 'all']))
        ->assertOk()
        ->assertSee($ownIntern->name)
        ->assertDontSee($otherIntern->name);

    $this->get(route('ojt.dashboard'))
        ->assertOk()
        ->assertDontSee('Clock In / Attendance');
});

test('supervisors cannot view attendance reports for another department', function () {
    $ownDepartment = Department::create(['name' => 'ICT']);
    $otherDepartment = Department::create(['name' => 'Finance']);
    $supervisor = createOjtRoleUser('ojt_supervisor', $ownDepartment->id);
    $otherIntern = createOjtRoleUser('ojt', $otherDepartment->id);

    $this->actingAs($supervisor)
        ->get(route('ojt.print-daily', $otherIntern))
        ->assertForbidden();
});

test('OJT interns cannot edit attendance records', function () {
    $department = Department::create(['name' => 'ICT']);
    $intern = createOjtRoleUser('ojt', $department->id);
    $attendance = OjtAttendance::create([
        'user_id' => $intern->id,
        'department_id' => $department->id,
        'date' => now('Asia/Manila')->toDateString(),
        'status' => 'AM Checked In',
    ]);

    $this->actingAs($intern)
        ->put("/ojt/attendance/{$attendance->id}", [
            'status' => 'Completed Today',
        ])
        ->assertForbidden();
});

test('admins and IT staff can edit attendance records from the dashboard', function () {
    $department = Department::create(['name' => 'ICT']);
    $intern = createOjtRoleUser('ojt', $department->id);
    $attendance = OjtAttendance::create([
        'user_id' => $intern->id,
        'department_id' => $department->id,
        'date' => now('Asia/Manila')->toDateString(),
        'status' => 'AM Checked In',
    ]);

    foreach (['admin', 'it_staff'] as $role) {
        $staff = createOjtRoleUser($role, $department->id);

        $this->actingAs($staff)
            ->get(route('ojt.dashboard'))
            ->assertOk()
            ->assertSee('Edit');

        $this->put(route('ojt.update-log', $attendance), [
            'am_in' => '08:15:00',
            'am_out' => '11:45:00',
            'pm_in' => '13:00:00',
            'pm_out' => '17:00:00',
            'status' => 'Completed Today',
        ])->assertRedirect();

        $this->assertDatabaseHas('ojt_attendances', [
            'id' => $attendance->id,
            'am_in' => '08:15:00',
            'status' => 'Completed Today',
        ]);

        $this->patch(route('ojt.update-required-hours', $intern), [
            'required_hours' => 600,
        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $intern->id,
            'required_hours' => 600,
        ]);
    }
});

test('OJT interns see their exact rendered hours and required target on their dashboard', function () {
    $department = Department::create(['name' => 'ICT']);
    $intern = createOjtRoleUser('ojt', $department->id);
    $otherIntern = createOjtRoleUser('ojt', $department->id);
    $intern->update(['required_hours' => 300]);

    OjtAttendance::create([
        'user_id' => $intern->id,
        'department_id' => $department->id,
        'date' => now('Asia/Manila')->toDateString(),
        'am_in' => '08:00:00',
        'am_out' => '10:15:00',
        'status' => 'AM Checked Out',
    ]);

    $this->actingAs($intern)
        ->get(route('ojt.dashboard'))
        ->assertOk()
        ->assertSee('Your Total Rendered OJT Hours')
        ->assertSee('2 hrs 15 mins')
        ->assertSee('Requirement: 300 hrs')
        ->assertSee('297 hrs 45 mins remaining')
        ->assertSee('Hours rendered today')
        ->assertSee('Clock In / Out')
        ->assertDontSee($otherIntern->name);
});

test('OJT dashboard shows present and absent totals for its selected intern roster', function () {
    $department = Department::create(['name' => 'ICT']);
    $otherDepartment = Department::create(['name' => 'Finance']);
    $admin = createOjtRoleUser('admin');
    $presentIntern = createOjtRoleUser('ojt', $department->id);
    createOjtRoleUser('ojt', $department->id);
    createOjtRoleUser('ojt', $otherDepartment->id);

    OjtAttendance::create([
        'user_id' => $presentIntern->id,
        'department_id' => $department->id,
        'date' => now('Asia/Manila')->toDateString(),
        'am_in' => '08:00:00',
        'status' => 'AM Checked In',
    ]);

    $this->actingAs($admin)
        ->get(route('ojt.dashboard', ['department_id' => $department->id]))
        ->assertOk()
        ->assertViewHas('presentToday', 1)
        ->assertViewHas('absentToday', 1)
        ->assertSee('Total OJT Interns')
        ->assertSee('Present Today')
        ->assertSee('Absent Today');
});

test('supervisor department dashboard counts present and absent interns per department', function () {
    $department = Department::create(['name' => 'ICT']);
    $supervisor = createOjtRoleUser('ojt_supervisor', $department->id);
    $presentIntern = createOjtRoleUser('ojt', $department->id);
    createOjtRoleUser('ojt', $department->id);

    OjtAttendance::create([
        'user_id' => $presentIntern->id,
        'department_id' => $department->id,
        'date' => now('Asia/Manila')->toDateString(),
        'pm_in' => '13:00:00',
        'status' => 'PM Checked In',
    ]);

    $this->actingAs($supervisor)
        ->get(route('supervisor.dashboard'))
        ->assertOk()
        ->assertViewHas('totalInterns', 2)
        ->assertViewHas('activeToday', 1)
        ->assertViewHas('departmentStats', function ($stats) use ($department) {
            $departmentStat = $stats->firstWhere('id', $department->id);

            return $departmentStat->total_interns === 2
                && $departmentStat->present_today === 1
                && $departmentStat->absent_today === 1;
        })
        ->assertSee('1 Present · 1 Absent');
});
