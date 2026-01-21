@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-3xl font-bold mb-6 text-center">ICTO-MIS Queuing System</h2>

    {{-- Add Queue Button --}}
    <div class="mb-6 text-center">
        @if($canAddQueue)
        <form action="{{ route('queues.add') }}" method="POST" class="inline-block">
            @csrf
            <button class="bg-blue-600 text-white px-8 py-3 rounded text-xl hover:bg-blue-700 transition">
                ➕ Add Queue Number
            </button>
        </form>
        @else
        <button class="bg-gray-400 text-white px-8 py-3 rounded text-xl cursor-not-allowed">
            ➕ Add Queue Number
        </button>
        <p class="mt-2 text-red-600 font-semibold">⚠ Finish the current queue before adding a new number</p>
        @endif
    </div>

    {{-- Queue Table --}}
    <table class="w-full text-center border border-gray-200 text-2xl">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-4">Queue #</th>
                <th class="border p-4">Status</th>
                <th class="border p-4">Counter</th>
                <th class="border p-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($queues as $queue)
            <tr class="{{ $queue->status == 'serving' ? 'bg-yellow-200' : '' }}">
                <td class="border p-4 font-bold">{{ $queue->queue_number }}</td>
                <td class="border p-4 capitalize">{{ $queue->status }}</td>
                <td class="border p-4 font-semibold">{{ $queue->served_by ?? '-' }}</td>
                <td class="border p-4 flex justify-center gap-2 flex-wrap">
                    @if($queue->status == 'waiting')
                    {{-- Serve Jhune --}}
                    <form action="{{ route('queues.serve', $queue->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="counter" value="Jhune">
                        <button class="bg-blue-500 text-white px-6 py-2 rounded text-xl hover:bg-blue-600 transition">
                            Serve (Jhune)
                        </button>
                    </form>

                    {{-- Serve Reymar --}}
                    <form action="{{ route('queues.serve', $queue->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="counter" value="Reymar">
                        <button class="bg-green-500 text-white px-6 py-2 rounded text-xl hover:bg-green-600 transition">
                            Serve (Reymar)
                        </button>
                    </form>
                    @elseif($queue->status == 'serving')
                    {{-- Complete Serving --}}
                    <form action="{{ route('queues.complete', $queue->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="bg-gray-700 text-white px-6 py-2 rounded text-xl hover:bg-gray-800 transition">
                            Complete
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="mt-4 p-4 bg-green-200 text-green-800 rounded text-center">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mt-4 p-4 bg-red-200 text-red-800 rounded text-center">
        {{ session('error') }}
    </div>
    @endif
</div>
@endsection
