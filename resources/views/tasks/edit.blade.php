@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow rounded p-6">
    <h2 class="text-xl font-bold mb-4">Edit Task</h2>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.update', $task) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block font-medium">Date</label>
            <input type="date" name="date" value="{{ old('date', $task->date) }}" 
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200" required>
        </div>

        <div>
            <label class="block font-medium">Description</label>
            <input type="text" name="description" value="{{ old('description', $task->description) }}" 
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200" required>
        </div>

        <div>
            <label class="block font-medium">Requested By</label>
            <input type="text" name="requested_by" value="{{ old('requested_by', $task->requested_by) }}" 
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200" required>
        </div>

        <div>
            <label class="block font-medium">Location</label>
            <input type="text" name="location" value="{{ old('location', $task->location) }}" 
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200" required>
        </div>

        <!-- Start Time -->
        <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
            <input type="time" name="start_time" id="start_time"
                value="{{ old('start_time', $task->start_time) }}"
                class="mt-1 block w-full border-gray-300 rounded-md" required>
        </div>

        <!-- End Time -->
        <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
            <input type="time" name="end_time" id="end_time"
                value="{{ old('end_time', $task->end_time) }}"
                class="mt-1 block w-full border-gray-300 rounded-md" required>
        </div>

        <!-- IT Personnel -->
       <div>
    <label class="block font-medium">IT Personnel</label>
    <select name="assigned_to" class="w-full px-3 py-2 border rounded">
        <option value="">-- Select IT Personnel --</option>
        <option value="Bryan" {{ old('assigned_to', $task->assigned_to ?? '') == 'Bryan' ? 'selected' : '' }}>Bryan</option>
        <option value="Jhune" {{ old('assigned_to', $task->assigned_to ?? '') == 'Jhune' ? 'selected' : '' }}>Jhune</option>
        <option value="Reymar" {{ old('assigned_to', $task->assigned_to ?? '') == 'Reymar' ? 'selected' : '' }}>Reymar</option>
        <option value="Walid" {{ old('assigned_to', $task->assigned_to ?? '') == 'Walid' ? 'selected' : '' }}>Walid</option>
    </select>
</div>


        <div>
            <label class="block font-medium">Remarks</label>
            <input type="text" name="remarks" value="{{ old('remarks', $task->remarks) }}" 
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200">
        </div>

       <div class="flex justify-end space-x-2">
    <a href="{{ route('tasks.index') }}" 
       class="px-4 py-2 border border-gray-400 text-gray-700 rounded hover:bg-gray-100">
        Cancel
    </a>
    <button type="submit" 
            class="px-4 py-2 bg-yellow-500 text-black font-semibold rounded hover:bg-yellow-600 shadow">
        Update Task
    </button>
</div>
    </form>
</div>
@endsection
