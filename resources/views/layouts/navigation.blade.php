<nav x-data="{ open: false, notifyOpen: false }" class="bg-green-800 border-b border-green-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left Side: Logo & Main Navigation -->
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex-shrink-0 flex items-center">
                    <img src="{{ asset('image/KSU-logo.png') }}" alt="KSU Logo" class="block h-12 w-auto">
                </a>

                <!-- Desktop Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-green-100 hover:text-white">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')" class="text-green-100 hover:text-white">
                        Tickets
                    </x-nav-link>

                    <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')" class="text-green-100 hover:text-white">
                        Task Schedule
                    </x-nav-link>

                    <x-nav-link :href="route('meetings.index')" :active="request()->routeIs('meetings.*')" class="text-green-100 hover:text-white">
                        Meeting Schedules
                    </x-nav-link>

                    @if(Route::has('categories.index'))
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="text-green-100 hover:text-white">
                            Categories
                        </x-nav-link>
                    @endif

                    <x-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.*')" class="text-green-100 hover:text-white">
                        💼 KSU Assets & Equipment
                    </x-nav-link>

                    <x-nav-link :href="route('queues.index')" :active="request()->routeIs('queues.*')" class="text-green-100 hover:text-white">
                        🎫 ICTO Queuing System
                    </x-nav-link>

                    {{-- Safe fallback for OJT Attendance Route --}}
                    @php
                        $ojtRoute = Route::has('ojt.dashboard') 
                            ? route('ojt.dashboard') 
                            : (Route::has('ojt.index') ? route('ojt.index') : '#');
                    @endphp

                    <x-nav-link :href="$ojtRoute" :active="request()->routeIs('ojt.*')" class="text-green-100 hover:text-white">
                        🕒 OJT Attendance
                    </x-nav-link>

                    @role('admin|it_staff')
                        <x-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')" class="text-green-100 hover:text-white">
                            📜 Activity Logs
                        </x-nav-link>
                    @endrole
                </div>
            </div>

            <!-- Right Side: Notifications & Profile Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:space-x-6">
                <!-- Notifications Dropdown -->
                <div class="relative" @click.outside="notifyOpen = false">
                    <button @click="notifyOpen = !notifyOpen" class="relative p-1 text-yellow-400 hover:text-yellow-300 focus:outline-none transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown Panel -->
                    <div x-show="notifyOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl py-2 z-50 text-gray-800 border border-gray-200"
                         style="display: none;">
                        <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                            <span class="font-bold text-sm text-gray-800">Notifications</span>
                            @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full font-medium">
                                    {{ auth()->user()->unreadNotifications->count() }} new
                                </span>
                            @endif
                        </div>

                        <div class="max-h-64 overflow-y-auto">
                            @if(auth()->check() && auth()->user()->notifications->count() > 0)
                                @foreach(auth()->user()->notifications->take(5) as $notification)
                                    @if(!empty($notification->data['ticket_id']))
                                        <a href="{{ $notification->data['action_url'] ?? route('tickets.show', $notification->data['ticket_id']) }}" class="block px-4 py-3 hover:bg-gray-50 border-b border-gray-100 text-xs text-gray-700">
                                            <p class="font-medium text-gray-900">{{ $notification->data['message'] ?? $notification->data['title'] ?? 'New notification' }}</p>
                                            <p class="text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                            @if(!empty($notification->data['action_label']))
                                                <p class="mt-1 font-semibold text-blue-700">{{ $notification->data['action_label'] }}</p>
                                            @endif
                                        </a>
                                    @else
                                        <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 text-xs text-gray-700">
                                            <p class="font-medium text-gray-900">{{ $notification->data['message'] ?? $notification->data['title'] ?? 'New notification' }}</p>
                                            <p class="text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="px-4 py-6 text-center text-xs text-gray-500">
                                    No notifications yet.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-green-100 hover:text-white focus:outline-none transition-colors duration-200">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Button (Mobile) -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-green-200 hover:text-white hover:bg-green-700 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-green-900 border-t border-green-700">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-green-100 hover:text-white">
                Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')" class="text-green-100 hover:text-white">
                Tickets
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')" class="text-green-100 hover:text-white">
                Task Schedule
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('meetings.index')" :active="request()->routeIs('meetings.*')" class="text-green-100 hover:text-white">
                Meeting Schedules
            </x-responsive-nav-link>

            @if(Route::has('categories.index'))
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" class="text-green-100 hover:text-white">
                    Categories
                </x-responsive-nav-link>
            @endif

            <x-responsive-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.*')" class="text-green-100 hover:text-white">
                💼 KSU Assets & Equipment
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('queues.index')" :active="request()->routeIs('queues.*')" class="text-green-100 hover:text-white">
                🎫 ICTO Queuing System
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="$ojtRoute" :active="request()->routeIs('ojt.*')" class="text-green-100 hover:text-white">
                🕒 OJT Attendance
            </x-responsive-nav-link>

            @role('admin|it_staff')
                <x-responsive-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')" class="text-green-100 hover:text-white">
                    📜 Activity Logs
                </x-responsive-nav-link>
            @endrole
        </div>

        <!-- Mobile User Options -->
        <div class="pt-4 pb-1 border-t border-green-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-green-200">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="text-green-100 hover:text-white">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>