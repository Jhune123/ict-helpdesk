@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6 relative bg-white shadow-lg rounded-xl mt-4">

    {{-- TITLE --}}
    <div class="text-center mb-6">
        <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight">
            ICTO–TICKET Queuing System
        </h1>
        {{-- PH TIME --}}
        <p class="text-lg text-indigo-600 font-medium mt-2">
            {{ \Carbon\Carbon::now('Asia/Manila')->format('F d, Y | h:i A') }} (PH Time)
        </p>
    </div>

    {{-- 🔹 REPORT & LIVE TV BUTTONS --}}
    <div class="flex justify-center gap-4 mb-8 flex-wrap">
        <button onclick="printDetailedReport()"
            class="w-56 h-12 flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg shadow-md transition">
            📝 Detailed Report (Print)
        </button>

        @role('admin|it_staff')
        <a href="{{ route('queues.live-tv') }}" target="_blank"
           class="w-56 h-12 flex items-center justify-center bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-lg shadow-md transition">
            🚀 Launch Monitor TV
        </a>
        @endrole
    </div>

    {{-- MAIN ACTION BUTTONS --}}
    @role('admin|it_staff')
    <div class="flex justify-center gap-6 mb-10 p-6 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
        <form action="{{ route('queues.add') }}" method="POST">
            @csrf
            <button class="w-64 h-14 flex items-center justify-center bg-green-600 hover:bg-green-700 text-white text-lg font-bold rounded-xl shadow-lg transition transform hover:scale-105">
                ➕ Issue New Ticket
            </button>
        </form>

        <form action="{{ route('queues.clear') }}" method="POST">
            @csrf
            <button class="w-64 h-14 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white text-lg font-bold rounded-xl shadow-lg transition transform hover:scale-105"
                    onclick="return confirm('Warning: This will delete all history. Reset now?')">
                🧹 Clear & Reset All
            </button>
        </form>
    </div>
    @endrole

    {{-- QUEUE TABLE --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
        <table id="queueTable" class="w-full text-center text-lg">
            <thead class="bg-gray-800 text-white uppercase text-sm tracking-widest">
                <tr>
                    <th class="p-4">Ticket #</th>
                    <th class="p-4">Current Status</th>
                    <th class="p-4">Assigned Personnel</th>
                    <th class="p-4 detailed-only">Time Created</th>
                    <th class="p-4 detailed-only">Time Closed</th>
                    @role('admin|it_staff')
                    <th class="p-4 no-print">Serve / Call To Counter</th>
                    @endrole
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($queues as $queue)
                <tr class="{{ $queue->status === 'serving' ? 'bg-yellow-100 font-semibold' : '' }}">
                    <td class="p-4 font-black text-2xl text-blue-700">{{ $queue->queue_number }}</td>
                    <td class="p-4">
                        @if($queue->status === 'waiting')
                            <span class="text-green-600 font-bold uppercase text-sm">● Open</span>
                        @elseif($queue->status === 'serving')
                            <span class="text-yellow-700 font-bold uppercase text-sm">● In Progress</span>
                        @else
                            <span class="text-gray-400 font-bold uppercase text-sm">● Closed</span>
                        @endif
                    </td>
                    <td class="p-4 text-gray-700 italic">{{ $queue->served_by ?? '---' }}</td>
                    <td class="p-4 detailed-only text-sm text-gray-500">{{ $queue->created_at->format('h:i A') }}</td>
                    <td class="p-4 detailed-only text-sm text-gray-500">
                        {{ $queue->status === 'served' ? $queue->updated_at->format('h:i A') : '-' }}
                    </td>

                    @role('admin|it_staff')
                    <td class="p-4 no-print">
                        @if($queue->status === 'waiting')
                            <div class="grid grid-cols-2 gap-2 w-full max-w-xs mx-auto">
                                {{-- Counter Buttons --}}
                                <form action="{{ route('queues.serve',$queue->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="counter" value="Jhune">
                                    <button class="w-full py-2 bg-blue-500 hover:bg-blue-600 text-white rounded text-xs font-bold uppercase">Jhune</button>
                                </form>

                                <form action="{{ route('queues.serve',$queue->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="counter" value="Reymar">
                                    <button class="w-full py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded text-xs font-bold uppercase">Reymar</button>
                                </form>

                                <form action="{{ route('queues.serve',$queue->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="counter" value="Bryan">
                                    <button class="w-full py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded text-xs font-bold uppercase">Bryan</button>
                                </form>

                                <form action="{{ route('queues.serve',$queue->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="counter" value="Walid">
                                    <button class="w-full py-2 bg-rose-500 hover:bg-rose-600 text-white rounded text-xs font-bold uppercase">Walid</button>
                                </form>
                            </div>
                        @elseif($queue->status === 'serving')
                            <form action="{{ route('queues.complete',$queue->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="w-full h-10 bg-gray-800 hover:bg-black text-white rounded-lg font-bold text-xs uppercase shadow">
                                    ✅ Mark as Closed
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-400 font-bold uppercase">Transaction Finished</span>
                        @endif
                    </td>
                    @endrole
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-8 flex justify-center">
        {{ $queues->links() }}
    </div>

</div>

{{-- PRINT RULES --}}
<style>
@media print {
    @page { size: A4 landscape; margin: 10mm; }
    body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .no-print { display: none !important; }
    .detailed-only { display: table-cell !important; }
    table { width: 100% !important; font-size: 11px; }
    th, td { padding: 4px !important; border: 1px solid #ccc !important; }
}
</style>

{{-- PRINT JS --}}
<script>
function printDetailedReport() {
    // Briefly force detail columns visible for print
    window.print();
}
</script>
@endsection