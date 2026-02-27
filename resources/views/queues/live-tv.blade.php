<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5"> <title>ICTO Live Queuing Monitor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .blink { animation: blinker 1.5s linear infinite; }
        @keyframes blinker { 50% { opacity: 0; } }
    </style>
</head>
<body class="bg-slate-900 text-white h-screen overflow-hidden flex flex-col">

    {{-- HEADER --}}
    <div class="bg-slate-800 p-6 shadow-2xl flex justify-between items-center border-b-4 border-orange-500">
        <h1 class="text-5xl font-black tracking-tighter text-orange-500">ICTO LIVE MONITOR</h1>
        <div class="text-right">
            <p class="text-3xl font-mono">{{ date('h:i:s A') }}</p>
            <p class="text-sm uppercase text-slate-400">{{ date('l, F d, Y') }}</p>
        </div>
    </div>

    {{-- MAIN COUNTER GRID --}}
    <div class="grid grid-cols-4 gap-6 p-8 flex-grow">
        @foreach([
            ['name' => 'JHUNE', 'data' => $servingJhune, 'color' => 'blue'],
            ['name' => 'REYMAR', 'data' => $servingReymar, 'color' => 'emerald'],
            ['name' => 'BRYAN', 'data' => $servingBryan, 'color' => 'indigo'],
            ['name' => 'WALID', 'data' => $servingWalid, 'color' => 'rose']
        ] as $counter)
        <div class="bg-slate-800 rounded-3xl border-b-8 border-{{ $counter['color'] }}-500 flex flex-col items-center justify-center p-6 shadow-2xl">
            <h2 class="text-4xl font-bold text-{{ $counter['color'] }}-400 mb-4">{{ $counter['name'] }}</h2>
            <div class="text-[120px] font-black leading-none {{ $counter['data'] ? 'text-white' : 'text-slate-700' }}">
                {{ $counter['data']->ticket_number ?? '---' }}
            </div>
            <p class="mt-4 text-xl font-bold uppercase text-slate-500">
                {{ $counter['data'] ? 'Now Serving' : 'Vacant' }}
            </p>
        </div>
        @endforeach
    </div>

    {{-- FOOTER: NEXT IN LINE --}}
    <div class="bg-orange-600 p-4 flex items-center gap-8 overflow-hidden">
        <span class="text-2xl font-black whitespace-nowrap bg-black px-4 py-1 rounded">NEXT IN LINE:</span>
        <div class="flex gap-12 text-3xl font-bold italic">
            @forelse($nextQueues as $next)
                <span>{{ $next->ticket_number }}</span>
            @empty
                <span class="opacity-50">No tickets waiting...</span>
            @endforelse
        </div>
    </div>

</body>
</html>