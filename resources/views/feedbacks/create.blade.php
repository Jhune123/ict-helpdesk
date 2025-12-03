@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10">

    <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
            📝 Submit Feedback for Ticket #{{ $ticket->id }}
        </h2>

        <p class="text-gray-600 mb-6">
            <strong>Ticket Title:</strong> {{ $ticket->title }}
        </p>

        <form action="{{ route('feedbacks.store', $ticket->id) }}" method="POST" class="space-y-4">
            @csrf

            <!-- Client Name -->
            <div>
                <label class="font-semibold text-gray-700">Client Name</label>
                <input type="text" name="client_name" value="{{ old('client_name', $ticket->client_name) }}"
                       class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                       required>
            </div>

            <!-- Rating -->
            <div>
                <label class="font-semibold text-gray-700">Rating (1–5)</label>
                <select name="rating"
                        class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        required>
                    <option value="">Select Rating</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} ⭐</option>
                    @endfor
                </select>
            </div>

            <!-- Comments -->
            <div>
                <label class="font-semibold text-gray-700">Comments</label>
                <textarea name="comments" rows="4"
                          class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="Write your feedback..."></textarea>
            </div>

            <!-- Submit -->
            <button class="w-full bg-pink-600 text-white py-3 rounded-lg font-semibold hover:bg-pink-700 transition">
                ✅ Submit Feedback
            </button>
        </form>
    </div>

</div>
@endsection
