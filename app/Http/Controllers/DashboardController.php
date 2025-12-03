<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the Analytics Dashboard
     */
    public function analytics()
    {
        /* ===============================
         * TOTAL COUNTS
         * =============================== */
        $totalTickets   = Ticket::count();
        $openTickets    = Ticket::where('status', 'Open')->count();
        $closedTickets  = Ticket::where('status', 'Closed')->count();
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
            'closedTickets',
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
