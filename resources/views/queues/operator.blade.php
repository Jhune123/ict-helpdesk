@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 relative">

    {{-- TITLE --}}
    <h1 class="text-3xl font-bold text-center mb-2">
        ICTO–MIS Queuing System
    </h1>

    {{-- PH TIME --}}
    <p class="text-center text-gray-600 mb-6">
        {{ \Carbon\Carbon::now('Asia/Manila')->format('F d, Y | h:i A') }} (PH Time)
    </p>

    {{-- 🔹 REPORT & LIVE TV BUTTONS --}}
    <div class="flex justify-center gap-4 mb-6 flex-wrap">

        {{-- Detailed Report --}}
        <button onclick="printDetailedReport()"
            class="w-56 h-12 flex items-center justify-center
                   bg-indigo-600 hover:bg-indigo-700
                   text-white font-semibold rounded-lg">
            📝 Detailed Report
        </button>

        {{-- Launch Live TV --}}
        <a href="{{ route('queues.live-tv') }}" target="_blank"
           class="w-56 h-12 flex items-center justify-center
                  bg-orange-600 hover:bg-orange-700
                  text-white font-semibold rounded-lg">
            🚀 Launch Live TV
        </a>
    </div>

    {{-- MAIN ACTION BUTTONS --}}
    <div class="flex justify-center gap-4 mb-8 flex-wrap">
        <form action="{{ route('queues.add') }}" method="POST">
            @csrf
            <button class="w-56 h-12 flex items-center justify-center
                           bg-green-600 hover:bg-green-700
                           text-white font-semibold rounded-lg">
                ➕ Add Queue Number
            </button>
        </form>

        <form action="{{ route('queues.clear') }}" method="POST">
            @csrf
            <button class="w-56 h-12 flex items-center justify-center
                           bg-red-600 hover:bg-red-700
                           text-white font-semibold rounded-lg">
                🧹 Clear & Reset
            </button>
        </form>
    </div>

    {{-- QUEUE TABLE --}}
    <table id="queueTable" class="w-full border text-center text-xl">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-4">Queue #</th>
                <th class="border p-4">Status</th>
                <th class="border p-4">Counter</th>
                <th class="border p-4 detailed-only">Time Submitted</th>
                <th class="border p-4 detailed-only">Time Served</th>
                <th class="border p-4 no-print">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($queues as $queue)
            <tr class="{{ $queue->status === 'serving' ? 'bg-yellow-200' : '' }}">
                <td class="border p-4 font-bold">{{ $queue->queue_number }}</td>
                <td class="border p-4 capitalize">{{ $queue->status }}</td>
                <td class="border p-4">{{ $queue->served_by ?? '-' }}</td>
                <td class="border p-4 detailed-only">{{ $queue->created_at->format('F d, Y | h:i A') }}</td>
                <td class="border p-4 detailed-only">
                    {{ $queue->status === 'served' ? $queue->updated_at->format('F d, Y | h:i A') : '-' }}
                </td>

                <td class="border p-4 no-print">
                    @if($queue->status === 'waiting')
                        <form action="{{ route('queues.serve',$queue->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="counter" value="Jhune">
                            <button class="w-32 h-10 bg-blue-500 text-white rounded font-semibold">
                                Serve (Jhune)
                            </button>
                        </form>

                        <form action="{{ route('queues.serve',$queue->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="counter" value="Reymar">
                            <button class="w-32 h-10 bg-green-500 text-white rounded font-semibold">
                                Serve (Reymar)
                            </button>
                        </form>
                    @elseif($queue->status === 'serving')
                        <form action="{{ route('queues.complete',$queue->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="w-32 h-10 bg-gray-800 text-white rounded font-semibold">
                                Complete
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PAGINATION --}}
    <div class="mt-6 flex justify-center">
        {{ $queues->links() }}
    </div>

</div>

{{-- PRINT RULES --}}
<style>
@media print {

    /* Force LANDSCAPE */
    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    body {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .no-print { display: none !important; }
    .detailed-only { display: table-cell !important; }

    table { width: 100% !important; font-size: 12px; }
    th, td { padding: 6px !important; white-space: nowrap; }
}
</style>

{{-- PRINT JS --}}
<script>
function printDetailedReport() {
    document.querySelectorAll('.detailed-only').forEach(el => el.style.display = '');
    window.print();
}
</script>
@endsection
