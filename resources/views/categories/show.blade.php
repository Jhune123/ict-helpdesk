@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-600">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="mr-2">📂</span> {{ $category->name }} Archive
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Displaying all <span class="font-semibold text-gray-700">Closed</span> and <span class="font-semibold text-red-600">Condemned</span> records for this category.
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition">
                ← Back to Active Workspace
            </a>
        </div>
    </div>

    {{-- Archive Table --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject & Equipment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resolved By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Finished</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600 font-bold">
                                {{ $ticket->ticket_number }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->title }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->equipment_type }} ({{ $ticket->brand_model }})</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->status === 'Condemned')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                        <svg class="mr-1.5 h-2 w-2 text-red-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                        Condemned
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <svg class="mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                        Closed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $ticket->assignee->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                {{ $ticket->date_finished ? \Carbon\Carbon::parse($ticket->date_finished)->format('M d, Y') : $ticket->updated_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded hover:bg-blue-100 transition">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-5xl mb-4 text-gray-300">📁</span>
                                    <p class="text-gray-500 text-lg font-medium">This archive is currently empty.</p>
                                    <p class="text-gray-400 text-sm">Resolved or condemned tickets in this category will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($tickets->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection