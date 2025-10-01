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

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full"
                        type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full"
                        type="password" name="password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4 text-left">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between mt-6">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900"
                           href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button>
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
