<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ICT Ticketing') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    @yield('header')
</head>
<body class="font-sans antialiased bg-green-900">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        <div class="flex flex-1 overflow-hidden">
            <aside class="hidden md:flex md:flex-shrink-0 w-64 flex-col border-r border-green-700 bg-green-800">
                <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                    <div class="flex-grow flex flex-col">
                        <nav class="flex-1 px-2 space-y-8 bg-green-800" aria-label="Sidebar">
                            <div>
                                <h1 class="px-5 text-s font-bold text-green-300 uppercase tracking-wider">
                                    ICTO Service Pillars (Archives)
                                </h1>
                                <div class="mt-1 space-y-1">
                                    
                                    {{-- ✅ DYNAMIC LOOP: Automatically pulls all categories from the database --}}
                                    @foreach(\App\Models\Category::orderBy('name')->get() as $category)
                                        <div x-data="{ open: false }">
                                            <button @click="open = !open" class="w-full group flex items-center px-3 py-2 text-sm font-medium rounded-md text-green-50 hover:bg-green-700 hover:text-white">
                                                <span class="mr-3">📁</span> {{ $category->name }}
                                                <svg :class="open ? 'rotate-90' : ''" class="ml-auto h-5 w-5 transform transition-transform text-green-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                                            </button>
                                            <div x-show="open" class="pl-10 space-y-1 mt-1">
                                                {{-- Links dynamically to the category's slug --}}
                                                <a href="{{ route('categories.show', $category->slug) }}" class="block py-2 text-xs text-green-200 hover:text-white">
                                                    Resolved & Condemned
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </aside>

            <div class="flex-1 min-w-0 bg-green-50">
                <main class="py-6 px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerText = 'Processing...';
                    }
                });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>