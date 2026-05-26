@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-white shadow rounded">

    <h1 class="text-2xl font-bold mb-6 text-red-700">
        ➕ Add Condemned Equipment
    </h1>

    <form action="{{ route('condemned-equipment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Property No --}}
            <div>
                <label class="block font-semibold mb-1">Property No</label>
                <input type="text" name="property_no" value="{{ old('property_no') }}" class="border rounded w-full p-2 @error('property_no') border-red-500 @enderror" required>
            </div>

            {{-- Item Name --}}
            <div>
                <label class="block font-semibold mb-1">Item Name</label>
                <input type="text" name="item_name" value="{{ old('item_name') }}" class="border rounded w-full p-2 @error('item_name') border-red-500 @enderror" required>
            </div>

            {{-- Title --}}
            <div>
                <label class="block font-semibold mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" class="border rounded w-full p-2 @error('title') border-red-500 @enderror" required>
            </div>

            {{-- Description --}}
            <div class="md:col-span-2">
                <label class="block font-semibold mb-1">Description / Remarks:</label>
                <textarea name="description" rows="3" class="border rounded w-full p-2">{{ old('description') }}</textarea>
            </div>

            {{-- ATTACHMENT / PROOF SECTION --}}
            <div class="md:col-span-2 bg-gray-50 p-3 rounded border border-gray-200">
                <label class="block font-semibold mb-1">Attachment / Proof (Optional)</label>
                <input type="file" name="attachment" class="w-full p-1" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                <p class="text-xs text-gray-500 mt-1">Allowed formats: JPG, PNG, PDF, DOC (Max 5MB)</p>
            </div>

            {{-- Equipment Type --}}
            <div>
                <label class="block font-semibold mb-1">Equipment Type</label>
                <input type="text" name="equipment_type" value="{{ old('equipment_type') }}" class="border rounded w-full p-2" required>
            </div>

            {{-- Brand / Model --}}
            <div>
                <label class="block font-semibold mb-1">Brand / Model</label>
                <input type="text" name="brand_model" value="{{ old('brand_model') }}" class="border rounded w-full p-2">
            </div>

            {{-- Serial No --}}
            <div>
                <label class="block font-semibold mb-1">Serial No</label>
                <input type="text" name="serial_no" value="{{ old('serial_no') }}" class="border rounded w-full p-2">
            </div>

            {{-- Category Dropdown --}}
            <div>
                <label class="block font-semibold mb-1">Category</label>
                <select name="category" class="border rounded w-full p-2">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}" {{ old('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Department Dropdown --}}
            <div>
                <label class="block font-semibold mb-1">Department</label>
                <select name="department" class="border rounded w-full p-2 select2">
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->name }}" {{ old('department') == $dept->name ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Dynamic IT Personnel Dropdown --}}
            <div>
                <label class="block font-semibold mb-1">IT Personnel</label>
                <select name="it_personnel" class="border rounded w-full p-2">
                    <option value="">Select Personnel</option>
                    @foreach($it_personnel as $person)
                        <option value="{{ $person->name }}" {{ old('it_personnel') == $person->name ? 'selected' : '' }}>{{ $person->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Client --}}
            <div>
                <label class="block font-semibold mb-1">Client</label>
                <input type="text" name="client_name" value="{{ old('client_name') }}" class="border rounded w-full p-2">
            </div>

            {{-- Priority --}}
            <div>
                <label class="block font-semibold mb-1">Priority</label>
                <select name="priority" class="border rounded w-full p-2">
                    @foreach(['Low', 'Medium', 'High', 'Critical'] as $prio)
                        <option value="{{ $prio }}" {{ old('priority') == $prio ? 'selected' : '' }}>{{ $prio }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Contact --}}
            <div>
                <label class="block font-semibold mb-1">Contact</label>
                <input type="text" name="contact" value="{{ old('contact') }}" class="border rounded w-full p-2">
            </div>

            {{-- Status --}}
            <div>
                <label class="block font-semibold mb-1">Status</label>
                <select name="status" class="border rounded w-full p-2">
                    @foreach(['Open', 'In Progress', 'Finished', 'Closed', 'Condemned'] as $stat)
                        <option value="{{ $stat }}" {{ (old('status') ?? 'Condemned') == $stat ? 'selected' : '' }}>{{ $stat }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Date Submitted --}}
            <div>
                <label class="block font-semibold mb-1">Date Submitted</label>
                <input type="date" name="date_submitted" value="{{ old('date_submitted') }}" class="border rounded w-full p-2">
            </div>

            {{-- Date Condemned --}}
            <div>
                <label class="block font-semibold mb-1">Date Condemned</label>
                <input type="date" name="date_condemned" value="{{ old('date_condemned', date('Y-m-d')) }}" class="border rounded w-full p-2">
            </div>

        </div>

        {{-- ACTION BUTTONS --}}
        <div class="mt-6 flex gap-2">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-2 rounded shadow">
                Save Condemned Equipment
            </button>
            <a href="{{ route('condemned-equipment.index') }}" class="bg-gray-300 hover:bg-gray-400 px-6 py-2 rounded shadow">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection