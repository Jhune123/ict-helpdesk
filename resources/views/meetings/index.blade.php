@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-green-700">Meetings</h2>

        {{-- Only allow staff and admin to create meetings --}}
        @role('admin|it_staff')
            <a href="{{ route('meetings.create') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
               + Create Meeting
            </a>
        @endrole

        @role('client')
            <a href="{{ route('meetings.create') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
               + Create Meeting
            </a>
        @endrole
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">Title</th>
                    <th class="px-4 py-2 border">Date</th>
                    <th class="px-4 py-2 border">Start Time</th>
                    <th class="px-4 py-2 border">End Time</th>
                    <th class="px-4 py-2 border">Location</th>
                    <th class="px-4 py-2 border">Facilitator</th>
                    <th class="px-4 py-2 border">Participants</th>
                    <th class="px-4 py-2 border">IT Personnel Attendees</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($meetings as $meeting)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">{{ $meeting->title }}</td>
                        <td class="px-4 py-2 border">{{ $meeting->date }}</td>
                        <td class="px-4 py-2 border">{{ $meeting->start_time }}</td>
                        <td class="px-4 py-2 border">{{ $meeting->end_time }}</td>
                        <td class="px-4 py-2 border">{{ $meeting->location }}</td>
                        <td class="px-4 py-2 border">{{ $meeting->facilitator ?? 'N/A' }}</td>
                        <td class="px-4 py-2 border">{{ $meeting->participants }}</td>
                        
                        {{-- IT Personnel --}}
                        <td class="px-4 py-2 border">
                            @if($meeting->itPersonnel->isNotEmpty())
                                <ul class="list-disc list-inside">
                                    @foreach($meeting->itPersonnel as $person)
                                        <li>{{ $person->name }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-500">None</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-2 border text-center">
                            <a href="{{ route('meetings.show', $meeting->id) }}" 
                               class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                View
                            </a>

                            {{-- Only Admin/IT Staff can edit or delete --}}
                            @role('admin|it_staff')
                                <a href="{{ route('meetings.edit', $meeting->id) }}" 
                                   class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                   Edit
                                </a>

                                <form action="{{ route('meetings.destroy', $meeting->id) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                        onclick="return confirm('Are you sure you want to delete this meeting?')">
                                        Delete
                                    </button>
                                </form>
                            @endrole
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-2 border text-center text-gray-500">
                            No meetings found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
