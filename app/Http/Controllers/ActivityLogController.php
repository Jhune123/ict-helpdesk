<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display all activity logs
     * Accessible only by Admin & IT Staff (via route middleware)
     */
    public function index()
    {
        // Eager-load 'user' to avoid N+1 queries
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(20);

        return view('activity_logs.index', compact('logs'));
    }
}
