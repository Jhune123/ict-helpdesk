@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-12">
    <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                🛡️ Preventive Maintenance Scheduler
            </h2>
            
            <div class="flex space-x-3">
                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print
                </button>

                <a href="{{ route('maintenance.pdf') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Download PDF
                </a>

                @hasanyrole('admin|it_staff')
                <a href="{{ route('maintenance.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Schedule
                </a>
                @endhasanyrole
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200 text-sm">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-3 py-3 text-left font-bold uppercase tracking-wider">PMS ID #</th>
                                <th class="px-3 py-3 text-left font-bold uppercase tracking-wider">Title</th>
                                <th class="px-3 py-3 text-left font-bold uppercase tracking-wider">Office / College</th>
                                <th class="px-3 py-3 text-left font-bold uppercase tracking-wider">Frequency</th>
                                <th class="px-3 py-3 text-left font-bold uppercase tracking-wider">Last Run</th>
                                <th class="px-3 py-3 text-left font-bold uppercase tracking-wider text-orange-400">Next Run</th>
                                <th class="px-3 py-3 text-left font-bold uppercase tracking-wider">Priority</th>
                                <th class="px-3 py-3 text-left font-bold uppercase tracking-wider">Assigned Staff</th>
                                <th class="px-3 py-3 text-left font-bold uppercase tracking-wider">Device / Model</th>
                                <th class="px-3 py-3 text-left font-bold uppercase tracking-wider">Property #</th>
                                <th class="px-3 py-3 text-left font-bold uppercase tracking-wider">Serial #</th>
                                <th class="px-3 py-3 text-center font-bold uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($schedules as $schedule)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-3 py-4 font-mono text-gray-600">
                                    PMS-{{ str_pad($schedule->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-3 py-4 font-medium text-gray-900">{{ $schedule->title }}</td>
                                <td class="px-3 py-4 text-gray-700">{{ $schedule->office_college }}</td>
                                <td class="px-3 py-4 text-center">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded">{{ ucfirst($schedule->frequency) }}</span>
                                </td>
                                <td class="px-3 py-4 text-gray-600">
                                    {{ $schedule->last_run_date ? $schedule->last_run_date->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-3 py-4 font-bold">
                                    @if($schedule->next_run_date->isPast())
                                        <span class="text-red-600 flex items-center" title="Overdue">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                            {{ $schedule->next_run_date->format('M d, Y') }}
                                        </span>
                                    @elseif($schedule->next_run_date->isToday())
                                        <span class="text-orange-500" title="Due Today">📅 Today</span>
                                    @else
                                        <span class="text-blue-600">{{ $schedule->next_run_date->format('M d, Y') }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 text-xs font-semibold">
                                    @php
                                        $prioColor = match($schedule->priority) {
                                            'Critical' => 'bg-red-100 text-red-800',
                                            'High' => 'bg-orange-100 text-orange-800',
                                            'Low' => 'bg-gray-100 text-gray-800',
                                            default => 'bg-green-100 text-green-800',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded-full {{ $prioColor }}">
                                        {{ $schedule->priority }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 text-gray-600 text-xs">
                                    @forelse($schedule->assignees as $staff)
                                        <div class="whitespace-nowrap font-medium">• {{ $staff->name }}</div>
                                    @empty
                                        <span class="text-gray-400 italic font-normal">Unassigned</span>
                                    @endforelse
                                </td>
                                <td class="px-3 py-4 text-gray-600">{{ $schedule->device_model ?? '-' }}</td>
                                <td class="px-3 py-4 text-gray-600">{{ $schedule->property_number ?? '-' }}</td>
                                <td class="px-3 py-4 text-gray-600">{{ $schedule->serial_number ?? '-' }}</td>
                                
                                <td class="px-3 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex justify-center items-center space-x-2">
                                        
                                        @hasanyrole('admin|it_staff')
                                        {{-- Fixed: This action hits the completeTask method --}}
                                        <form action="{{ route('maintenance.complete', $schedule->id) }}" method="POST" onsubmit="return confirm('Mark this task as done today? This will automatically schedule the next one.');">
                                            @csrf
                                            <button type="submit" class="text-green-500 hover:text-green-700" title="Complete & Reschedule">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </button>
                                        </form>
                                        @endhasanyrole

                                        <a href="{{ route('maintenance.job_order', $schedule->id) }}" class="text-purple-600 hover:text-purple-800" title="Download Job Order">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </a>

                                        <a href="{{ route('maintenance.show', $schedule->id) }}" class="text-blue-500 hover:text-blue-700" title="View Details">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>

                                        @hasanyrole('admin|it_staff')
                                        <a href="{{ route('maintenance.edit', $schedule->id) }}" class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <form action="{{ route('maintenance.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Delete">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                        @endhasanyrole
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" class="px-6 py-10 text-center text-gray-500">
                                    No schedules found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $schedules->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection