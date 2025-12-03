<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Asset;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Show full Analytics Dashboard
     */
    public function index()
    {
        // -----------------------
        // TICKET STATS
        // -----------------------
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'Open')->count();
        $inProgressTickets = Ticket::where('status', 'In Progress')->count();
        $closedTickets = Ticket::where('status', 'Closed')->count();

        // -----------------------
        // ASSET STATS
        // -----------------------
        $totalAssets = Asset::count();
        $activeAssets = Asset::where('status', 'Active')->count();
        $inactiveAssets = Asset::where('status', 'Inactive')->count();

        // -----------------------
        // MONTHLY TICKETS TREND
        // -----------------------
        $monthlyData = Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Ensure all 12 months exist
        $monthlyLabels = [];
        $monthlyCounts = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyLabels[] = date('F', mktime(0,0,0,$m,1));
            $monthlyCounts[] = $monthlyData[$m] ?? 0;
        }

        // -----------------------
        // TICKETS PER DEPARTMENT
        // -----------------------
        $ticketsPerDepartment = Ticket::selectRaw('department, COUNT(*) as total')
            ->groupBy('department')
            ->pluck('total', 'department')
            ->toArray();

        // -----------------------
        // TICKETS PER CATEGORY
        // -----------------------
        $ticketsPerCategory = Ticket::with('category')
            ->get()
            ->groupBy(fn($t) => $t->category?->name ?? 'Uncategorized')
            ->map(fn($items) => count($items))
            ->toArray();

        // -----------------------
        // TICKETS PER IT PERSONNEL
        // -----------------------
        $ticketsPerPersonnel = Ticket::with('assignee')
            ->get()
            ->groupBy(fn($t) => $t->assignee?->name ?? 'Unassigned')
            ->map(fn($items) => count($items))
            ->toArray();

        // -----------------------
        // RECENT ACTIVITY
        // -----------------------
        $recentActivities = Ticket::latest()->take(5)->get();

        // -----------------------
        // RETURN VIEW
        // -----------------------
        return view('dashboard.analytics', [
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'inProgressTickets' => $inProgressTickets,
            'closedTickets' => $closedTickets,

            'totalAssets' => $totalAssets,
            'activeAssets' => $activeAssets,
            'inactiveAssets' => $inactiveAssets,

            'monthlyLabels' => $monthlyLabels,
            'monthlyData' => $monthlyCounts,

            'ticketsPerDepartment' => $ticketsPerDepartment,
            'ticketsPerCategory' => $ticketsPerCategory,
            'ticketsPerPersonnel' => $ticketsPerPersonnel,

            'recentActivities' => $recentActivities,
        ]);
    }
}
