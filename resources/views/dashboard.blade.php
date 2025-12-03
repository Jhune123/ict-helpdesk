@extends('layouts.app')

@section('content')
<!-- Full-page background -->
<div class="min-h-screen flex flex-col items-center justify-start"
     style="
        background-image: url('{{ asset('image/school-logo.jpg') }}');
        background-size: 70%;
        background-repeat: no-repeat;
        background-position: center;
     ">

    <!-- Header / Title -->
    <div class="text-center mt-10 mb-8">
        <img src="{{ asset('image/icto-logo.png') }}" alt="ICTO Logo" class="mx-auto w-32 h-32 drop-shadow-lg">
        <h1 class="text-4xl font-bold text-white mt-4 drop-shadow-md">
            KSU ICTO-HELPDESK Management System
        </h1>
        <p class="text-white mt-2 text-lg drop-shadow-sm">
            Manage your IT requests efficiently
        </p>
        <p class="text-white mt-1 font-semibold">
            {{ \Carbon\Carbon::now()->format('l, F d, Y') }}
        </p>
    </div>

    <!-- Dashboard Buttons -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6 px-6 mb-10 w-full max-w-6xl">
        <a href="{{ route('tickets.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-6 px-4 rounded-lg shadow-lg text-center transition transform hover:scale-105">
            + Create Ticket
        </a>

        <a href="{{ route('tickets.mine') }}"
           class="bg-green-600 hover:bg-green-700 text-white font-bold py-6 px-4 rounded-lg shadow-lg text-center transition transform hover:scale-105">
            My Tickets
        </a>

        <a href="{{ route('categories.index') }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-6 px-4 rounded-lg shadow-lg text-center transition transform hover:scale-105">
            Categories
        </a>

        <a href="{{ route('tickets.departments') }}"
           class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-6 px-4 rounded-lg shadow-lg text-center transition transform hover:scale-105">
            Departments
        </a>

        <!-- Client Feedback Button -->
        <a href="{{ route('feedbacks.index') }}"
           class="bg-pink-500 hover:bg-pink-600 text-white font-bold py-6 px-4 rounded-lg shadow-lg text-center transition transform hover:scale-105">
            Client Feedback
        </a>

        <a href="{{ route('analytics.index') }}"
           class="bg-red-500 hover:bg-red-600 text-white font-bold py-6 px-4 rounded-lg shadow-lg text-center transition transform hover:scale-105">
            Analytics Dashboard
        </a>
    </div>

    <!-- Optional: Notifications Bell -->
    @auth
    <div class="absolute top-6 right-6">
        <a href="#" class="relative inline-block">
            🔔
            @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ auth()->user()->unreadNotifications->count() }}
                </span>
            @endif
        </a>
    </div>
    @endauth
</div>

<!-- 🌤️ Light Transparent Footer -->
<footer class="fixed bottom-0 left-0 w-full backdrop-blur-xl bg-white/30 border-t border-gray-300 text-gray-800 py-4 shadow-md">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <p class="text-base font-semibold mb-1 flex items-center justify-center gap-2">
            🏢 ICT Office, KSU - Main Campus
        </p>
        <p class="text-sm mb-1">
            ✉️ <a href="mailto:ksumail@ksu.edu.ph" class="hover:text-blue-700">ksumail@ksu.edu.ph</a> |
            🌐 <a href="https://ksu.edu.ph" target="_blank" class="hover:text-blue-700">https://ksu.edu.ph</a>
        </p>
        <p class="text-xs text-gray-700 mt-1">© 2025. All Rights Reserved.</p>
    </div>
</footer>
@endsection
