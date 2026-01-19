<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center"
         style="background-image: url('{{ asset('image/school-logo.jpg') }}');">

        <div class="bg-white/90 p-10 rounded-2xl shadow-2xl w-full max-w-md text-center">

            <!-- ICTO Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('image/icto-logo.png') }}"
                     alt="ICTO Logo"
                     class="h-24 w-auto">
            </div>

            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-800 mb-1">
                KSU ICTO-HELPDESK Management System
            </h1>

            <p class="text-sm text-gray-600 mb-6">
                Empowering ICT Services for KSU
            </p>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="text-left">
                    <x-input-label for="email" value="Email" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4 text-left">
                    <x-input-label for="password" value="Password" />

                    <div class="relative">
                        <x-text-input
                            id="password"
                            class="block mt-1 w-full pr-10"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password" />

                        <!-- 👁 Toggle Password -->
                        <button type="button"
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-600 hover:text-gray-800 focus:outline-none">
                            <span id="eyeIcon">👁</span>
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4 text-left">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me"
                               type="checkbox"
                               name="remember"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ms-2 text-sm text-gray-600">
                            Remember me
                        </span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between mt-6">
                    <div class="flex flex-col gap-2 text-left">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="underline text-sm text-gray-600 hover:text-gray-900">
                                Forgot your password?
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="underline text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                New user? Register a new account
                            </a>
                        @endif
                    </div>

                    <x-primary-button>
                        Log in
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <!-- 👁 PASSWORD TOGGLE SCRIPT -->
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '👁‍🗨';
            } else {
                input.type = 'password';
                icon.textContent = '👁';
            }
        }
    </script>
</x-guest-layout>
