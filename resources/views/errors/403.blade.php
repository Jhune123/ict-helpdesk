
@extends('layouts.app')

@section('title', 'Access Denied | KSU ICTO-HELPDESK')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-green-50 to-white px-6">
    
    <div class="text-center">
        <!-- Error Icon -->
        <div class="mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-green-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728M5.636 5.636l12.728 12.728" />
            </svg>
        </div>

        <!-- Error Title -->
        <h1 class="text-5xl font-bold text-green-800 mb-4">403</h1>
        <h2 class="text-2xl font-semibold text-gray-700 mb-2">Access Denied</h2>

        <!-- Message -->
        <p class="text-gray-600 mb-8">
            You are not authorized to perform this action.<br>
            Please contact the ICT Office if you believe this is an error.
        </p>

        <!-- Back Button -->
        <a href="{{ route('dashboard') }}"
           class="bg-green-700 hover:bg-green-800 text-white font-semibold py-2 px-6 rounded-full shadow-md transition">
            ⬅️ Return to Dashboard
        </a>
    </div>

    <!-- Footer -->
    <footer class="mt-16 text-center text-gray-600 text-sm">
        <p class="mb-1 font-semibold text-green-700">Kalinga State University</p>
        <p>📧 ksumail@ksu.edu.ph | 🌐 <a href="https://ksu.edu.ph" class="text-green-700 hover:underline">https://ksu.edu.ph</a></p>
        <p class="mt-2">&copy; 2025. All Rights Reserved. ICT Office, KSU-Main Campus.</p>
    </footer>
</div>
@endsection
