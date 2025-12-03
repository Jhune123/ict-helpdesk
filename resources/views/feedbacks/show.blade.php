@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">

    <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">⭐ Feedback Details</h2>

        <div class="space-y-4 text-gray-800">

            <p><strong>Ticket #:</strong> {{ $feedback->ticket_id }}</p>

            <p><strong>Client Name:</strong> {{ $feedback->client_name }}</p>

            <p><strong>Rating:</strong> {{ $feedback->rating }} ⭐</p>

            <p><strong>Comments:</strong></p>
            <p class="bg-gray-50 p-3 rounded-lg border">{{ $feedback->comments ?? 'No comments' }}</p>

            <p><strong>Submitted On:</strong> 
                {{ $feedback->created_at->timezone('Asia/Manila')->format('F d, Y h:i A') }}
            </p>
        </div>

        <div class="mt-6">
            <a href="{{ route('feedbacks.index') }}"
               class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                ⬅ Back to Feedback List
            </a>
        </div>

    </div>

</div>
@endsection
