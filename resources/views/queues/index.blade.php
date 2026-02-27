@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-xl mt-4">
    <div class="text-center mb-6">
        <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight">ICTO–TICKET Queuing System</h1>
        <p class="text-lg text-indigo-600 font-medium mt-2">{{ date('F d, Y | h:i A') }}</p>
    </div>

    <div class="flex justify-center gap-4 mb-8">
        <button onclick="window.print()" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-indigo-700">📝 Print Report</button>
        <a href="{{ route('queues.live-tv') }}" target="_blank" class="bg-orange-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-orange-700">🚀 Launch Monitor TV</a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
        <table class="w-full text-center text-lg">
            <thead class="bg-gray-800 text-white uppercase text-sm">
                <tr>
                    <th class="p-4">Ticket #</th>
                    <th class="p-4">Client</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Personnel</th>
                    <th class="p-4 no-print">Serve / Call To Counter</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($queues as $ticket)
                <tr class="{{ $ticket->status === 'In Progress' ? 'bg-yellow-50 font-semibold' : '' }}">
                    <td class="p-4 font-black text-2xl text-blue-700">{{ $ticket->ticket_number }}</td>
                    <td class="p-4 font-medium">{{ $ticket->client_name }}</td>
                    <td class="p-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $ticket->status === 'Open' ? 'bg-green-100 text-green-800' : ($ticket->status === 'In Progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-500') }}">
                            {{ $ticket->status }}
                        </span>
                    </td>
                    <td class="p-4 italic text-gray-700">{{ $ticket->assignee_name }}</td>
                    <td class="p-4 no-print">
                        @if($ticket->status === 'Open')
                            <div class="flex gap-1 justify-center">
                                @foreach(['Jhune', 'Reymar', 'Bryan', 'Walid'] as $staff)
                                <form action="{{ route('queues.serve', $ticket->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="counter" value="{{ $staff }}">
                                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-[10px] font-bold uppercase">{{ $staff }}</button>
                                </form>
                                @endforeach
                            </div>
                        @elseif($ticket->status === 'In Progress')
                            <form action="{{ route('queues.complete', $ticket->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="bg-gray-800 hover:bg-black text-white px-4 py-1.5 rounded text-xs font-bold">CLOSE TICKET</button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400 font-bold">TRANSACTION FINISHED</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // Auto-refresh the dashboard every 10 seconds to see new tickets
    setInterval(function() {
        if (!document.querySelector('button:active')) {
            window.location.reload();
        }
    }, 10000);
</script>
@endsection