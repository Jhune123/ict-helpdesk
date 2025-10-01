@extends('layouts.app')

@section('content')
<div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-2xl p-6">
        <h2 class="text-2xl font-bold mb-6">🏢 Tickets by Department</h2>

        @if($tickets->isEmpty())
            <div class="p-4 bg-yellow-100 text-yellow-800 rounded-lg">
                No tickets found 🚫
            </div>
        @else
            @foreach($tickets as $department => $deptTickets)
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-indigo-700 mb-4">
                        {{ $department ?? 'Unspecified Department' }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200 rounded-lg shadow-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">🎫 Title</th>
                                    <th class="px-4 py-2 text-left">📝 Description</th>
                                    <th class="px-4 py-2 text-left">📊 Status</th>
                                    <th class="px-4 py-2 text-left">⭐ Priority</th>
                                    <th class="px-4 py-2 text-left">👤 Client</th>
                                    <th class="px-4 py-2 text-left">🧑‍💻 IT Personnel</th>
                                    <th class="px-4 py-2 text-left">📅 Submitted</th>
                                    <th class="px-4 py-2 text-left">✅ Finished</th>
                                    <th class="px-4 py-2 text-left">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deptTickets as $ticket)
                                    <tr class="border-t hover:bg-gray-50">
                                        <td class="px-4 py-2 font-semibold">{{ $ticket->title }}</td>
                                        <td class="px-4 py-2">{{ $ticket->description ?? '-' }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded text-white
                                                {{ $ticket->status == 'Open' ? 'bg-red-500' : '' }}
                                                {{ $ticket->status == 'In Progress' ? 'bg-yellow-500' : '' }}
                                                {{ $ticket->status == 'Closed' ? 'bg-green-500' : '' }}">
                                                {{ $ticket->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">{{ $ticket->priority ?? 'Normal' }}</td>
                                        <td class="px-4 py-2">{{ $ticket->client_name }}</td>
                                        <td class="px-4 py-2">{{ $ticket->assignee_name }}</td>
                                        <td class="px-4 py-2">{{ $ticket->date_submitted?->format('M d, Y') }}</td>
                                        <td class="px-4 py-2">{{ $ticket->date_finished?->format('M d, Y') ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ $ticket->remarks ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
