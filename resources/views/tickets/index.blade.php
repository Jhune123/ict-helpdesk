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

            {{-- SEARCH --}}
            <div class="w-full md:w-[500px]">
                <label class="text-sm font-semibold text-gray-600">Search Tickets</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search Ticket #, Serial No., Category, or Department..."
                           class="border rounded p-2 w-full pl-10 focus:ring-2 focus:ring-blue-500 outline-none">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
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

                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow font-semibold hover:bg-blue-700">
                    Filter
                </button>

                <a href="{{ route('tickets.index') }}"
                   class="px-4 py-2 bg-gray-400 text-white rounded-lg shadow hover:bg-gray-500">
                    Reset
                </a>
            </div>

            {{-- EXPORT --}}
            <div class="flex gap-2 flex-wrap ml-auto">
                <a href="{{ route('tickets.export.pdf', request()->all()) }}" target="_blank"
                   class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 shadow text-sm">PDF</a>

                <a href="{{ route('tickets.export.excel', request()->all()) }}" target="_blank"
                   class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 shadow text-sm">Excel</a>

                <a href="{{ route('tickets.export.csv', request()->all()) }}" target="_blank"
                   class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 shadow text-sm">CSV</a>

                <button type="button" onclick="window.print()"
                        class="bg-gray-200 px-3 py-2 rounded hover:bg-gray-300 shadow text-sm">
                    Print
                </button>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y text-sm">
            <thead class="bg-gray-100 font-bold text-gray-700">
                <tr>
                    <th class="px-3 py-3 text-left">Ticket #</th>
                    <th class="px-3 py-3 text-left">Title</th>
                    <th class="px-3 py-3 text-left">Description</th>
                    <th class="px-3 py-3 text-left">Brand / Model</th>
                    <th class="px-3 py-3 text-left">Serial No.</th>
                    <th class="px-3 py-3 text-left">Category</th>
                    <th class="px-3 py-3 text-left">Department</th>
                    <th class="px-3 py-3 text-left">IT Personnel</th>
                    <th class="px-3 py-3 text-left">Client</th>
                    <th class="px-3 py-3 text-left">Priority</th>
                    <th class="px-3 py-3 text-left">Status</th>
                    <th class="px-3 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($tickets as $ticket)
                <tr class="
                    {{ $ticket->status === 'Closed' 
                        ? 'bg-green-50 hover:bg-green-100' 
                        : ($ticket->status === 'Condemned' 
                            ? 'bg-red-50 text-red-900 hover:bg-red-100' 
                            : 'hover:bg-gray-50') 
                    }}
                ">
                    <td class="px-3 py-2 font-bold">{{ $ticket->ticket_number }}</td>
                    <td class="px-3 py-2 font-medium">{{ $ticket->title }}</td>
                    <td class="px-3 py-2" title="{{ $ticket->description }}">{{ Str::limit($ticket->description, 30) }}</td>
                    <td class="px-3 py-2">{{ $ticket->brand_model ?? '-' }}</td>
                    <td class="px-3 py-2 font-mono text-xs">{{ $ticket->serial_no ?? '-' }}</td>
                    <td class="px-3 py-2">
                        {{-- ✅ FIX: Added null check for category route --}}
                        @if($ticket->category)
                             <a href="{{ route('categories.show', $ticket->category->id) }}" class="text-blue-600 hover:underline">
                                {{ $ticket->category->name }}
                             </a>
                        @else
                            {{ ucfirst(str_replace('_', ' ', $ticket->form_type)) }}
                        @endif
                    </td>
                    <td class="px-3 py-2">{{ $ticket->department ?? '-' }}</td>
                    <td class="px-3 py-2">{{ $ticket->assignee?->name ?? '-' }}</td>
                    
                    <td class="px-3 py-2">
                        @if($ticket->form_type === 'generic')
                            <span class="text-xs text-gray-600">{{ $ticket->contact_info }}</span>
                        @else
                            <div class="font-semibold">{{ $ticket->client_name }}</div>
                            <div class="text-[10px] text-gray-500">{{ $ticket->contact_number }}</div>
                        @endif
                    </td>

                    <td class="px-3 py-2">
                        <span class="px-2 py-0.5 rounded text-[10px] uppercase font-bold 
                            {{ $ticket->priority === 'High' ? 'bg-red-200 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $ticket->priority ?? 'Normal' }}
                        </span>
                    </td>
                    <td class="px-3 py-2 font-semibold">
                        <span class="
                            {{ $ticket->status === 'Closed' ? 'text-green-700' : '' }}
                            {{ $ticket->status === 'Condemned' ? 'text-red-700' : '' }}
                        ">
                            {{ $ticket->status }}
                        </span>
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-3 py-2 text-center space-x-1 whitespace-nowrap">
                        <a href="{{ route('tickets.show', $ticket) }}"
                           class="bg-blue-500 text-white px-2 py-1 rounded text-[10px] font-bold uppercase shadow-sm">View</a>

                        <a href="{{ route('tickets.jobOrderPdf', $ticket) }}" target="_blank"
                           class="bg-indigo-500 text-white px-2 py-1 rounded text-[10px] font-bold uppercase shadow-sm">Job Order</a>

                        @if(in_array($ticket->status, ['Closed', 'Condemned']))
                            @if(!$ticket->feedback)
                                <a href="{{ route('feedbacks.create', $ticket->id) }}"
                                   class="bg-pink-600 text-white px-2 py-1 rounded text-[10px] font-bold uppercase shadow-sm">Feedback</a>
                            @else
                                <span class="text-green-600 font-bold text-[10px] uppercase">Rated ✅</span>
                            @endif
                        @endif

                        @role('admin|it_staff')
                            <a href="{{ route('tickets.edit', $ticket) }}"
                               class="bg-yellow-500 text-white px-2 py-1 rounded text-[10px] font-bold uppercase shadow-sm">Edit</a>

                            <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Permanently delete this ticket?')"
                                        class="bg-red-600 text-white px-2 py-1 rounded text-[10px] font-bold uppercase shadow-sm">Delete</button>
                            </form>
                        @endrole
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="text-center py-10 text-gray-500 italic">
                        No tickets matching your search criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t">
            {{ $tickets->appends(request()->all())->links() }}
        </div>
    </div>
</div>
@endsection