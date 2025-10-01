@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">✏️ Edit Ticket</h2>

    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Title</label>
            <input type="text" name="title" 
                   value="{{ old('title', $ticket->title) }}"
                   class="w-full border rounded-lg p-2" required>
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded-lg p-2">{{ old('description', $ticket->description) }}</textarea>
            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Category</label>
            <select name="category_id" class="w-full border rounded-lg p-2">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" 
                        {{ old('category_id', $ticket->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="category_manual" 
                   placeholder="Or type new category"
                   value="{{ old('category_manual') }}"
                   class="w-full border rounded-lg p-2 mt-2">
        </div>

        <!-- Department -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Department</label>
            <select name="department" class="w-full border rounded-lg p-2">
                <option value="">-- Select Department --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->name }}" 
                        {{ old('department', $ticket->department) == $dept->name ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="department_manual" 
                   placeholder="Or type new department"
                   value="{{ old('department_manual') }}"
                   class="w-full border rounded-lg p-2 mt-2">
        </div>

        <!-- Assigned To -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Assign To (IT Personnel)</label>
            <select name="assigned_to" class="w-full border rounded-lg p-2">
                <option value="">-- Select IT Personnel --</option>
                @foreach($it_personnel as $user)
                    <option value="{{ $user->id }}" 
                        {{ old('assigned_to', $ticket->assigned_to) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Priority -->
        <div class="mb-4">
            <label class="block font-semibold text-gray-700">Priority</label>
            <select name="priority" class="w-full border-gray-300 rounded-lg shadow-sm">
                <option value="Low" {{ $ticket->priority == 'Low' ? 'selected' : '' }}>Low</option>
                <option value="Normal" {{ $ticket->priority == 'Normal' ? 'selected' : '' }}>Normal</option>
                <option value="High" {{ $ticket->priority == 'High' ? 'selected' : '' }}>High</option>
            </select>
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Status</label>
            <select name="status" class="w-full border rounded-lg p-2">
                <option value="Open" {{ old('status', $ticket->status) == 'Open' ? 'selected' : '' }}>Open</option>
                <option value="In Progress" {{ old('status', $ticket->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Closed" {{ old('status', $ticket->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>

        <!-- Date Created -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Date Created</label>
            <input type="text" class="w-full border rounded-lg p-2 bg-gray-100" 
                   value="{{ $ticket->created_at->format('Y-m-d H:i') }}" readonly>
        </div>

        <!-- Date Finished -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Date Finished</label>
            <input type="text" class="w-full border rounded-lg p-2 bg-gray-100"
                   value="{{ $ticket->date_finished ? $ticket->date_finished->format('Y-m-d H:i') : 'Not yet finished' }}" readonly>
        </div>

        <!-- Remarks -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Remarks</label>
            <textarea name="remarks" class="w-full border rounded-lg p-2">{{ old('remarks', $ticket->remarks) }}</textarea>
        </div>

        <!-- Client Info -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Client Name</label>
            <input type="text" name="client_name" 
                   value="{{ old('client_name', $ticket->client_name) }}"
                   class="w-full border rounded-lg p-2">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Contact Number</label>
            <input type="text" name="contact_number" 
                   value="{{ old('contact_number', $ticket->contact_number) }}"
                   class="w-full border rounded-lg p-2">
        </div>

        <!-- Submit -->
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Update Ticket
        </button>
    </form>
</div>
@endsection
