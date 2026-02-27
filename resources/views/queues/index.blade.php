@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-4">

    {{-- TITLE --}}
    <h1 class="text-2xl font-bold text-center mb-1">
        ICTO–MIS Queuing System
    </h1>

    {{-- PH TIME --}}
    <p class="text-center text-gray-600 text-sm mb-4">
        {{ \Carbon\Carbon::now('Asia/Manila')->format('F d, Y | h:i A') }} (PH Time)
    </p>

    {{-- REPORT & LIVE TV BUTTONS --}}
    <div class="flex justify-center gap-2 mb-4">

        <button onclick="printDetailedReport()"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm font-semibold">
            📝 Detailed Report
        </button>

        @role('admin|it_staff')
        <button onclick="printSummaryReport()"
            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm font-semibold">
            📊 Summary Report
        </button>

        <a href="{{ route('queues.live-tv') }}" target="_blank"
           class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded text-sm font-semibold">
           🚀 Launch Live TV
        </a>
        @endrole
    </div>

    {{-- MAIN ACTION BUTTONS --}}
    @role('admin|it_staff')
    <div class="flex justify-center gap-3 mb-5">
        <form action="{{ route('queues.add') }}" method="POST">
            @csrf
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm font-semibold">
                ➕ Add Queue
            </button>
        </form>

        <form action="{{ route('queues.clear') }}" method="POST">
            @csrf
            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm font-semibold">
                🧹 Reset
            </button>
        </form>
    </div>
    @endrole

    {{-- QUEUE TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full border text-center text-sm whitespace-nowrap">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">Queue #</th>
                    <th class="border px-3 py-2">Status</th>
                    <th class="border px-3 py-2">Counter</th>
                    <th class="border px-3 py-2 detailed-only">Time Submitted</th>
                    <th class="border px-3 py-2 detailed-only">Time Served</th>
                    
                    @role('admin|it_staff')
                    <th class="border px-3 py-2 no-print">Actions</th>
                    @endrole
                </tr>
            </thead>
            <tbody>
                @foreach($queues as $queue)
                <tr class="{{ $queue->status === 'serving' ? 'bg-yellow-100' : '' }}">
                    <td class="border px-3 py-1 font-semibold">{{ $queue->queue_number }}</td>
                    <td class="border px-3 py-1 capitalize">{{ $queue->status }}</td>
                    <td class="border px-3 py-1">{{ $queue->served_by ?? '-' }}</td>
                    <td class="border px-3 py-1 detailed-only">
                        {{ $queue->created_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="border px-3 py-1 detailed-only">
                        {{ $queue->status === 'served'
                            ? $queue->updated_at->format('M d, Y h:i A')
                            : '-' }}
                    </td>

                    @role('admin|it_staff')
                    <td class="border px-3 py-1 no-print">
                        @if($queue->status === 'waiting')
                            <form action="{{ route('queues.serve',$queue->id) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="counter" value="Jhune">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded text-xs">
                                    Jhune
                                </button>
                            </form>

                            <form action="{{ route('queues.serve',$queue->id) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="counter" value="Reymar">
                                <button class="bg-green-500 text-white px-2 py-1 rounded text-xs">
                                    Reymar
                                </button>
                            </form>
                        @elseif($queue->status === 'serving')
                            <form action="{{ route('queues.complete',$queue->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="bg-gray-800 text-white px-3 py-1 rounded text-xs">
                                    Complete
                                </button>
                            </form>
                        @endif
                    </td>
                    @endrole
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- PRINT RULES --}}
<style>
@media print {
    .no-print { display: none !important; }
}
</style>

{{-- PRINT JS --}}
<script>
function printDetailedReport() {
    document.querySelectorAll('.detailed-only').forEach(el => el.style.display = '');
    window.print();
}

function printSummaryReport() {
    document.querySelectorAll('.detailed-only').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.no-print').forEach(el => el.style.display = 'none');
    window.print();

    document.querySelectorAll('.detailed-only').forEach(el => el.style.display = '');
    document.querySelectorAll('.no-print').forEach(el => el.style.display = '');
}
</script>
@endsection