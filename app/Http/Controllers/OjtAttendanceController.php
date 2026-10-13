<?php

namespace App\Http\Controllers;

use App\Models\OjtAttendance;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class OjtAttendanceController extends Controller
{
    /**
     * Set explicit timezone to Philippine Standard Time for reliable clock-ins
     */
    private string $timezone = 'Asia/Manila';

    public function dashboard(Request $request)
    {
        $currentUser = Auth::user();
        
        // 1. If user has no OJT role assigned yet (and is not Admin/IT Staff), direct them to role selection
        $hasRole = $currentUser->hasAnyRole(['ojt', 'supervisor', 'ojt_supervisor', 'admin', 'it_staff']);
        if (!$hasRole) {
            $departments = Department::orderBy('name')->get();
            return view('ojt.select-role', compact('departments'));
        }

        // 2. Reliable Server Date & Time
        $now = Carbon::now($this->timezone);
        $today = $now->toDateString();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $departments = Department::orderBy('name')->get();

        // 3. Base Query for OJT Interns
        $studentsQuery = User::where(function ($q) use ($today) {
                $q->where('is_ojt', true)
                  ->orWhereHas('roles', function ($r) {
                      $r->where('name', 'ojt');
                  })
                  ->orWhereHas('ojtAttendances', function ($a) use ($today) {
                      $a->whereDate('date', $today);
                  });
            })
            ->with(['department', 'ojtAttendances' => function ($q) use ($today) {
                $q->whereDate('date', $today);
            }]);

        if (
            !$currentUser->hasAnyRole(['supervisor', 'ojt_supervisor', 'admin', 'it_staff']) &&
            ($currentUser->hasRole('ojt') || $currentUser->is_ojt)
        ) {
            $studentsQuery->whereKey($currentUser->id);
        }

        // 🔒 DEPARTMENT SCOPING FOR SUPERVISOR
        if ($currentUser->hasAnyRole(['supervisor', 'ojt_supervisor'])) {
            $selectedDept = $currentUser->department_id;

            if ($selectedDept) {
                $studentsQuery->where('department_id', $selectedDept);
            } else {
                $studentsQuery->whereRaw('1 = 0');
            }
        } else {
            // Admin & IT Staff can filter by department
            $selectedDept = $request->get('department_id');
            if (!empty($selectedDept) && $selectedDept !== 'all') {
                $studentsQuery->where('department_id', $selectedDept);
            }
        }

        $students = $studentsQuery->get()->map(function ($student) use ($today, $currentMonth, $currentYear) {
            $todayLog = $student->ojtAttendances->first();
            $attendanceLogs = OjtAttendance::where('user_id', $student->id)->get();

            $todaysMinutes = $todayLog?->calculateRenderedMinutes() ?? 0;
            $renderedMinutes = $attendanceLogs->sum(
                fn ($log) => $log->calculateRenderedMinutes()
            );
            $monthlyMinutes = $attendanceLogs
                ->filter(fn ($log) => $log->date?->month === $currentMonth && $log->date?->year === $currentYear)
                ->sum(fn ($log) => $log->calculateRenderedMinutes());

            return (object) [
                'id'            => $student->id,
                'name'          => $student->name,
                'department'    => $student->department->name ?? 'Unassigned',
                'department_id' => $student->department_id,
                'proof_photo'   => $todayLog?->proof_photo,
                'status'        => $todayLog?->status ?? 'Not Checked In',
                'raw_am_in'     => $todayLog?->am_in,
                'raw_am_out'    => $todayLog?->am_out,
                'raw_pm_in'     => $todayLog?->pm_in,
                'raw_pm_out'    => $todayLog?->pm_out,
                'am_in'         => $todayLog?->am_in ? Carbon::parse($todayLog->am_in)->format('h:i A') : '--:--',
                'am_out'        => $todayLog?->am_out ? Carbon::parse($todayLog->am_out)->format('h:i A') : '--:--',
                'pm_in'         => $todayLog?->pm_in ? Carbon::parse($todayLog->pm_in)->format('h:i A') : '--:--',
                'pm_out'        => $todayLog?->pm_out ? Carbon::parse($todayLog->pm_out)->format('h:i A') : '--:--',
                'todays_hrs'    => OjtAttendance::formatDuration($todaysMinutes),
                'monthly_total' => OjtAttendance::formatDuration($monthlyMinutes),
                'rendered_total' => OjtAttendance::formatDuration($renderedMinutes),
                'required_hours' => $student->required_hours ?? 486,
                'log_id'        => $todayLog?->id,
            ];
        });

        $isOjtIntern = !$currentUser->hasAnyRole(['supervisor', 'ojt_supervisor', 'admin', 'it_staff'])
            && ($currentUser->hasRole('ojt') || $currentUser->is_ojt);
        $myRenderedMinutes = $isOjtIntern
            ? $currentUser->ojtAttendances()->get()->sum(fn ($log) => $log->calculateRenderedMinutes())
            : 0;
        $myRequiredHours = $currentUser->required_hours ?? 486;

        $totalLogsToday = OjtAttendance::whereDate('date', $today)->count();
        $departmentsCount = $departments->count();
        $serverTime = $now->format('F d, Y - h:i:s A') . ' (PST)';

        return view('ojt.dashboard', compact(
            'students',
            'departments',
            'selectedDept',
            'totalLogsToday',
            'departmentsCount',
            'serverTime',
            'isOjtIntern',
            'myRenderedMinutes',
            'myRequiredHours'
        ));
    }

    /**
     * Assign Role (OJT Intern, Supervisor, or OJT Supervisor)
     */
    public function selectRole(Request $request)
    {
        $request->validate([
            'role'          => 'required|in:ojt,supervisor,ojt_supervisor',
            'department_id' => 'required|exists:departments,id',
        ]);

        $user = Auth::user();
        $roleName = $request->role;

        // 🛡️ Auto-create role if it doesn't exist in DB to prevent RoleDoesNotExist exceptions
        Role::firstOrCreate([
            'name'       => $roleName,
            'guard_name' => 'web',
        ]);

        // Assign Spatie Role
        $user->syncRoles([$roleName]);

        // Update user record
        $user->update([
            'department_id'  => $request->department_id,
            'is_ojt'         => ($roleName === 'ojt'),
            'required_hours' => ($roleName === 'ojt') ? ($user->required_hours ?? 486) : 0,
        ]);

        return redirect()->route('ojt.dashboard')->with('success', 'Role assigned successfully as ' . strtoupper(str_replace('_', ' ', $roleName)) . '! 🎉');
    }

    /**
     * Store Time Log (Reliable Server Date-Time)
     */
    public function storeLog(Request $request)
    {
        $user = Auth::user();

        if (
            $user->hasAnyRole(['supervisor', 'ojt_supervisor']) ||
            (!$user->hasAnyRole(['ojt', 'admin', 'it_staff']) && !$user->is_ojt)
        ) {
            abort(403, 'Only OJT interns and authorized staff can submit attendance logs.');
        }

        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'action_type'   => 'required|in:am_in,am_out,pm_in,pm_out',
            'proof_photo'   => 'nullable|image|mimes:jpeg,jpg,png|max:10240',
        ]);

        $user->update([
            'department_id' => $request->department_id,
            'is_ojt'        => true,
        ]);

        // Strict Server Time
        $now = Carbon::now($this->timezone);
        $today = $now->toDateString();
        $timeNow = $now->toTimeString();

        $attendance = OjtAttendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            ['department_id' => $request->department_id, 'status' => 'Not Checked In']
        );

        $attendance->department_id = $request->department_id;

        if ($request->hasFile('proof_photo')) {
            $path = $request->file('proof_photo')->store('ojt_proofs', 'public');
            $attendance->proof_photo = $path;
        }

        switch ($request->action_type) {
            case 'am_in':
                $attendance->am_in = $timeNow;
                $attendance->status = 'AM Checked In';
                break;
            case 'am_out':
                $attendance->am_out = $timeNow;
                $attendance->status = 'AM Checked Out';
                break;
            case 'pm_in':
                $attendance->pm_in = $timeNow;
                $attendance->status = 'PM Checked In';
                break;
            case 'pm_out':
                $attendance->pm_out = $timeNow;
                $attendance->status = 'Completed Today';
                break;
        }

        $attendance->hours_rendered = $attendance->calculateDailyHours();
        $attendance->save();

        return back()->with('success', 'Attendance record updated successfully at ' . $now->format('h:i A') . '.');
    }

    /**
     * 🔒 Admin & IT-Staff Attendance Log Edit Method
     */
    public function updateLog(Request $request, OjtAttendance $attendance)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can edit attendance records.');
        }

        $request->validate([
            'am_in'  => 'nullable|date_format:H:i,H:i:s',
            'am_out' => 'nullable|date_format:H:i,H:i:s',
            'pm_in'  => 'nullable|date_format:H:i,H:i:s',
            'pm_out' => 'nullable|date_format:H:i,H:i:s',
            'status' => 'required|string|max:255',
        ]);

        $attendance->am_in  = $request->am_in;
        $attendance->am_out = $request->am_out;
        $attendance->pm_in  = $request->pm_in;
        $attendance->pm_out = $request->pm_out;
        $attendance->status = $request->status;

        // Recalculate rendered hours accurately
        $attendance->hours_rendered = $attendance->calculateDailyHours();
        $attendance->save();

        return back()->with('success', 'Attendance log updated successfully by Administrator! ✏️');
    }

    public function updateRequiredHours(Request $request, User $user)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized. Only Admins and IT Staff can update OJT required hours.');
        }

        $request->validate([
            'required_hours' => 'required|integer|min:0',
        ]);

        if (!$user->is_ojt && !$user->hasRole('ojt')) {
            abort(404, 'OJT intern not found.');
        }

        $user->update(['required_hours' => $request->integer('required_hours')]);

        return back()->with('success', 'OJT required hours updated successfully.');
    }

    /**
     * Generate Printable Daily Hours Report for Intern
     */
    public function printDaily(Request $request, User $user)
    {
        $this->authorizeSupervisorReportAccess($user);

        $today = Carbon::now($this->timezone)->toDateString();

        $attendance = OjtAttendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $renderedMinutes = $attendance?->calculateRenderedMinutes() ?? 0;

        return view('ojt.print-daily', compact('user', 'attendance', 'today', 'renderedMinutes'));
    }

    /**
     * Generate Printable Monthly Total Hours Report for Intern
     */
    public function printMonthly(Request $request, User $user)
    {
        $this->authorizeSupervisorReportAccess($user);

        $now = Carbon::now($this->timezone);
        $month = $request->get('month', $now->month);
        $year = $request->get('year', $now->year);

        $attendances = OjtAttendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'asc')
            ->get();

        $monthlyTotalMinutes = $attendances->sum(fn($log) => $log->calculateRenderedMinutes());

        return view('ojt.print-monthly', compact('user', 'attendances', 'month', 'year', 'monthlyTotalMinutes'));
    }

    private function authorizeSupervisorReportAccess(User $user): void
    {
        $viewer = Auth::user();

        if (
            $viewer->hasAnyRole(['supervisor', 'ojt_supervisor']) &&
            $viewer->department_id !== $user->department_id
        ) {
            abort(403, 'Supervisors can only view reports for interns in their department.');
        }
    }
}