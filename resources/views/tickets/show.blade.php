@extends('layouts.app')

@section('content')
<div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-2xl p-6">
        <h2 class="text-2xl font-bold mb-4">🎫 Ticket Details</h2>

        <div class="space-y-4">
            <div>
                <span class="font-semibold text-gray-700">Title:</span>
                <span class="ml-2">{{ $ticket->title }}</span>
            </div>

            <div>
                <span class="font-semibold text-gray-700">Description:</span>
                <p class="ml-2 text-gray-600">{{ $ticket->description ?? 'N/A' }}</p>
            </div>

            <div>
                <span class="font-semibold text-gray-700">Status:</span>
                <span class="ml-2">{{ $ticket->status }}</span>
            </div>

            <div>
                <span class="font-semibold text-gray-700">Category:</span>
                <span class="ml-2">{{ $ticket->categoryName }}</span>
            </div>

            <div>
                <span class="font-semibold text-gray-700">Department:</span>
                <span class="ml-2">{{ $ticket->department ?? 'N/A' }}</span>
            </div>

            <div>
                <span class="font-semibold text-gray-700">Client Name:</span>
                <span class="ml-2">{{ $ticket->client_name }}</span>
            </div>

            <div>
                <span class="font-semibold text-gray-700">IT Personnel:</span>
                <span class="ml-2">{{ $ticket->assigneeName }}</span>
            </div>

            <div>
                <span class="font-semibold text-gray-700">Remarks:</span>
                <p class="ml-2 text-gray-600">{{ $ticket->remarks ?? 'N/A' }}</p>
            </div>

            <!-- Date Created -->
            <div>
                <span class="font-semibold text-gray-700">Date Created:</span>
                <span class="ml-2">
                    {{ $ticket->created_at->timezone('Asia/Manila')->format('F d, Y h:i A') }}
                </span>
            </div>

            <!-- Date Finished -->
            @if($ticket->status === 'Closed' && $ticket->date_finished)
                <div>
                    <span class="font-semibold text-gray-700">Date Finished:</span>
                    <span class="ml-2">
                        {{ \Carbon\Carbon::parse($ticket->date_finished)->timezone('Asia/Manila')->format('F d, Y h:i A') }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Back & Edit Buttons -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg shadow hover:bg-gray-600">
                ⬅️ Back
            </a>

            @role('admin|it_staff')
            <a href="{{ route('tickets.edit', $ticket->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700">
                ✏️ Edit
            </a>
            @endrole
        </div>
    </div>
</div>
@endsection
