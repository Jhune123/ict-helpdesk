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

    {{-- FILTER & EXPORT --}}
    <div class="flex flex-wrap items-center justify-between mb-4 gap-2">
        <form method="GET" class="flex flex-wrap items-end gap-4 w-full md:flex-nowrap">

            {{-- SEARCH BOX --}}
            <div class="w-[500px]">
                <label class="text-sm font-semibold text-gray-600">Search Tickets</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by Ticket #, Title, Description, Department, Client..."
                       class="border rounded p-2 w-full"
                       onkeyup="this.form.submit()">
            </div>

            {{-- MONTH & YEAR --}}
            <div class="flex items-end gap-2">
                <div>
                    <label class="text-sm font-semibold text-gray-600">Month</label>
                    <select name="month" class="border rounded p-2 w-28">
                        <option value="">All</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600">Year</label>
                    <select name="year" class="border rounded p-2 w-28">
                        <option value="">All</option>
                        @for ($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow font-semibold">
                    Filter
                </button>
                <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-gray-400 text-white rounded-lg shadow hover:bg-gray-500">
                    Reset
                </a>
            </div>

            {{-- EXPORT BUTTONS --}}
            <div class="flex gap-2 flex-wrap ml-auto">
                @php
                    $exportBtns = ['csv' => 'CSV', 'xlsx' => 'Excel', 'pdf' => 'PDF'];
                @endphp
                @foreach($exportBtns as $type => $label)
                    <a href="{{ route('tickets.export', ['type'=>$type] + request()->all()) }}"
                       class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 transition shadow text-sm font-medium"
                       target="_blank">{{ $label }}</a>
                @endforeach

                <button onclick="window.print()"
                        class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 transition shadow text-sm font-medium">
                    Print
                </button>
            </div>
        </form>
    </div>

    {{-- TICKETS TABLE --}}
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-2">Ticket #</th>
                    <th class="px-3 py-2">Title</th>
                    <th class="px-3 py-2">Description</th>
                    <th class="px-3 py-2">Category</th>
                    <th class="px-3 py-2">Department</th>
                    <th class="px-3 py-2">IT Personnel</th>
                    <th class="px-3 py-2">Client Name</th>
                    <th class="px-3 py-2">Priority</th>
                    <th class="px-3 py-2">Contact No.</th>
                    <th class="px-3 py-2">Remarks</th>
                    <th class="px-3 py-2">Status</th>
                    <th class="px-3 py-2">Date Submitted</th>
                    <th class="px-3 py-2">Date Finished</th>
                    <th class="px-3 py-2 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 font-semibold">{{ $ticket->ticket_number }}</td>
                    <td class="px-3 py-2">{{ $ticket->title }}</td>
                    <td class="px-3 py-2">{{ Str::limit($ticket->description, 40) }}</td>
                    <td class="px-3 py-2">{{ $ticket->category->name ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $ticket->department ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $ticket->assignee?->name ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $ticket->client_name }}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-1 rounded text-white text-xs
                            {{ $ticket->priority === 'High' ? 'bg-red-500' : ($ticket->priority === 'Medium' ? 'bg-yellow-500' : 'bg-green-500') }}">
                            {{ $ticket->priority ?? 'Normal' }}
                        </span>
                    </td>
                    <td class="px-3 py-2">{{ $ticket->contact_number ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $ticket->remarks ?? '-' }}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-1 rounded-full text-white text-xs
                            {{ $ticket->status === 'Closed' ? 'bg-green-600' : 'bg-gray-500' }}">
                            {{ $ticket->status }}
                        </span>
                    </td>
                    <td class="px-3 py-2">{{ $ticket->date_submitted->format('M d, Y h:i A') }}</td>
                    <td class="px-3 py-2">{{ $ticket->date_finished?->format('M d, Y h:i A') ?? '-' }}</td>

                    {{-- ACTIONS --}}
                    <td class="px-3 py-2 text-center space-x-1">
                        <a href="{{ route('tickets.show', $ticket) }}" class="bg-blue-500 text-white px-2 py-1 rounded text-xs">View</a>
                        <a href="{{ route('tickets.jobOrderPdf', $ticket) }}?{{ http_build_query(request()->all()) }}" 
                           class="bg-indigo-500 text-white px-2 py-1 rounded text-xs" target="_blank">
                           Job Order
                        </a>
                        @if(auth()->user()->hasAnyRole(['admin','it_staff']))
                            <a href="{{ route('tickets.edit', $ticket) }}" class="bg-yellow-500 text-white px-2 py-1 rounded text-xs">Edit</a>
                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Delete ticket?')" class="bg-red-600 text-white px-2 py-1 rounded text-xs">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="text-center py-4 text-gray-500">No tickets found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div class="p-4">
            {{ $tickets->links() }}
        </div>
    </div>

</div>
@endsection
