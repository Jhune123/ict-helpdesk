<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center" 
         style="background-image: url('{{ asset('image/school-logo.jpg') }}');">
        <div class="bg-white/90 p-10 rounded-2xl shadow-2xl w-full max-w-md text-center">

            <!-- ICTO Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('image/icto-logo.png') }}" alt="ICTO Logo" class="h-24 w-auto">
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-800 mb-1">
                KSU ICTO-HELPDESK Management System
            </h1>

            <!-- Tagline -->
            <p class="text-sm text-gray-600 mb-6">
                Empowering ICT Services for KSU
            </p>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" 
                        type="text" name="name" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" 
                        type="email" name="email" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                        type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                        type="password" name="password_confirmation" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end mt-6">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900"
                       href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-primary-button class="ms-3">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
