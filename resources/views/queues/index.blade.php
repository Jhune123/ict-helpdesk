@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-3xl font-bold mb-6 text-center">ICTO-MIS Enrollment Queue</h2>

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
                <td class="border p-4">{{ $queue->counter ?? '-' }}</td>
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
</div>
@endsection
