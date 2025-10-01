<!-- resources/views/layouts/navigation.blade.php -->
<nav x-data="{ open: false, notifyOpen: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left: Logo + Nav -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-10 w-auto fill-current text-gray-600"/>
                </a>
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                    <x-nav-link :href="route('tickets.index')" :active="request()->routeIs('tickets.*')">Tickets</x-nav-link>
                    <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">Task Schedule</x-nav-link>
                    <x-nav-link :href="route('meetings.index')" :active="request()->routeIs('meetings.*')">Meeting Schedules</x-nav-link>
                </div>
            </div>

            <!-- Right: Gold Bell + User -->
            <div class="flex items-center space-x-6">
                <!-- Gold Bell Notification -->
                <div class="relative">
                    <button @click="notifyOpen = !notifyOpen" class="relative focus:outline-none">
                        <!-- Gold Bell Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6 text-yellow-500 hover:text-yellow-600 transition"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002
                                     6.002 0 00-4-5.659V5a2 2 0 10-4
                                     0v.341C7.67 6.165 6 8.388 6
                                     11v3.159c0 .538-.214 1.055-.595
                                     1.436L4 17h5m6 0v1a3 3 0
                                     11-6 0v-1m6 0H9"/>
                        </svg>
                        <!-- Red badge -->
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center
                                         px-1.5 py-0.5 text-xs font-bold leading-none text-white
                                         bg-red-600 rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="notifyOpen" x-transition @click.away="notifyOpen = false"
                         class="absolute right-0 mt-2 w-80 bg-white text-gray-800 rounded-lg shadow-lg z-50">
                        <div class="p-3 font-bold border-b">Notifications</div>

                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                            <div class="px-4 py-3 border-b hover:bg-gray-100 cursor-pointer">
                                <a href="{{ route('tickets.show', $notification->data['ticket_id'] ?? '#') }}">
                                    {{ $notification->data['message'] ?? 'New Notification' }}
                                </a>
                                <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="px-4 py-3 text-gray-500 text-sm">No notifications</div>
                        @endforelse

                        <div class="text-center p-2">
                            <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:underline text-sm">View All</a>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293
                                             a1 1 0 111.414 1.414l-4 4a1 1 0
                                             01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>

<!-- AlpineJS -->
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
