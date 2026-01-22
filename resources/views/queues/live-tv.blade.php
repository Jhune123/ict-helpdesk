<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MIS Queuing System – Live TV</title>

    <!-- Auto-refresh every 5 seconds -->
    <meta http-equiv="refresh" content="3">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-white w-screen h-screen flex flex-col justify-between overflow-hidden">

    <!-- HEADER -->
    <div class="text-center py-8">

        <h1 class="text-[5vw] font-extrabold tracking-wide">
            ICTO–MIS ENROLLMENT CONCERN
        </h1>

        <!-- DATE & TIME (CENTERED, PH TIME) -->
        <div class="mt-3">
            <div id="currentDate" class="text-[1.6vw] font-semibold text-gray-300"></div>
            <div id="currentTime" class="text-[2.2vw] font-bold text-yellow-400"></div>
        </div>
    </div>

    <!-- COUNTERS -->
    <div class="flex-1 flex items-center justify-center px-8">
        @if(isset($servingJhune) || isset($servingReymar))
            <div class="grid {{ isset($servingJhune) && isset($servingReymar) ? 'grid-cols-2' : 'grid-cols-1' }} gap-12 w-full h-full items-center">

                {{-- COUNTER 1 --}}
                @if(isset($servingJhune))
                <div class="border-[6px] border-yellow-400 rounded-2xl p-4 text-center h-85 flex flex-col justify-center">
                    <h1 class="text-[3.5vw] font-bold mb-1">COUNTER 1</h1>
                    <p class="text-[1.5vw] mb-2">Sir Jhune</p>
                    <div class="text-[4vw] font-extrabold text-yellow-400 mb-2">
                        {{ $servingJhune->queue_number }}
                    </div>
                    <p class="text-[1.5vw] text-gray-300">NOW SERVING</p>
                </div>
                @endif

                {{-- COUNTER 2 --}}
                @if(isset($servingReymar))
                <div class="border-[6px] border-green-400 rounded-2xl p-4 text-center h-85 flex flex-col justify-center">
                    <h1 class="text-[3.5vw] font-bold mb-1">COUNTER 2</h1>
                    <p class="text-[1.5vw] mb-2">Sir Reymar</p>
                    <div class="text-[4vw] font-extrabold text-green-400 mb-2">
                        {{ $servingReymar->queue_number }}
                    </div>
                    <p class="text-[1.5vw] text-gray-300">NOW SERVING</p>
                </div>
                @endif

            </div>
        @else
            <div class="text-center">
                <p class="text-[4vw] text-gray-400">NO QUEUE CURRENTLY SERVING</p>
                <p class="text-[2vw] mt-2 text-gray-500">Please wait…</p>
            </div>
        @endif
    </div>

    <!-- NEXT QUEUE -->
    <div class="bg-gray-900 py-6 text-center">
        <p class="text-[2.5vw] mb-3">NEXT QUEUE</p>

        @if($nextQueues->isNotEmpty())
            <div class="flex justify-center gap-6">
                @foreach($nextQueues as $queue)
                    <span class="text-[4vw] font-bold text-blue-400">
                        {{ $queue->queue_number }}
                    </span>
                @endforeach
            </div>
        @else
            <div class="text-[3vw] text-gray-500">---</div>
        @endif
    </div>

    <!-- LIVE DATE & TIME (PHILIPPINE TIMEZONE LOCK) -->
    <script>
        function updateDateTime() {
            const now = new Date(
                new Date().toLocaleString("en-US", { timeZone: "Asia/Manila" })
            );

            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            const timeOptions = {
                hour: 'numeric',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };

            document.getElementById('currentDate').textContent =
                now.toLocaleDateString('en-US', dateOptions);

            document.getElementById('currentTime').textContent =
                now.toLocaleTimeString('en-US', timeOptions);
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>

</body>
</html>
