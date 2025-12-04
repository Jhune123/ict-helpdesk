@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- PAGE HEADER --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-700 flex items-center gap-2">
            🎫 Ticket Management
        </h1>

        @role('admin|it_staff')
        <a href="{{ route('tickets.create') }}" 
           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-semibold shadow flex items-center gap-2 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Ticket
        </a>
        @endrole
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-4 p-4 text-green-700 bg-green-100 rounded-lg shadow font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTERS + EXPORT BUTTONS --}}
    <div class="bg-white p-4 rounded-lg shadow mb-4 flex flex-wrap items-center gap-4 justify-between">

        {{-- Month & Year Filters --}}
        <form method="GET" class="flex items-end gap-3">
            <div>
                <label class="text-sm font-semibold text-gray-600">Month</label>
                <select name="month" class="border rounded p-2 w-40">
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
                <select name="year" class="border rounded p-2 w-32">
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
        </form>

        {{-- EXPORT BUTTONS --}}
        <div class="flex gap-2">
            @php
                $exportBtns = [
                    'csv'   => 'CSV',
                    'xlsx'  => 'Excel',
                    'pdf'   => 'PDF',
                ];
            @endphp

            @foreach($exportBtns as $type => $label)
                <a href="{{ route('tickets.export', ['type' => $type]) }}"
                   class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 transition shadow text-sm font-medium"
                   target="_blank">
                   {{ $label }}
                </a>
            @endforeach

            <button onclick="window.print()" 
                class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 transition shadow text-sm font-medium">
                Print
            </button>
        </div>
    </div>

    {{-- TICKET TABLE --}}
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table id="ticketsTable" class="min-w-full border border-gray-200 text-sm">
            <thead class="bg-blue-700 text-white">
                <tr>
                    <th class="px-3 py-3 text-left">Ticket #</th>
                    <th class="px-3 py-3 text-left">Title</th>
                    <th class="px-3 py-3 text-left">Description</th>
                    <th class="px-3 py-3 text-left">Category</th>
                    <th class="px-3 py-3 text-left">Department</th>
                    <th class="px-3 py-3 text-left">IT Personnel</th>
                    <th class="px-3 py-3 text-left">Client Name</th>
                    <th class="px-3 py-3 text-left">Priority</th>
                    <th class="px-3 py-3 text-left">Contact No.</th>
                    <th class="px-3 py-3 text-left">Remarks</th>
                    <th class="px-3 py-3 text-left">Status</th>
                    <th class="px-3 py-3 text-left">Date Submitted</th>
                    <th class="px-3 py-3 text-left">Date Finished</th>
                    <th class="px-3 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="text-gray-700 divide-y divide-gray-200">
                @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-3 py-2 font-semibold text-gray-800">#{{ $ticket->ticket_number }}</td>
                        <td class="px-3 py-2">{{ $ticket->title }}</td>
                        <td class="px-3 py-2">{{ Str::limit($ticket->description, 50) }}</td>
                        <td class="px-3 py-2">{{ $ticket->category->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2">{{ $ticket->department ?? 'N/A' }}</td>
                        <td class="px-3 py-2">{{ $ticket->assignee?->name ?? 'Unassigned' }}</td>
                        <td class="px-3 py-2">{{ $ticket->client_name }}</td>
                        <td class="px-3 py-2">{{ $ticket->priority ?? 'Normal' }}</td>
                        <td class="px-3 py-2">{{ $ticket->contact_number }}</td>
                        <td class="px-3 py-2">{{ $ticket->remarks ?? 'No remarks' }}</td>
                        <td class="px-3 py-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($ticket->status === 'Open') bg-green-100 text-green-700
                                @elseif($ticket->status === 'In Progress') bg-yellow-100 text-yellow-700
                                @elseif($ticket->status === 'Closed') bg-gray-200 text-gray-700
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ $ticket->status }}
                            </span>
                        </td>
                        <td class="px-3 py-2">{{ $ticket->date_submitted?->format('M d, Y h:i A') }}</td>
                        <td class="px-3 py-2">
                            @if($ticket->status === 'Closed')
                                {{ $ticket->date_finished?->format('M d, Y h:i A') }}
                            @else
                                <span class="text-gray-400 italic">Pending</span>
                            @endif
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-3 py-2 text-center space-x-1">
                            <a href="{{ route('tickets.show', $ticket->id) }}" 
                               class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 inline-flex items-center gap-1 text-xs">
                                👁 View
                            </a>

                            {{-- PRINT JOB ORDER --}}
                            <a href="{{ route('tickets.joborder.pdf', $ticket->id) }}"
                               target="_blank"
                               class="bg-purple-600 text-white px-2 py-1 rounded hover:bg-purple-700 inline-flex items-center gap-1 text-xs">
                                🧾 Job Order
                            </a>

                            @role('admin|it_staff')
                                <a href="{{ route('tickets.edit', $ticket->id) }}" 
                                   class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600 inline-flex items-center gap-1 text-xs">
                                    ✏ Edit
                                </a>

                                <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Delete this ticket?')"
                                        class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700 inline-flex items-center gap-1 text-xs">
                                        🗑 Delete
                                    </button>
                                </form>
                            @endrole
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="px-4 py-6 text-center text-gray-500">No tickets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
