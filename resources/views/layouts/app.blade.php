<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ICT Ticketing') }}</title>

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Vite (LOAD EARLY & DEFERRED) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Page-specific styles -->
    @yield('styles')
</head>

<body class="font-sans antialiased bg-gray-100">

    @include('layouts.navigation')

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main class="py-6">
        @yield('content')
    </main>

    <!-- 🔥 GLOBAL JS FIXES -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* ✅ Prevent double-click on buttons */
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    form.querySelectorAll('button[type="submit"]').forEach(btn => {
                        btn.disabled = true;
                        btn.classList.add('opacity-60', 'cursor-not-allowed');
                        btn.innerText = 'Processing...';
                    });
                });
            });

            /* ✅ Ensure links trigger immediately */
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    link.style.pointerEvents = 'none';
                });
            });

        });
    </script>

    <!-- Page-specific Scripts -->
    @yield('scripts')

</body>
</html>
