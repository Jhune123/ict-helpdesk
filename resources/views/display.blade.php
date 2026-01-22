<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KSU Cashier Queue Display</title>

    <!-- Tailwind CDN (safe for TV display) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Auto Refresh every 5 seconds -->
    <meta http-equiv="refresh" content="5">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .blink {
            animation: blink 1.2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
    </style>
</head>

<body class="bg-gray-900 text-white min-h-screen">

    <!-- HEADER -->
    <div class="bg-blue-800 py-6 text-center shadow-lg">
        <h1 class="text-5xl font-extrabold tracking-wide">
            KSU CASHIER QUEUING SYSTEM
        </h1>
        <p class="text-xl mt-2">
            Please wait for your number to be called
        </p>
    </div>

    <!-- DATE & TIME -->
    <div class="flex justify-center mt-4">
        <div class="bg-gray-800 px-6 py-2 rounded-lg text-xl font-semibold">
            <span id="dateTime"></span>
        </div>
    </div>

    <!-- MAIN DISPLAY -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 px-12 py-12">

        <!-- NOW SERVING -->
        <div class="bg-green-700 rounded-3xl shadow-2xl p-10 text-center fade-in">
            <h2 class="text-4xl font-bold mb-6">
                NOW SERVING
            </h2>

            @if($currentQueue)
                <div class="text-9xl font-extrabold tracking-widest blink">
                    {{ $currentQueue->queue_number }}
                </div>

                <div class="mt-6 text-3xl font-semibold">
                    Window {{ $currentQueue->window_number }}
                </div>
            @else
                <div class="text-6xl font-bold text-yellow-300">
                    ---
                </div>
                <p class="mt-4 text-2xl">Waiting for next customer</p>
            @endif
        </div>

        <!-- NEXT QUEUES -->
        <div class="bg-gray-800 rounded-3xl shadow-2xl p-10 fade-in">
            <h2 class="text-4xl font-bold mb-6 text-center">
                NEXT IN LINE
            </h2>

            <ul class="space-y-4 text-3xl">
                @forelse($nextQueues as $queue)
                    <li class="flex justify-between border-b border-gray-600 pb-2">
                        <span class="font-bold">
                            {{ $queue->queue_number }}
                        </span>
                        <span class="text-gray-300">
                            Window {{ $queue->window_number ?? '—' }}
                        </span>
                    </li>
                @empty
                    <li class="text-center text-gray-400 text-2xl">
                        No pending queues
                    </li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="bg-gray-800 text-center py-4 text-lg text-gray-300">
        © {{ date('Y') }} Kalinga State University | ICT Office
    </div>

    <!-- LIVE CLOCK -->
    <script>
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('dateTime').innerText =
                now.toLocaleDateString('en-US', options);
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);
    </script>

</body>
</html>
