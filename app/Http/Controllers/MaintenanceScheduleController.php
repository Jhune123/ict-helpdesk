<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class MaintenanceScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = MaintenanceSchedule::with('assignees')
            ->orderBy('next_run_date', 'asc')
            ->paginate(15);

        return view('maintenance.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staff = User::whereHas('roles', function($q){
            $q->whereIn('name', ['admin', 'it_staff']); 
        })->orWhereIn('role', ['admin', 'it_staff'])->get();

        return view('maintenance.create', compact('staff'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'office_college'  => 'required|string|max:255',
            'device_model'    => 'nullable|string|max:255',
            'property_number' => 'nullable|string|max:255',
            'serial_number'   => 'nullable|string|max:255',
            'description'     => 'required|string',
            'frequency'       => 'required|in:daily,weekly,monthly,quarterly,semi-annual,yearly',
            'last_run_date'   => 'required|date',
            'assigned_to'     => 'required|array', 
            'assigned_to.*'   => 'exists:users,id',
            'priority'        => 'required|in:Low,Normal,High,Critical',
            'category'        => 'nullable|string',
        ]);

        $validated['next_run_date'] = $this->calculateNextRun($request->last_run_date, $request->frequency);

        $staffIds = $validated['assigned_to'];
        unset($validated['assigned_to']);

        $schedule = MaintenanceSchedule::create($validated);

        // Save multiple staff to the pivot table
        $schedule->assignees()->sync($staffIds);

        return redirect()->route('maintenance.index')
            ->with('success', 'Maintenance Schedule created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $maintenance = MaintenanceSchedule::findOrFail($id);

        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'office_college'  => 'required|string|max:255',
            'device_model'    => 'nullable|string|max:255',
            'property_number' => 'nullable|string|max:255',
            'serial_number'   => 'nullable|string|max:255',
            'description'     => 'required|string',
            'frequency'       => 'required|in:daily,weekly,monthly,quarterly,semi-annual,yearly',
            'last_run_date'   => 'required|date',
            'assigned_to'     => 'required|array', 
            'assigned_to.*'   => 'exists:users,id',
            'priority'        => 'required',
            'category'        => 'nullable|string',
        ]);

        $validated['next_run_date'] = $this->calculateNextRun($request->last_run_date, $request->frequency);

        $staffIds = $validated['assigned_to'];
        unset($validated['assigned_to']);

        $maintenance->update($validated);
        $maintenance->assignees()->sync($staffIds);

        return redirect()->route('maintenance.index')
            ->with('success', 'Schedule updated successfully.');
    }

    /**
     * Mark a task as completed and automatically schedule the next one.
     */
    public function completeTask($id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $today = Carbon::now();

        // Ensure we update using the existing frequency logic
        $schedule->update([
            'last_run_date' => $today->toDateString(),
            'next_run_date' => $this->calculateNextRun($today, $schedule->frequency)
        ]);

        return redirect()->route('maintenance.index')
            ->with('success', "Maintenance marked as completed and rescheduled.");
    }

    /**
     * Download a detailed Job Order for a specific task.
     */
    public function downloadJobOrder($id)
    {
        $schedule = MaintenanceSchedule::with('assignees')->findOrFail($id);
        
        $pdf = Pdf::loadView('maintenance.job_order_pdf', compact('schedule'))
                  ->setPaper('a4', 'portrait');
        
        return $pdf->download('JobOrder_PMS_' . str_pad($schedule->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }

    private function calculateNextRun($lastRunDate, $frequency)
    {
        $date = Carbon::parse($lastRunDate);

        switch ($frequency) {
            case 'daily':       return $date->addDay();
            case 'weekly':      return $date->addWeek();
            case 'monthly':     return $date->addMonth();
            case 'quarterly':   return $date->addMonths(3);
            case 'semi-annual': return $date->addMonths(6);
            case 'yearly':      return $date->addYear();
            default:            return $date;
        }
    }

    public function show($id)
    {
        $maintenance = MaintenanceSchedule::with('assignees')->findOrFail($id);
        return view('maintenance.show', compact('maintenance'));
    }

    public function edit($id)
    {
        $maintenance = MaintenanceSchedule::findOrFail($id);
        
        $staff = User::whereHas('roles', function($q){
            $q->whereIn('name', ['admin', 'it_staff']); 
        })->orWhereIn('role', ['admin', 'it_staff'])->get();

        $selectedStaff = $maintenance->assignees->pluck('id')->toArray();

        return view('maintenance.edit', compact('maintenance', 'staff', 'selectedStaff'));
    }

    public function destroy($id)
    {
        $maintenance = MaintenanceSchedule::findOrFail($id);
        $maintenance->delete();
        return redirect()->route('maintenance.index')
            ->with('success', 'Schedule deleted successfully.');
    }

    public function exportPdf()
    {
        $schedules = MaintenanceSchedule::with('assignees')
            ->orderBy('next_run_date', 'asc')
            ->get();

        $pdf = Pdf::loadView('maintenance.pdf_view', compact('schedules'))
                  ->setPaper('a4', 'landscape'); 
        
        return $pdf->download('KSU_Preventive_Maintenance_Schedule_' . date('Y-m-d') . '.pdf');
    }
}