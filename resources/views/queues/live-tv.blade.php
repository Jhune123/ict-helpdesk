<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="5"> 
    <title>ICTO Live Queuing Monitor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .blink { animation: blinker 1.5s linear infinite; }
        @keyframes blinker { 50% { opacity: 0; } }
        .counter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 16rem), 1fr));
        }
        .ticket-number { font-size: clamp(3rem, 8vw, 7.5rem); }
    </style>
</head>
<body class="bg-slate-900 text-white h-screen overflow-hidden flex flex-col">

    {{-- Audio Element --}}
    <audio id="callSound" src="{{ asset('sounds/punch-gaming-sound-effect-hd_RzlG1GE.mp3') }}" preload="auto"></audio>

    {{-- HEADER --}}
    <div class="bg-slate-800 p-6 shadow-2xl flex justify-between items-center border-b-4 border-orange-500">
        <h1 class="text-5xl font-black tracking-tighter text-orange-500">ICTO LIVE MONITOR</h1>
        <div class="text-right">
            <p class="text-3xl font-mono">{{ date('h:i:s A') }}</p>
            <p class="text-sm uppercase text-slate-400">{{ date('l, F d, Y') }}</p>
        </div>
    </div>

    {{-- MAIN COUNTER GRID --}}
    <div class="counter-grid gap-6 p-8 flex-grow min-h-0 overflow-y-auto">
        @forelse($itPersonnel as $person)
            @php($servingTicket = $servingTickets->get('Serving at Counter: ' . $person->name))
            <div class="bg-slate-800 rounded-3xl border-b-8 border-orange-500 flex flex-col items-center justify-center p-6 shadow-2xl">
                <h2 class="text-4xl font-bold text-orange-400 mb-4">{{ $person->name }}</h2>
                <div class="ticket-number font-black leading-none {{ $servingTicket ? 'text-white' : 'text-slate-700' }}">
                    {{ $servingTicket->ticket_number ?? '---' }}
                </div>
                <p class="mt-4 text-xl font-bold uppercase text-slate-500">
                    {{ $servingTicket ? 'Now Serving' : 'Vacant' }}
                </p>
            </div>
        @empty
            <div class="col-span-full flex items-center justify-center text-3xl font-bold text-slate-400">
                No registered IT staff found.
            </div>
        @endforelse
    </div>

    {{-- FOOTER: NEXT IN LINE --}}
    <div class="bg-orange-600 p-4 flex items-start gap-8">
        <span class="text-2xl font-black whitespace-nowrap bg-black px-4 py-1 rounded">NEXT IN LINE:</span>
        <div class="flex flex-wrap gap-x-12 gap-y-2 text-3xl font-bold italic max-h-32 overflow-y-auto">
            @forelse($nextQueues as $next)
                <span>{{ $next->ticket_number }} <small class="text-sm not-italic">({{ $next->status === 'In Progress' ? 'NOW SERVING' : 'WAITING' }})</small></span>
            @empty
                <span class="opacity-50">No tickets waiting...</span>
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get current IDs of tickets being served
            const currentServing = @json($servingTickets->pluck('id')->sort()->values()->implode('|'));

            const previousServing = localStorage.getItem('last_call_ids');

            // Play sound if a new ticket ID appears in the list
            if (previousServing && previousServing !== currentServing) {
                // Only play if the currentServing has at least one actual ticket (not just empty pipes)
                if (currentServing.replace(/\|/g, '').length > 0) {
                    const audio = document.getElementById('callSound');
                    audio.play().catch(e => console.log("Interaction required for audio."));
                }
            }

            // Store current state
            localStorage.setItem('last_call_ids', currentServing);
        });
    </script>

</body>
</html>