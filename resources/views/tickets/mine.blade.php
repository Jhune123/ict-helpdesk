@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-2xl p-6">
        <h2 class="text-2xl font-bold mb-4">📋 My Tickets</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">Title</th>
                        <th class="px-4 py-2 border">Category</th>
                        <th class="px-4 py-2 border">Department</th>
                        <th class="px-4 py-2 border">Client</th>
                        <th class="px-4 py-2 border">IT Personnel</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Date Created</th>
                        <th class="px-4 py-2 border">Date Finished</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr class="text-gray-700 hover:bg-gray-50">
                            <td class="px-4 py-2 border">{{ $ticket->id }}</td>
                            <td class="px-4 py-2 border">{{ $ticket->title }}</td>
                            <td class="px-4 py-2 border">{{ $ticket->categoryName ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border">{{ $ticket->department ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border">{{ $ticket->client_name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border">{{ $ticket->assigneeName ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border">
                                <span class="px-2 py-1 rounded-lg text-sm 
                                    @if($ticket->status === 'Open') bg-green-100 text-green-700
                                    @elseif($ticket->status === 'In Progress') bg-yellow-100 text-yellow-700
                                    @elseif($ticket->status === 'Closed') bg-red-100 text-red-700
                                    @endif">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border">
                                {{ $ticket->created_at ? $ticket->created_at->timezone('Asia/Manila')->format('F d, Y h:i A') : 'N/A' }}
                            </td>
                            <td class="px-4 py-2 border">
                                {{ $ticket->date_finished ? \Carbon\Carbon::parse($ticket->date_finished)->timezone('Asia/Manila')->format('F d, Y h:i A') : 'N/A' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-2 border flex gap-2 items-center">
                                <!-- View button (always visible) -->
                                <a href="{{ route('tickets.show', $ticket->id) }}" 
                                   class="px-3 py-1 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                                    👁️ View
                                </a>

                                @php
                                    $role = strtolower(auth()->user()?->role ?? 'user');
                                @endphp

                                @if(in_array($role, ['admin', 'it_staff']))
                                    <!-- Edit button -->
                                    <a href="{{ route('tickets.edit', $ticket->id) }}" 
                                       class="px-3 py-1 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                                        ✏️ Edit
                                    </a>

                                    <!-- Delete button -->
                                    <form action="{{ route('tickets.destroy', $ticket->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this ticket?')" 
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="px-3 py-1 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700">
                                            🗑️ Delete
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-4 text-center text-gray-500">
                                You have no tickets yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection
