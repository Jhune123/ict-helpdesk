@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-white shadow rounded">

    <h1 class="text-2xl font-bold mb-6 text-blue-700">
        ✏️ Edit Condemned Equipment
    </h1>

    <form action="{{ route('condemned-equipment.update', $condemnedEquipment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Property No --}}
            <div>
                <label class="block font-semibold mb-1">Property No</label>
                <input type="text" name="property_no" value="{{ old('property_no', $condemnedEquipment->property_no) }}" class="border rounded w-full p-2" required>
            </div>

            {{-- Item Name --}}
            <div>
                <label class="block font-semibold mb-1">Item Name</label>
                <input type="text" name="item_name" value="{{ old('item_name', $condemnedEquipment->item_name) }}" class="border rounded w-full p-2" required>
            </div>

            {{-- Title --}}
            <div>
                <label class="block font-semibold mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title', $condemnedEquipment->title) }}" class="border rounded w-full p-2" required>
            </div>

            {{-- Description --}}
            <div class="md:col-span-2">
                <label class="block font-semibold mb-1">Description / Remarks:</label>
                <textarea name="description" rows="3" class="border rounded w-full p-2">{{ old('description', $condemnedEquipment->description) }}</textarea>
            </div>

            {{-- ATTACHMENT SECTION --}}
            <div class="md:col-span-2 bg-blue-50 p-4 rounded border border-blue-200">
                <label class="block font-semibold mb-2">Attachment / Proof</label>
                
                @if($condemnedEquipment->attachment_path)
                    <div class="mb-3 flex items-center gap-2">
                        <span class="text-sm text-green-700 font-bold">✅ Current File:</span>
                        <a href="{{ asset('storage/' . $condemnedEquipment->attachment_path) }}" target="_blank" class="text-blue-600 underline text-sm hover:text-blue-800">
                            View Current Attachment
                        </a>
                    </div>
                @else
                    <p class="text-xs text-gray-500 mb-1">No file currently uploaded.</p>
                @endif

                <input type="file" name="attachment" class="w-full p-1 border bg-white rounded" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                <p class="text-xs text-gray-500 mt-1">Allowed formats: JPG, PNG, PDF, DOC (Max 5MB)</p>
            </div>

            {{-- Equipment Type --}}
            <div>
                <label class="block font-semibold mb-1">Equipment Type</label>
                <input type="text" name="equipment_type" value="{{ old('equipment_type', $condemnedEquipment->equipment_type) }}" class="border rounded w-full p-2" required>
            </div>

            {{-- Brand / Model --}}
            <div>
                <label class="block font-semibold mb-1">Brand / Model</label>
                <input type="text" name="brand_model" value="{{ old('brand_model', $condemnedEquipment->brand_model) }}" class="border rounded w-full p-2">
            </div>

            {{-- Serial No --}}
            <div>
                <label class="block font-semibold mb-1">Serial No</label>
                <input type="text" name="serial_no" value="{{ old('serial_no', $condemnedEquipment->serial_no) }}" class="border rounded w-full p-2">
            </div>

            {{-- Category --}}
            <div>
                <label class="block font-semibold mb-1">Category</label>
                <select name="category" class="border rounded w-full p-2">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}" {{ old('category', $condemnedEquipment->category) == $cat->name ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Department --}}
            <div>
                <label class="block font-semibold mb-1">Department</label>
                <select name="department" class="border rounded w-full p-2">
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->name }}" {{ old('department', $condemnedEquipment->department) == $dept->name ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Dynamic IT Personnel Dropdown --}}
            <div>
                <label class="block font-semibold mb-1">IT Personnel</label>
                <select name="it_personnel" class="border rounded w-full p-2">
                    <option value="">Select Personnel</option>
                    @foreach($it_personnel as $person)
                        <option value="{{ $person->name }}" {{ old('it_personnel', $condemnedEquipment->it_personnel) == $person->name ? 'selected' : '' }}>
                            {{ $person->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Client --}}
            <div>
                <label class="block font-semibold mb-1">Client</label>
                <input type="text" name="client_name" value="{{ old('client_name', $condemnedEquipment->client_name) }}" class="border rounded w-full p-2">
            </div>

            {{-- Priority --}}
            <div>
                <label class="block font-semibold mb-1">Priority</label>
                <select name="priority" class="border rounded w-full p-2">
                    @foreach(['Low', 'Medium', 'High', 'Critical'] as $prio)
                        <option value="{{ $prio }}" {{ $condemnedEquipment->priority == $prio ? 'selected' : '' }}>{{ $prio }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Contact --}}
            <div>
                <label class="block font-semibold mb-1">Contact</label>
                <input type="text" name="contact" value="{{ old('contact', $condemnedEquipment->contact) }}" class="border rounded w-full p-2">
            </div>

            {{-- Status --}}
            <div>
                <label class="block font-semibold mb-1">Status</label>
                <select name="status" class="border rounded w-full p-2">
                    @foreach(['Open', 'In Progress', 'Finished', 'Closed', 'Condemned'] as $stat)
                        <option value="{{ $stat }}" {{ $condemnedEquipment->status == $stat ? 'selected' : '' }}>{{ $stat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- DATES FIXED --}}
            <div>
                <label class="block font-semibold mb-1">Date Submitted</label>
                <input type="date" name="date_submitted" 
                       value="{{ old('date_submitted', $condemnedEquipment->date_submitted ? \Carbon\Carbon::parse($condemnedEquipment->date_submitted)->format('Y-m-d') : '') }}" 
                       class="border rounded w-full p-2">
            </div>

            <div>
                <label class="block font-semibold mb-1">Date Condemned</label>
                <input type="date" name="date_condemned" 
                       value="{{ old('date_condemned', $condemnedEquipment->date_condemned ? \Carbon\Carbon::parse($condemnedEquipment->date_condemned)->format('Y-m-d') : '') }}" 
                       class="border rounded w-full p-2">
            </div>

        </div>

        {{-- ACTION BUTTONS --}}
        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2 rounded shadow">
                Update Record
            </button>
            <a href="{{ route('condemned-equipment.show', $condemnedEquipment->id) }}" class="bg-gray-300 hover:bg-gray-400 px-6 py-2 rounded shadow">
                Cancel
            </a>
        </div>

    </form>
</div>
@endsection