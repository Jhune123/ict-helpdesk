@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-3xl font-bold mb-6 text-center">ICTO-MIS Queuing System</h2>

    {{-- Launch Live TV Button --}}
    <div class="mb-6 text-center">
        <a href="{{ route('queues.live-tv') }}" target="_blank"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            🚀 Launch Live TV
        </a>
    </div>

    {{-- Add Queue & Clear Buttons --}}
    <div class="mb-6 text-center flex justify-center gap-4">
        @if($canAddQueue)
        <form action="{{ route('queues.add') }}" method="POST">
            @csrf
            <button class="bg-green-500 text-white px-6 py-2 rounded-lg text-xl">➕ Add Queue Number</button>
        </form>
        @endif

        <form action="{{ route('queues.clear') }}" method="POST">
            @csrf
            <button class="bg-red-500 text-white px-6 py-2 rounded-lg text-xl">🧹 Clear & Reset</button>
        </form>
    </div>

    <table class="w-full text-center border border-gray-200 text-3xl">
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
                <td class="border p-4">{{ $queue->queue_number }}</td>
                <td class="border p-4 capitalize">{{ $queue->status }}</td>
                <td class="border p-4">{{ $queue->served_by ?? '-' }}</td>
                <td class="border p-4 flex justify-center gap-2">
                    @if($queue->status == 'waiting')
                    <form action="{{ route('queues.serve', $queue->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="counter" value="Jhune">
                        <button class="bg-blue-500 text-white px-6 py-2 rounded text-xl">Serve (Jhune)</button>
                    </form>
                    <form action="{{ route('queues.serve', $queue->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="counter" value="Reymar">
                        <button class="bg-green-500 text-white px-6 py-2 rounded text-xl">Serve (Reymar)</button>
                    </form>
                    @elseif($queue->status == 'serving')
                    <form action="{{ route('queues.complete', $queue->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="bg-gray-700 text-white px-6 py-2 rounded text-xl">Complete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if(session('success'))
    <div class="mt-4 p-4 bg-green-200 text-green-800 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mt-4 p-4 bg-red-200 text-red-800 rounded">
        {{ session('error') }}
    </div>
    @endif
</div>
@endsection
