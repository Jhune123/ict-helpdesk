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

    <!-- 🔄 Export Buttons (carry all filters automatically) -->
    <div class="flex gap-2 mb-4">
        @php
            // Preserve all current query parameters for export
            $query = http_build_query(request()->all());
        @endphp

        <a href="{{ route('activity-logs.export', 'excel') }}?{{ $query }}"
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
            Export Excel
        </a>

        <a href="{{ route('activity-logs.export', 'pdf') }}?{{ $query }}"
           class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
            Export PDF
        </a>
    </div>

    <!-- 🔍 Search & Filters -->
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">

        <!-- Search Description / Subject -->
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search description or subject..."
               class="border rounded px-3 py-2">

        <!-- Filter by User -->
        <select name="user_id" class="border rounded px-3 py-2">
            <option value="">All Users</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>

        <!-- Filter by Action -->
        <input type="text"
               name="action"
               value="{{ request('action') }}"
               placeholder="Filter by action"
               class="border rounded px-3 py-2">

        <!-- Filter by Date Range -->
        <input type="date" name="from" value="{{ request('from') }}" class="border rounded px-3 py-2">
        <input type="date" name="to" value="{{ request('to') }}" class="border rounded px-3 py-2">

        <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2 col-span-1 md:col-span-5">
            Apply Filters
        </button>
    </form>

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

                        <!-- ID -->
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $log->id }}
                        </td>

                        <!-- User -->
                        <td class="px-4 py-3">
                            {{ $log->user?->name ?? 'System' }}
                        </td>

                        <!-- Action -->
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>

                        <!-- Subject (Ticket Title) -->
                        <td class="px-4 py-3">
                            @if ($log->subject_type === \App\Models\Ticket::class && $log->subject)
                                <a href="{{ route('tickets.show', $log->subject->id) }}"
                                   class="text-blue-600 hover:underline font-medium">
                                    {{ $log->subject->title }}
                                </a>
                            @else
                                —
                            @endif
                        </td>

                        <!-- Subject ID -->
                        <td class="px-4 py-3">
                            {{ $log->subject_id ? '#'.$log->subject_id : '—' }}
                        </td>

                        <!-- Description -->
                        <td class="px-4 py-3 text-gray-600">
                            {{ $log->description ?? '—' }}
                        </td>

                        <!-- Date -->
                        <td class="px-4 py-3 text-gray-500">
                            {{ $log->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}
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
