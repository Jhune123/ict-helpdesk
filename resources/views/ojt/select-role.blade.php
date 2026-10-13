@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] bg-gray-50/50 flex items-center justify-center p-4 sm:p-6">
    <div class="max-w-lg w-full bg-white rounded-3xl shadow-2xl p-6 sm:p-8 border border-gray-100 space-y-6">
        
        {{-- Header Section --}}
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-700 text-3xl mb-1 shadow-inner">
                🎓
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">
                Welcome to KSU OJT Portal
            </h2>
            <p class="text-sm text-gray-600 font-medium">
                Please select your portal role and department to continue
            </p>
        </div>

        {{-- Role & Department Form --}}
        <form method="POST" action="{{ route('ojt.select-role') }}" class="space-y-6">
            @csrf

            {{-- 1. Role Selection --}}
            <div>
                <label class="block text-xs font-black uppercase text-gray-500 tracking-wider mb-3">
                    1. Choose Account Role <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    
                    {{-- OJT Intern --}}
                    <label class="relative border-2 border-gray-200 rounded-2xl p-4 text-center cursor-pointer hover:border-emerald-500 transition-all duration-200 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/80 has-[:checked]:ring-2 has-[:checked]:ring-emerald-500/20 shadow-sm">
                        <input type="radio" name="role" value="ojt" class="sr-only" required checked>
                        <span class="text-3xl block mb-1">👨‍🎓</span>
                        <span class="text-sm font-extrabold text-gray-900 block">OJT Intern</span>
                        <span class="text-xs font-medium text-gray-500 block mt-1">Clock in/out & track hours</span>
                    </label>

                    {{-- OJT Supervisor --}}
                    <label class="relative border-2 border-gray-200 rounded-2xl p-4 text-center cursor-pointer hover:border-emerald-500 transition-all duration-200 has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/80 has-[:checked]:ring-2 has-[:checked]:ring-emerald-500/20 shadow-sm">
                        <input type="radio" name="role" value="supervisor" class="sr-only" required>
                        <span class="text-3xl block mb-1">👔</span>
                        <span class="text-sm font-extrabold text-gray-900 block">OJT Supervisor</span>
                        <span class="text-xs font-medium text-gray-500 block mt-1">Monitor department interns</span>
                    </label>

                </div>
            </div>

            {{-- 2. Department Selection --}}
            <div>
                <label for="department_id" class="block text-xs font-black uppercase text-gray-500 tracking-wider mb-2">
                    2. Select Department <span class="text-red-500">*</span>
                </label>
                <select name="department_id" id="department_id" required 
                        class="w-full bg-gray-50 border border-gray-300 text-sm font-bold text-gray-800 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm">
                    <option value="" disabled selected>🏢 Choose your department...</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Submit Button --}}
            <button type="submit" 
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-4 px-6 rounded-xl text-sm sm:text-base shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                <span>Confirm & Access Portal</span>
                <span>→</span>
            </button>
        </form>

    </div>
</div>
@endsection