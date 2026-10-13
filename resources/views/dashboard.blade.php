@extends('layouts.app')

@section('content')
{{-- Main Dashboard Container --}}
<div class="min-h-screen flex flex-col items-center justify-start relative pb-32"
     style="
        background-image: url('{{ asset('image/school-logo.jpg') }}');
        background-size: 70%;
        background-repeat: no-repeat;
        background-position: center;
     ">

    {{-- Top Left ICTO Logo --}}
    <div class="absolute top-6 left-6 z-10">
        <img src="{{ asset('image/icto-logo.png') }}" alt="ICTO Logo" class="w-24 h-24 sm:w-32 sm:h-32 drop-shadow-lg">
    </div>

    {{-- Header / Welcome Section --}}
    <div class="text-center mt-36 sm:mt-40 mb-10 px-4">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white mt-4 drop-shadow-md tracking-tight">
            KSU ICTO-HELPDESK Management System
        </h1>
        <p class="text-white mt-2 text-base sm:text-lg drop-shadow-sm font-medium">
            Manage your IT requests efficiently
        </p>
        <p class="text-green-100 mt-1 font-semibold text-sm sm:text-base">
            {{ \Carbon\Carbon::now()->format('l, F d, Y') }}
        </p>
    </div>

    {{-- Action Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 px-6 mb-10 w-full max-w-6xl z-10">
        
        {{-- Create Ticket --}}
        <a href="{{ Route::has('tickets.create') ? route('tickets.create') : '#' }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-6 px-4 rounded-xl shadow-lg text-center transition duration-200 transform hover:-translate-y-1 hover:shadow-2xl flex items-center justify-center gap-2">
            ➕ Create Ticket
        </a>

        {{-- My Tickets --}}
        @php
            $myTicketsRoute = Route::has('tickets.mine') 
                ? route('tickets.mine') 
                : (Route::has('tickets.index') ? route('tickets.index') : '#');
        @endphp
        <a href="{{ $myTicketsRoute }}"
           class="bg-green-600 hover:bg-green-700 text-white font-bold py-6 px-4 rounded-xl shadow-lg text-center transition duration-200 transform hover:-translate-y-1 hover:shadow-2xl flex items-center justify-center gap-2">
            🎫 My Tickets
        </a>

        {{-- Categories --}}
        <a href="{{ Route::has('categories.index') ? route('categories.index') : '#' }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-6 px-4 rounded-xl shadow-lg text-center transition duration-200 transform hover:-translate-y-1 hover:shadow-2xl flex items-center justify-center gap-2">
            📂 Categories
        </a>

        {{-- Departments --}}
        <a href="{{ Route::has('departments.index') ? route('departments.index') : '#' }}"
           class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-6 px-4 rounded-xl shadow-lg text-center transition duration-200 transform hover:-translate-y-1 hover:shadow-2xl flex items-center justify-center gap-2">
            🏢 Departments
        </a>

        {{-- Customer Feedback --}}
        <a href="{{ Route::has('feedbacks.index') ? route('feedbacks.index') : '#' }}"
           class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-6 px-4 rounded-xl shadow-lg text-center transition duration-200 transform hover:-translate-y-1 hover:shadow-2xl flex items-center justify-center gap-2">
            ⭐ Client Satisfaction Survey
        </a>

        {{-- Analytics --}}
        <a href="{{ Route::has('analytics.index') ? route('analytics.index') : '#' }}"
           class="bg-red-500 hover:bg-red-600 text-white font-bold py-6 px-4 rounded-xl shadow-lg text-center transition duration-200 transform hover:-translate-y-1 hover:shadow-2xl flex items-center justify-center gap-2">
            📊 Analytics Dashboard
        </a>

        {{-- Condemned Equipment --}}
        <a href="{{ Route::has('condemned-equipment.index') ? route('condemned-equipment.index') : '#' }}"
           class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-6 px-4 rounded-xl shadow-lg text-center transition duration-200 transform hover:-translate-y-1 hover:shadow-2xl flex items-center justify-center gap-2">
            🗑️ Condemned Equipment
        </a>

        {{-- Maintenance --}}
        <a href="{{ Route::has('maintenance.index') ? route('maintenance.index') : '#' }}"
           class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-6 px-4 rounded-xl shadow-lg text-center transition duration-200 transform hover:-translate-y-1 hover:shadow-2xl flex items-center justify-center gap-2">
            🛡️ Preventive Maintenance
        </a>

        {{-- OJT Attendance --}}
        @php
            $ojtRoute = Route::has('ojt.index') 
                ? route('ojt.index') 
                : (Route::has('ojt.dashboard') ? route('ojt.dashboard') : '#');
        @endphp
        <a href="{{ $ojtRoute }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-6 px-4 rounded-xl shadow-lg text-center transition duration-200 transform hover:-translate-y-1 hover:shadow-2xl flex items-center justify-center gap-2">
            🕒 OJT Attendance
        </a>
    </div>

    {{-- Top Right Notifications Icon --}}
    @auth
    <div class="absolute top-6 right-6 z-10">
        <a href="{{ Route::has('notifications.index') ? route('notifications.index') : '#' }}" class="relative inline-block text-2xl transition hover:scale-110">
            🔔
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-md animate-pulse">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </a>
    </div>
    @endauth
</div>

{{-- Fixed Footer --}}
<footer class="fixed bottom-0 left-0 w-full backdrop-blur-xl bg-white/80 border-t border-gray-300 text-gray-800 py-3 shadow-md z-30">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <p class="text-sm sm:text-base font-bold mb-0.5 flex items-center justify-center gap-2 text-green-900">
            🏢 ICT Office, KSU - Main Campus
        </p>
        <p class="text-xs sm:text-sm text-gray-600 mb-0.5">
            ✉️ <a href="mailto:ksumail@ksu.edu.ph" class="hover:text-green-700 underline transition">ksumail@ksu.edu.ph</a> |
            🌐 <a href="https://ksu.edu.ph" target="_blank" rel="noopener noreferrer" class="hover:text-green-700 underline transition">https://ksu.edu.ph</a>
        </p>
        <p class="text-xs text-gray-500">© 2025 Kalinga State University. All Rights Reserved.</p>
    </div>
</footer>
@endsection