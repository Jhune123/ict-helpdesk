@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-6 mt-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
        ✏️ Edit Meeting
    </h2>

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

    <form action="{{ route('meetings.update', $meeting->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 font-medium mb-1">Title</label>
            <input type="text" name="title"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                value="{{ old('title', $meeting->title) }}" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-700 font-medium mb-1">Date</label>
                <input type="date" name="date"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                    value="{{ old('date', $meeting->date) }}" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">Start Time</label>
                <input type="time" name="start_time"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                    value="{{ old('start_time', $meeting->start_time) }}" required>
            </div>

            <div>
                <label class="block text-gray-700 font-medium mb-1">End Time</label>
                <input type="time" name="end_time"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                    value="{{ old('end_time', $meeting->end_time) }}" required>
            </div>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Location</label>
            <input type="text" name="location"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                value="{{ old('location', $meeting->location) }}" required>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Facilitator</label>
            <input type="text" name="facilitator"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                value="{{ old('facilitator', $meeting->facilitator) }}">
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Participants</label>
            <textarea name="participants"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 h-24 resize-y focus:ring focus:ring-blue-200 focus:border-blue-500"
                placeholder="List of participants...">{{ old('participants', $meeting->participants) }}</textarea>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">Remarks</label>
            <textarea name="remarks"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 h-20 resize-y focus:ring focus:ring-blue-200 focus:border-blue-500"
                placeholder="Additional notes or updates...">{{ old('remarks', $meeting->remarks) }}</textarea>
        </div>

        <div>
            <label class="block text-gray-700 font-medium mb-1">IT Personnel Attendees</label>
            <select name="it_personnels[]" id="it_personnels"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500 min-h-[120px]"
                multiple>
                @foreach($itPersonnels as $personnel)
                    <option value="{{ $personnel->id }}"
                        {{ in_array($personnel->id, old('it_personnels', $meeting->itPersonnel->pluck('id')->toArray())) ? 'selected' : '' }}>
                        {{ $personnel->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-sm text-gray-500 mt-1">
                Hold <strong>Ctrl</strong> (Windows) or <strong>Cmd</strong> (Mac) to select multiple attendees.
            </p>
        </div>

        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('meetings.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white font-medium px-5 py-2.5 rounded-lg shadow-md transition duration-150">
                Cancel
            </a>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg shadow-md transition duration-150">
                💾 Update Meeting
            </button>
        </div>
    </form>
</div>
@endsection