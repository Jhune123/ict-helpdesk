@extends('layouts.app')

@section('content')
<div class="py-6 max-w-6xl mx-auto sm:px-6 lg:px-8">

    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
        <h2 class="text-2xl font-bold mb-6 text-gray-900">
            ⭐ Client Feedbacks
        </h2>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Ticket #</th>
                        <th class="border px-4 py-2">Client</th>
                        <th class="border px-4 py-2">Office Visited</th> {{-- 👈 NEW: Added Contextual Column Header --}}
                        <th class="border px-4 py-2">Rating</th>
                        <th class="border px-4 py-2">Comments/Suggestions</th>
                        <th class="border px-4 py-2">Date</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($feedbacks as $fb)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $fb->id }}</td>
                            <td class="border px-4 py-2">#{{ $fb->ticket_id }}</td>
                            <td class="border px-4 py-2">{{ $fb->client_name ?? 'Anonymous' }}</td>
                            <td class="border px-4 py-2">{{ $fb->office_visited ?? '—' }}</td> {{-- 👈 NEW: Added Office Location --}}
                            <td class="border px-4 py-2">{{ $fb->sqd0 ?? $fb->rating ?? '0' }} ⭐</td> {{-- Safe fallback logic --}}
                            <td class="border px-4 py-2">{{ $fb->suggestions ?? $fb->comments ?? '—' }}</td> {{-- Safe fallback logic --}}
                            <td class="border px-4 py-2">
                                {{ $fb->created_at->timezone('Asia/Manila')->format('F d, Y h:i A') }}
                            </td>

                            <td class="border px-4 py-2 flex gap-2">
                                <a href="{{ route('feedbacks.show', $fb->id) }}"
                                   class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                   👁 View
                                </a>

                                {{-- 👇 SECURED: Only Admin and IT Staff can see the Edit & Delete Actions --}}
                                @hasanyrole('admin|it_staff')
                                    <a href="{{ route('feedbacks.edit', $fb->id) }}"
                                       class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                       ✏️ Edit
                                    </a>

                                    <form action="{{ route('feedbacks.destroy', $fb->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this feedback?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                            🗑 Delete
                                        </button>
                                    </form>
                                @endhasanyrole
                            </td>
                        </tr>

                    @empty
                        <tr>
                            {{-- 🌟 Note: colspan adjusted to 8 to account for the new column --}}
                            <td colspan="8" class="text-center py-4 text-gray-500">No feedbacks yet.</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $feedbacks->links() }}
        </div>

    </div>

</div>
@endsection