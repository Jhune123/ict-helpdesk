<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Department;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // PDF support

class TaskScheduleController extends Controller
{
    /**
     * Display a listing of tasks with search and department eager-load.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $tasks = Task::with('department') // ✅ eager load department
            ->when($search, function ($query, $search) {
                $query->where('description', 'like', "%{$search}%")
                      ->orWhere('requested_by', 'like', "%{$search}%")
                      ->orWhereHas('department', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      })
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('remarks', 'like', "%{$search}%");
            })
            ->orderBy('date', 'asc')
            ->paginate(10);

        return view('tasks.index', compact('tasks', 'search'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff', 'client'])) {
            abort(403, 'Unauthorized action.');
        }

        $departments = Department::orderBy('name')->get();

        return view('tasks.create', compact('departments'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff', 'client'])) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure time has seconds
        $startTime = $request->start_time;
        $endTime = $request->end_time;
        if (strlen($startTime) === 5) $startTime .= ':00';
        if (strlen($endTime) === 5) $endTime .= ':00';

        $request->merge([
            'start_time' => $startTime,
            'end_time'   => $endTime,
        ]);

        $request->validate([
            'date'          => 'required|date',
            'description'   => 'required|string|max:255',
            'requested_by'  => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'location'      => 'required|string|max:255',
            'start_time'    => 'required|date_format:H:i:s',
            'end_time'      => 'required|date_format:H:i:s|after:start_time',
            'assigned_to'   => 'nullable|string|max:255',
            'remarks'       => 'nullable|string|max:500',
        ]);

        Task::create($request->all());

        return redirect()->route('tasks.index')
            ->with('success', 'Task Schedule created successfully ✅');
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $task->load('department');

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $departments = Department::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'departments'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, Task $task)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $startTime = $request->start_time;
        $endTime = $request->end_time;
        if (strlen($startTime) === 5) $startTime .= ':00';
        if (strlen($endTime) === 5) $endTime .= ':00';

        $request->merge([
            'start_time' => $startTime,
            'end_time'   => $endTime,
        ]);

        $request->validate([
            'date'          => 'required|date',
            'description'   => 'required|string|max:255',
            'requested_by'  => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'location'      => 'required|string|max:255',
            'start_time'    => 'required|date_format:H:i:s',
            'end_time'      => 'required|date_format:H:i:s|after:start_time',
            'assigned_to'   => 'nullable|string|max:255',
            'remarks'       => 'nullable|string|max:500',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')
            ->with('success', 'Task Schedule updated successfully ✅');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task Schedule deleted successfully ❌');
    }

    /**
     * Export all tasks to PDF (A4 Landscape).
     */
    public function exportPdf()
    {
        $tasks = Task::with('department')->orderBy('date', 'asc')->get();

        $pdf = Pdf::loadView('tasks.pdf', compact('tasks'))
                  ->setPaper('A4', 'landscape');

        $fileName = 'task_schedule_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($fileName);
    }
}
