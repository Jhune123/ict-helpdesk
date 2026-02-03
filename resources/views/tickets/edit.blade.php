@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">✏️ Edit Ticket</h2>

    <form action="{{ route('tickets.update', $ticket) }}" method="POST" enctype="multipart/form-data">
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
            <textarea name="description" rows="4"
                      class="w-full border rounded-lg p-2"
                      required>{{ old('description', $ticket->description) }}</textarea>
            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- ✅ Equipment Information -->
        <div class="mb-4 bg-gray-50 p-4 rounded border">
            <h3 class="font-semibold mb-2">🖥 Equipment Information</h3>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block font-semibold">Equipment Type</label>
                    <input type="text" name="equipment_type" 
                           value="{{ old('equipment_type', $ticket->equipment_type) }}" 
                           class="w-full border rounded-lg p-2" required>
                    @error('equipment_type') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-semibold">Brand & Model No.</label>
                    <input type="text" name="brand_model" 
                           value="{{ old('brand_model', $ticket->brand_model) }}" 
                           class="w-full border rounded-lg p-2" required>
                    @error('brand_model') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-semibold">Serial No.</label>
                    <input type="text" name="serial_no" 
                           value="{{ old('serial_no', $ticket->serial_no) }}" 
                           class="w-full border rounded-lg p-2" required>
                    @error('serial_no') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Category</label>
            <select name="category_id" class="w-full border rounded-lg p-2">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $ticket->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" name="category_manual" placeholder="Or type new category"
                   value="{{ old('category_manual') }}" class="w-full border rounded-lg p-2 mt-2">
            @error('category_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            @error('category_manual') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Department -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Department</label>
            <select name="department" class="w-full border rounded-lg p-2 bg-white">
                <option value="">-- Select Department --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->name }}" {{ old('department', $ticket->department) == $dept->name ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
            @error('department') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Assign To (IT Personnel) -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Assign To (IT Personnel)</label>
            <select name="assigned_to" class="w-full border rounded-lg p-2">
                <option value="">-- Select IT Personnel --</option>
                @foreach($it_personnel as $person)
                    <option value="{{ $person->id }}" {{ old('assigned_to', $ticket->assigned_to) == $person->id ? 'selected' : '' }}>
                        {{ $person->name }}
                    </option>
                @endforeach
            </select>
            @error('assigned_to') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Priority -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Priority</label>
            <select name="priority" class="w-full border rounded-lg p-2">
                @php $priorities = ['Low','Normal','High','Urgent']; @endphp
                @foreach($priorities as $priority)
                    <option value="{{ $priority }}" {{ old('priority', $ticket->priority) == $priority ? 'selected' : '' }}>
                        {{ $priority }}
                    </option>
                @endforeach
            </select>
            @error('priority') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Status</label>
            <select name="status" class="w-full border rounded-lg p-2" required>
                @php $statuses = ['Open','In Progress','Closed']; @endphp
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ old('status', $ticket->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                @endforeach
            </select>
            @error('status') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Client Name -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Client Name</label>
            <input type="text" name="client_name"
                   value="{{ old('client_name', $ticket->client_name) }}"
                   class="w-full border rounded-lg p-2" required>
            @error('client_name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Contact Number -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Contact Number</label>
            <input type="text" name="contact_number"
                   value="{{ old('contact_number', $ticket->contact_number) }}"
                   class="w-full border rounded-lg p-2">
            @error('contact_number') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Remarks -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Remarks</label>
            <textarea name="remarks" rows="3"
                      class="w-full border rounded-lg p-2">{{ old('remarks', $ticket->remarks) }}</textarea>
            @error('remarks') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Update Ticket
        </button>
    </form>
</div>
@endsection
