@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow rounded p-6">
    <h2 class="text-xl font-bold mb-4">Task Details</h2>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-gray-600 font-medium">Date</p>
            <p class="text-lg">{{ $task->date }}</p>
        </div>

        <div>
            <p class="text-gray-600 font-medium">Time</p>
            <p class="text-lg">{{ $task->time }}</p>
        </div>

        <div class="col-span-2">
            <p class="text-gray-600 font-medium">Description</p>
            <p class="text-lg">{{ $task->description }}</p>
        </div>

        <div>
            <p class="text-gray-600 font-medium">Requested By</p>
            <p class="text-lg">{{ $task->requested_by }}</p>
        </div>

        <div>
            <p class="text-gray-600 font-medium">Location</p>
            <p class="text-lg">{{ $task->location }}</p>
        </div>

        <div class="col-span-2">
            <p class="text-gray-600 font-medium">Remarks</p>
            <p class="text-lg">{{ $task->remarks ?? '—' }}</p>
        </div>
    </div>

    <div class="flex justify-end mt-6 space-x-2">
        <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-300 rounded">Back</a>
        <a href="{{ route('tasks.edit', $task) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</a>
        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button onclick="return confirm('Are you sure you want to delete this task?')" 
                class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                Delete
            </button>
        </form>
    </div>
</div>
@endsection
