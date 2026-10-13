<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'KSU OJT Portal & ICT Helpdesk') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js CDN Fallback (Ensures sidebar & modals work seamlessly) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @yield('styles')
    @yield('header')
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col">
        {{-- Navigation Header --}}
        @include('layouts.navigation')

        <div class="flex flex-1 overflow-hidden">
            {{-- Left Sidebar --}}
            <aside class="hidden md:flex md:flex-shrink-0 w-64 flex-col border-r border-green-800 bg-green-900 text-white">
                <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                    <div class="flex-grow flex flex-col">
                        <nav class="flex-1 px-3 space-y-6" aria-label="Sidebar">
                            <div>
                                <h1 class="px-3 text-xs font-bold text-green-300 uppercase tracking-wider mb-3">
                                    📂 ICTO Service Pillars
                                </h1>
                                
                                <div class="space-y-1">
                                    {{-- DYNAMIC CATEGORIES LOOP --}}
                                    @php
                                        $categories = \App\Models\Category::orderBy('name')->get();
                                    @endphp

                                    @forelse($categories as $category)
                                        <div x-data="{ open: false }">
                                            <button @click="open = !open" 
                                                    class="w-full group flex items-center justify-between px-3 py-2 text-xs font-semibold rounded-lg text-green-100 hover:bg-green-800 hover:text-white transition">
                                                <span class="flex items-center gap-2 truncate">
                                                    <span>📁</span> 
                                                    <span class="truncate">{{ $category->name }}</span>
                                                </span>
                                                <svg :class="open ? 'rotate-90' : ''" class="h-4 w-4 transform transition-transform text-green-300" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            
                                            <div x-show="open" x-cloak class="pl-8 space-y-1 mt-1">
                                                <a href="{{ route('categories.show', $category->id) }}" 
                                                   class="block py-1.5 px-2 text-[11px] font-medium text-green-200 hover:text-white hover:bg-green-800/60 rounded-md transition">
                                                    View {{ $category->name }} Tickets →
                                                </a>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-green-300 italic px-3">No categories found.</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Navigation Direct Links --}}
                            <div class="pt-4 border-t border-green-800 space-y-1">
                                <h1 class="px-3 text-xs font-bold text-green-300 uppercase tracking-wider mb-2">
                                    ⚙️ Quick Navigation
                                </h1>
                                <a href="{{ route('ojt.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-green-100 hover:bg-green-800 rounded-lg transition">
                                    🕒 OJT Attendance
                                </a>
                                <a href="{{ route('tickets.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-green-100 hover:bg-green-800 rounded-lg transition">
                                    🎫 Helpdesk Tickets
                                </a>
                                <a href="{{ route('assets.index') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-green-100 hover:bg-green-800 rounded-lg transition">
                                    💻 Assets & Equipment
                                </a>
                            </div>
                        </nav>
                    </div>
                </div>
            </aside>

            {{-- Main Content Area --}}
            <div class="flex-1 min-w-0 bg-gray-50 overflow-y-auto">
                <main class="py-6 px-4 sm:px-6 lg:px-8">
                    @yield('content')
                    {{ $slot ?? '' }}
                </main>
            </div>
        </div>
    </div>

    {{-- Form Protection Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', (e) => {
                    if (!form.checkValidity()) return; // Don't disable if HTML5 validation fails

                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.dataset.noDisable) {
                        setTimeout(() => {
                            submitBtn.disabled = true;
                            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                            submitBtn.innerText = '⏳ Processing...';
                        }, 50);
                    }
                });
            });
        });
    </script>

    @yield('scripts')
</body>
</html>