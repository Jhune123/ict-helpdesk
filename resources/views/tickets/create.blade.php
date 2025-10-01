@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">🎫 Create New Ticket</h2>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf

        <!-- Title -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Title</label>
            <input type="text" name="title" value="{{ old('title') }}"
                class="w-full border rounded-lg p-2" required>
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded-lg p-2" required>{{ old('description') }}</textarea>
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Category</label>
            <select name="category_id" class="w-full border rounded-lg p-2">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <input type="text" name="category_manual" placeholder="Or type new category"
                class="w-full border rounded-lg p-2 mt-2">
        </div>

        <!-- Department -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Department</label>
            <select name="department" class="w-full border rounded-lg p-2">
                <option value="">-- Select Department --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                @endforeach
            </select>
            <input type="text" name="department_manual" placeholder="Or type new department"
                class="w-full border rounded-lg p-2 mt-2">
        </div>

        <!-- Assigned To -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Assign To (IT Personnel)</label>
            <select name="assigned_to" class="w-full border rounded-lg p-2">
                <option value="">-- Select IT Personnel --</option>
                @foreach($it_personnel->unique('id') as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Priority -->
        <div class="mb-4">
            <label class="block font-semibold text-gray-700">Priority</label>
            <select name="priority" class="w-full border-gray-300 rounded-lg shadow-sm">
                <option value="Low">Low</option>
                <option value="Normal" selected>Normal</option>
                <option value="High">High</option>
            </select>
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Status</label>
            <select name="status" class="w-full border rounded-lg p-2">
                <option value="Open" selected>Open</option>
                <option value="In Progress">In Progress</option>
                <option value="Closed">Closed</option>
            </select>
        </div>

        <!-- Date Created (readonly + hidden input for saving) -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Date Created</label>
            <input type="text" class="w-full border rounded-lg p-2 bg-gray-100" 
                   value="{{ now()->format('Y-m-d H:i') }}" readonly>
            <input type="hidden" name="created_at" value="{{ now() }}">
        </div>

        <!-- Remarks -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Remarks</label>
            <textarea name="remarks" class="w-full border rounded-lg p-2">{{ old('remarks') }}</textarea>
        </div>

        <!-- Client Info -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Client Name</label>
            <input type="text" name="client_name" value="{{ old('client_name') }}"
                class="w-full border rounded-lg p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Contact Number</label>
            <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                class="w-full border rounded-lg p-2">
        </div>

        <!-- Submit -->
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
            Create Ticket
        </button>
    </form>
</div>
@endsection
