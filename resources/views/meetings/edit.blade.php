@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl p-6 mt-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
        ✏️ Edit Meeting
    </h2>

    <form action="{{ route('meetings.update', $meeting->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Title</label>
            <input type="text" name="title"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                value="{{ old('title', $meeting->title) }}" required>
        </div>

        <!-- Date -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Date</label>
            <input type="date" name="date"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                value="{{ old('date', $meeting->date) }}" required>
        </div>

        <!-- Start Time -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Start Time</label>
            <input type="time" name="start_time"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                value="{{ old('start_time', $meeting->start_time) }}" required>
        </div>

        <!-- End Time -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">End Time</label>
            <input type="time" name="end_time"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                value="{{ old('end_time', $meeting->end_time) }}" required>
        </div>

        <!-- Location -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Location</label>
            <input type="text" name="location"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                value="{{ old('location', $meeting->location) }}" required>
        </div>

        <!-- Facilitator -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Facilitator</label>
            <input type="text" name="facilitator"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                value="{{ old('facilitator', $meeting->facilitator) }}">
        </div>

        <!-- Participants -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Participants</label>
            <textarea name="participants"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 h-24 resize-y focus:ring focus:ring-blue-200 focus:border-blue-500"
                placeholder="List of participants...">{{ old('participants', $meeting->participants) }}</textarea>
        </div>

        <!-- Remarks -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">Remarks</label>
            <textarea name="remarks"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 h-20 resize-y focus:ring focus:ring-blue-200 focus:border-blue-500"
                placeholder="Additional notes or updates...">{{ old('remarks', $meeting->remarks) }}</textarea>
        </div>

        <!-- IT Personnel Attendees -->
        <div>
            <label class="block text-gray-700 font-medium mb-1">IT Personnel Attendees</label>
            <select name="it_personnels[]" id="it_personnels"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-500"
                multiple>
                @foreach($itPersonnels as $personnel)
                    <option value="{{ $personnel->id }}"
                        @if($meeting->itPersonnels->contains($personnel->id)) selected @endif>
                        {{ $personnel->name }}
                    </option>
                @endforeach
            </select>
            <p class="text-sm text-gray-500 mt-1">
                Hold <strong>Ctrl</strong> (Windows) or <strong>Cmd</strong> (Mac) to select multiple attendees.
            </p>
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg shadow-md transition duration-150">
                💾 Update Meeting
            </button>
        </div>
    </form>
</div>
@endsection
