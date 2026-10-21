@extends('layouts.app')

@section('content')
<main class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Follow Up</h1>
            <p class="mt-1 text-gray-600">Check Your Ticket Status with IT Staff</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-blue-700 hover:underline">Back to dashboard</a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if($chatTickets->isEmpty())
        <div class="rounded-xl border border-gray-200 bg-white p-8 text-center shadow-sm">
            <p class="text-gray-700">You do not have any tickets to discuss yet.</p>
            <a href="{{ route('tickets.create') }}" class="mt-4 inline-flex rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">
                Create a ticket
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            @foreach($chatTickets as $ticket)
                <article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3 border-b border-gray-100 pb-4">
                        <div>
                            <h2 class="font-bold text-gray-900">{{ $ticket->title }}</h2>
                            <p class="text-sm text-gray-500">Ticket {{ $ticket->ticket_number }} · {{ $ticket->status }}</p>
                        </div>
                        <a href="{{ route('tickets.show', $ticket) }}" class="text-sm font-semibold text-blue-700 hover:underline">View ticket</a>
                    </div>

                    <details class="mt-4">
                        <summary class="inline-flex cursor-pointer list-none items-center rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span class="conversation-open-label">Open conversation</span>
                            <span class="conversation-closed-label">Close conversation</span>
                        </summary>

                        <div id="ticket-conversation-{{ $ticket->id }}">
                            <div class="my-4 max-h-64 space-y-3 overflow-y-auto rounded-lg bg-gray-50 p-3">
                                @forelse($ticket->comments as $comment)
                                    <div class="rounded-lg border border-gray-200 bg-white p-3">
                                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                                            <span class="text-sm font-semibold text-gray-900">{{ $comment->user->name ?? 'Unknown User' }}</span>
                                            <time class="text-xs text-gray-500">{{ $comment->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</time>
                                        </div>
                                        @if($comment->recipient)
                                            <p class="mt-1 text-xs text-gray-500">To {{ $comment->recipient->name }}</p>
                                        @endif
                                        <p class="mt-2 whitespace-pre-wrap break-words text-sm text-gray-700">{{ $comment->message }}</p>
                                    </div>
                                @empty
                                    <p class="py-6 text-center text-sm text-gray-500">No messages about this ticket yet.</p>
                                @endforelse
                            </div>

                            @if($chatPersonnel->isNotEmpty())
                                @php
                                    $selectedRecipientId = old('ticket_id') == $ticket->id
                                        ? old('recipient_id')
                                        : $ticket->assigned_to;
                                @endphp
                                <form action="{{ route('dashboard.chat.store') }}" method="POST" class="border-t border-gray-100 pt-4">
                                    @csrf
                                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

                                    <label for="recipient-{{ $ticket->id }}" class="mb-1 block text-sm font-semibold text-gray-700">Message</label>
                                    <select id="recipient-{{ $ticket->id }}" name="recipient_id" required class="mb-3 w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select IT staff or administrator</option>
                                        @foreach($chatPersonnel as $person)
                                            <option value="{{ $person->id }}" @selected((string) $selectedRecipientId === (string) $person->id)>
                                                {{ $person->name }}{{ $ticket->assigned_to === $person->id ? ' (assigned to this ticket)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <label for="message-{{ $ticket->id }}" class="sr-only">Your message about {{ $ticket->ticket_number }}</label>
                                    <textarea id="message-{{ $ticket->id }}" name="message" rows="3" maxlength="1000" required class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Write a message or ask for an update...">{{ old('ticket_id') == $ticket->id ? old('message') : '' }}</textarea>
                                    @if($errors->any() && old('ticket_id') == $ticket->id)
                                        <p class="mt-2 text-sm text-red-600">{{ $errors->first() }}</p>
                                    @endif
                                    <div class="mt-3 flex items-center justify-between gap-3">
                                        <p class="text-xs text-gray-500">The selected person will receive an email notification.</p>
                                        <button type="submit" class="shrink-0 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            Send message
                                        </button>
                                    </div>
                                </form>
                            @else
                                <p class="border-t border-gray-100 pt-4 text-sm text-gray-600">No IT staff or administrators are available to message right now.</p>
                            @endif
                        </div>
                    </details>
                </article>
            @endforeach
        </div>
    @endif
</main>

<style>
    details[open] .conversation-open-label,
    details:not([open]) .conversation-closed-label {
        display: none;
    }
</style>
@endsection
