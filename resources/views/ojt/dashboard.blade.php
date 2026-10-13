@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50/60 py-4 px-3 sm:py-8 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Top Navigation / Header --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-gray-100 no-print">
            <div class="flex min-w-0 items-center gap-3 sm:gap-4">
                <img src="{{ asset('image/school-logo.jpg') }}"
                     alt="Kalinga State University logo"
                     class="h-12 w-12 sm:h-14 sm:w-14 shrink-0 rounded-full border border-gray-200 bg-white object-contain p-1">
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 flex items-center gap-2 tracking-tight">
                        ⚡ KSU OJT Portal Dashboard
                    </h1>
                    <p class="text-xs text-gray-500 font-medium mt-1">
                        Welcome back, <span class="font-bold text-gray-700">{{ auth()->user()->name }}</span>!
                        @role('supervisor')
                            <span class="text-emerald-600 font-bold">({{ auth()->user()->department->name ?? 'Department Supervisor' }})</span>
                        @endrole
                    </p>
                </div>
            </div>
            <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto sm:gap-3">
                {{-- Reliable Server Time Badge --}}
                <div class="w-full sm:w-auto bg-slate-100 text-slate-700 text-xs font-bold px-3 py-2 rounded-xl border border-slate-200 flex flex-wrap items-center gap-1.5">
                    🌐 Server Time: <span class="text-emerald-700">{{ $serverTime }}</span>
                </div>

                @if(!auth()->user()->hasAnyRole(['supervisor', 'ojt_supervisor']) && (auth()->user()->hasRole('ojt') || auth()->user()->is_ojt || auth()->user()->hasAnyRole(['admin', 'it_staff'])))
                <button onclick="toggleModal('checkinModal', true)"
                        class="hidden sm:flex min-h-11 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm transition items-center gap-1.5 cursor-pointer">
                    📷 Clock In / Attendance
                </button>
                @endif

                @if(auth()->user()->hasAnyRole(['admin', 'it_staff']))
                <a href="{{ route('users.index') }}" class="min-h-11 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm transition flex items-center gap-1.5">
                    👥 Manage Users
                </a>
                @endif

                <span class="min-h-10 inline-flex items-center bg-purple-100 text-purple-700 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                    ● {{ auth()->user()->getRoleNames()->first() ?? 'USER' }}
                </span>
            </div>
        </div>

        {{-- Alert Notifications --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl shadow-sm flex items-center justify-between no-print">
                <div class="flex items-center gap-2">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 font-bold">✕</button>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl shadow-sm space-y-1 no-print">
                <div class="font-bold">⚠️ Please fix the following errors:</div>
                <ul class="list-disc list-inside text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($isOjtIntern)
            @php
                $requiredMinutes = $myRequiredHours * 60;
                $remainingMinutes = max(0, $requiredMinutes - $myRenderedMinutes);
                $completionPercent = $requiredMinutes > 0
                    ? min(100, ($myRenderedMinutes / $requiredMinutes) * 100)
                    : 100;
            @endphp
            <section class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-4 sm:p-6" aria-label="OJT hours progress">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide text-gray-500">Your Total Rendered OJT Hours</p>
                        <p class="mt-1 text-3xl font-black text-emerald-700">
                            {{ \App\Models\OjtAttendance::formatDuration($myRenderedMinutes) }}
                        </p>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-sm font-bold text-gray-700">
                            Requirement: {{ number_format($myRequiredHours) }} hrs
                        </p>
                        <p class="mt-1 text-xs font-semibold text-gray-500">
                            @if($remainingMinutes > 0)
                                {{ \App\Models\OjtAttendance::formatDuration($remainingMinutes) }} remaining
                            @else
                                OJT hours requirement completed
                            @endif
                        </p>
                    </div>
                </div>
                <div class="mt-4 h-3 overflow-hidden rounded-full bg-gray-100">
                    <div class="h-full rounded-full bg-emerald-500 transition-all"
                         style="width: {{ $completionPercent }}%"></div>
                </div>
                <p class="mt-1 text-right text-xs font-bold text-emerald-700">{{ number_format($completionPercent, 1) }}% complete</p>
            </section>
        @endif

        @if($isOjtIntern)
            @php
                $myAttendance = $students->firstWhere('id', auth()->id());
            @endphp
            <section class="sm:hidden bg-white rounded-2xl shadow-sm border border-emerald-100 p-4 space-y-4" aria-label="Today's attendance">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-base font-black text-gray-900">Today's Attendance</h2>
                        <p class="mt-1 text-xs font-medium text-gray-500">{{ \Carbon\Carbon::now('Asia/Manila')->format('l, F d') }}</p>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-center text-xs font-bold text-emerald-800">
                        {{ $myAttendance->status ?? 'Not Checked In' }}
                    </span>
                </div>

                <dl class="grid grid-cols-2 gap-3 text-xs">
                    <div class="rounded-xl bg-gray-50 p-3">
                        <dt class="font-bold text-gray-500">AM IN</dt>
                        <dd class="mt-1 font-mono font-bold text-gray-900">{{ $myAttendance->am_in ?? '--:--' }}</dd>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3">
                        <dt class="font-bold text-gray-500">AM OUT</dt>
                        <dd class="mt-1 font-mono font-bold text-gray-900">{{ $myAttendance->am_out ?? '--:--' }}</dd>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3">
                        <dt class="font-bold text-gray-500">PM IN</dt>
                        <dd class="mt-1 font-mono font-bold text-gray-900">{{ $myAttendance->pm_in ?? '--:--' }}</dd>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3">
                        <dt class="font-bold text-gray-500">PM OUT</dt>
                        <dd class="mt-1 font-mono font-bold text-gray-900">{{ $myAttendance->pm_out ?? '--:--' }}</dd>
                    </div>
                </dl>

                <div class="flex items-center justify-between rounded-xl border border-emerald-100 bg-emerald-50 px-3 py-2.5">
                    <span class="text-xs font-bold text-emerald-900">Hours rendered today</span>
                    <span class="text-sm font-black text-emerald-700">{{ $myAttendance->todays_hrs ?? '0 hrs 0 mins' }}</span>
                </div>

                <button type="button" onclick="toggleModal('checkinModal', true)"
                        class="flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-black text-white shadow-sm transition hover:bg-emerald-700">
                    📷 Clock In / Out
                </button>
                <p class="text-center text-[11px] text-gray-500">Choose your attendance action and take or upload your proof photo.</p>
            </section>
        @endif

        @unless($isOjtIntern)
            <section class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4" aria-label="Today's OJT attendance totals">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm">
                    <p class="text-xs font-black uppercase text-slate-500">Total OJT Interns</p>
                    <p class="mt-1 text-3xl font-black text-slate-800">{{ $students->count() }}</p>
                </div>
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-center shadow-sm">
                    <p class="text-xs font-black uppercase text-emerald-800">Present Today</p>
                    <p class="mt-1 text-3xl font-black text-emerald-700">{{ $presentToday }}</p>
                </div>
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-center shadow-sm">
                    <p class="text-xs font-black uppercase text-rose-800">Absent Today</p>
                    <p class="mt-1 text-3xl font-black text-rose-700">{{ $absentToday }}</p>
                </div>
            </section>
        @endunless

        {{-- Tracking Board --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            {{-- Board Header --}}
            <div class="p-4 sm:p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 no-print">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Live OJT Tracking Board</h2>
                    <p class="text-xs text-gray-500 font-medium">Monitor student attendance logs and total hours</p>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm transition cursor-pointer">
                        🖨️ Print Hours Summary
                    </button>

                    @if(auth()->user()->hasAnyRole(['supervisor', 'ojt_supervisor']))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold px-3 py-2 rounded-xl">
                            🏢 {{ auth()->user()->department->name ?? 'My Department' }}
                        </div>
                    @else
                        <form method="GET" action="{{ route('ojt.dashboard') }}">
                            <select name="department_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-xs font-semibold rounded-xl px-3 py-2 cursor-pointer">
                                <option value="">🏢 All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ ($selectedDept == $dept->id) ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Table --}}
            <div class="{{ $isOjtIntern ? 'hidden sm:block' : 'block' }} overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-[11px] font-bold text-gray-400 uppercase bg-gray-50/50">
                            <th class="py-4 px-6 no-print">Proof</th>
                            <th class="py-4 px-4">Student Name</th>
                            <th class="py-4 px-4">Department</th>
                            <th class="py-4 px-4">Status</th>
                            <th class="py-4 px-3">AM IN</th>
                            <th class="py-4 px-3">AM OUT</th>
                            <th class="py-4 px-3">PM IN</th>
                            <th class="py-4 px-3">PM OUT</th>
                            <th class="py-4 px-4 font-bold text-emerald-700">Today's Hrs</th>
                            <th class="py-4 px-4 font-bold text-indigo-700">Monthly Total</th>
                            <th class="py-4 px-4 font-bold text-violet-700">Rendered / Required</th>
                            <th class="py-4 px-6 text-center no-print">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs font-medium text-gray-700">
                        @forelse($students as $student)
                        @php
                            $proofUrl = $student->proof_photo ? \Illuminate\Support\Facades\Storage::url($student->proof_photo) : null;
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-4 px-6 no-print">
                                @if($proofUrl)
                                    <button onclick="viewImage('{{ $proofUrl }}', '{{ $student->name }}')" class="focus:outline-none cursor-pointer">
                                        <img src="{{ $proofUrl }}" alt="Proof" class="w-10 h-10 object-cover rounded-lg border shadow-sm">
                                    </button>
                                @else
                                    <span class="text-gray-400 italic text-xs">No Photo</span>
                                @endif
                            </td>

                            <td class="py-4 px-4 font-bold text-gray-900 uppercase">{{ $student->name }}</td>
                            <td class="py-4 px-4"><span class="bg-gray-100 font-semibold px-2.5 py-1 rounded-full text-[11px]">{{ $student->department }}</span></td>
                            <td class="py-4 px-4"><span class="bg-emerald-100 text-emerald-800 font-semibold px-2.5 py-1 rounded-full text-[11px]">● {{ $student->status }}</span></td>
                            <td class="py-4 px-3 font-mono text-gray-500">{{ $student->am_in }}</td>
                            <td class="py-4 px-3 font-mono text-gray-500">{{ $student->am_out }}</td>
                            <td class="py-4 px-3 font-mono text-gray-500">{{ $student->pm_in }}</td>
                            <td class="py-4 px-3 font-mono text-gray-500">{{ $student->pm_out }}</td>
                            <td class="py-4 px-4 font-bold text-emerald-600">{{ $student->todays_hrs }}</td>
                            <td class="py-4 px-4 font-bold text-indigo-700">{{ $student->monthly_total }}</td>
                            <td class="py-4 px-4">
                                <div class="font-bold text-violet-700">{{ $student->rendered_total }}</div>
                                <div class="text-[11px] text-gray-500">of {{ number_format($student->required_hours) }} hrs</div>
                                @if(auth()->user()->hasAnyRole(['admin', 'it_staff']))
                                    <form action="{{ route('ojt.update-required-hours', $student->id) }}" method="POST" class="mt-2 flex items-center gap-1 no-print">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="required_hours" min="0" step="1"
                                               value="{{ $student->required_hours }}" required
                                               aria-label="Required OJT hours for {{ $student->name }}"
                                               class="w-20 rounded-lg border border-gray-300 px-2 py-1 text-xs">
                                        <button type="submit" class="rounded-lg bg-violet-600 px-2 py-1 text-xs font-bold text-white hover:bg-violet-700">
                                            Save
                                        </button>
                                    </form>
                                @endif
                            </td>

                            {{-- Actions Column --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- View Proof Photo Button --}}
                                    @if(!empty($student->proof_photo))
                                        <button onclick="viewImage('{{ Storage::url($student->proof_photo) }}', @js($student->name))"
                                                class="px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 font-bold rounded-xl text-xs flex items-center gap-1 transition shadow-sm border border-blue-200">
                                            👁️ View
                                        </button>
                                    @endif

                                    @if(auth()->user()->hasAnyRole(['admin', 'it_staff']) && $student->log_id)
                                        <button onclick="openEditModal(@js($student), @js(route('ojt.update-log', $student->log_id)))"
                                                class="px-3 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 font-bold rounded-xl text-xs flex items-center gap-1 transition shadow-sm border border-amber-200">
                                            ✏️ Edit
                                        </button>
                                    @endif

                                    {{-- Print Daily Hours --}}
                                    <a href="{{ route('ojt.print-daily', $student->id) }}" 
                                       target="_blank"
                                       class="px-3 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-bold rounded-xl text-xs flex items-center gap-1 transition shadow-sm border border-emerald-200">
                                        🖨️ Print Daily
                                    </a>

                                    {{-- Print Monthly Total Hours --}}
                                    <a href="{{ route('ojt.print-monthly', $student->id) }}" 
                                       target="_blank"
                                       class="px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold rounded-xl text-xs flex items-center gap-1 transition shadow-sm border border-indigo-200">
                                        📊 Print Monthly
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="py-12 text-center text-gray-400 italic">No active OJT intern records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- 📸 Modal 1: OJT Intern Clock In / Attendance Modal --}}
@if(!auth()->user()->hasAnyRole(['supervisor', 'ojt_supervisor']) && (auth()->user()->hasRole('ojt') || auth()->user()->is_ojt || auth()->user()->hasAnyRole(['admin', 'it_staff'])))
<div id="checkinModal" onclick="if(event.target === this) toggleModal('checkinModal', false)" class="fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-black/60 p-2 sm:items-center sm:p-4">
    <div role="dialog" aria-modal="true" aria-labelledby="checkinModalTitle" class="relative my-auto max-h-[calc(100dvh-1rem)] w-full max-w-lg overflow-y-auto rounded-2xl bg-white p-4 shadow-2xl sm:max-h-[calc(100dvh-2rem)] sm:p-6">
        <button type="button" aria-label="Close attendance form" onclick="toggleModal('checkinModal', false)" class="absolute right-3 top-3 flex h-11 w-11 items-center justify-center rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-800">✕</button>
        
        <div class="mb-4 pr-10">
            <h3 id="checkinModalTitle" class="text-lg font-bold text-gray-900 flex items-center gap-2">📷 OJT Duty Clock-In</h3>
            <p class="text-xs text-gray-500">Select your duty action and take or upload a proof photo.</p>
        </div>

        @php
            $myTodayLog = \App\Models\OjtAttendance::where('user_id', auth()->id())
                ->whereDate('date', \Carbon\Carbon::today('Asia/Manila'))
                ->first();

            // Smart pre-selected default, while keeping ALL options 100% unlocked and selectable
            $defaultAction = 'am_in';
            if (!$myTodayLog?->am_in) {
                $defaultAction = 'am_in';
            } elseif (!$myTodayLog?->am_out) {
                $defaultAction = 'am_out';
            } elseif (!$myTodayLog?->pm_in) {
                $defaultAction = 'pm_in';
            } elseif (!$myTodayLog?->pm_out) {
                $defaultAction = 'pm_out';
            }
        @endphp

        <form action="{{ route('ojt.store-log') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Department ID Hidden Field -->
            <input type="hidden" name="department_id" value="{{ auth()->user()->department_id ?? $departments->first()?->id }}">

            <!-- 1. Duty Action Selection (ALL ENABLED) -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2">1. Select Action:</label>
                <div class="grid grid-cols-2 gap-2">
                    
                    <!-- AM IN -->
                    <label class="relative block cursor-pointer">
                        <input type="radio" name="action_type" value="am_in" class="peer sr-only" 
                            {{ $defaultAction === 'am_in' ? 'checked' : '' }}>
                        <div class="min-h-20 p-3 text-center rounded-xl border-2 border-gray-200 transition-all 
                                    hover:border-blue-400 hover:bg-blue-50/30
                                    peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                            <div class="text-[10px] font-bold uppercase text-gray-400">Morning</div>
                            <div class="text-sm font-bold">☀️ AM IN</div>
                            <div class="text-[10px] text-gray-500">
                                {{ $myTodayLog?->am_in ? 'Logged: ' . \Carbon\Carbon::parse($myTodayLog->am_in)->format('h:i A') : '08:00 AM' }}
                            </div>
                        </div>
                    </label>

                    <!-- AM OUT -->
                    <label class="relative block cursor-pointer">
                        <input type="radio" name="action_type" value="am_out" class="peer sr-only"
                            {{ $defaultAction === 'am_out' ? 'checked' : '' }}>
                        <div class="min-h-20 p-3 text-center rounded-xl border-2 border-gray-200 transition-all 
                                    hover:border-amber-400 hover:bg-amber-50/30
                                    peer-checked:border-amber-600 peer-checked:bg-amber-50 peer-checked:text-amber-700">
                            <div class="text-[10px] font-bold uppercase text-gray-400">Lunch Break</div>
                            <div class="text-sm font-bold">🌤 AM OUT</div>
                            <div class="text-[10px] text-gray-500">
                                {{ $myTodayLog?->am_out ? 'Logged: ' . \Carbon\Carbon::parse($myTodayLog->am_out)->format('h:i A') : '12:00 PM' }}
                            </div>
                        </div>
                    </label>

                    <!-- PM IN -->
                    <label class="relative block cursor-pointer">
                        <input type="radio" name="action_type" value="pm_in" class="peer sr-only"
                            {{ $defaultAction === 'pm_in' ? 'checked' : '' }}>
                        <div class="min-h-20 p-3 text-center rounded-xl border-2 border-gray-200 transition-all 
                                    hover:border-indigo-400 hover:bg-indigo-50/30
                                    peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700">
                            <div class="text-[10px] font-bold uppercase text-gray-400">Afternoon</div>
                            <div class="text-sm font-bold">🌆 PM IN</div>
                            <div class="text-[10px] text-gray-500">
                                {{ $myTodayLog?->pm_in ? 'Logged: ' . \Carbon\Carbon::parse($myTodayLog->pm_in)->format('h:i A') : '01:00 PM' }}
                            </div>
                        </div>
                    </label>

                    <!-- PM OUT -->
                    <label class="relative block cursor-pointer">
                        <input type="radio" name="action_type" value="pm_out" class="peer sr-only"
                            {{ $defaultAction === 'pm_out' ? 'checked' : '' }}>
                        <div class="min-h-20 p-3 text-center rounded-xl border-2 border-gray-200 transition-all 
                                    hover:border-emerald-400 hover:bg-emerald-50/30
                                    peer-checked:border-emerald-600 peer-checked:bg-emerald-50 peer-checked:text-emerald-700">
                            <div class="text-[10px] font-bold uppercase text-gray-400">End of Duty</div>
                            <div class="text-sm font-bold">🌙 PM OUT</div>
                            <div class="text-[10px] text-gray-500">
                                {{ $myTodayLog?->pm_out ? 'Logged: ' . \Carbon\Carbon::parse($myTodayLog->pm_out)->format('h:i A') : '05:00 PM' }}
                            </div>
                        </div>
                    </label>

                </div>
            </div>

            <!-- 2. Proof Photo Input -->
            <div class="bg-gray-50 p-3.5 rounded-xl border border-dashed border-gray-300">
                <label for="modal_proof_photo" class="block font-bold text-xs text-gray-700 mb-1">
                    📷 2. Capture / Upload Proof Photo <span class="text-red-500">*</span>
                </label>
                <input type="file" 
                       name="proof_photo" 
                       id="modal_proof_photo" 
                       accept="image/*" 
                       capture="user" 
                       required
                       onchange="previewModalPhoto(event)"
                       class="block min-h-12 w-full text-xs text-gray-500 
                              file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 
                              file:text-xs file:font-bold file:bg-emerald-600 file:text-white 
                              hover:file:bg-emerald-700 cursor-pointer">

                <div id="modalPhotoPreviewContainer" class="mt-3 hidden">
                    <span class="block text-[11px] font-bold text-gray-600 mb-1">Photo Preview:</span>
                    <img id="modalPhotoPreview" src="#" alt="Proof Preview" class="h-32 w-full object-cover rounded-lg border shadow-sm">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 py-3 text-sm font-bold text-white shadow-md transition hover:bg-emerald-700">
                <span>✅ Save Attendance Clock-In</span>
            </button>
        </form>
    </div>
</div>
@endif

{{-- Modal 2: Image Lightbox --}}
<div id="imageViewerModal" onclick="if(event.target === this) toggleModal('imageViewerModal', false)" class="fixed inset-0 bg-black/75 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-xl w-full p-5 shadow-2xl relative">
        <h3 id="modalStudentName" class="text-sm font-bold text-gray-900 mb-3">📷 Duty Proof Photo</h3>
        <img id="modalImagePreview" src="" class="w-full max-h-[70vh] object-contain rounded-xl">
        <div class="mt-4 flex justify-between">
            <a id="modalDownloadBtn" href="#" download class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-xl text-xs">📥 Download</a>
            <button onclick="toggleModal('imageViewerModal', false)" class="bg-gray-100 text-gray-700 font-bold px-4 py-2 rounded-xl text-xs cursor-pointer">Close</button>
        </div>
    </div>
</div>

{{-- Modal 3: Admin/IT-Staff Attendance Edit Modal --}}
@if(auth()->user()->hasAnyRole(['admin', 'it_staff']))
<div id="editAttendanceModal" onclick="if(event.target === this) toggleModal('editAttendanceModal', false)" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
        <button onclick="toggleModal('editAttendanceModal', false)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 font-bold cursor-pointer">✕</button>
        <h3 class="text-lg font-bold text-gray-900 mb-1">✏ Edit Attendance Record</h3>
        <p id="editStudentTitle" class="text-xs text-gray-500 mb-4"></p>

        <form id="editAttendanceForm" method="POST" action="" class="space-y-3">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">AM IN</label>
                    <input type="time" step="1" name="am_in" id="edit_am_in" class="w-full bg-gray-50 border text-xs rounded-xl p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">AM OUT</label>
                    <input type="time" step="1" name="am_out" id="edit_am_out" class="w-full bg-gray-50 border text-xs rounded-xl p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">PM IN</label>
                    <input type="time" step="1" name="pm_in" id="edit_pm_in" class="w-full bg-gray-50 border text-xs rounded-xl p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">PM OUT</label>
                    <input type="time" step="1" name="pm_out" id="edit_pm_out" class="w-full bg-gray-50 border text-xs rounded-xl p-2.5">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Status</label>
                <input type="text" name="status" id="edit_status" required class="w-full bg-gray-50 border text-xs rounded-xl p-2.5">
            </div>

            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-xl text-xs transition shadow-md cursor-pointer">
                💾 Save Attendance Adjustments
            </button>
        </form>
    </div>
</div>
@endif

<script>
    function toggleModal(id, show) {
        document.getElementById(id)?.classList.toggle('hidden', !show);
    }

    function viewImage(url, name) {
        document.getElementById('modalImagePreview').src = url;
        document.getElementById('modalDownloadBtn').href = url;
        document.getElementById('modalStudentName').textContent = '📷 Duty Proof Photo - ' + name;
        toggleModal('imageViewerModal', true);
    }

    function openEditModal(student, updateUrl) {
        document.getElementById('editStudentTitle').textContent = 'Updating log for: ' + student.name;
        document.getElementById('editAttendanceForm').action = updateUrl;
        document.getElementById('edit_am_in').value = student.raw_am_in ?? '';
        document.getElementById('edit_am_out').value = student.raw_am_out ?? '';
        document.getElementById('edit_pm_in').value = student.raw_pm_in ?? '';
        document.getElementById('edit_pm_out').value = student.raw_pm_out ?? '';
        document.getElementById('edit_status').value = student.status ?? 'AM Checked In';
        toggleModal('editAttendanceModal', true);
    }

    function previewModalPhoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('modalPhotoPreview');
                const container = document.getElementById('modalPhotoPreviewContainer');
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection