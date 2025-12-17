{{-- resources/views/activity_logs/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-6">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            📜 System Activity Logs
        </h2>

        <span class="text-sm text-gray-500">
            Visible to Admin & IT Staff only
        </span>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Action</th>
                    <th class="px-4 py-3">Subject</th>
                    <th class="px-4 py-3">Subject ID</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3">Date & Time</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $log->id }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $log->user->name ?? 'System' }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                {{ $log->action }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            @php
                                $subjectTitle = '—';
                                $subjectLink = '#';

                                if($log->subject_type === \App\Models\Ticket::class) {
                                    $ticket = \App\Models\Ticket::find($log->subject_id);
                                    if($ticket) {
                                        $subjectTitle = $ticket->title;
                                        $subjectLink = route('tickets.show', $ticket->id);
                                    }
                                }
                            @endphp

                            @if($subjectTitle !== '—')
                                <a href="{{ $subjectLink }}" class="text-blue-600 hover:underline">
                                    {{ $subjectTitle }}
                                </a>
                            @else
                                {{ $subjectTitle }}
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            {{ $log->subject_id ? '#' . $log->subject_id : '—' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $log->description ?? '—' }}
                        </td>

                        <td class="px-4 py-3 text-gray-500">
                            {{ $log->created_at->format('M d, Y h:i A') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            No activity logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $logs->links() }}
    </div>

</div>
@endsection
