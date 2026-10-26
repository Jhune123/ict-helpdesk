@extends('layouts.app')

@section('content')
@php
    $snapshot = $completion->snapshot;
    $details = json_decode($snapshot['description'] ?? '', true) ?? [];
    $assignees = collect($snapshot['assignees'] ?? []);
@endphp

<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-6 py-5 bg-slate-800 text-white">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-emerald-300">Completed Maintenance Record</p>
                <h1 class="mt-1 text-xl font-extrabold">{{ $snapshot['title'] ?? 'Maintenance Schedule' }}</h1>
            </div>
            <a href="{{ route('maintenance.index') }}" class="inline-flex justify-center px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-sm font-semibold transition">
                Back to Scheduler
            </a>
        </div>

        <div class="p-6 sm:p-8 space-y-8">
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-full bg-emerald-100 text-emerald-800 px-3 py-1 text-sm font-bold">Marked Done</span>
                <span class="text-sm text-slate-600">
                    Completed {{ $completion->completed_at->format('F j, Y \a\t g:i A') }}
                </span>
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">PMS ID</dt>
                    <dd class="mt-1 font-semibold text-slate-800">PMS-{{ str_pad($snapshot['schedule_id'] ?? $completion->id, 5, '0', STR_PAD_LEFT) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Office/College</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ $snapshot['office_college'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Frequency</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ ucfirst($snapshot['frequency'] ?? 'N/A') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Performed By / ICT In Charge</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ $assignees->pluck('name')->implode(', ') ?: 'Unassigned' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Device / Brand</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ $snapshot['title'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Model</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ $snapshot['device_model'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Property No.</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ $snapshot['property_number'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Serial No.</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ $snapshot['serial_number'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Completed Date</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ $snapshot['last_run_date'] ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Next Schedule</dt>
                    <dd class="mt-1 font-semibold text-emerald-700">
                        {{ !empty($snapshot['next_run_date']) ? \Carbon\Carbon::parse($snapshot['next_run_date'])->format('F j, Y') : 'N/A' }}
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Confirmed By</dt>
                    <dd class="mt-1 font-semibold text-slate-800">{{ $snapshot['confirmed_by'] ?? 'N/A' }}</dd>
                </div>
            </dl>

            <section>
                <h2 class="text-lg font-extrabold text-slate-800 border-b border-slate-200 pb-2">Maintenance Checklist</h2>
                @if(!empty($details['tasks']))
                    <ul class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($details['tasks'] as $task)
                            <li class="flex items-start gap-2 text-sm text-slate-700">
                                <span class="text-emerald-600 font-bold" aria-hidden="true">&#10003;</span>
                                <span>{{ $task }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-3 text-sm text-slate-500">No checklist tasks were recorded.</p>
                @endif
            </section>

            <section>
                <h2 class="text-lg font-extrabold text-slate-800 border-b border-slate-200 pb-2">Remarks / Findings</h2>
                <p class="mt-3 text-sm text-slate-700 whitespace-pre-line">{{ $details['remarks'] ?? 'None' }}</p>
            </section>
        </div>
    </div>
</div>
@endsection
