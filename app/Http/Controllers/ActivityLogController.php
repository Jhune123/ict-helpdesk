<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; // For Excel export
use App\Exports\ActivityLogsExport;    // Custom Excel export class
use PDF;                               // For PDF export (barryvdh/laravel-dompdf)

class ActivityLogController extends Controller
{
    /**
     * Display filtered activity logs
     * Accessible to Admin & IT Staff only
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'subject'])->latest();

        // 🔍 Filter by User
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // 🔍 Filter by Action
        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        // 🔍 Filter by Description / Subject
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('subject', function ($q2) use ($search) {
                      $q2->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // 🔍 Filter by Date Range
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59',
            ]);
        }

        // 🔍 Pagination
        $logs = $query->paginate(20)->withQueryString();

        // 🔍 Users for dropdown filter
        $users = User::orderBy('name')->get();

        return view('activity_logs.index', compact('logs', 'users'));
    }

    /**
     * Export Activity Logs (Excel / PDF)
     * Admin & IT Staff only
     */
    public function export($type, Request $request)
    {
        $query = ActivityLog::with(['user', 'subject'])->latest();

        // Apply same filters as index
        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);
        if ($request->filled('action')) $query->where('action', 'like', "%{$request->action}%");
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('subject', function ($q2) use ($search) {
                      $q2->where('title', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [
                $request->from . ' 00:00:00',
                $request->to . ' 23:59:59',
            ]);
        }

        $logs = $query->get();

        if ($type === 'excel') {
            return Excel::download(new ActivityLogsExport($logs), 'activity_logs.xlsx');
        } elseif ($type === 'pdf') {
            $pdf = PDF::loadView('activity_logs.export_pdf', compact('logs'));
            return $pdf->download('activity_logs.pdf');
        }

        return redirect()->back()->with('error', 'Invalid export type.');
    }
}
