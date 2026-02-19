@extends('layouts.app')

@section('content')
<div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">

    <!-- 🎫 Ticket Details -->
    <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-200">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">🎫 Ticket Details</h2>

        <div class="space-y-3 text-gray-800">
            <div><span class="font-semibold">Title:</span> <span class="ml-2">{{ $ticket->title }}</span></div>
            <div>
                <span class="font-semibold">Description:</span>
                <p class="ml-2 text-gray-600">{{ $ticket->description ?? 'N/A' }}</p>
            </div>
            <div><span class="font-semibold">Status:</span> <span class="ml-2">{{ $ticket->status }}</span></div>
            <div><span class="font-semibold">Category:</span> <span class="ml-2">{{ $ticket->categoryName }}</span></div>
            <div><span class="font-semibold">Department:</span> <span class="ml-2">{{ $ticket->department ?? 'N/A' }}</span></div>
            <div><span class="font-semibold">IT Personnel:</span> <span class="ml-2">{{ $ticket->assigneeName ?? 'Unassigned' }}</span></div>
            <div>
                <span class="font-semibold">Remarks:</span>
                <p class="ml-2 text-gray-600">{{ $ticket->remarks ?? 'N/A' }}</p>
            </div>
            <div>
                <span class="font-semibold">Date Created:</span>
                <span class="ml-2">{{ $ticket->created_at->timezone('Asia/Manila')->format('F d, Y h:i A') }}</span>
            </div>
            @if($ticket->status === 'Closed' && $ticket->date_finished)
            <div>
                <span class="font-semibold">Date Finished:</span>
                <span class="ml-2">{{ \Carbon\Carbon::parse($ticket->date_finished)->timezone('Asia/Manila')->format('F d, Y h:i A') }}</span>
            </div>
            @endif
        </div>

        <!-- 🖥 Equipment Information -->
        <div class="bg-gray-50 rounded-xl p-4 mt-6 border border-gray-200">
            <h3 class="font-semibold text-gray-900 mb-2">🖥 Equipment Information</h3>
            <div class="grid grid-cols-3 gap-4 text-gray-800">
                <div>
                    <span class="font-semibold">Equipment Type:</span>
                    <span class="ml-1">{{ $ticket->equipment_type ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-semibold">Brand & Model No.:</span>
                    <span class="ml-1">{{ $ticket->brand_model ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="font-semibold">Serial No.:</span>
                    <span class="ml-1">{{ $ticket->serial_no ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- 👤 Client Information -->
        <div class="bg-gray-50 rounded-xl p-4 mt-6 border border-gray-200">
            <h3 class="font-semibold text-gray-900 mb-2">👤 Client Information</h3>
            <p><strong>Client Name:</strong> {{ $ticket->client_name }}</p>
            <p><strong>Contact Number / E-mail Address:</strong> {{ $ticket->contact_number ?? 'N/A' }}</p>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg">⬅ Back</a>
            <div class="flex gap-2">
                @role('admin|it_staff')
                <a href="{{ route('tickets.edit', $ticket->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">✏ Edit</a>
                @endrole
                @if($ticket->status === 'Closed' && !$ticket->feedback)
                    <a href="{{ route('feedbacks.create', $ticket->id) }}" class="px-4 py-2 bg-pink-500 text-white rounded-lg">📝 Submit Feedback</a>
                @elseif($ticket->feedback)
                    <span class="text-green-600 font-semibold">Feedback Submitted ✅</span>
                @endif
            </div>
        </div>
    </div>

    <!-- 💬 COMMENTS -->
    <div class="bg-white shadow-xl rounded-2xl p-6 mt-10 border">
        <h3 class="text-xl font-bold mb-4">💬 Ticket Comments</h3>

        <div class="space-y-4">
            @forelse($ticket->comments as $comment)
                <div class="bg-gray-50 p-4 rounded-lg border">
                    <div class="flex justify-between">
                        <strong>{{ $comment->user->name ?? 'Unknown' }}</strong>
                        <small>{{ $comment->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</small>
                    </div>
                    <p class="mt-2">{{ $comment->message }}</p>
                    @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin'))
                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="mt-2">
                            @csrf @method('DELETE')
                            <button class="text-red-600 text-sm">🗑 Delete</button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-gray-500 italic">No comments yet.</p>
            @endforelse
        </div>

        <!-- Add Comment -->
        <form action="{{ route('tickets.comments.store', $ticket->id) }}" method="POST" class="mt-6">
            @csrf
            <textarea name="message" required class="w-full border rounded-lg p-3" placeholder="Write a comment..."></textarea>
            <div class="text-right mt-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg">➕ Post</button>
            </div>
        </form>
    </div>

</div>
@endsection
