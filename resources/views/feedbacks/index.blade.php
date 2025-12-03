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
                        <th class="border px-4 py-2">Rating</th>
                        <th class="border px-4 py-2">Comments</th>
                        <th class="border px-4 py-2">Date</th>
                        <th class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($feedbacks as $fb)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ $fb->id }}</td>
                            <td class="border px-4 py-2">#{{ $fb->ticket_id }}</td>
                            <td class="border px-4 py-2">{{ $fb->client_name }}</td>
                            <td class="border px-4 py-2">{{ $fb->rating }} ⭐</td>
                            <td class="border px-4 py-2">{{ $fb->comments ?? '—' }}</td>
                            <td class="border px-4 py-2">
                                {{ $fb->created_at->timezone('Asia/Manila')->format('F d, Y h:i A') }}
                            </td>

                            <td class="border px-4 py-2 flex gap-2">
                                <a href="{{ route('feedbacks.show', $fb->id) }}"
                                   class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    👁 View
                                </a>

                                @role('admin')
                                    <form action="{{ route('feedbacks.destroy', $fb->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this feedback?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                            🗑 Delete
                                        </button>
                                    </form>
                                @endrole
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-gray-500">No feedbacks yet.</td>
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
