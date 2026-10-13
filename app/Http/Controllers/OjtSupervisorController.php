<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OjtAttendance;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OjtSupervisorController extends Controller
{
    private string $timezone = 'Asia/Manila';

    public function dashboard(Request $request)
    {
        $supervisor = Auth::user();
        $now = Carbon::now($this->timezone);
        $today = $now->toDateString();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $isDepartmentSupervisor = $supervisor->hasAnyRole(['supervisor', 'ojt_supervisor']);

        if ($isDepartmentSupervisor) {
            $selectedDepartmentId = (string) ($supervisor->department_id ?? '');
            $departments = Department::whereKey($supervisor->department_id)
                ->when(!$supervisor->department_id, fn ($query) => $query->whereRaw('1 = 0'))
                ->orderBy('name')
                ->get();
        } else {
            $selectedDepartmentId = $request->get('department_id', 'all');
            $departments = Department::orderBy('name')->get();
        }

        // Base query to retrieve all OJT interns
        $allInternsQuery = User::where(function ($q) {
            $q->where('is_ojt', true)
              ->orWhereHas('roles', function ($r) {
                  $r->where('name', 'ojt');
              });
        })->with(['department', 'ojtAttendances' => function ($q) use ($today) {
            $q->whereDate('date', $today);
        }]);

        if ($isDepartmentSupervisor) {
            if ($supervisor->department_id) {
                $allInternsQuery->where('department_id', $supervisor->department_id);
            } else {
                $allInternsQuery->whereRaw('1 = 0');
            }
        }

        $allInterns = $allInternsQuery->get();

        // Generate per-department statistics breakdown
        $departmentStats = $departments->map(function ($dept) use ($allInterns) {
            $deptInterns = $allInterns->where('department_id', $dept->id);
            $total = $deptInterns->count();
            
            $present = $deptInterns->filter(function ($intern) {
                $log = $intern->ojtAttendances->first();
                return $log && in_array($log->status, ['AM Checked In', 'AM Checked Out', 'PM Checked In', 'Completed Today', 'On Premises']);
            })->count();

            return (object) [
                'id'            => $dept->id,
                'name'          => $dept->name,
                'total_interns' => $total,
                'present_today' => $present,
                'absent_today'  => max(0, $total - $present),
            ];
        });

        // Filter intern list based on selected department
        $filteredInterns = $allInterns;
        if (!$isDepartmentSupervisor && $selectedDepartmentId !== 'all') {
            $filteredInterns = $allInterns->where('department_id', (int) $selectedDepartmentId);
        }

        // Stats for current selected scope
        $totalInterns = $filteredInterns->count();
        $activeToday = 0;
        $totalRenderedMinutes = 0;

        $students = $filteredInterns->map(function ($student) use ($today, $currentMonth, $currentYear, &$activeToday, &$totalRenderedMinutes) {
            $todayLog = $student->ojtAttendances->first();

            $isPresent = $todayLog && in_array($todayLog->status, ['AM Checked In', 'AM Checked Out', 'PM Checked In', 'Completed Today', 'On Premises']);
            if ($isPresent) {
                $activeToday++;
            }

            $todaysMinutes = $todayLog?->calculateRenderedMinutes() ?? 0;

            // Monthly logs
            $monthlyLogs = OjtAttendance::where('user_id', $student->id)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->get();

            $monthlyMinutes = $monthlyLogs->sum(fn($log) => $log->calculateRenderedMinutes());
            $totalRenderedMinutes += $monthlyMinutes;

            return (object) [
                'id'            => $student->id,
                'name'          => $student->name,
                'email'         => $student->email,
                'department'    => $student->department->name ?? 'Unassigned',
                'department_id' => $student->department_id,
                'proof_photo'   => $todayLog?->proof_photo,
                'status'        => $todayLog?->status ?? 'Not Checked In',
                'am_in'         => $todayLog?->am_in ? Carbon::parse($todayLog->am_in)->format('h:i A') : '--:--',
                'am_out'        => $todayLog?->am_out ? Carbon::parse($todayLog->am_out)->format('h:i A') : '--:--',
                'pm_in'         => $todayLog?->pm_in ? Carbon::parse($todayLog->pm_in)->format('h:i A') : '--:--',
                'pm_out'        => $todayLog?->pm_out ? Carbon::parse($todayLog->pm_out)->format('h:i A') : '--:--',
                'todays_hrs'    => OjtAttendance::formatDuration($todaysMinutes),
                'monthly_total' => OjtAttendance::formatDuration($monthlyMinutes),
                'required_hours'=> $student->required_hours ?? 486,
                'log_id'        => $todayLog?->id,
            ];
        });

        $totalHoursRendered = $totalRenderedMinutes / 60;

        // Determine Header Display Title
        $selectedDeptObj = $departments->firstWhere('id', (int) $selectedDepartmentId);
        $departmentName = $selectedDepartmentId === 'all' ? 'All Departments' : ($selectedDeptObj->name ?? 'Department');

        return view('supervisor.dashboard', compact(
            'students',
            'departments',
            'departmentStats',
            'selectedDepartmentId',
            'departmentName',
            'totalInterns',
            'activeToday',
            'totalHoursRendered',
            'today',
            'supervisor'
        ));
    }

}