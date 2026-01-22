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
        <form method="GET" class="flex flex-wrap items-center gap-2">
           {{-- Month Dropdown --}}
<label for="month" class="text-gray-700 font-semibold inline-block w-25">Month:</label>
<select name="month" class="border rounded p-1 w-36">
    <option value="">-- All --</option>
    @for($m = 1; $m <= 12; $m++)
        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
        </option>
    @endfor
</select>

<<<<<<< Updated upstream
    {{-- SEARCH & FILTERS --}}
    <div class="bg-white p-4 rounded-lg shadow mb-4 flex flex-wrap items-center justify-between gap-4">

        <form method="GET" class="flex flex-wrap items-end gap-4 w-full md:flex-nowrap">
            {{-- SEARCH BOX --}}
            <div class="w-[500px]">
                <label class="text-sm font-semibold text-gray-600">Search Tickets</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by Ticket #, Title, Description, Department, Client..."
                       class="border rounded p-2 w-full"
                       onkeyup="this.form.submit()">
            </div>

            {{-- MONTH, YEAR & FILTER BUTTON IN SAME ROW --}}
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
            </div>

            {{-- EXPORT BUTTONS --}}
            <div class="flex gap-2 flex-wrap ml-auto">
                @php
                    $exportBtns = ['csv' => 'CSV', 'xlsx' => 'Excel', 'pdf' => 'PDF'];
                @endphp
                @foreach($exportBtns as $type => $label)
                    <a href="{{ route('tickets.export', ['type' => $type]) }}"
                       class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 transition shadow text-sm font-medium"
                       target="_blank">{{ $label }}</a>
                @endforeach

                <button onclick="window.print()"
                        class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 transition shadow text-sm font-medium">
                    Print
                </button>
            </div>
        </form>
=======
{{-- Year Dropdown --}}
<label for="year" class="text-gray-700 font-semibold inline-block w-25">Year:</label>
<select name="year" class="border rounded p-1 w-36">
    <option value="">-- All --</option>
    @for($y = date('Y'); $y >= 2000; $y--)
        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
            {{ $y }}
        </option>
    @endfor
</select>


            <button type="submit" class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800">Filter</button>
            <a href="{{ route('tickets.index') }}" class="bg-gray-400 text-white px-3 py-1 rounded hover:bg-gray-500">Reset</a>
        </form>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('tickets.export', ['type'=>'csv'] + request()->all()) }}" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">CSV</a>
            <a href="{{ route('tickets.export', ['type'=>'xlsx'] + request()->all()) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Excel</a>
            <a href="{{ route('tickets.export', ['type'=>'pdf'] + request()->all()) }}" class="bg-indigo-500 text-white px-3 py-1 rounded hover:bg-indigo-600">PDF</a>
            <button onclick="window.print()" class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700">Print</button>
        </div>
>>>>>>> Stashed changes
    </div>

    {{-- TICKETS TABLE --}}
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
<<<<<<< Updated upstream
        <table class="min-w-full border border-gray-200 text-sm">
            <thead class="bg-blue-700 text-white">
=======
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
                        {{-- ACTIONS --}}
                        <td class="px-3 py-2 text-center space-x-1">
                            <a href="{{ route('tickets.show', $ticket->id) }}" 
                               class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 inline-flex items-center gap-1 text-xs">
                                👁 View
                            </a>
                            <a href="{{ route('tickets.joborder.pdf', $ticket->id) }}" target="_blank"
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
=======
                    {{-- Priority Badge --}}
                    <td class="px-3 py-2">
                        <span class="px-2 py-1 rounded text-white text-xs
                            {{ $ticket->priority === 'High' ? 'bg-red-500' : ($ticket->priority === 'Medium' ? 'bg-yellow-500' : 'bg-green-500') }}">
                            {{ $ticket->priority ?? 'Normal' }}
                        </span>
                    </td>

                    <td class="px-3 py-2">{{ $ticket->contact_number ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $ticket->remarks ?? '-' }}</td>

                    {{-- Status Badge --}}
                    <td class="px-3 py-2">
                        <span class="px-2 py-1 rounded-full text-white text-xs
                            {{ $ticket->status === 'Closed' ? 'bg-green-600' : 'bg-gray-500' }}">
                            {{ $ticket->status }}
                        </span>
                    </td>

                    {{-- Date Submitted & Finished --}}
                    <td class="px-3 py-2">{{ $ticket->date_submitted->format('M d, Y h:i A') }}</td>
                    <td class="px-3 py-2">
                        {{ $ticket->date_finished ? $ticket->date_finished->format('M d, Y h:i A') : '-' }}
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-3 py-2 text-center space-x-1">
                        <a href="{{ route('tickets.show', $ticket) }}" class="bg-blue-500 text-white px-2 py-1 rounded text-xs">View</a>
                        <a href="{{ route('tickets.joborder.pdf', $ticket) }}" class="bg-indigo-500 text-white px-2 py-1 rounded text-xs">Job Order</a>
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
>>>>>>> Stashed changes
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

    {{-- PAGINATION LINKS --}}
    <div class="mt-4">
        {{ $tickets->links() }}
    </div>

</div>
@endsection
