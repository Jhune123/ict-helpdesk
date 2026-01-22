@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4 text-green-700">➕ Create Meeting</h2>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <strong>⚠️ Please fix the following errors:</strong>
            <ul class="list-disc list-inside mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('meetings.store') }}" method="POST">
        @csrf

        {{-- Basic Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium text-gray-700">Title</label>
                <input type="text" name="title"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                       value="{{ old('title') }}" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Date</label>
                <input type="date" name="date"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                       value="{{ old('date') }}" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">Start Time</label>
                <input type="time" name="start_time"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                       value="{{ old('start_time') }}" required>
            </div>

            <div>
                <label class="block font-medium text-gray-700">End Time</label>
                <input type="time" name="end_time"
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                       value="{{ old('end_time') }}" required>
            </div>
        </div>

        {{-- Location --}}
        <div class="mt-4">
            <label class="block font-medium text-gray-700">Location</label>
            <input type="text" name="location"
                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                   value="{{ old('location') }}" required>
        </div>

        {{-- Facilitator --}}
        <div class="mt-4">
            <label class="block font-medium text-gray-700">Facilitator</label>
            <input type="text" name="facilitator"
                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500"
                   value="{{ old('facilitator') }}">
        </div>

        {{-- Participants --}}
        <div class="mt-4">
            <label class="block font-medium text-gray-700">Participants</label>
            <textarea name="participants" rows="3"
                      class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500">{{ old('participants') }}</textarea>
        </div>

        {{-- Remarks --}}
        <div class="mt-4">
            <label class="block font-medium text-gray-700">Remarks</label>
            <textarea name="remarks" rows="3"
                      class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500">{{ old('remarks') }}</textarea>
        </div>

        {{-- IT Personnel --}}
        @role('admin|it_staff')
        <div class="mt-4">
            <label class="block font-medium text-gray-700">IT Personnel Attendees</label>
            <select name="it_personnels[]" multiple
                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500">
                @foreach($itPersonnels as $personnel)
                    <option value="{{ $personnel->id }}"
                        {{ collect(old('it_personnels'))->contains($personnel->id) ? 'selected' : '' }}>
                        {{ $personnel->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-sm text-gray-500 mt-1">
                Hold <strong>Ctrl</strong> (Windows) or <strong>Cmd</strong> (Mac) to select multiple.
            </p>
        </div>
        @endrole

        {{-- Actions --}}
        <div class="flex justify-end space-x-2 mt-6">
            <a href="{{ route('meetings.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
               ⬅ Cancel
            </a>
            <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                ✅ Save Meeting
            </button>
        </div>
    </form>
</div>
@endsection
