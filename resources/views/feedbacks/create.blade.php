@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-lg border-t-4 border-blue-600">
        <h2 class="text-2xl font-bold mb-2 text-gray-800">
            Submit Feedback for Ticket #{{ $ticket->ticket_number }}
        </h2>
        <p class="text-gray-600 mb-6 italic text-sm">
            Subject: {{ $ticket->title }}
        </p>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ✅ FORM ACTION: Points to feedbacks.store with Ticket ID --}}
        <form action="{{ route('feedbacks.store', $ticket->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-bold text-gray-700 mb-1">Client Name</label>
                <input type="text" name="client_name" value="{{ $ticket->client_name }}" 
                       class="w-full border rounded p-2 bg-gray-50 text-gray-500 cursor-not-allowed" readonly>
            </div>

            <div class="mb-4">
                <label class="block font-bold text-gray-700 mb-1">How would you rate our service? (1-5)</label>
                <div class="relative">
                    <select name="rating" class="w-full border rounded p-2 appearance-none focus:ring-2 focus:ring-blue-500 outline-none" required>
                        <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 - Excellent)</option>
                        <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ (4 - Very Good)</option>
                        <option value="3" {{ old('rating', '3') == '3' ? 'selected' : '' }}>⭐⭐⭐ (3 - Good)</option>
                        <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>⭐⭐ (2 - Fair)</option>
                        <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>⭐ (1 - Poor)</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block font-bold text-gray-700 mb-1">Additional Comments</label>
                <textarea name="comments" rows="4" 
                          class="w-full border rounded p-2 focus:ring-2 focus:ring-blue-500 outline-none" 
                          placeholder="Tell us what we did well or how we can improve...">{{ old('comments') }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('tickets.index') }}" 
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded font-semibold hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded font-semibold hover:bg-blue-700 shadow-md transition">
                    Submit Feedback
                </button>
            </div>
        </form>
    </div>
</div>
@endsection