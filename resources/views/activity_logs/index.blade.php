@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-6">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            📜 System Activity Logs
        </h2>

        <span class="text-sm text-gray-500">
            Visible to Admin & IT Staff only
        </span>
    </div>

    <div class="flex gap-2 mb-4">
        @php
            // Preserve all current query parameters for export
            $query = http_build_query(request()->all());
        @endphp

        <a href="{{ route('activity-logs.export', ['type' => 'excel']) }}?{{ $query }}"
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm font-semibold shadow-sm transition">
            Export Excel
        </a>

        <a href="{{ route('activity-logs.export', ['type' => 'pdf']) }}?{{ $query }}"
           class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm font-semibold shadow-sm transition">
            Export PDF
        </a>
    </div>

    <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">

        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-600 mb-1 uppercase">Keyword</label>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search description..."
                   class="border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-600 mb-1 uppercase">User</label>
            <select name="user_id" class="border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-600 mb-1 uppercase">Action</label>
            <select name="action" class="border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">All Actions</option>
                <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
                <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
                <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
                <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
            </select>
        </div>

        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-600 mb-1 uppercase">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        
        <div class="flex flex-col">
            <label class="text-xs font-bold text-gray-600 mb-1 uppercase">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2 col-span-1 md:col-span-5 font-bold hover:bg-blue-700 transition">
            Apply Filters
        </button>
    </form>

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
                    <th class="px-4 py-3 text-right">Date & Time</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50 transition">

                        <td class="px-4 py-3 font-medium text-gray-400">
                            {{ $log->id }}
                        </td>

                        <td class="px-4 py-3 font-semibold text-gray-700">
                            {{ $log->user?->name ?? 'System' }}
                        </td>

                        <td class="px-4 py-3">
                            @php
                                $badgeColor = match(strtolower($log->action)) {
                                    'create' => 'bg-green-100 text-green-700',
                                    'update' => 'bg-blue-100 text-blue-700',
                                    'delete' => 'bg-red-100 text-red-700',
                                    default  => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded-full {{ $badgeColor }} text-[10px] font-bold uppercase">
                                {{ $log->action }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            @if ($log->subject_type === \App\Models\Ticket::class && $log->subject)
                                <a href="{{ route('tickets.show', $log->subject->id) }}"
                                   class="text-blue-600 hover:underline font-medium">
                                    {{ Str::limit($log->subject->title, 30) }}
                                </a>
                            @elseif($log->subject)
                                <span class="text-gray-500 italic text-xs">
                                    {{ class_basename($log->subject_type) }}: {{ $log->subject->name ?? $log->subject->title ?? 'N/A' }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            {{ $log->subject_id ? '#'.$log->subject_id : '—' }}
                        </td>

                        <td class="px-4 py-3 text-gray-600 italic">
                            {{ $log->description ?? '—' }}
                        </td>

                        <td class="px-4 py-3 text-gray-500 text-right whitespace-nowrap">
                            {{ $log->created_at->timezone('Asia/Manila')->format('M d, Y') }}<br>
                            <span class="text-xs opacity-75">{{ $log->created_at->timezone('Asia/Manila')->format('h:i A') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-gray-500 italic">
                            No activity logs found matching your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $logs->appends(request()->all())->links() }}
    </div>

</div>
@endsection