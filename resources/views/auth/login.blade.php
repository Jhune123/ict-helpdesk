<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 relative"
         style="background: #f3f4f6; position: relative; overflow: hidden;">
        
        <div class="absolute inset-0 z-0 opacity-10 pointer-events-none" 
             style="background-image: url('{{ asset('image/school-logo.jpg') }}'); background-size: cover; background-position: center; opacity: 0.1;">
        </div>

        <div class="relative z-10 w-full sm:max-w-md px-6 py-8 bg-white shadow-2xl rounded-2xl border border-gray-200"
             style="background-color: white; max-width: 450px; width: 90%; margin: 20px auto; padding: 30px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            
            <div class="flex justify-center items-center gap-4 mb-6" style="display: flex; justify-content: center; align-items: center; gap: 15px; margin-bottom: 20px;">
                <img src="{{ asset('image/school-logo.jpg') }}" 
                     alt="KSU Logo" 
                     class="h-20 w-auto object-contain hover:scale-105 transition-transform"
                     style="height: 80px; width: auto; object-fit: contain;">

                <div class="h-12 w-px bg-gray-300" style="height: 50px; width: 1px; background-color: #d1d5db;"></div>

                <img src="{{ asset('image/icto-logo.png') }}" 
                     alt="ICTO Logo" 
                     class="h-20 w-auto object-contain hover:scale-105 transition-transform"
                     style="height: 80px; width: auto; object-fit: contain;">
            </div>

            <div class="text-center mb-6" style="text-align: center; margin-bottom: 20px;">
                <h2 class="text-2xl font-bold text-gray-800" style="font-size: 1.5rem; font-weight: bold; color: #1f2937;">
                    ICTO Help Desk
                </h2>
                <p class="text-sm text-gray-500 mt-1" style="color: #6b7280; font-size: 0.875rem;">
                    Kalinga State University
                </p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4" style="margin-bottom: 1rem;">
                    <label for="email" class="block font-medium text-sm text-gray-700" style="display: block; font-weight: 500; margin-bottom: 5px;">Email</label>
                    <input id="email" 
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border" 
                           style="width: 100%; padding: 8px; border: 1px solid #d1d5db; border-radius: 6px;"
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" style="color: red; font-size: 0.8rem;" />
                </div>

                <div class="mt-4" style="margin-top: 1rem;">
                    <label for="password" class="block font-medium text-sm text-gray-700" style="display: block; font-weight: 500; margin-bottom: 5px;">Password</label>
                    
                    <div class="relative mt-1" style="position: relative;">
                        <input id="password" 
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2 border pr-10" 
                               style="width: 100%; padding: 8px; padding-right: 40px; border: 1px solid #d1d5db; border-radius: 6px;"
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password" />
                        
                        <button type="button" onclick="togglePassword()" 
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500"
                                style="position: absolute; top: 0; right: 0; height: 100%; padding: 0 10px; background: none; border: none; cursor: pointer; color: #6b7280;">
                            <span id="eyeIcon">👁</span>
                        </button>
                    </div>

                    <x-input-error :messages="$errors->get('password')" class="mt-2" style="color: red; font-size: 0.8rem;" />
                </div>

                <div class="flex items-center justify-between mt-4" style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600" style="margin-left: 5px; font-size: 0.875rem;">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}" style="font-size: 0.875rem; color: #4b5563;">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <div class="mt-6" style="margin-top: 1.5rem;">
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            style="width: 100%; background-color: #4f46e5; color: white; padding: 10px; border-radius: 6px; border: none; cursor: pointer; font-weight: 600;">
                        Log in
                    </button>
                </div>
                
                @if (Route::has('register'))
                <div class="mt-6 text-center border-t border-gray-100 pt-4" style="margin-top: 1.5rem; text-align: center; border-top: 1px solid #f3f4f6; padding-top: 1rem;">
                    <p class="text-sm text-gray-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800" style="color: #4f46e5; font-weight: bold; text-decoration: none;">
                            Register here
                        </a>
                    </p>
                </div>
                @endif
            </form>
        </div>
        
        <div class="mt-8 text-center text-xs text-gray-400 relative z-10" style="margin-top: 2rem; text-align: center; color: #9ca3af; font-size: 0.75rem;">
            &copy; {{ date('Y') }} Kalinga State University - ICTO
        </div>
    </div>

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