@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">✏️ Edit Meeting</h2>

    <form action="{{ route('meetings.update', $meeting->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700">Title</label>
            <input type="text" name="title" class="w-full border rounded-lg px-3 py-2"
                   value="{{ old('title', $meeting->title) }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Date</label>
            <input type="date" name="date" class="w-full border rounded-lg px-3 py-2"
                   value="{{ old('date', $meeting->date) }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Start Time</label>
            <input type="time" name="start_time" class="w-full border rounded-lg px-3 py-2"
                   value="{{ old('start_time', $meeting->start_time) }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">End Time</label>
            <input type="time" name="end_time" class="w-full border rounded-lg px-3 py-2"
                   value="{{ old('end_time', $meeting->end_time) }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Location</label>
            <input type="text" name="location" class="w-full border rounded-lg px-3 py-2"
                   value="{{ old('location', $meeting->location) }}" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Facilitator</label>
            <input type="text" name="facilitator" class="w-full border rounded-lg px-3 py-2"
                   value="{{ old('facilitator', $meeting->facilitator) }}">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Participants</label>
            <textarea name="participants" class="w-full border rounded-lg px-3 py-2">{{ old('participants', $meeting->participants) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Remarks</label>
            <textarea name="remarks" class="w-full border rounded-lg px-3 py-2">{{ old('remarks', $meeting->remarks) }}</textarea>
        </div>

        <!-- IT Personnel Attendees -->
        <div class="mb-4">
            <label class="block text-gray-700">IT Personnel Attendees</label>
           <select name="it_personnels[]" id="it_personnels" class="form-select" multiple>
    @foreach($itPersonnels as $personnel)
        <option value="{{ $personnel->id }}"
            @if(isset($meeting) && $meeting->itPersonnels->contains($personnel->id)) selected @endif>
            {{ $personnel->name }}
        </option>
    @endforeach
</select>

            <p class="text-sm text-gray-500 mt-1">
                Hold <strong>Ctrl</strong> (Windows) or <strong>Cmd</strong> (Mac) to select multiple.
            </p>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            💾 Update
        </button>
    </form>
</div>
@endsection
