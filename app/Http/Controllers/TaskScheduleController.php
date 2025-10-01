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
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        // Transform HH:MM -> HH:MM:00
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
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        // Transform HH:MM -> HH:MM:00
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
        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task Schedule deleted successfully ❌');
    }
}
