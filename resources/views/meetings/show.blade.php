@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-6 text-green-700">📑 Meeting Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">

        {{-- LEFT COLUMN --}}
        <div class="space-y-3">
            <p class="break-words">
                <strong>📌 Title:</strong><br>
                {{ $meeting->title }}
            </p>

            <p>
                <strong>📅 Date:</strong><br>
                {{ \Carbon\Carbon::parse($meeting->date)->format('F d, Y') }}
            </p>

            <p>
                <strong>🕒 Time:</strong><br>
                {{ \Carbon\Carbon::parse($meeting->start_time)->format('h:i A') }}
                –
                {{ \Carbon\Carbon::parse($meeting->end_time)->format('h:i A') }}
            </p>

            <p class="break-words">
                <strong>📍 Location:</strong><br>
                {{ $meeting->location }}
            </p>

            <p class="break-words">
                <strong>👨‍💼 Facilitator:</strong><br>
                {{ $meeting->facilitator ?? 'N/A' }}
            </p>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="space-y-3">
            <p class="break-words">
                <strong>👥 Participants:</strong><br>
                {{ $meeting->participants ?? '—' }}
            </p>

            <p class="break-words">
                <strong>📝 Remarks:</strong><br>
                {{ $meeting->remarks ?? 'No remarks' }}
            </p>

            <div>
                <strong>💻 IT Personnel Attending:</strong>
                <ul class="list-disc list-inside mt-1">
                    @forelse($meeting->itPersonnels as $personnel)
                        <li class="break-words">{{ $personnel->name }}</li>
                    @empty
                        <li class="text-gray-500">No IT Personnel assigned</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="mt-8 flex flex-wrap gap-2">
        <a href="{{ route('meetings.index') }}"
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            ⬅ Back
        </a>

        @role('admin|it_staff')
            <a href="{{ route('meetings.edit', $meeting) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                ✏ Edit
            </a>

            <form action="{{ route('meetings.destroy', $meeting) }}"
                  method="POST"
                  onsubmit="return confirm('Are you sure you want to delete this meeting?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    🗑 Delete
                </button>
            </form>
        @endrole
    </div>
</div>
@endsection
