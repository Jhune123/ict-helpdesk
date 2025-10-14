@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-700">🗓 Task Schedule</h2>

        @role('admin|it_staff')
        <a href="{{ route('tasks.create') }}" 
           class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow hover:bg-blue-700 transition">
            + Add Task
        </a>
        @endrole
    </div>

    <!-- 🔍 Search Bar -->
    <form method="GET" action="{{ route('tasks.index') }}" class="mb-4">
        <div class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by description, requested by, or location..."
                   class="flex-1 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
            <button type="submit" 
                    class="px-4 py-2 bg-yellow-500 text-black text-sm font-medium rounded-lg shadow hover:bg-yellow-600">
                🔍 Search
            </button>
        </div>
    </form>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full border border-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="p-3 border">Date</th>
                    <th class="p-3 border">Description</th>
                    <th class="p-3 border">Requested By</th>
                    <th class="p-3 border">Location</th>
                    <th class="p-3 border">Time Range</th>
                    <th class="p-3 border">IT Personnel</th>
                    <th class="p-3 border">Remarks</th>
                    <th class="p-3 border text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border">{{ \Carbon\Carbon::parse($task->date)->format('M d, Y') }}</td>
                    <td class="p-3 border">{{ $task->description }}</td>
                    <td class="p-3 border">{{ $task->requested_by }}</td>
                    <td class="p-3 border">{{ $task->location }}</td>
                    <td class="p-3 border">
                        {{ \Carbon\Carbon::parse($task->start_time)->format('h:i A') }} -
                        {{ \Carbon\Carbon::parse($task->end_time)->format('h:i A') }}
                    </td>
                    <td class="p-3 border">{{ $task->assigned_to ?? 'N/A' }}</td>
                    <td class="p-3 border">{{ $task->remarks }}</td>
                    <td class="p-3 border text-center space-x-2">
                        <a href="{{ route('tasks.show', $task) }}" 
                           class="inline-block px-3 py-1 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700">
                            👁 View
                        </a>

                        @role('admin|it_staff')
                        <a href="{{ route('tasks.edit', $task) }}" 
                           class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700">
                            ✏️ Edit
                        </a>

                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf 
                            @method('DELETE')
                            <button onclick="return confirm('Delete this task?')" 
                                    class="inline-block px-3 py-1 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700">
                                🗑 Delete
                            </button>
                        </form>
                        @endrole
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-6 text-center text-gray-500">No tasks found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tasks->appends(['search' => request('search')])->links() }}
    </div>
</div>
@endsection
