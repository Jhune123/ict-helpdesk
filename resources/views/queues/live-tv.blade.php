<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    {{-- HEADER --}}
    <div class="bg-slate-800 p-6 shadow-2xl flex justify-between items-center border-b-4 border-orange-500">
        <h1 class="text-5xl font-black tracking-tighter text-orange-500">ICTO LIVE MONITOR</h1>
        <div class="flex items-center gap-6">
            <button id="enableAnnouncements" type="button" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-3 rounded-lg font-bold">
                Enable announcements
            </button>
            <div class="text-right">
                <p class="text-3xl font-mono">{{ date('h:i:s A') }}</p>
                <p class="text-sm uppercase text-slate-400">{{ date('l, F d, Y') }}</p>
            </div>
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
            const servingTickets = @json($servingAnnouncements);
            const currentAssignments = servingTickets.map(ticket => `${ticket.id}:${ticket.counter}`).sort();
            const ticketStateKey = 'icto_live_monitor_serving_ids';
            const announcementsEnabledKey = 'icto_live_monitor_announcements_enabled';
            const enableButton = document.getElementById('enableAnnouncements');
            const previousIds = localStorage.getItem(ticketStateKey);

            function announceNewTickets() {
                if (!previousIds) {
                    localStorage.setItem(ticketStateKey, currentAssignments.join('|'));
                    return;
                }

                const previouslyServing = previousIds.split('|');
                const newlyServing = servingTickets.filter(ticket =>
                    !previouslyServing.includes(`${ticket.id}:${ticket.counter}`)
                );
                localStorage.setItem(ticketStateKey, currentAssignments.join('|'));

                if (localStorage.getItem(announcementsEnabledKey) !== 'true' || newlyServing.length === 0) {
                    return;
                }

                newlyServing.forEach((ticket, index) => {
                    window.setTimeout(() => {
                        playBell();
                        window.setTimeout(() => {
                            if (!('speechSynthesis' in window) || !('SpeechSynthesisUtterance' in window)) {
                                enableButton.textContent = 'Voice announcements unavailable';
                                return;
                            }

                            const announcement = new SpeechSynthesisUtterance(
                                `Now serving ticket ${ticket.ticketNumber}. ${ticket.counter}, please proceed to the counter.`
                            );
                            window.speechSynthesis.speak(announcement);
                        }, 700);
                    }, index * 3000);
                });
            }

            function playBell() {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) {
                    return;
                }

                const context = new AudioContext();
                const now = context.currentTime;

                [880, 1174].forEach((frequency, index) => {
                    const oscillator = context.createOscillator();
                    const volume = context.createGain();
                    const start = now + index * 0.16;

                    oscillator.type = 'sine';
                    oscillator.frequency.value = frequency;
                    volume.gain.setValueAtTime(0.0001, start);
                    volume.gain.exponentialRampToValueAtTime(0.25, start + 0.02);
                    volume.gain.exponentialRampToValueAtTime(0.0001, start + 0.6);
                    oscillator.connect(volume);
                    volume.connect(context.destination);
                    oscillator.start(start);
                    oscillator.stop(start + 0.65);
                });

                window.setTimeout(() => context.close(), 1000);
            }

            enableButton.addEventListener('click', function() {
                localStorage.setItem(announcementsEnabledKey, 'true');
                enableButton.disabled = true;
                enableButton.textContent = 'Announcements enabled';
                enableButton.classList.replace('bg-orange-600', 'bg-green-700');
                enableButton.classList.replace('hover:bg-orange-700', 'hover:bg-green-800');

                if ('speechSynthesis' in window && 'SpeechSynthesisUtterance' in window) {
                    window.speechSynthesis.cancel();
                    window.speechSynthesis.speak(new SpeechSynthesisUtterance('ICTO live monitor announcements enabled.'));
                } else {
                    enableButton.textContent = 'Voice announcements unavailable';
                }
                announceNewTickets();
            });

            if (localStorage.getItem(announcementsEnabledKey) === 'true') {
                enableButton.disabled = true;
                enableButton.textContent = 'Announcements enabled';
                enableButton.classList.replace('bg-orange-600', 'bg-green-700');
                enableButton.classList.replace('hover:bg-orange-700', 'hover:bg-green-800');
                announceNewTickets();
            } else if (!previousIds) {
                localStorage.setItem(ticketStateKey, currentAssignments.join('|'));
            }

            window.setTimeout(() => window.location.reload(), 5000);
        });
    </script>

</body>
</html>