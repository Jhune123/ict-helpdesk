@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6 my-8 border border-gray-200">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800">✏️ Edit Ticket #{{ $ticket->id }}</h2>
        <a href="{{ route('tickets.index') }}" class="text-gray-500 hover:text-gray-700">Cancel</a>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
            <p class="font-bold">Please correct the following errors:</p>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Basic Information --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-1">Issue Title</label>
            <input type="text" name="title"
                   value="{{ old('title', $ticket->title) }}"
                   class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-1">Detailed Description</label>
            <textarea name="description" rows="4"
                      class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none"
                      required>{{ old('description', $ticket->description) }}</textarea>
        </div>

        {{-- Equipment Information Block --}}
        <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
            <h3 class="font-bold text-blue-800 mb-3 flex items-center">
                <span class="mr-2">🖥</span> Equipment Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Equipment Type</label>
                    <input type="text" name="equipment_type"
                           value="{{ old('equipment_type', $ticket->equipment_type) }}"
                           class="w-full border border-gray-300 rounded p-2 bg-white" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Brand & Model</label>
                    <input type="text" name="brand_model"
                           value="{{ old('brand_model', $ticket->brand_model) }}"
                           class="w-full border border-gray-300 rounded p-2 bg-white" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Serial No.</label>
                    <input type="text" name="serial_no"
                           value="{{ old('serial_no', $ticket->serial_no) }}"
                           class="w-full border border-gray-300 rounded p-2 bg-white" required>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            {{-- Category & Manual Entry --}}
            <div>
                <label class="block text-gray-700 font-bold mb-1">Category</label>
                <select name="category_id" class="w-full border border-gray-300 rounded-lg p-2 bg-white">
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
                       class="w-full border border-gray-300 rounded-lg p-2 mt-2 italic text-sm">
            </div>

            {{-- Department --}}
            <div>
                <label class="block text-gray-700 font-bold mb-1">Department</label>
                <select name="department" class="w-full border border-gray-300 rounded-lg p-2 bg-white">
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->name }}"
                            {{ old('department', $ticket->department) == $dept->name ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            {{-- Assign To --}}
            <div>
                <label class="block text-gray-700 font-bold mb-1">Assign To</label>
                <select name="assigned_to" class="w-full border border-gray-300 rounded-lg p-2 bg-white">
                    <option value="">-- Select IT --</option>
                    @foreach($it_personnel as $person)
                        <option value="{{ $person->id }}"
                            {{ old('assigned_to', $ticket->assigned_to) == $person->id ? 'selected' : '' }}>
                            {{ $person->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Priority --}}
            <div>
                <label class="block text-gray-700 font-bold mb-1">Priority</label>
                <select name="priority" class="w-full border border-gray-300 rounded-lg p-2 bg-white">
                    @foreach(['Low','Normal','High','Urgent'] as $priority)
                        <option value="{{ $priority }}"
                            {{ old('priority', $ticket->priority) == $priority ? 'selected' : '' }}>
                            {{ $priority }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-gray-700 font-bold mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg p-2 bg-white" required>
                    @foreach(['Open','In Progress','Closed','Condemned'] as $status)
                        <option value="{{ $status }}"
                            {{ old('status', $ticket->status) == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <hr class="my-6">

        {{-- Client Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-1">Client Name</label>
                <input type="text" name="client_name"
                       value="{{ old('client_name', $ticket->client_name) }}"
                       class="w-full border border-gray-300 rounded-lg p-2" required>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-1">Contact Details</label>
                <input type="text" name="contact_number"
                       value="{{ old('contact_number', $ticket->contact_number) }}"
                       class="w-full border border-gray-300 rounded-lg p-2" placeholder="Email or Phone">
            </div>
        </div>

        {{-- Remarks --}}
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-1">Final Remarks / Resolution</label>
            <textarea name="remarks" rows="3"
                      class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-500 outline-none"
                      placeholder="Enter technical notes or resolution details...">{{ old('remarks', $ticket->remarks) }}</textarea>
        </div>

        <div class="flex gap-4">
            <button type="submit"
                    class="flex-1 bg-blue-600 text-white font-bold px-6 py-3 rounded-lg hover:bg-blue-700 shadow-lg transition">
                💾 Save Changes
            </button>
            <a href="{{ route('tickets.index') }}"
               class="flex-1 bg-gray-100 text-gray-700 font-bold px-6 py-3 rounded-lg hover:bg-gray-200 text-center transition border border-gray-300">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection