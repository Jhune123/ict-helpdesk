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

    {{-- 🔹 REPORT BUTTONS --}}
    <div class="flex justify-center gap-3 mb-6 relative z-50">
        {{-- Print current page --}}
        <button onclick="window.print()"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold">
            🖨 Print Report
        </button>

        {{-- Download server-generated PDF --}}
        <a href="{{ route('queues.pdf') }}" target="_blank"
           class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold">
           📄 Download PDF
        </a>
    </div>

    {{-- MAIN ACTION BUTTONS --}}
    <div class="flex justify-center gap-4 mb-8">
        <form action="{{ route('queues.add') }}" method="POST">
            @csrf
            <button class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold">
                ➕ Add Queue Number
            </button>
        </form>

        <form action="{{ route('queues.reset') }}" method="POST">
            @csrf
            <button class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold">
                🧹 Clear & Reset
            </button>
        </form>
    </div>

    {{-- QUEUE TABLE --}}
    <table class="w-full border text-center text-xl">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-4">Queue #</th>
                <th class="border p-4">Status</th>
                <th class="border p-4">Counter</th>
                <th class="border p-4 no-print">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($queues as $queue)
            <tr class="{{ $queue->status === 'serving' ? 'bg-yellow-200' : '' }}">
                <td class="border p-4 font-bold">{{ $queue->queue_number }}</td>
                <td class="border p-4 capitalize">{{ $queue->status }}</td>
                <td class="border p-4">{{ $queue->served_by ?? '-' }}</td>

                <td class="border p-4 no-print">
                    @if($queue->status === 'waiting')
                        <form action="{{ route('queues.serve',$queue->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="counter" value="Jhune">
                            <button class="bg-blue-500 text-white px-3 py-2 rounded">
                                Serve (Jhune)
                            </button>
                        </form>

                        <form action="{{ route('queues.serve',$queue->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <input type="hidden" name="counter" value="Reymar">
                            <button class="bg-green-500 text-white px-3 py-2 rounded">
                                Serve (Reymar)
                            </button>
                        </form>
                    @elseif($queue->status === 'serving')
                        <form action="{{ route('queues.complete',$queue->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="bg-gray-800 text-white px-4 py-2 rounded">
                                Complete
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- SIGNATURES --}}
    <div class="grid grid-cols-2 gap-12 mt-16 text-center">
        <div>
            <p class="font-semibold">Prepared by</p>
            <p class="mt-10 border-t pt-2">ICTO Staff</p>
        </div>
        <div>
            <p class="font-semibold">Approved by</p>
            <p class="mt-10 border-t pt-2">ICTO Head</p>
        </div>
    </div>

</div>

{{-- PRINT RULE --}}
<style>
@media print {
    .no-print { display: none !important; }
}
</style>
@endsection
