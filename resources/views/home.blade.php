<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-cover bg-center" 
         style="background-image: url('{{ asset('image/login-bg.jpg') }}');">

        <div class="bg-white bg-opacity-90 shadow-2xl rounded-2xl p-10 w-full max-w-3xl text-center">
            
            {{-- Welcome Message --}}
            <h1 class="text-3xl font-bold text-gray-800 mb-4 drop-shadow">
                🎉 Welcome, {{ Auth::user()->name }}!
            </h1>
            <p class="text-gray-700 mb-8">
                You are now logged in to the <b>ICT Helpdesk System</b>.
            </p>

            {{-- Action Buttons --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('tickets.index') }}"
                   class="px-6 py-4 bg-blue-600 text-white rounded-xl hover:bg-blue-700 shadow-lg transition">
                    📋 View All Tickets
                </a>
                <a href="{{ route('tickets.create') }}"
                   class="px-6 py-4 bg-green-600 text-white rounded-xl hover:bg-green-700 shadow-lg transition">
                    ➕ Create New Ticket
                </a>
                <a href="{{ route('tickets.mine') }}"
                   class="px-6 py-4 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-lg transition">
                    🙋 My Tickets
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
