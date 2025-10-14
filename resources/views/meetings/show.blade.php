@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4 text-green-700">📑 Meeting Details</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
        <div>
            <p><strong>📌 Title:</strong> {{ $meeting->title }}</p>
            <p><strong>📅 Date:</strong> {{ \Carbon\Carbon::parse($meeting->date)->format('F d, Y') }}</p>
            <p><strong>🕒 Time:</strong> {{ $meeting->start_time }} - {{ $meeting->end_time }}</p>
            <p><strong>📍 Location:</strong> {{ $meeting->location }}</p>
            <p><strong>👨‍💼 Facilitator:</strong> {{ $meeting->facilitator ?? 'N/A' }}</p>
        </div>

        <div>
            <p><strong>👥 Participants:</strong> {{ $meeting->participants }}</p>
            <p><strong>📝 Remarks:</strong> {{ $meeting->remarks ?? 'No remarks' }}</p>
            <p><strong>💻 IT Personnel Attending:</strong></p>
            <ul class="list-disc list-inside">
                @forelse($meeting->itPersonnel as $personnel)
                    <li>{{ $personnel->name }}</li>
                @empty
                    <li class="text-gray-500">No IT Personnel assigned</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-2">
        <a href="{{ route('meetings.index') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            ⬅ Back
        </a>

        {{-- Only Admin and IT Staff can edit or delete --}}
        @role('admin|it_staff')
            <a href="{{ route('meetings.edit', $meeting->id) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
               ✏ Edit
            </a>

            <form action="{{ route('meetings.destroy', $meeting->id) }}" 
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
