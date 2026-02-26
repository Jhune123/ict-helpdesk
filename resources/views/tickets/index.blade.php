@php use Illuminate\Support\Str; @endphp
@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- PAGE HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-700 flex items-center gap-2">
            🎫 Ticket Management
        </h1>

        <a href="{{ route('tickets.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded shadow">
            + Create Ticket
        </a>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2">Ticket #</th>
                    <th class="px-3 py-2">Title</th>
                    <th class="px-3 py-2">Description</th>
                    {{-- Equipment Type HIDDEN --}}
                    <th class="px-3 py-2">Brand / Model</th>
                    <th class="px-3 py-2">Serial No.</th>
                    <th class="px-3 py-2">Category</th>
                    <th class="px-3 py-2">Department</th>
                    <th class="px-3 py-2">IT Personnel</th>
                    <th class="px-3 py-2">Client</th>
                    <th class="px-3 py-2">Priority</th>
                    {{-- Contact HIDDEN --}}
                    <th class="px-3 py-2">Status</th>
                    {{-- Submitted HIDDEN --}}
                    {{-- Finished HIDDEN --}}
                    <th class="px-3 py-2 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($tickets as $ticket)

                <tr class="
                    {{ $ticket->status === 'Closed' 
                        ? 'bg-green-500 text-white [&_a]:text-white [&_svg]:text-white hover:bg-green-600' 
                        : ($ticket->status === 'Condemned' 
                            ? 'bg-red-100 text-red-900 hover:bg-red-200' 
                            : 'hover:bg-gray-50') 
                    }}
                ">

                    <td class="px-3 py-2 font-semibold">
                        {{ $ticket->ticket_number }}
                    </td>

                    <td class="px-3 py-2">
                        {{ $ticket->title }}
                    </td>

                    <td class="px-3 py-2 max-w-xs truncate">
                        {{ Str::limit($ticket->description, 50) }}
                    </td>

                    {{-- Equipment Type HIDDEN --}}
                    
                    <td class="px-3 py-2">
                        {{ $ticket->brand_model ?? '-' }}
                    </td>

                    <td class="px-3 py-2">
                        {{ $ticket->serial_no ?? '-' }}
                    </td>

                    <td class="px-3 py-2">
                        {{ $ticket->category->name ?? '-' }}
                    </td>

                    <td class="px-3 py-2">
                        {{ $ticket->department ?? '-' }}
                    </td>

                    <td class="px-3 py-2">
                        {{ $ticket->assignee?->name ?? '-' }}
                    </td>

                    <td class="px-3 py-2">
                        {{ $ticket->client_name }}
                    </td>

                    <td class="px-3 py-2">
                        {{ $ticket->priority ?? 'Normal' }}
                    </td>

                    {{-- Contact HIDDEN --}}
                    {{-- Submitted HIDDEN --}}
                    {{-- Finished HIDDEN --}}

                    <td class="px-3 py-2">
                        {{ $ticket->status }}
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-3 py-2 text-center space-x-1">

                        <a href="{{ route('tickets.show', $ticket) }}"
                           class="bg-blue-500 text-white px-2 py-1 rounded text-xs">
                           View
                        </a>

                        <a href="{{ route('tickets.jobOrderPdf', $ticket) }}" target="_blank"
                           class="bg-indigo-500 text-white px-2 py-1 rounded text-xs">
                           Job Order
                        </a>

                        @if($ticket->status === 'Closed')
                            <a href="{{ route('feedbacks.create', $ticket) }}"
                               class="bg-green-600 text-white px-2 py-1 rounded text-xs">
                               Feedback
                            </a>
                        @endif

                        @role('admin|it_staff')
                            <a href="{{ route('tickets.edit', $ticket) }}"
                               class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">
                               Edit
                            </a>

                            <form action="{{ route('tickets.destroy', $ticket) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete ticket?')"
                                        class="bg-red-600 text-white px-2 py-1 rounded text-xs">
                                        Delete
                                </button>
                            </form>
                        @endrole

                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="12" class="text-center py-6 text-gray-500">
                        No tickets found.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>

        <div class="p-4">
            {{ $tickets->links() }}
        </div>

    </div>
</div>
@endsection