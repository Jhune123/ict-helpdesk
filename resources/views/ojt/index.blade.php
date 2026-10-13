@extends('layouts.app')


<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">OJT Attendance Management</h2>

        <!-- Clock In/Out Action Box -->
        <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg mb-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-600">Today: {{ \Carbon\Carbon::now('Asia/Manila')->format('F d, Y') }}</p>
                <p class="text-xs text-gray-500">Status: 
                    @if(!$todayRecord)
                        <span class="text-yellow-600 font-bold">Not Clocked In</span>
                    @elseif(!$todayRecord->time_out)
                        <span class="text-green-600 font-bold">Clocked In ({{ $todayRecord->time_in }})</span>
                    @else
                        <span class="text-blue-600 font-bold">Completed (In: {{ $todayRecord->time_in }} | Out: {{ $todayRecord->time_out }})</span>
                    @endif
                </p>
            </div>

            <div class="space-x-2">
                @if(!$todayRecord)
                    <form action="{{ route('ojt.clock-in') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Clock In
                        </button>
                    </form>
                @elseif(!$todayRecord->time_out)
                    <form action="{{ route('ojt.clock-out') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Clock Out
                        </button>
                    </form>
                @else
                    <button disabled class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                        Day Complete
                    </button>
                @endif
            </div>
        </div>

        <!-- Attendance Logs Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Trainee Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time In</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Time Out</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($attendances as $row)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-900">{{ $row->user->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $row->date }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $row->time_in ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $row->time_out ?? '-' }}</td>
                        <td class="px-4 py-2 text-sm font-semibold {{ $row->status === 'Late' ? 'text-red-600' : 'text-green-600' }}">
                            {{ $row->status }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No attendance records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $attendances->links() }}
        </div>
    </div>
</div>