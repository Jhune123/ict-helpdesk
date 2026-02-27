@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-6 gap-3">
        <h1 class="text-3xl font-bold text-red-700">
            🗑️ Condemned Equipment
        </h1>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('condemned-equipment.export.pdf') }}" class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded shadow text-sm">PDF</a>
            <a href="{{ route('condemned-equipment.export.excel') }}" class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded shadow text-sm">Excel</a>
            <a href="{{ route('condemned-equipment.export.csv') }}" class="bg-gray-200 hover:bg-gray-300 px-3 py-2 rounded shadow text-sm">CSV</a>

            {{-- ONLY ADMIN or IT STAFF can ADD --}}
            @hasanyrole('admin|it_staff')
            <a href="{{ route('condemned-equipment.create') }}"
               class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded shadow text-sm">
                 + Add Condemned Equipment
            </a>
            @endhasanyrole
        </div>
    </div>

    {{-- SEARCH --}}
    <form method="GET" class="mb-4">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search ticket #, property no, item, equipment..."
               class="border rounded p-2 w-full md:w-1/2">
    </form>

    {{-- TABLE --}}
    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-3 py-2">Ticket #</th>
                    <th class="px-3 py-2">Property No</th>
                    <th class="px-3 py-2">Item Name</th>
                    <th class="px-3 py-2">Title</th>
                    <th class="px-3 py-2">Description</th>
                    <th class="px-3 py-2">Equipment Type</th>
                    <th class="px-3 py-2">Brand / Model</th>
                    <th class="px-3 py-2">Serial No</th>
                    <th class="px-3 py-2">Category</th>
                    <th class="px-3 py-2">Department</th>
                    <th class="px-3 py-2">IT Personnel</th>
                    <th class="px-3 py-2">Client</th>
                    <th class="px-3 py-2">Priority</th>
                    <th class="px-3 py-2">Contact</th>
                    <th class="px-3 py-2">Status</th>
                    <th class="px-3 py-2">Submitted</th>
                    <th class="px-3 py-2">Condemned</th>
                    <th class="px-3 py-2 text-center">Attachment</th> {{-- ADDED --}}
                    <th class="px-3 py-2">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($equipments as $equipment)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 font-semibold">{{ $equipment->ticket_number }}</td>
                    <td class="px-3 py-2">{{ $equipment->property_no }}</td>
                    <td class="px-3 py-2">{{ $equipment->item_name }}</td>
                    <td class="px-3 py-2">{{ $equipment->title }}</td>
                    <td class="px-3 py-2 truncate max-w-xs" title="{{ $equipment->description }}">
                        {{ Str::limit($equipment->description, 30) }}
                    </td>
                    <td class="px-3 py-2">{{ $equipment->equipment_type }}</td>
                    <td class="px-3 py-2">{{ $equipment->brand_model }}</td>
                    <td class="px-3 py-2">{{ $equipment->serial_no }}</td>
                    <td class="px-3 py-2">{{ $equipment->category }}</td>
                    <td class="px-3 py-2">{{ $equipment->department }}</td>
                    <td class="px-3 py-2">{{ $equipment->it_personnel }}</td>
                    <td class="px-3 py-2">{{ $equipment->client_name }}</td>
                    <td class="px-3 py-2">
                        <span class="{{ $equipment->priority == 'Critical' ? 'text-red-600 font-bold' : '' }}">
                            {{ $equipment->priority }}
                        </span>
                    </td>
                    <td class="px-3 py-2">{{ $equipment->contact }}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-1 rounded text-xs 
                            {{ $equipment->status == 'Condemned' ? 'bg-red-100 text-red-800' : 
                              ($equipment->status == 'Finished' ? 'bg-green-100 text-green-800' : 'bg-gray-100') }}">
                            {{ $equipment->status }}
                        </span>
                    </td>
                    {{-- SAFE DATE FORMATTING --}}
                    <td class="px-3 py-2">{{ optional($equipment->date_submitted)->format('M d, Y') }}</td>
                    <td class="px-3 py-2">{{ optional($equipment->date_condemned)->format('M d, Y') }}</td>

                    {{-- ATTACHMENT COLUMN --}}
                    <td class="px-3 py-2 text-center">
                        @if($equipment->attachment_path)
                            <a href="{{ asset('storage/' . $equipment->attachment_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-bold text-lg" title="Download">
                                📎
                            </a>
                        @else
                            <span class="text-gray-300">-</span>
                        @endif
                    </td>

                    <td class="px-3 py-2">
                        <div class="flex gap-1">
                            {{-- VIEW: Visible to ALL --}}
                            <a href="{{ route('condemned-equipment.show', $equipment->id) }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs">
                                View
                            </a>

                            {{-- EDIT/DELETE: Admin or IT Staff Only --}}
                            @hasanyrole('admin|it_staff')
                            <a href="{{ route('condemned-equipment.edit', $equipment->id) }}"
                               class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                                Edit
                            </a>

                            <form action="{{ route('condemned-equipment.destroy', $equipment->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this condemned equipment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
                                    Delete
                                </button>
                            </form>
                            @endhasanyrole
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="19" class="text-center py-6 text-gray-500">
                        No condemned equipment found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div class="p-4">
            {{ $equipments->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection