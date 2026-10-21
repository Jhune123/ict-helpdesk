@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/60 px-4 py-8">
    <div class="mx-auto max-w-3xl">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-black text-gray-900">Edit OJT Intern</h1>
                <p class="mt-1 text-sm text-gray-600">Update the intern's account and placement details.</p>
            </div>
            <a href="{{ route('ojt.dashboard') }}" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50">
                Back to OJT Dashboard
            </a>
        </div>

        <form action="{{ route('ojt.update-intern', $user) }}" method="POST" class="space-y-5 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full rounded-lg border-gray-300">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="mt-1 w-full rounded-lg border-gray-300">
                    @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="student_id" class="block text-sm font-semibold text-gray-700">Student ID</label>
                    <input id="student_id" name="student_id" value="{{ old('student_id', $user->student_id) }}" class="mt-1 w-full rounded-lg border-gray-300">
                    @error('student_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="department_id" class="block text-sm font-semibold text-gray-700">Department</label>
                    <select id="department_id" name="department_id" class="mt-1 w-full rounded-lg border-gray-300">
                        <option value="">Select a department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected((string) old('department_id', $user->department_id) === (string) $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="required_hours" class="block text-sm font-semibold text-gray-700">Required OJT Hours</label>
                    <input id="required_hours" name="required_hours" type="number" min="0" step="1" value="{{ old('required_hours', $user->required_hours ?? 486) }}" required class="mt-1 w-full rounded-lg border-gray-300">
                    @error('required_hours') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end border-t border-gray-100 pt-4">
                <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-indigo-700">
                    Save Intern Details
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
