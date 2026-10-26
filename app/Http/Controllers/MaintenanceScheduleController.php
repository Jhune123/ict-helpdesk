<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceCompletion;
use App\Models\User;
use App\Notifications\MaintenanceRescheduledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class MaintenanceScheduleController extends Controller
{
    public function index()
    {
        $schedules = MaintenanceSchedule::with('assignees')
            ->orderBy('next_run_date', 'asc')
            ->paginate(15);
        $completions = MaintenanceCompletion::latest('completed_at')
            ->paginate(15, ['*'], 'completed_page');

        return view('maintenance.index', compact('schedules', 'completions'));
    }

    public function create()
    {
        $staff = $this->getItPersonnel();
        // Load the checklist categories for the form
        $categories = $this->getChecklistCategories();
        
        return view('maintenance.create', compact('staff', 'categories'));
    }

    public function store(Request $request)
    {
        $staffIds = $this->getItPersonnel()->modelKeys();

        $request->validate([
            'office_college' => 'required|string',
            'frequency' => 'required|string',
            'title' => 'required|string',
            'confirmed_by' => 'nullable|string|max:255',
            'last_run_date' => 'required|date',
            'assigned_to' => 'required|array|min:1',
            'assigned_to.*' => ['required', 'integer', Rule::in($staffIds)],
        ]);

        $descriptionData = [
            'tasks' => $request->input('checklist', []),
            'remarks' => $request->input('remarks', '')
        ];

        $schedule = new MaintenanceSchedule($request->all());
        $schedule->description = json_encode($descriptionData);
        $schedule->next_run_date = $this->calculateNextRun($request->last_run_date, $request->frequency);
        $schedule->save();

        $schedule->assignees()->sync($request->assigned_to);

        return redirect()->route('maintenance.index')->with('success', 'Schedule created successfully.');
    }

    public function show($id)
    {
        $maintenance = MaintenanceSchedule::with('assignees')->findOrFail($id);
        return view('maintenance.show', compact('maintenance'));
    }

    public function showCompletion($completionId)
    {
        $completion = MaintenanceCompletion::findOrFail($completionId);

        return view('maintenance.completion', compact('completion'));
    }

    public function edit($id)
    {
        $maintenance = MaintenanceSchedule::findOrFail($id);
        $staff = $this->getItPersonnel();
        
        // Load the checklist categories for the form
        $categories = $this->getChecklistCategories();

        return view('maintenance.edit', compact('maintenance', 'staff', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $maintenance = MaintenanceSchedule::findOrFail($id);
        $staffIds = $this->getItPersonnel()->modelKeys();

        $request->validate([
            'office_college' => 'required|string',
            'frequency' => 'required|string',
            'title' => 'required|string',
            'confirmed_by' => 'nullable|string|max:255',
            'last_run_date' => 'required|date',
            'assigned_to' => 'required|array|min:1',
            'assigned_to.*' => ['required', 'integer', Rule::in($staffIds)],
        ]);

        $descriptionData = [
            'tasks' => $request->input('checklist', []),
            'remarks' => $request->input('remarks', '')
        ];

        $maintenance->fill($request->all());
        $maintenance->description = json_encode($descriptionData);
        $maintenance->next_run_date = $this->calculateNextRun($request->last_run_date, $request->frequency);
        $maintenance->save();

        $maintenance->assignees()->sync($request->assigned_to);

        return redirect()->route('maintenance.index')->with('success', 'Schedule updated.');
    }

    /**
     * Matches route: maintenance.complete (POST /{id}/complete)
     */
    public function completeTask($id)
    {
        $schedule = MaintenanceSchedule::with('assignees')->findOrFail($id);
        $newLastRun = Carbon::now();
        $newNextRun = $this->calculateNextRun($newLastRun, $schedule->frequency);

        DB::transaction(function () use ($schedule, $newLastRun, $newNextRun) {
            $snapshot = $schedule->only([
                'office_college',
                'frequency',
                'title',
                'device_model',
                'property_number',
                'serial_number',
                'confirmed_by',
                'description',
            ]);
            $snapshot['schedule_id'] = $schedule->id;
            $snapshot['last_run_date'] = $newLastRun->toDateString();
            $snapshot['next_run_date'] = $newNextRun->toDateString();
            $snapshot['assignees'] = $schedule->assignees
                ->map(fn (User $assignee) => [
                    'id' => $assignee->id,
                    'name' => $assignee->name,
                ])
                ->values()
                ->all();

            MaintenanceCompletion::create([
                'maintenance_schedule_id' => $schedule->id,
                'completed_at' => $newLastRun,
                'snapshot' => $snapshot,
            ]);

            $schedule->update([
                'last_run_date' => $newLastRun,
                'next_run_date' => $newNextRun,
            ]);
        });

        foreach ($schedule->assignees as $assignee) {
            $assignee->notify(new MaintenanceRescheduledNotification($schedule));
        }

        return back()->with('success', 'Task completed and rescheduled.');
    }

    /**
     * Matches route: maintenance.pdf (GET /export/pdf)
     */
    public function exportPdf()
    {
        $schedules = MaintenanceSchedule::with('assignees')->get();
        $pdf = Pdf::loadView('maintenance.pdf_view', compact('schedules'))
                  ->setPaper('a4', 'landscape');
        
        return $pdf->download('KSU-Maintenance-Summary-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Matches route: maintenance.job_order (GET /{id}/job-order)
     */
    public function downloadJobOrder($id)
    {
        $maintenance = MaintenanceSchedule::with('assignees')->findOrFail($id);
        $pdf = Pdf::loadView('maintenance.job_order_pdf', compact('maintenance'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('KSU-Job-Order-PMS-'.$maintenance->id.'.pdf');
    }

    public function destroy($id)
    {
        $maintenance = MaintenanceSchedule::findOrFail($id);
        $maintenance->delete();

        return redirect()->route('maintenance.index')->with('success', 'Record deleted.');
    }

    /**
     * Helper to calculate the next scheduled maintenance date
     */
    private function calculateNextRun($date, $frequency)
    {
        $lastRun = Carbon::parse($date);
        return match(strtolower($frequency)) {
            'daily' => $lastRun->addDay(),
            'weekly' => $lastRun->addWeek(),
            'monthly' => $lastRun->addMonth(),
            'quarterly' => $lastRun->addMonths(3),
            'semi-annual' => $lastRun->addMonths(6),
            'yearly' => $lastRun->addYear(),
            default => $lastRun,
        };
    }

    /**
     * Helper to define the predefined maintenance tasks/checklists
     */
    private function getChecklistCategories()
    {
        return [
            'hardware' => [
                'label' => 'Hardware Maintenance',
                'tasks' => ['Physical Cleaning', 'Cable Management', 'Port Inspection', 'Fan Dusting']
            ],
            'software' => [
                'label' => 'Software & OS',
                'tasks' => ['OS Updates', 'Antivirus Scan', 'Registry Cleanup', 'Driver Updates']
            ],
            'performance' => [
                'label' => 'Performance Check',
                'tasks' => ['Disk Defrag', 'Startup Optimization', 'Temp File Deletion', 'Stress Testing']
            ]
        ];
    }

    private function getItPersonnel()
    {
        return User::query()
            ->where(function ($query) {
                $query->whereIn('role', ['admin', 'it_staff'])
                    ->orWhereHas('roles', function ($roleQuery) {
                        $roleQuery->whereIn('name', ['admin', 'it_staff']);
                    });
            })
            ->orderBy('name')
            ->get();
    }
}