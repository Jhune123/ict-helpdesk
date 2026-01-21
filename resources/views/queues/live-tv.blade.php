@extends('layouts.app')

@section('content')
<div class="w-full h-screen bg-gray-100 flex flex-col items-center justify-center p-4">

    <h1 class="text-5xl font-bold mb-8 text-center">🎫 ICTO-MIS Helpdesk & Queuing System</h1>

    <div class="w-full max-w-7xl grid grid-cols-1 md:grid-cols-2 gap-8">

        @php
            $counters = ['Sir Jhune', 'Sir Reymar'];
        @endphp

        @foreach($counters as $counter)
        <div class="bg-white rounded-3xl shadow-2xl p-8 flex flex-col items-center">
            <h2 class="text-4xl font-semibold mb-6">Counter: {{ $counter }}</h2>

            @php
                $current = $queues->where('counter', $counter)->where('status', 'serving')->first();
                $next    = $queues->where('status', 'waiting')->sortBy('queue_number')->first();
            @endphp

            <!-- Current Serving -->
            <div class="w-full mb-6 text-center">
                <p class="text-2xl font-medium text-gray-500">Now Serving</p>
                <div class="text-8xl font-bold text-yellow-500 mt-2">
                    {{ $current->queue_number ?? '-' }}
                </div>
            </div>

            <!-- Next Queue Preview -->
            <div class="w-full mt-4 text-center">
                <p class="text-2xl font-medium text-gray-500">Next Queue</p>
                <div class="text-6xl font-bold text-green-500 mt-2">
                    {{ $next ? $next->queue_number : '-' }}
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <div class="mt-12 text-gray-400 text-xl">Auto-refresh every 5 seconds</div>
</div>

<!-- Auto-refresh every 5 seconds -->
<meta http-equiv="refresh" content="5">
@endsection

@section('styles')
<style>
    /* Fullscreen TV mode */
    html, body, #app {
        height: 100%;
        margin: 0;
        overflow: hidden;
    }
</style>
@endsection
