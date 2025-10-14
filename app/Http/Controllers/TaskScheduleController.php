<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskScheduleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $tasks = Task::query()
            ->when($search, function ($query, $search) {
                $query->where('description', 'like', "%{$search}%")
                    ->orWhere('requested_by', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('remarks', 'like', "%{$search}%");
            })
            ->orderBy('date', 'asc')
            ->paginate(10);

        return view('tasks.index', compact('tasks', 'search'));
    }

    public function create()
    {
        // Only clients, admins, or IT staff can create
        if (auth()->user()->hasRole('client') || auth()->user()->hasAnyRole(['admin', 'it_staff'])) {
            return view('tasks.create');
        }

        abort(403, 'Unauthorized action.');
    }

    public function store(Request $request)
    {
        // Ensure only authorized users can store
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff', 'client'])) {
            abort(403, 'Unauthorized action.');
        }

        // Add seconds to time format
        $request->merge([
            'start_time' => $request->start_time . ':00',
            'end_time'   => $request->end_time . ':00',
        ]);

        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'requested_by' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i:s',
            'end_time'   => 'required|date_format:H:i:s|after:start_time',
            'assigned_to' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
        ]);

        Task::create($request->all());

        return redirect()->route('tasks.index')
            ->with('success', 'Task Schedule created successfully ✅');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        // Only admin and IT staff can edit
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action.');
        }

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        // Only admin and IT staff can update
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->merge([
            'start_time' => $request->start_time . ':00',
            'end_time'   => $request->end_time . ':00',
        ]);

        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'requested_by' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i:s',
            'end_time'   => 'required|date_format:H:i:s|after:start_time',
            'assigned_to' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:500',
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')
            ->with('success', 'Task Schedule updated successfully ✅');
    }

    public function destroy(Task $task)
    {
        // Only admin and IT staff can delete
        if (!auth()->user()->hasAnyRole(['admin', 'it_staff'])) {
            abort(403, 'Unauthorized action.');
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task Schedule deleted successfully ❌');
    }
}
