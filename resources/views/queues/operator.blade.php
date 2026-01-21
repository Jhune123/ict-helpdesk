@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">MIS Queuing – Operator Panel</h1>

    <!-- ADD QUEUE NUMBER -->
    <form method="POST" action="{{ route('queues.add') }}" class="mb-8 flex gap-4">
        @csrf
        <input type="number" name="queue_number" required
               class="border rounded px-4 py-2"
               placeholder="Manual Queue Number">

        <button class="bg-blue-600 text-white px-6 py-2 rounded">
            ➕ Add Queue Number
        </button>
    </form>

    <!-- WAITING QUEUES -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        @foreach(['Jhune', 'Reymar'] as $operator)
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-2xl font-bold mb-4">Operator: {{ $operator }}</h2>

            @php
                $current = ${'serving'.$operator} ?? null;
            @endphp

            <!-- CURRENT SERVING -->
            <div class="mb-4">
                <p class="text-gray-500">Now Serving</p>
                <div class="text-4xl font-bold text-yellow-500">
                    {{ $current->queue_number ?? '-' }}
                </div>
            </div>

            <!-- COMPLETE BUTTON -->
            @if($current)
                <form method="POST" action="{{ route('queues.complete', $current->id) }}">
                    @csrf
                    @method('PATCH') <!-- FIXED: PATCH METHOD -->
                    <button class="bg-green-600 text-white px-4 py-2 rounded mb-4">
                        ✔ Complete
                    </button>
                </form>
            @endif

            <!-- NEXT QUEUES -->
            <h3 class="font-semibold mb-2">Waiting</h3>

            @foreach($waiting as $queue)
                <form method="POST"
                      action="{{ route('queues.serve', $queue->id) }}"
                      class="flex justify-between items-center mb-2">
                    @csrf
                    @method('PATCH') <!-- FIXED: PATCH METHOD -->
                    <input type="hidden" name="counter" value="{{ $operator }}">

                    <span class="text-xl">{{ $queue->queue_number }}</span>

                    <button class="bg-yellow-500 text-white px-3 py-1 rounded">
                        Serve
                    </button>
                </form>
            @endforeach

        </div>
        @endforeach

    </div>
</div>
@endsection
