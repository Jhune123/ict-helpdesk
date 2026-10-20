<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the Main OJT Dashboard
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();

        // 1. Fetch OJT Trainees with their department & attendance relationship
        $studentsQuery = User::role('ojt')->with(['department', 'ojtAttendances']);

        // 2. Department Scoping Logic:
        // Lock Supervisors to ONLY see interns in their assigned department
        if ($currentUser->hasRole('supervisor')) {
            $selectedDept = $currentUser->department_id;
            $studentsQuery->where('department_id', $selectedDept);
        } else {
            // Admin & IT Staff can filter by selected department
            $selectedDept = $request->input('department_id');
            if (!empty($selectedDept)) {
                $studentsQuery->where('department_id', $selectedDept);
            }
        }

        $students = $studentsQuery->orderBy('name', 'asc')->get();

        // 3. Stats calculation
        $departments = class_exists(Department::class) ? Department::all() : collect();
        $departmentsCount = $departments->count();

        // Count total active logs for today
        $totalLogsToday = User::role('ojt')
            ->whereHas('ojtAttendances', function ($query) {
                $query->whereDate('created_at', Carbon::today());
            })
            ->count();

        return view('dashboard', compact(
            'students',
            'departments',
            'selectedDept',
            'departmentsCount',
            'totalLogsToday'
        ));
    }

    /**
     * Display the Analytics Dashboard
     */
    public function analytics()
    {
        /* ===============================
         * TOTAL COUNTS
         * =============================== */
        $openTickets    = Ticket::open()->count();
        $inProgressTickets = Ticket::withStatus('In Progress')->count();
        $closedTickets  = Ticket::resolved()->count();
        $condemnedTickets = Ticket::withStatus('Condemned')->count();
        $totalTickets   = $openTickets + $inProgressTickets + $condemnedTickets + $closedTickets;
        $closedTicketPercentage = $totalTickets > 0
            ? round(($closedTickets / $totalTickets) * 100, 1)
            : 0;
        $totalAssets    = Asset::count();

        /* ===============================
         * MONTHLY TICKET TREND (12 MONTHS)
         * =============================== */
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create(null, $month, 1)->format('M');
        });

        $monthlyData = collect(range(1, 12))->map(function ($month) {
            return Ticket::whereMonth('created_at', $month)->count();
        });

        $monthlyLabels = $months->values();

        /* ===============================
         * TICKETS PER DEPARTMENT
         * =============================== */
        $ticketsPerDepartment = Ticket::selectRaw('department, COUNT(*) as total')
            ->groupBy('department')
            ->pluck('total', 'department')
            ->toArray();

        /* ===============================
         * TICKETS PER CATEGORY
         * =============================== */
        $ticketsPerCategory = Ticket::selectRaw('category_id, COUNT(*) as total')
            ->groupBy('category_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->category->name ?? 'Uncategorized' => $item->total];
            })
            ->toArray();

        /* ===============================
         * TICKETS PER IT PERSONNEL
         * =============================== */
        $ticketsPerPersonnel = Ticket::selectRaw('assigned_to, COUNT(*) as total')
            ->whereNotNull('assigned_to')
            ->groupBy('assigned_to')
            ->get()
            ->mapWithKeys(function ($item) {
                $user = User::find($item->assigned_to);
                return [$user->name ?? 'Unknown' => $item->total];
            })
            ->toArray();

        /* ===============================
         * RECENT ACTIVITIES (Latest 10)
         * =============================== */
        $recentActivities = Ticket::latest()
            ->take(10)
            ->get()
            ->map(function ($ticket) {
                return (object)[
                    'title' => "Ticket #{$ticket->ticket_number} - {$ticket->status}",
                    'created_at' => $ticket->created_at
                ];
            });

        /* ===============================
         * RETURN TO VIEW
         * =============================== */
        return view('dashboard.analytics', compact(
            'totalTickets',
            'openTickets',
            'inProgressTickets',
            'closedTickets',
            'condemnedTickets',
            'closedTicketPercentage',
            'totalAssets',
            'monthlyLabels',
            'monthlyData',
            'ticketsPerDepartment',
            'ticketsPerCategory',
            'ticketsPerPersonnel',
            'recentActivities'
        ));
    }
}