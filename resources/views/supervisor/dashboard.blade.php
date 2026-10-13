@extends('layouts.app')

@section('content')
@php
    $supervisorUser = $supervisor ?? auth()->user();
    $internList = $students ?? collect();
    $presentCount = $activeToday ?? 0;
    $absentCount = max(0, $totalInterns - $presentCount);
@endphp

{{-- Root Container with Alpine.js Instant Search --}}
<div x-data="{ search: '' }" class="min-h-screen bg-slate-50/60 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- 🌿 Eye-Comfortable Emerald Header Banner -->
        <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-teal-900 text-white rounded-3xl p-6 sm:p-8 shadow-2xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border border-emerald-700/60 relative overflow-hidden">
            
            {{-- Decorative Accent Seal Glow --}}
            <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 space-y-1">
                <span class="inline-flex items-center gap-1.5 bg-emerald-950/80 text-emerald-300 text-xs font-black px-3 py-1 rounded-full uppercase tracking-wider border border-emerald-700/50 shadow-inner">
                    <span>👔</span> SUPERVISOR PORTAL
                </span>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white drop-shadow-sm">
                    {{ $departmentName }} OJT Attendance
                </h1>
                <p class="text-sm text-emerald-200/90 font-semibold">
                    Supervisor: <strong class="text-white font-extrabold">{{ $supervisorUser->name }}</strong>
                </p>
            </div>

            <div class="relative z-10 bg-emerald-950/60 backdrop-blur-md px-5 py-3 rounded-2xl border border-emerald-600/40 text-left md:text-right shadow-md">
                <p class="text-xs text-emerald-300 font-bold uppercase tracking-wide">Today's Date (PST)</p>
                <p class="text-sm font-black text-white mt-0.5">{{ \Carbon\Carbon::now('Asia/Manila')->format('l, M d, Y') }}</p>
            </div>
        </div>

        <!-- 🔍 Department & Intern Search Bar -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-emerald-100 shadow-md space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                
                {{-- Live Search Input --}}
                <div class="relative flex-1 max-w-xl">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-600 text-base">
                        🔍
                    </div>
                    <input type="text"
                           x-model="search"
                           placeholder="Type to search department name, intern name, or email..."
                           class="w-full pl-11 pr-10 py-3 bg-slate-50 border border-emerald-200 rounded-2xl text-sm font-bold text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white focus:border-emerald-500 transition shadow-inner">
                    
                    <button x-show="search.length > 0" 
                            @click="search = ''" 
                            x-cloak
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-xs font-extrabold text-gray-400 hover:text-emerald-700">
                        ✕ Clear
                    </button>
                </div>

                {{-- Status Display Badge --}}
                <div class="text-xs font-extrabold text-emerald-900 bg-emerald-50 px-4 py-2.5 rounded-2xl border border-emerald-200/70 flex items-center gap-2 self-start md:self-auto">
                    <span class="inline-block w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    <span>Active Filter: <span class="text-emerald-700 font-black">{{ $departmentName }}</span></span>
                </div>
            </div>

            <!-- 🏢 Department Filter Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-thin">
                {{-- All Departments Pill --}}
                <a href="{{ route('supervisor.dashboard', ['department_id' => 'all']) }}"
                   x-show="!search || 'all departments'.includes(search.toLowerCase())"
                   class="px-4 py-2 rounded-2xl text-xs font-extrabold whitespace-nowrap transition-all duration-200 flex items-center gap-2 shadow-sm
                          {{ $selectedDepartmentId === 'all' 
                             ? 'bg-emerald-800 text-white ring-2 ring-emerald-500 shadow-md scale-[1.02]' 
                             : 'bg-emerald-50/70 text-emerald-950 hover:bg-emerald-100 hover:text-emerald-900 border border-emerald-200/60' }}">
                    <span>🏛️ All Departments</span>
                </a>

                {{-- Individual Department Pills --}}
                @foreach($departments as $dept)
                    @php
                        $stat = $departmentStats->firstWhere('id', $dept->id);
                        $deptPresent = $stat?->present_today ?? 0;
                        $deptTotal = $stat?->total_interns ?? 0;
                        $isSelected = (string)$selectedDepartmentId === (string)$dept->id;
                    @endphp
                    <a href="{{ route('supervisor.dashboard', ['department_id' => $dept->id]) }}"
                       x-show="!search || '{{ strtolower($dept->name) }}'.includes(search.toLowerCase())"
                       class="px-4 py-2 rounded-2xl text-xs font-extrabold whitespace-nowrap transition-all duration-200 flex items-center gap-2 shadow-sm
                              {{ $isSelected 
                                 ? 'bg-emerald-800 text-white ring-2 ring-emerald-500 shadow-md scale-[1.02]' 
                                 : 'bg-emerald-50/70 text-emerald-950 hover:bg-emerald-100 hover:text-emerald-900 border border-emerald-200/60' }}">
                        <span>🏢 {{ $dept->name }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black
                                     {{ $isSelected ? 'bg-emerald-950 text-emerald-200' : 'bg-emerald-200/80 text-emerald-900' }}">
                            {{ $deptPresent }} Present · {{ max(0, $deptTotal - $deptPresent) }} Absent
                        </span>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- 📌 Department Breakdown Cards -->
        @if($selectedDepartmentId === 'all' && $departmentStats->isNotEmpty())
            <div class="space-y-3">
                <h3 class="text-xs font-black uppercase text-emerald-900 tracking-wider flex items-center gap-1.5 px-1">
                    <span>📌</span> Real-Time Department Entry Status
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($departmentStats as $stat)
                        <a href="{{ route('supervisor.dashboard', ['department_id' => $stat->id]) }}" 
                           x-show="!search || '{{ strtolower($stat->name) }}'.includes(search.toLowerCase())"
                           class="bg-white p-4 rounded-2xl border border-emerald-100 shadow-sm hover:shadow-md hover:border-emerald-400 transition-all duration-200 group flex flex-col justify-between space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="font-extrabold text-gray-900 text-sm group-hover:text-emerald-700 transition">
                                    🏢 {{ $stat->name }}
                                </span>
                                <span class="text-xs font-black px-2.5 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                                    {{ $stat->total_interns }} Interns
                                </span>
                            </div>

                            <div class="flex items-end justify-between border-t border-emerald-50 pt-2.5">
                                <div>
                                    <p class="text-[10px] font-black uppercase text-gray-400">Entered Today</p>
                                    <p class="text-lg font-black text-emerald-600">{{ $stat->present_today }} Present</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black uppercase text-gray-400">Pending</p>
                                    <p class="text-sm font-extrabold text-rose-500">{{ $stat->absent_today }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- 📊 Counters Grid -->
        <div class="grid grid-cols-3 gap-3 sm:gap-6">
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-emerald-100 shadow-sm text-center">
                <p class="text-xs sm:text-sm font-black uppercase text-gray-500">Total Interns</p>
                <p class="text-2xl sm:text-4xl font-black text-slate-800 mt-1">{{ $totalInterns }}</p>
            </div>
            <div class="bg-emerald-50/90 p-4 sm:p-5 rounded-2xl border border-emerald-200 shadow-sm text-center">
                <p class="text-xs sm:text-sm font-black uppercase text-emerald-800">Present Today</p>
                <p class="text-2xl sm:text-4xl font-black text-emerald-600 mt-1">{{ $presentCount }}</p>
            </div>
            <div class="bg-rose-50/90 p-4 sm:p-5 rounded-2xl border border-rose-200 shadow-sm text-center">
                <p class="text-xs sm:text-sm font-black uppercase text-rose-800">Absent / Pending</p>
                <p class="text-2xl sm:text-4xl font-black text-rose-600 mt-1">{{ $absentCount }}</p>
            </div>
        </div>

        <!-- 📋 Real-Time Intern List Table -->
        <div class="bg-white rounded-3xl border border-emerald-100 shadow-xl overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-emerald-50 flex justify-between items-center bg-emerald-50/30">
                <h2 class="text-base sm:text-lg font-black text-gray-900 flex items-center gap-2">
                    <span>📋</span> {{ $departmentName }} Interns Status
                </h2>
                <button onclick="window.location.reload()" class="text-xs font-extrabold text-emerald-800 hover:text-emerald-950 flex items-center gap-1 bg-emerald-100/80 hover:bg-emerald-200 px-3.5 py-2 rounded-xl border border-emerald-300/60 transition shadow-sm">
                    🔄 Refresh
                </button>
            </div>

            @if($internList->isEmpty())
                <div class="p-12 text-center text-gray-400 font-medium text-sm italic">
                    No OJT interns are currently assigned to {{ $departmentName }}.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-700">
                        <thead class="bg-emerald-900 text-white text-xs font-black uppercase tracking-wider">
                            <tr>
                                <th class="py-4 px-6">Intern Name</th>
                                <th class="py-4 px-6">Department</th>
                                <th class="py-4 px-6">Status Today</th>
                                <th class="py-4 px-6">AM IN</th>
                                <th class="py-4 px-6">AM OUT</th>
                                <th class="py-4 px-6">PM IN</th>
                                <th class="py-4 px-6">PM OUT</th>
                                <th class="py-4 px-6 text-right">Reports & Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-50 font-medium">
                            @foreach($internList as $intern)
                                <tr x-show="!search || '{{ strtolower($intern->name . ' ' . $intern->email . ' ' . $intern->department) }}'.includes(search.toLowerCase())"
                                    class="hover:bg-emerald-50/40 transition-colors">
                                    <td class="py-4 px-6">
                                        <div class="font-extrabold text-gray-900">{{ $intern->name }}</div>
                                        <div class="text-xs text-gray-400 font-semibold">{{ $intern->email }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1 bg-emerald-50 text-emerald-900 rounded-xl text-xs font-extrabold border border-emerald-200/80">
                                            🏢 {{ $intern->department }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="px-3 py-1 rounded-full text-xs font-black inline-block 
                                            @if(str_contains($intern->status, 'Completed') || $intern->status === 'Timed Out') bg-blue-100 text-blue-900
                                            @elseif(str_contains($intern->status, 'Checked In') || $intern->status === 'On Premises') bg-emerald-100 text-emerald-900 animate-pulse
                                            @else bg-rose-100 text-rose-900 @endif">
                                            {{ $intern->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 font-mono text-xs font-bold text-gray-800">{{ $intern->am_in }}</td>
                                    <td class="py-4 px-6 font-mono text-xs font-bold text-gray-800">{{ $intern->am_out }}</td>
                                    <td class="py-4 px-6 font-mono text-xs font-bold text-gray-800">{{ $intern->pm_in }}</td>
                                    <td class="py-4 px-6 font-mono text-xs font-bold text-gray-800">{{ $intern->pm_out }}</td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('ojt.print-daily', $intern->id) }}" target="_blank"
                                               class="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-extrabold rounded-xl text-xs transition border border-emerald-200">
                                                🖨️ Daily
                                            </a>
                                            <a href="{{ route('ojt.print-monthly', $intern->id) }}" target="_blank"
                                               class="px-3.5 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-800 font-extrabold rounded-xl text-xs transition border border-teal-200">
                                                📊 Monthly
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection