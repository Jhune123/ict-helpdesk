@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-xl font-bold mb-4">➕ Create New Ticket</h2>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf

        <!-- Title -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Title</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="w-full border rounded-lg p-2" required>
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Description -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Description</label>
            <textarea name="description" rows="4"
                      class="w-full border rounded-lg p-2"
                      required>{{ old('description') }}</textarea>
            @error('description') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- EQUIPMENT INFORMATION -->
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold mb-2">Equipment Information</label>

            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-3 py-2 text-left">Equipment Type</th>
                            <th class="border px-3 py-2 text-left">Brand & Model No.</th>
                            <th class="border px-3 py-2 text-left">Serial No.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-2 py-2">
                                <input type="text" name="equipment_type"
                                       value="{{ old('equipment_type') }}"
                                       class="w-full border rounded p-2" required>
                            </td>
                            <td class="border px-2 py-2">
                                <input type="text" name="brand_model"
                                       value="{{ old('brand_model') }}"
                                       class="w-full border rounded p-2" required>
                            </td>
                            <td class="border px-2 py-2">
                                <input type="text" name="serial_no"
                                       value="{{ old('serial_no') }}"
                                       class="w-full border rounded p-2" required>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Priority -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Priority</label>
            <select name="priority" class="w-full border rounded-lg p-2">
                @foreach(['Low','Normal','High','Urgent'] as $priority)
                    <option value="{{ $priority }}" {{ old('priority') == $priority ? 'selected' : '' }}>
                        {{ $priority }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- ✅ STATUS (NEW) -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Status</label>
            <select name="status" class="w-full border rounded-lg p-2" required>
                @foreach(['Open','In Progress','Closed','Condemned'] as $status)
                    <option value="{{ $status }}"
                        {{ old('status','Open') == $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Category -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Category</label>
            <select name="category_id" class="w-full border rounded-lg p-2">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="category_manual"
                   value="{{ old('category_manual') }}"
                   placeholder="Or enter new category"
                   class="w-full border rounded-lg p-2 mt-2">
        </div>

        <!-- Department -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Department</label>
            <select name="department" class="w-full border rounded-lg p-2">
                <option value="">-- Select Department --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->name }}" {{ old('department') == $dept->name ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Assign To -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Assign To (IT Personnel)</label>
            <select name="assigned_to" class="w-full border rounded-lg p-2">
                <option value="">-- Select IT Personnel --</option>
                @foreach($it_personnel as $person)
                    <option value="{{ $person->id }}" {{ old('assigned_to') == $person->id ? 'selected' : '' }}>
                        {{ $person->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Client -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Client Name</label>
            <input type="text" name="client_name"
                   value="{{ old('client_name') }}"
                   class="w-full border rounded-lg p-2" required>
        </div>

        <!-- Contact -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold">Contact Number</label>
            <input type="text" name="contact_number"
                   value="{{ old('contact_number') }}"
                   class="w-full border rounded-lg p-2">
        </div>

        <!-- Remarks -->
        <div class="mb-6">
            <label class="block text-gray-700 font-semibold">Remarks</label>
            <textarea name="remarks" rows="3"
                      class="w-full border rounded-lg p-2">{{ old('remarks') }}</textarea>
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Create Ticket
        </button>
    </form>
</div>
@endsection
