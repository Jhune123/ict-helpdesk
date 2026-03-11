<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Department;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TaskScheduleController extends Controller
{
    /**
     * 📋 Display a listing of tasks.
     * Latest tasks appear on TOP for better visibility.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $tasks = Task::with('department')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhere('requested_by', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('remarks', 'like', "%{$search}%")
                      ->orWhereHas('department', function ($dq) use ($search) {
                          $dq->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->orderBy('created_at', 'desc') // ✅ FIXED: latest ADDED first
            ->get();                         // ✅ REQUIRED for DataTables integration

        return view('tasks.index', compact('tasks', 'search'));
    }

    /**
     * ➕ Show the form for creating a new task.
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
     * 💾 Store a newly created task.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff', 'client'])) {
            abort(403, 'Unauthorized action.');
        }

        // Normalize time format (ensure H:i:s for database)
        $request->merge([
            'start_time' => strlen($request->start_time) === 5 ? $request->start_time . ':00' : $request->start_time,
            'end_time'   => strlen($request->end_time) === 5 ? $request->end_time . ':00' : $request->end_time,
        ]);

        $validated = $request->validate([
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

        Task::create($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task Schedule created successfully ✅');
    }

    /**
     * 🔍 Display the specified task.
     */
    public function show(Task $task)
    {
        $task->load('department');

        return view('tasks.show', compact('task'));
    }

    /**
     * ✏️ Show the form for editing.
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
     * 🆙 Update the specified task.
     */
    public function update(Request $request, Task $task)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->merge([
            'start_time' => strlen($request->start_time) === 5 ? $request->start_time . ':00' : $request->start_time,
            'end_time'   => strlen($request->end_time) === 5 ? $request->end_time . ':00' : $request->end_time,
        ]);

        $validated = $request->validate([
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

        $task->update($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'Task Schedule updated successfully ✅');
    }

    /**
     * 🗑️ Remove the specified task.
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
     * 📄 Export all tasks to PDF (A4 Landscape).
     */
    public function exportPdf()
    {
        $tasks = Task::with('department')
            ->orderBy('date', 'asc')
            ->get();

        $pdf = Pdf::loadView('tasks.pdf', compact('tasks'))
            ->setPaper('A4', 'landscape');

        return $pdf->download(
            'task_schedule_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}