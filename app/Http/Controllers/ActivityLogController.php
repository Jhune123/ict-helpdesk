<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ActivityLogsExport;
// Using the modern Facade alias for barryvdh/laravel-dompdf
use Barryvdh\DomPDF\Facade\Pdf;

class ActivityLogController extends Controller
{
    /**
     * Display filtered activity logs
     * Accessible to Admin & IT Staff only via middleware in web.php
     */
    public function index(Request $request)
    {
        $users = User::orderBy('name')->get();
        $logs = $this->applyFilters($request)->paginate(20)->withQueryString();

        return view('activity_logs.index', compact('logs', 'users'));
    }

    /**
     * Export Activity Logs (Excel / PDF)
     * Admin & IT Staff only
     */
    public function export($type, Request $request)
    {
        $logs = $this->applyFilters($request)->get();

        if ($type === 'excel') {
            return Excel::download(new ActivityLogsExport($logs), 'activity_logs_' . now()->format('Ymd') . '.xlsx');
        } 
        
        if ($type === 'pdf') {
            // Ensure this matches the name of your blade file in resources/views/activity_logs/
            $pdf = Pdf::loadView('activity_logs.export_pdf', compact('logs'))
                      ->setPaper('a4', 'landscape'); // Landscape is better for log tables
            
            return $pdf->download('activity_logs_' . now()->format('Ymd') . '.pdf');
        }

        return redirect()->back()->with('error', 'Invalid export type.');
    }

    /**
     * Private helper to keep filtering logic DRY (Don't Repeat Yourself)
     * used by both index() and export()
     */
    private function applyFilters(Request $request)
    {
        $query = ActivityLog::with(['user', 'subject'])->latest();

        // 🔍 Filter by User
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // 🔍 Filter by Action (Exact match usually better for dropdowns, like for search)
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // 🔍 Search Description or Subject Title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHasMorph('subject', ['App\Models\Ticket'], function ($q2) use ($search) {
                      $q2->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // 🔍 Filter by Date Range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        return $query;
    }
}