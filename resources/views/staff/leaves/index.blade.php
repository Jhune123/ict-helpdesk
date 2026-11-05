@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl space-y-6"
     x-data="{
        open: {{ $errors->hasAny(['leave_type', 'details', 'start_date', 'end_date', 'filed_at', 'commutation_requested', 'office_department', 'position', 'designation', 'monthly_basic_salary', 'applicant_last_name', 'applicant_first_name', 'applicant_middle_name', 'applicant_signature', 'vacation_location', 'vacation_destination', 'sick_treatment', 'sick_illness', 'special_leave_illness', 'study_purpose', 'other_leave_type', 'other_purpose']) ? 'true' : 'false' }},
        leaveType: @js(old('leave_type', '')),
        vacationLocation: @js(old('vacation_location', '')),
        sickTreatment: @js(old('sick_treatment', '')),
        startDate: @js(old('start_date', '')),
        endDate: @js(old('end_date', '')),
        workingDays() {
            if (!this.startDate || !this.endDate) return 0;
            const [startYear, startMonth, startDay] = this.startDate.split('-').map(Number);
            const [endYear, endMonth, endDay] = this.endDate.split('-').map(Number);
            const startUtc = Date.UTC(startYear, startMonth - 1, startDay);
            const endUtc = Date.UTC(endYear, endMonth - 1, endDay);
            if (startUtc > endUtc) return 0;

            const totalDays = Math.floor((endUtc - startUtc) / 86400000) + 1;
            const fullWeeks = Math.floor(totalDays / 7);
            let weekdays = fullWeeks * 5;
            const remainingDays = totalDays % 7;
            const startWeekday = new Date(startUtc).getUTCDay();

            for (let offset = 0; offset < remainingDays; offset++) {
                const weekday = (startWeekday + (fullWeeks * 7) + offset) % 7;
                if (weekday !== 0 && weekday !== 6) weekdays++;
            }

            return weekdays;
        }
     }">
    <div class="flex flex-col gap-4 rounded-2xl bg-gradient-to-r from-green-950 to-green-800 p-6 text-white shadow-lg sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-green-200">Staff Services</p>
            <h1 class="mt-1 text-2xl font-bold sm:text-3xl">Leave Management</h1>
            <p class="mt-2 text-sm text-green-100">Check your leave credits and schedule, or submit an application for review.</p>
        </div>
        <button type="button" @click="open = true"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 font-bold text-green-900 shadow transition hover:bg-green-50 focus:outline-none focus:ring-4 focus:ring-green-300">
            <span class="text-xl leading-none" aria-hidden="true">+</span>
            Apply for Leave
        </button>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800" role="status">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            <p class="font-bold">Please correct the following:</p>
            <ul class="mt-1 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    @php
        $balance = $currentBalance;
        $usage = $currentUsage;
        $availableCredits = [
            'mandatory' => $balance?->mandatory_days === null ? null : max(0, (float) $balance->mandatory_days - ($usage['mandatory'] ?? 0)),
            'sick' => $balance?->sick_days === null ? null : max(0, (float) $balance->sick_days - ($usage['sick'] ?? 0)),
            'special_privilege' => $balance?->special_privilege_days === null ? null : max(0, (float) $balance->special_privilege_days - ($usage['special_privilege'] ?? 0)),
            'wellness_break' => $balance?->wellness_break_days === null ? null : max(0, (float) $balance->wellness_break_days - ($usage['wellness_break'] ?? 0)),
        ];
    @endphp

    @unless($isAdmin)
        <section aria-labelledby="leave-balance-heading">
            <div class="mb-3">
                <h2 id="leave-balance-heading" class="text-lg font-bold text-gray-900">Your Leave Balance</h2>
                <p class="text-sm text-gray-500">Approved leave is deducted from the credits recorded by Admin.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                @foreach([
                    'Mandatory/Forced Leave' => $availableCredits['mandatory'],
                    'Sick Leave' => $availableCredits['sick'],
                    'Special Privilege Leave' => $availableCredits['special_privilege'],
                    'Others (Wellness Break)' => $availableCredits['wellness_break'],
                ] as $label => $days)
                    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $days === null ? '—' : number_format($days, 2) }}
                            @if($days !== null)<span class="text-sm font-medium text-gray-500">days</span>@endif
                        </p>
                        @if($days === null)
                            <p class="mt-1 text-xs text-amber-700">Credits have not been recorded by Admin.</p>
                        @endif
                    </div>
                @endforeach
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Others (CTO)</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($usage['cto'] ?? 0, 2) }} <span class="text-sm font-medium text-gray-500">{{ Str::plural('day', $usage['cto'] ?? 0) }}</span></p>
                    <p class="mt-1 text-xs text-gray-500">Approved CTO working days</p>
                </div>
            </div>
        </section>
        <section class="mt-6" aria-labelledby="leave-total-heading">
            <div class="mb-3">
                <h2 id="leave-total-heading" class="text-lg font-bold text-gray-900">Your Approved Leave Totals</h2>
                <p class="text-sm text-gray-500">Approved working days for {{ $now->format('F Y') }} and the {{ $now->format('Y') }} calendar year.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Monthly Total of Leave</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $currentStaffLeaveTotals['monthly'] }} <span class="text-sm font-medium text-gray-500">days</span></p>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-gray-500">Yearly Total of Leave</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $currentStaffLeaveTotals['yearly'] }} <span class="text-sm font-medium text-gray-500">days</span></p>
                </div>
            </div>
        </section>
    @endunless

    @if($isAdmin)
        <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-5 py-4">
                <h2 class="font-bold text-gray-900">Staff Leave Credits</h2>
                <p class="mt-1 text-sm text-gray-500">Set earned credits for Mandatory/Forced, Sick, Special Privilege, and Wellness Break leave. Others (CTO) has unlimited credits; approved usage is tracked.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3">Staff member</th>
                            <th class="px-5 py-3">Mandatory/Forced credits</th>
                            <th class="px-5 py-3">Sick credits</th>
                            <th class="px-5 py-3">Special Privilege credits</th>
                            <th class="px-5 py-3">Wellness Break credits</th>
                            <th class="px-5 py-3">Others (CTO)</th>
                            <th class="px-5 py-3"><span class="sr-only">Save</span></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($staff as $staffMember)
                            @php
                                $staffBalance = $balances->get($staffMember->id);
                                $staffUsage = $usedCredits->get($staffMember->id, []);
                            @endphp
                            <tr>
                                <td class="whitespace-nowrap px-5 py-3">
                                    <div class="font-semibold text-gray-900">{{ $staffMember->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $staffMember->department->name ?? 'ICT Department' }}</div>
                                </td>
                                <td colspan="6" class="px-5 py-3">
                                    <form method="POST" action="{{ route('staff.leaves.balance', $staffMember) }}" class="flex min-w-[1080px] items-center gap-3">
                                        @csrf
                                        @method('PUT')
                                        <label class="sr-only" for="mandatory-{{ $staffMember->id }}">Mandatory/Forced credits</label>
                                        <input id="mandatory-{{ $staffMember->id }}" type="number" name="mandatory_days" min="0" max="999999.99" step="0.01" required
                                               value="{{ $staffBalance?->mandatory_days }}"
                                               class="w-28 rounded-lg border-gray-300 text-sm focus:border-green-600 focus:ring-green-600" placeholder="Not set">
                                        <span class="w-20 text-xs text-gray-500">Available {{ $staffBalance?->mandatory_days === null ? '—' : number_format(max(0, (float) $staffBalance->mandatory_days - ($staffUsage['mandatory'] ?? 0)), 2) }}</span>
                                        <label class="sr-only" for="sick-{{ $staffMember->id }}">Sick credits</label>
                                        <input id="sick-{{ $staffMember->id }}" type="number" name="sick_days" min="0" max="999999.99" step="0.01" required
                                               value="{{ $staffBalance?->sick_days }}"
                                               class="w-28 rounded-lg border-gray-300 text-sm focus:border-green-600 focus:ring-green-600" placeholder="Not set">
                                        <span class="w-20 text-xs text-gray-500">Available {{ $staffBalance?->sick_days === null ? '—' : number_format(max(0, (float) $staffBalance->sick_days - ($staffUsage['sick'] ?? 0)), 2) }}</span>
                                        <label class="sr-only" for="special-privilege-{{ $staffMember->id }}">Special Privilege credits</label>
                                        <input id="special-privilege-{{ $staffMember->id }}" type="number" name="special_privilege_days" min="0" max="999999.99" step="0.01" required
                                               value="{{ $staffBalance?->special_privilege_days }}"
                                               class="w-28 rounded-lg border-gray-300 text-sm focus:border-green-600 focus:ring-green-600" placeholder="Not set">
                                        <span class="w-20 text-xs text-gray-500">Available {{ $staffBalance?->special_privilege_days === null ? '—' : number_format(max(0, (float) $staffBalance->special_privilege_days - ($staffUsage['special_privilege'] ?? 0)), 2) }}</span>
                                        <label class="sr-only" for="wellness-{{ $staffMember->id }}">Others (Wellness Break) credits</label>
                                        <input id="wellness-{{ $staffMember->id }}" type="number" name="wellness_break_days" min="0" max="999999.99" step="0.01" required
                                               value="{{ $staffBalance?->wellness_break_days }}"
                                               class="w-28 rounded-lg border-gray-300 text-sm focus:border-green-600 focus:ring-green-600" placeholder="Not set">
                                        <span class="w-20 text-xs text-gray-500">Available {{ $staffBalance?->wellness_break_days === null ? '—' : number_format(max(0, (float) $staffBalance->wellness_break_days - ($staffUsage['wellness_break'] ?? 0)), 2) }}</span>
                                        <span class="w-40 shrink-0 text-sm font-semibold text-green-700">CTO approved: {{ number_format($staffUsage['cto'] ?? 0, 2) }} {{ Str::plural('day', $staffUsage['cto'] ?? 0) }}</span>
                                        <button type="submit" class="rounded-lg bg-green-700 px-3 py-2 text-xs font-bold text-white transition hover:bg-green-800">Save credits</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
        <section class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm" aria-labelledby="staff-leave-totals-heading">
            <div class="border-b border-gray-200 px-5 py-4">
                <h2 id="staff-leave-totals-heading" class="font-bold text-gray-900">Individual IT Staff Leave Totals</h2>
                <p class="mt-1 text-sm text-gray-500">Approved working days for {{ $now->format('F Y') }} and the {{ $now->format('Y') }} calendar year.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-5 py-3">IT Staff</th>
                            <th class="px-5 py-3">Monthly Total of Leave (days)</th>
                            <th class="px-5 py-3">Yearly Total of Leave (days)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($itStaff as $staffMember)
                            @php
                                $leaveTotals = $staffLeaveTotals->get($staffMember->id, ['monthly' => 0, 'yearly' => 0]);
                            @endphp
                            <tr>
                                <td class="whitespace-nowrap px-5 py-3 font-semibold text-gray-900">{{ $staffMember->name }}</td>
                                <td class="px-5 py-3 text-gray-700">{{ $leaveTotals['monthly'] }}</td>
                                <td class="px-5 py-3 text-gray-700">{{ $leaveTotals['yearly'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-5 py-4 text-center text-gray-500">No IT Staff accounts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    @endif

    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
            <div>
                <h2 class="font-bold text-gray-900">{{ $isAdmin ? 'Staff Leave Applications' : 'Your Leave Applications' }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $isAdmin ? 'Review submitted requests and track their status.' : 'Your requests and review decisions appear here.' }}</p>
            </div>
            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">{{ $applications->total() }} total</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-left text-xs font-bold uppercase tracking-wide text-gray-500">
                    <tr>
                        @if($isAdmin)<th class="px-4 py-3">Staff</th>@endif
                        <th class="px-4 py-3">Leave Type</th>
                        <th class="px-4 py-3">Inclusive Dates</th>
                        <th class="px-4 py-3">Working Days</th>
                        <th class="px-4 py-3">Filed</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($applications as $application)
                        @php
                            $applicantName = trim(implode(' ', array_filter([
                                $application->applicant_first_name,
                                $application->applicant_middle_name,
                                $application->applicant_last_name,
                            ]))) ?: $application->user->name;
                            $statusClass = match($application->status) {
                                'approved' => 'bg-green-100 text-green-800',
                                'disapproved' => 'bg-red-100 text-red-800',
                                default => 'bg-amber-100 text-amber-800',
                            };
                        @endphp
                        <tr class="align-top">
                            @if($isAdmin)
                                <td class="whitespace-nowrap px-4 py-4">
                                    <div class="font-semibold text-gray-900">{{ $applicantName }}</div>
                                    <div class="text-xs text-gray-500">{{ $application->user->email }}</div>
                                </td>
                            @endif
                            <td class="px-4 py-4">
                                <div class="font-semibold text-gray-900">{{ $application->leave_type }}</div>
                                @if($application->leave_type === 'Others' && $application->other_leave_type)
                                    <div class="text-xs text-gray-500">{{ $application->other_leave_type }}</div>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-gray-700">
                                {{ $application->start_date->format('M d, Y') }}<br>
                                <span class="text-gray-500">to {{ $application->end_date->format('M d, Y') }}</span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-4 text-gray-700">{{ $application->working_days }} {{ Str::plural('day', $application->working_days) }}</td>
                            <td class="whitespace-nowrap px-4 py-4 text-gray-700">{{ $application->filed_at->format('M d, Y') }}</td>
                            <td class="px-4 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $statusClass }}">{{ ucfirst($application->status) }}</span></td>
                            <td class="min-w-52 px-4 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('staff.leaves.show', $application) }}" class="rounded-md border border-blue-200 px-2.5 py-1.5 text-xs font-bold text-blue-700 hover:bg-blue-50">View</a>
                                    <a href="{{ route('staff.leaves.print', $application) }}" class="rounded-md border border-gray-300 px-2.5 py-1.5 text-xs font-bold text-gray-700 hover:bg-gray-50">Print</a>
                                    @if($isAdmin || $application->status === 'pending')
                                        <a href="{{ route('staff.leaves.edit', $application) }}" class="rounded-md border border-amber-200 px-2.5 py-1.5 text-xs font-bold text-amber-700 hover:bg-amber-50">Edit</a>
                                    @endif
                                    @if($isAdmin)
                                        <form method="POST" action="{{ route('staff.leaves.destroy', $application) }}" onsubmit="return confirm('Delete this leave application? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 px-2.5 py-1.5 text-xs font-bold text-red-700 hover:bg-red-50">Delete</button>
                                        </form>
                                    @endif
                                </div>
                                @if($application->status === 'pending' && ($isAdmin || $application->user_id === auth()->id()))
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        <form method="POST" action="{{ route('staff.leaves.review', $application) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="rounded-md bg-green-700 px-2.5 py-1.5 text-xs font-bold text-white hover:bg-green-800">Approve</button>
                                        </form>
                                        @if($isAdmin)
                                            <form method="POST" action="{{ route('staff.leaves.review', $application) }}" class="flex gap-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="disapproved">
                                                <input type="text" name="review_notes" maxlength="2000" aria-label="Reason for disapproval" placeholder="Reason"
                                                       class="w-24 rounded-md border-gray-300 px-2 py-1 text-xs">
                                                <button type="submit" class="rounded-md border border-red-200 px-2 py-1 text-xs font-bold text-red-700 hover:bg-red-50">Decline</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $isAdmin ? 7 : 6 }}" class="px-5 py-12 text-center">
                                <p class="text-3xl" aria-hidden="true">🗓️</p>
                                <p class="mt-2 font-semibold text-gray-800">No leave applications yet</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $isAdmin ? 'Submitted staff applications will appear here.' : 'Your submitted applications will appear here.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
            <div class="border-t border-gray-200 px-5 py-4">{{ $applications->links() }}</div>
        @endif
    </section>

<div @keydown.escape.window="open = false"
     x-show="open" x-cloak
     class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="leave-modal-title">
    <div class="fixed inset-0 bg-gray-950/60" @click="open = false"></div>
    <div class="relative flex min-h-full items-center justify-center p-4">
        <div @click.stop class="relative w-full max-w-4xl rounded-2xl bg-white shadow-2xl">
            <div class="flex items-start justify-between border-b border-gray-200 px-6 py-5">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-green-700">Civil Service Form No. 6 · Revised 2020 · KSU-QF-HRMO-6</p>
                    <h2 id="leave-modal-title" class="mt-1 text-xl font-bold text-gray-900">Application for Leave</h2>
                    <div class="mt-3 flex items-center gap-3">
                        <img src="{{ asset('image/KSU-logo.png') }}" alt="Kalinga State University logo" class="h-12 w-12 object-contain">
                        <div>
                            <p class="text-[10px] text-gray-600">Republic of the Philippines</p>
                            <p class="text-sm font-bold text-gray-900">Kalinga State University · Main Campus</p>
                            <p class="text-xs text-gray-500">National Highway, Purok 6, Bulanao, City of Tabuk, Kalinga</p>
                        </div>
                        <img src="{{ asset('image/Bagong-Pilipinas.png') }}" alt="Bagong Pilipinas logo" class="h-12 w-12 object-contain">
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="hidden rounded border border-gray-300 px-3 py-2 text-center text-[10px] text-gray-500 sm:block">STAMP OF DATE<br>OF RECEIPT</div>
                    <button type="button" @click="open = false" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-800" aria-label="Close application form">✕</button>
                </div>
            </div>

            <form method="POST" action="{{ route('staff.leaves.store') }}" class="max-h-[75vh] overflow-y-auto px-6 py-5">
                @csrf
                @if($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <p class="font-bold">Please correct the following:</p>
                        <ul class="mt-1 list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <div class="grid gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="office_department" class="mb-1 block text-sm font-semibold text-gray-700">1. Office / Department</label>
                        <input id="office_department" name="office_department" type="text" maxlength="255"
                               value="{{ old('office_department') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-sm font-semibold text-gray-700">2. Name (Last, First and Middle)</label>
                        <div class="grid gap-2 sm:grid-cols-3">
                            <div>
                                <label for="applicant_last_name" class="mb-1 block text-xs text-gray-500">Last</label>
                                <input id="applicant_last_name" name="applicant_last_name" type="text" required maxlength="255"
                                       value="{{ old('applicant_last_name') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">
                            </div>
                            <div>
                                <label for="applicant_first_name" class="mb-1 block text-xs text-gray-500">First</label>
                                <input id="applicant_first_name" name="applicant_first_name" type="text" required maxlength="255"
                                       value="{{ old('applicant_first_name') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">
                            </div>
                            <div>
                                <label for="applicant_middle_name" class="mb-1 block text-xs text-gray-500">Middle</label>
                                <input id="applicant_middle_name" name="applicant_middle_name" type="text" maxlength="255"
                                       value="{{ old('applicant_middle_name') }}"
                                       class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="filed_at" class="mb-1 block text-sm font-semibold text-gray-700">3. Date of Filing</label>
                        <input id="filed_at" name="filed_at" type="date" required value="{{ old('filed_at', now()->toDateString()) }}"
                               class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">
                    </div>
                    <div>
                        <label for="position" class="mb-1 block text-sm font-semibold text-gray-700">4. Position</label>
                        <input id="position" name="position" type="text" maxlength="255" value="{{ old('position') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">
                    </div>
                    <div>
                        <label for="designation" class="mb-1 block text-sm font-semibold text-gray-700">4.A Designation</label>
                        <input id="designation" name="designation" type="text" maxlength="255" value="{{ old('designation') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">
                    </div>
                    <div>
                        <label for="monthly_basic_salary" class="mb-1 block text-sm font-semibold text-gray-700">5. Monthly Basic Salary (₱)</label>
                        <input id="monthly_basic_salary" name="monthly_basic_salary" type="number" min="0" max="9999999999.99" step="0.01" value="{{ old('monthly_basic_salary') }}"
                               class="w-full rounded-lg border-gray-300 focus:border-green-600 focus:ring-green-600">
                    </div>
                </div>

                <section class="mt-5 rounded-xl border border-gray-200 p-4">
                    <h3 class="font-bold text-gray-900">6. Details of Application</h3>
                    <div class="mt-3">
                        <label class="mb-2 block text-sm font-semibold text-gray-700">6.A Type of leave to be availed of</label>
                        @php
                            $leaveDescriptions = [
                                'Vacation Leave' => 'Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                                'Mandatory/Forced Leave' => 'Sec. 25, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                                'Sick Leave' => 'Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                                'Maternity Leave' => 'R.A. No. 11210 / IRR issued by CSC, DOLE and SSS',
                                'Paternity Leave' => 'R.A. No. 8187 / CSC MC No. 71, s. 1998, as amended',
                                'Special Privilege Leave' => 'Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                                'Solo Parent Leave' => 'R.A. No. 8972 / CSC MC No. 8, s. 2004',
                                'Study Leave' => 'Sec. 68, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                                '10-Day VAWC Leave' => 'R.A. No. 9262 / CSC MC No. 15, s. 2005',
                                'Rehabilitation Privilege' => 'Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292',
                                'Special Leave Benefits for Women' => 'R.A. No. 9710 / CSC MC No. 25, s. 2010',
                                'Special Emergency (Calamity) Leave' => 'CSC MC No. 2, s. 2012, as amended',
                                'Adoption Leave' => 'R.A. No. 8552',
                                "Teacher's Leave" => 'Teacher’s leave credits',
                                'BAR/Board Examination Review' => 'Examination review',
                                'Monetization of Leave Credits' => 'Conversion of unused leave credits',
                                'Terminal Leave' => 'Terminal leave',
                                'Others' => 'Specify other leave type',
                                'Others (CTO)' => 'Compensatory Time Off',
                            ];
                        @endphp
                        <div class="grid gap-2 sm:grid-cols-2">
                            @foreach($leaveDescriptions as $leaveType => $legalBasis)
                                <div class="rounded-lg border border-gray-200 p-2.5 text-sm hover:border-green-400 hover:bg-green-50">
                                    <label class="flex cursor-pointer items-start gap-2">
                                        <input type="radio" name="leave_type" value="{{ $leaveType }}" required x-model="leaveType"
                                               class="mt-0.5 border-gray-300 text-green-700 focus:ring-green-600">
                                        <span><span class="font-semibold text-gray-800">{{ $leaveType }}</span><span class="block text-xs text-gray-500">{{ $legalBasis }}</span></span>
                                    </label>
                                    @if($leaveType === 'Others')
                                        <label for="other_leave_type" class="mb-1 mt-2 block text-xs font-semibold text-gray-600">Specify the type of leave you are applying for</label>
                                        <input id="other_leave_type" name="other_leave_type" type="text" maxlength="255"
                                               value="{{ old('other_leave_type') }}"
                                               x-show="leaveType === 'Others'" x-cloak
                                               :required="leaveType === 'Others'"
                                               aria-label="Specify the type of leave you are applying for"
                                               placeholder="Type of leave"
                                               class="mt-2 w-full rounded-lg border-gray-300 text-sm focus:border-green-600 focus:ring-green-600">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-4 rounded-lg bg-gray-50 p-3">
                        <label class="mb-2 block text-sm font-semibold text-gray-700">6.B Details of leave</label>
                        <div class="space-y-4">
                            <fieldset class="space-y-2">
                                <legend class="mb-1 text-sm italic text-gray-700">In case of Vacation/Special Privilege Leave:</legend>
                                <div class="flex flex-wrap items-center gap-2">
                                    <label class="inline-flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="vacation_within_philippines" value="1" @checked(old('vacation_within_philippines')) class="rounded border-gray-400 text-blue-700 focus:ring-blue-600">
                                        Within the Philippines
                                    </label>
                                    <input id="vacation_within_destination" name="vacation_within_destination" type="text" maxlength="255" value="{{ old('vacation_within_destination') }}" aria-label="Within the Philippines destination"
                                           class="min-w-48 flex-1 border-0 border-b border-gray-700 bg-transparent px-1 py-1 text-sm focus:border-green-600 focus:ring-0">
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <label class="inline-flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="vacation_abroad" value="1" @checked(old('vacation_abroad')) class="rounded border-gray-400 text-blue-700 focus:ring-blue-600">
                                        Abroad <em>(Specify)</em>
                                    </label>
                                    <input id="vacation_abroad_destination" name="vacation_abroad_destination" type="text" maxlength="255" value="{{ old('vacation_abroad_destination') }}" aria-label="Abroad destination"
                                           class="min-w-48 flex-1 border-0 border-b border-gray-700 bg-transparent px-1 py-1 text-sm focus:border-green-600 focus:ring-0">
                                </div>
                            </fieldset>

                            <fieldset class="space-y-2">
                                <legend class="mb-1 text-sm italic text-gray-700">In case of Sick Leave:</legend>
                                <div class="flex flex-wrap items-center gap-2">
                                    <label class="inline-flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="sick_in_hospital" value="1" @checked(old('sick_in_hospital')) class="rounded border-gray-400 text-blue-700 focus:ring-blue-600">
                                        In Hospital <em>(Specify Illness)</em>
                                    </label>
                                    <input id="sick_in_hospital_illness" name="sick_in_hospital_illness" type="text" maxlength="255" value="{{ old('sick_in_hospital_illness') }}" aria-label="In Hospital illness"
                                           class="min-w-40 flex-1 border-0 border-b border-gray-700 bg-transparent px-1 py-1 text-sm focus:border-green-600 focus:ring-0">
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <label class="inline-flex items-center gap-2 text-sm">
                                        <input type="checkbox" name="sick_out_patient" value="1" @checked(old('sick_out_patient')) class="rounded border-gray-400 text-blue-700 focus:ring-blue-600">
                                        Out Patient <em>(Specify Illness)</em>
                                    </label>
                                    <input id="sick_out_patient_illness" name="sick_out_patient_illness" type="text" maxlength="255" value="{{ old('sick_out_patient_illness') }}" aria-label="Out Patient illness"
                                           class="min-w-40 flex-1 border-0 border-b border-gray-700 bg-transparent px-1 py-1 text-sm focus:border-green-600 focus:ring-0">
                                </div>
                                <input id="sick_additional_details" name="sick_additional_details" type="text" maxlength="1000" value="{{ old('sick_additional_details') }}" aria-label="Additional sick leave details"
                                       class="ml-6 w-[calc(100%-1.5rem)] border-0 border-b border-gray-700 bg-transparent px-1 py-1 text-sm focus:border-green-600 focus:ring-0">
                            </fieldset>

                            <fieldset>
                                <legend class="mb-1 text-sm italic text-gray-700">In case of Special Leave Benefits for Women:</legend>
                                <label for="special_leave_illness" class="mb-1 block px-6 text-sm italic">(Specify Illness)</label>
                                <input id="special_leave_illness" name="special_leave_illness" type="text" maxlength="255" value="{{ old('special_leave_illness') }}"
                                       class="ml-6 w-[calc(100%-1.5rem)] border-0 border-b border-gray-700 bg-transparent px-1 py-1 text-sm focus:border-green-600 focus:ring-0">
                                <input id="special_leave_additional_details" name="special_leave_additional_details" type="text" maxlength="1000" value="{{ old('special_leave_additional_details') }}" aria-label="Additional special leave details"
                                       class="ml-6 mt-2 w-[calc(100%-1.5rem)] border-0 border-b border-gray-700 bg-transparent px-1 py-1 text-sm focus:border-green-600 focus:ring-0">
                            </fieldset>

                            <fieldset class="space-y-2">
                                <legend class="mb-1 text-sm italic text-gray-700">In case of Study Leave:</legend>
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="study_completion" value="1" @checked(old('study_completion')) class="rounded border-gray-400 text-blue-700 focus:ring-blue-600">
                                    Completion of Master's Degree/ Full-time Study Leave
                                </label>
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="study_bar_review" value="1" @checked(old('study_bar_review')) class="rounded border-gray-400 text-blue-700 focus:ring-blue-600">
                                    BAR/Board Examination Review
                                </label>
                                <div class="flex flex-wrap items-center gap-2 pl-6">
                                    <label class="inline-flex items-center gap-2 text-sm italic">
                                        <input type="checkbox" name="study_other_purpose_selected" value="1" @checked(old('study_other_purpose_selected')) class="rounded border-gray-400 text-blue-700 focus:ring-blue-600">
                                        Other purpose:
                                    </label>
                                    <input id="other_purpose" name="other_purpose" type="text" maxlength="1000" value="{{ old('other_purpose') }}" aria-label="Other study leave purpose"
                                           class="min-w-48 flex-1 border-0 border-b border-gray-700 bg-transparent px-1 py-1 text-sm focus:border-green-600 focus:ring-0">
                                </div>
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="study_monetization" value="1" @checked(old('study_monetization')) class="rounded border-gray-400 text-blue-700 focus:ring-blue-600">
                                    Monetization of Leave Credits
                                </label>
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="study_terminal_leave" value="1" @checked(old('study_terminal_leave')) class="rounded border-gray-400 text-blue-700 focus:ring-blue-600">
                                    Terminal Leave
                                </label>
                            </fieldset>
                        </div>

                    </div>

                    <fieldset class="mt-4 border-2 border-gray-800 p-3">
                        <legend class="px-1 text-sm font-bold uppercase text-gray-900">6.C &nbsp; Number of working days applied for</legend>
                        <div class="grid gap-3 sm:grid-cols-3 sm:items-end">
                            <label for="working_days_display" class="text-sm text-gray-800">
                                Number of working days applied for
                                <span class="mt-1 block border-b border-gray-800 pb-2 text-center font-semibold">
                                    <span id="working_days_display" x-text="workingDays()">0</span>
                                </span>
                            </label>
                            <div class="sm:col-span-2">
                                <p class="border-b border-gray-800 py-1 text-sm font-semibold uppercase text-gray-800">Inclusive Dates</p>
                                <div class="grid gap-3 pt-2 sm:grid-cols-2">
                                    <div>
                                        <label for="start_date" class="mb-1 block text-xs text-gray-600">From</label>
                                        <input id="start_date" name="start_date" type="date" required x-model="startDate" value="{{ old('start_date') }}"
                                               class="w-full rounded-lg border-gray-300 text-sm focus:border-green-600 focus:ring-green-600">
                                    </div>
                                    <div>
                                        <label for="end_date" class="mb-1 block text-xs text-gray-600">Through</label>
                                        <input id="end_date" name="end_date" type="date" required x-model="endDate" value="{{ old('end_date') }}"
                                               class="w-full rounded-lg border-gray-300 text-sm focus:border-green-600 focus:ring-green-600">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-right text-xs text-gray-500">Working days exclude weekends.</p>
                    </fieldset>
                    <fieldset class="mt-4 border border-gray-800 p-3">
                        <legend class="px-1 text-sm font-bold text-gray-900">
                            6.D &nbsp; COMMUTATION
                            <span class="font-normal italic">(of leave credits refers to the conversion of unused leave credits to corresponding money value)</span>
                        </legend>
                        <div class="space-y-1">
                            <label class="flex items-center gap-3 text-sm text-gray-900">
                                <input type="radio" name="commutation_requested" value="0" @checked(old('commutation_requested', '0') === '0') class="border-gray-500 text-blue-700 focus:ring-blue-600">
                                <span>Not Requested</span>
                            </label>
                            <label class="flex items-center gap-3 text-sm text-gray-900">
                                <input type="radio" name="commutation_requested" value="1" @checked(old('commutation_requested') === '1') class="border-gray-500 text-blue-700 focus:ring-blue-600">
                                <span>Requested</span>
                            </label>
                        </div>
                        <div class="ml-8 mt-4 max-w-md">
                            <label for="applicant_signature" class="mb-1 block text-xs italic text-gray-700">(Signature of Applicant)</label>
                            <input id="applicant_signature" name="applicant_signature" type="text" required maxlength="255"
                                   value="{{ old('applicant_signature') }}" placeholder="Type your full name as your electronic signature"
                                   class="w-full border-0 border-b border-gray-800 bg-transparent px-1 py-2 text-sm italic focus:border-green-600 focus:ring-0">
                        </div>
                    </fieldset>
                </section>

                <div class="mt-4 rounded-lg bg-gray-50 p-3">
                    <label class="flex items-start gap-2 text-sm text-gray-700">
                        <input type="checkbox" required class="mt-1 rounded border-gray-300 text-green-700 focus:ring-green-600">
                        <span>I certify that the information in this application is true and complete. The typed name above is my electronic signature.</span>
                    </label>
                </div>

                <p class="mt-4 rounded-lg bg-blue-50 px-4 py-3 text-xs leading-5 text-blue-900">
                    Submit your completed application to record it in Leave Management. You can view your application and its current status there.
                </p>
                <div class="mt-5 flex flex-col-reverse justify-end gap-3 border-t border-gray-100 pt-4 sm:flex-row">
                    <button type="button" @click="open = false" class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-bold text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="rounded-lg bg-green-700 px-5 py-2.5 text-sm font-bold text-white shadow hover:bg-green-800">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
