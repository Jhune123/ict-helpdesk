@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-[99%] mx-auto">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="font-extrabold text-2xl text-slate-800 flex items-center">
                <span class="bg-blue-600 text-white p-2 rounded-lg mr-3 shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </span>
                Preventive Maintenance Scheduler
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('maintenance.pdf') }}" class="bg-white hover:bg-slate-50 text-slate-700 font-bold py-2 px-4 border border-slate-300 rounded-xl shadow-sm transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Export Summary
                </a>
                
                {{-- ONLY Admins and IT Staff can see the Add button --}}
                @hasanyrole('admin|it_staff')
                <a href="{{ route('maintenance.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl shadow-lg transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Schedule
                </a>
                @endhasanyrole
            </div>
        </div>

        {{-- Table Card --}}
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-800 text-white text-[11px] uppercase tracking-widest text-left">
                            <th class="px-4 py-4">PMS ID</th>
                            <th class="px-4 py-4">Office/College</th>
                            <th class="px-4 py-4">Frequency</th>
                            <th class="px-4 py-4">ICT In Charge</th>
                            <th class="px-4 py-4">Device Info</th>
                            <th class="px-4 py-4 text-orange-400">Next Schedule</th>
                            <th class="px-4 py-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($schedules as $schedule)
                        <tr class="hover:bg-blue-50/40 transition-colors">
                            <td class="px-4 py-4 font-mono text-xs font-bold text-slate-500 italic">
                                PMS-{{ str_pad($schedule->id, 5, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-4 py-4 font-extrabold text-slate-900 uppercase text-sm">
                                {{ $schedule->office_college }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 rounded bg-slate-100 text-slate-600 text-[10px] font-black uppercase border">
                                    {{ $schedule->frequency }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($schedule->assignees as $staff)
                                        <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-100">
                                            {{ $staff->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <strong class="text-slate-800">{{ $schedule->title }}</strong><br>
                                <span class="text-slate-500">{{ $schedule->device_model ?? 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="{{ $schedule->next_run_date->isPast() ? 'text-rose-600 font-black animate-pulse' : 'text-emerald-600 font-bold' }}">
                                    {{ $schedule->next_run_date->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-wrap justify-center items-center gap-2 min-w-[300px]">
                                    
                                    {{-- 1. View Button (Everyone) --}}
                                    <a href="{{ route('maintenance.show', $schedule->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-slate-400" title="View Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span>View</span>
                                    </a>

                                    {{-- 2. PDF Job Order Button (Everyone) --}}
                                    <a href="{{ route('maintenance.job_order', $schedule->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-amber-800 bg-amber-50 hover:bg-amber-100 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-amber-400" title="Print Job Order">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        <span>Print</span>
                                    </a>

                                    @hasanyrole('admin|it_staff')
                                    {{-- 3. Complete/Done Button (Restricted) --}}
                                    <form action="{{ route('maintenance.complete', $schedule->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span>Mark Done</span>
                                        </button>
                                    </form>

                                    {{-- 4. Edit Button (Restricted) --}}
                                    <a href="{{ route('maintenance.edit', $schedule->id) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-400" title="Edit Schedule">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        <span>Edit</span>
                                    </a>

                                    {{-- 5. Delete Button (Restricted) --}}
                                    <form action="{{ route('maintenance.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Delete this maintenance record permanently?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-rose-400" title="Delete Schedule">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                    @endhasanyrole

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center text-slate-400 italic">
                                No maintenance schedules found. Click "New Schedule" to start.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($schedules->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                {{ $schedules->links() }}
            </div>
            @endif
        </div>

        <section class="mt-10 bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-200">
            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
                <h3 class="text-lg font-extrabold text-slate-800">Completed Maintenance History</h3>
                <p class="mt-1 text-sm text-slate-600">Archived copies of each schedule when it was marked done.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-800 text-white text-[11px] uppercase tracking-widest text-left">
                            <th class="px-4 py-4">Completed</th>
                            <th class="px-4 py-4">PMS ID</th>
                            <th class="px-4 py-4">Office/College</th>
                            <th class="px-4 py-4">ICT In Charge</th>
                            <th class="px-4 py-4">Device</th>
                            <th class="px-4 py-4">Next Schedule</th>
                            <th class="px-4 py-4 text-center">Record</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($completions as $completion)
                            @php
                                $snapshot = $completion->snapshot;
                                $assigneeNames = collect($snapshot['assignees'] ?? [])->pluck('name');
                            @endphp
                            <tr class="hover:bg-emerald-50/40 transition-colors">
                                <td class="px-4 py-4 text-sm font-semibold text-slate-700">
                                    {{ $completion->completed_at->format('M d, Y') }}
                                </td>
                                <td class="px-4 py-4 font-mono text-xs font-bold text-slate-500">
                                    PMS-{{ str_pad($snapshot['schedule_id'] ?? $completion->id, 5, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-4 py-4 text-sm font-semibold text-slate-800">
                                    {{ $snapshot['office_college'] ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-700">
                                    {{ $assigneeNames->isNotEmpty() ? $assigneeNames->implode(', ') : 'Unassigned' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-700">
                                    <strong>{{ $snapshot['title'] ?? 'N/A' }}</strong>
                                    @if(!empty($snapshot['device_model']))
                                        <br><span class="text-xs text-slate-500">{{ $snapshot['device_model'] }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm font-semibold text-emerald-700">
                                    {{ !empty($snapshot['next_run_date']) ? \Carbon\Carbon::parse($snapshot['next_run_date'])->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <a href="{{ route('maintenance.completions.show', $completion) }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-white bg-slate-700 hover:bg-slate-800 rounded-lg transition focus:outline-none focus:ring-2 focus:ring-slate-500" aria-label="View completed maintenance record">
                                        View Record
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500">
                                    No completed maintenance records yet. Marking a schedule done will save a copy here.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($completions->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    {{ $completions->links() }}
                </div>
            @endif
        </section>
    </div>
</div>
@endsection