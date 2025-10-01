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
        <h1 class="text-4xl font-bold text-white mt-4">KSU ICTO-HELPDESK Management System</h1>
        <p class="text-white mt-2 text-lg">Manage your IT requests efficiently</p>
        <p class="text-white mt-1 font-semibold">{{ \Carbon\Carbon::now()->format('l, F d, Y') }}</p>
    </div>

    <!-- Dashboard Buttons -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 px-6 mb-10 w-full max-w-6xl">

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

        <!-- ✅ Updated to tickets.departments -->
        <a href="{{ route('tickets.departments') }}"
           class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-6 px-4 rounded-lg shadow-lg text-center transition transform hover:scale-105">
            Departments
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
@endsection
