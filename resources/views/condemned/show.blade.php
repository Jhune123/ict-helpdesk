@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h1 class="text-2xl font-bold text-red-700">
            📋 Condemned Equipment Details
        </h1>
        <div class="flex gap-2">
            <a href="{{ route('condemned-equipment.print', $condemnedEquipment->id) }}" 
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow text-sm flex items-center gap-1 transition duration-200">
                🖨️ Print Certification
            </a>
            <a href="{{ route('condemned-equipment.edit', $condemnedEquipment->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm flex items-center gap-1 transition duration-200">
                ✏️ Edit
            </a>
            <a href="{{ route('condemned-equipment.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow text-sm flex items-center gap-1 transition duration-200">
                ⬅ Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-800">

        {{-- Row 1 --}}
        <div>
            <span class="block text-sm font-bold text-gray-500 uppercase">Property No</span>
            <p class="text-lg font-semibold">{{ $condemnedEquipment->property_no }}</p>
        </div>
        <div>
            <span class="block text-sm font-bold text-gray-500 uppercase">Item Name</span>
            <p class="text-lg font-semibold">{{ $condemnedEquipment->item_name }}</p>
        </div>

        {{-- Row 2 --}}
        <div>
            <span class="block text-sm font-bold text-gray-500 uppercase">Ticket Number</span>
            <p class="text-lg">{{ $condemnedEquipment->ticket_number }}</p>
        </div>
        <div>
            <span class="block text-sm font-bold text-gray-500 uppercase">Title / Issue</span>
            <p class="text-lg">{{ $condemnedEquipment->title }}</p>
        </div>

        {{-- Description --}}
        <div class="md:col-span-2 bg-gray-50 p-4 rounded border">
            <span class="block text-sm font-bold text-gray-500 uppercase mb-2">Description / Remarks:</span>
            <p class="whitespace-pre-wrap">{{ $condemnedEquipment->description ?: 'No description provided.' }}</p>
        </div>

        {{-- ✅ ATTACHMENT SECTION --}}
        <div class="md:col-span-2 mt-2">
            <span class="block text-sm font-bold text-gray-500 uppercase mb-2">Attachment / Proof</span>
            
            @if($condemnedEquipment->attachment_path)
                <div class="flex items-center gap-4 bg-green-50 p-4 rounded border border-green-200">
                    <span class="text-2xl">📎</span>
                    <div>
                        <p class="text-sm text-green-800 font-semibold mb-1">Proof Document Available</p>
                        <a href="{{ asset('storage/' . $condemnedEquipment->attachment_path) }}" 
                           target="_blank"
                           class="inline-block bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2 rounded shadow transition">
                            ⬇️ Download / View Attachment
                        </a>
                    </div>
                </div>
            @else
                <div class="p-3 bg-gray-100 rounded text-gray-500 text-sm italic border">
                    No attachment uploaded for this equipment.
                </div>
            @endif
        </div>

        <hr class="md:col-span-2 my-2">

        {{-- Details --}}
        <div>
            <span class="block text-sm font-bold text-gray-500 uppercase">Equipment Type</span>
            <p>{{ $condemnedEquipment->equipment_type }}</p>
        </div>
        <div>
            <span class="block text-sm font-bold text-gray-500 uppercase">Brand / Model</span>
            <p>{{ $condemnedEquipment->brand_model ?? 'N/A' }}</p>
        </div>
        <div>
            <span class="block text-sm font-bold text-gray-500 uppercase">Serial No</span>
            <p>{{ $condemnedEquipment->serial_no ?? 'N/A' }}</p>
        </div>
        <div>
            <span class="block text-sm font-bold text-gray-500 uppercase">Department</span>
            <p>{{ $condemnedEquipment->department ?? 'N/A' }}</p>
        </div>
        <div>
            <span class="block text-sm font-bold text-gray-500 uppercase">IT Personnel</span>
            <p>{{ $condemnedEquipment->it_personnel ?? 'N/A' }}</p>
        </div>
        <div>
            <span class="block text-sm font-bold text-gray-500 uppercase">Status</span>
            <span class="px-2 py-1 rounded text-white text-xs font-bold 
                {{ $condemnedEquipment->status === 'Condemned' ? 'bg-red-600' : 'bg-gray-500' }}">
                {{ $condemnedEquipment->status }}
            </span>
        </div>
    </div>
</div>
@endsection