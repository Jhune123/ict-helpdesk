@extends('layouts.app')

@section('content')
@php
    $applicantName = trim(implode(' ', array_filter([
        $leave->applicant_first_name,
        $leave->applicant_middle_name,
        $leave->applicant_last_name,
    ]))) ?: $leave->user->name;
@endphp
<div class="mx-auto max-w-5xl space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-green-700">Staff Services / Leave Management</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-900">Leave Application</h1>
        </div>
        <a href="{{ route('staff.leaves.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50">Back to Leave Management</a>
    </div>

    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-3 border-b border-gray-200 px-5 py-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $applicantName }}</h2>
                <p class="text-sm text-gray-500">{{ $leave->leave_type }} · Filed {{ $leave->filed_at->format('M d, Y') }}</p>
            </div>
            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $leave->status === 'approved' ? 'bg-green-100 text-green-800' : ($leave->status === 'disapproved' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                {{ ucfirst($leave->status) }}
            </span>
        </div>

        <div class="grid gap-6 p-5 md:grid-cols-2">
            <div class="space-y-4">
                <h3 class="font-bold text-gray-900">Applicant Information</h3>
                <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                    <dt class="text-gray-500">Office / Department</dt><dd class="font-medium text-gray-900">{{ $leave->office_department ?: '—' }}</dd>
                    <dt class="text-gray-500">Position</dt><dd class="font-medium text-gray-900">{{ $leave->position ?: '—' }}</dd>
                    <dt class="text-gray-500">Designation</dt><dd class="font-medium text-gray-900">{{ $leave->designation ?: '—' }}</dd>
                    <dt class="text-gray-500">Monthly Basic Salary</dt><dd class="font-medium text-gray-900">{{ $leave->monthly_basic_salary ? '₱'.number_format((float) $leave->monthly_basic_salary, 2) : '—' }}</dd>
                    <dt class="text-gray-500">Applicant Signature</dt><dd class="font-serif italic text-gray-900">{{ $leave->applicant_signature ?: '—' }}</dd>
                </dl>
            </div>
            <div class="space-y-4">
                <h3 class="font-bold text-gray-900">Leave Details</h3>
                <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                    <dt class="text-gray-500">Inclusive Dates</dt><dd class="font-medium text-gray-900">{{ $leave->start_date->format('M d, Y') }} – {{ $leave->end_date->format('M d, Y') }}</dd>
                    <dt class="text-gray-500">Working Days</dt><dd class="font-medium text-gray-900">{{ $leave->working_days }}</dd>
                    <dt class="text-gray-500">Commutation</dt><dd class="font-medium text-gray-900">{{ $leave->commutation_requested ? 'Requested' : 'Not Requested' }}</dd>
                    @if($leave->leave_type === 'Others')
                        <dt class="text-gray-500">Other Leave Type</dt><dd class="font-medium text-gray-900">{{ $leave->other_leave_type ?: '—' }}</dd>
                    @endif
                </dl>
                <div>
                    <h4 class="text-sm font-semibold text-gray-500">6.B Details of Leave</h4>
                    <p class="mt-1 whitespace-pre-line text-sm text-gray-900">{{ $leave->details ?: '—' }}</p>
                </div>
            </div>
        </div>

        @if($leave->review_notes || $leave->reviewer)
            <div class="border-t border-gray-200 bg-gray-50 px-5 py-4 text-sm">
                <p class="font-bold text-gray-800">Administrative Review</p>
                @if($leave->reviewer)<p class="mt-1 text-gray-600">Reviewed by {{ $leave->reviewer->name }}{{ $leave->reviewed_at ? ' on '.$leave->reviewed_at->format('M d, Y') : '' }}</p>@endif
                @if($leave->review_notes)<p class="mt-1 text-gray-700">{{ $leave->review_notes }}</p>@endif
            </div>
        @endif

        <div class="flex flex-wrap gap-2 border-t border-gray-200 px-5 py-4">
            <a href="{{ route('staff.leaves.print', $leave) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-50">Print</a>
            @if(auth()->user()->hasRole('admin') || auth()->user()->role === 'admin')
                <a href="{{ route('staff.leaves.edit', $leave) }}" class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-bold text-white hover:bg-amber-700">Edit</a>
            @endif
        </div>
    </section>
</div>
@endsection
