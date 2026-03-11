@extends('layouts.app')

@section('content')
@php
    $data = json_decode($maintenance->description, true);
    $tasks = $data['tasks'] ?? [];
    $remarks = $data['remarks'] ?? 'No specific remarks or findings provided.';
@endphp

<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        {{-- Top Action Bar --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <a href="{{ route('maintenance.index') }}" class="flex items-center text-slate-500 hover:text-blue-600 transition font-bold text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Schedules
            </a>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('maintenance.job_order', $maintenance->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl font-bold shadow-sm transition flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Job Order
                </a>
                @hasanyrole('admin|it_staff')
                <a href="{{ route('maintenance.edit', $maintenance->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-bold shadow-sm transition flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Record
                </a>
                @endhasanyrole
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-200">
            
            {{-- Header Section --}}
            <div class="bg-slate-800 p-8 text-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div>
                    <h2 class="text-3xl font-black italic tracking-tight">PMS RECORD</h2>
                    <p class="text-blue-400 font-bold font-mono text-lg mt-1">PMS-{{ str_pad($maintenance->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="text-left sm:text-right bg-slate-700/50 p-5 rounded-xl border border-slate-600 w-full sm:w-auto">
                    <p class="text-[10px] uppercase font-bold text-slate-300 tracking-widest mb-1">Next Schedule Due</p>
                    <p class="text-2xl font-black text-orange-400">
                        {{ $maintenance->next_run_date->format('M d, Y') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-1.5 flex items-center sm:justify-end gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Frequency: <span class="uppercase text-white font-bold">{{ $maintenance->frequency }}</span>
                    </p>
                </div>
            </div>

            <div class="p-8 space-y-8">
                
                {{-- General Details Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 bg-slate-50 p-6 rounded-xl border border-slate-100 shadow-inner">
                    <section>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Office / College</label>
                        <p class="text-base font-bold text-slate-800">{{ $maintenance->office_college }}</p>
                    </section>
                    <section>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Device Brand / Title</label>
                        <p class="text-base font-bold text-slate-800">{{ $maintenance->title }}</p>
                    </section>
                    <section>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Model</label>
                        <p class="text-base font-bold text-slate-800">{{ $maintenance->device_model ?: 'N/A' }}</p>
                    </section>
                    <section>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Property Number</label>
                        <p class="text-base font-bold text-slate-800 font-mono">{{ $maintenance->property_number ?: 'N/A' }}</p>
                    </section>
                    <section>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Serial Number</label>
                        <p class="text-base font-bold text-slate-800 font-mono">{{ $maintenance->serial_number ?: 'N/A' }}</p>
                    </section>
                    <section>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Assigned ICT Staff</label>
                        <div class="flex flex-wrap gap-1.5 mt-1">
                            @forelse($maintenance->assignees as $staff)
                                <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-1 rounded border border-blue-200">
                                    {{ $staff->name }}
                                </span>
                            @empty
                                <span class="text-sm text-slate-500 italic">Unassigned</span>
                            @endforelse
                        </div>
                    </section>
                </div>

                {{-- Completed Checklist --}}
                <section>
                    <h3 class="text-sm font-black text-slate-800 uppercase mb-4 border-b pb-2 tracking-widest flex items-center">
                        <span class="bg-emerald-500 w-2 h-5 rounded mr-2 block"></span>
                        Tasks Checklist
                    </h3>
                    
                    @if(!empty($tasks))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($tasks as $t)
                            <div class="flex items-start bg-emerald-50/50 text-slate-700 p-3 rounded-xl text-sm border border-emerald-100/50 shadow-sm transition hover:bg-emerald-50">
                                <svg class="w-5 h-5 mr-3 text-emerald-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span class="leading-tight font-medium">{{ $t }}</span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center p-8 bg-slate-50 border border-slate-200 rounded-xl border-dashed">
                            <p class="text-slate-500 italic text-sm">No specific tasks were selected for this schedule.</p>
                        </div>
                    @endif
                </section>

                {{-- Remarks --}}
                <section class="bg-amber-50/50 p-6 rounded-xl text-slate-700 border border-amber-100 border-l-4 border-l-amber-400 relative overflow-hidden shadow-sm">
                    <svg class="w-24 h-24 text-amber-500/10 absolute -right-4 -top-4 transform rotate-12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <label class="text-[10px] font-black text-amber-700 uppercase block mb-2 tracking-widest relative z-10">Remarks & Findings</label>
                    <p class="font-medium whitespace-pre-line relative z-10 leading-relaxed">{{ $remarks }}</p>
                </section>

            </div>
        </div>
    </div>
</div>
@endsection