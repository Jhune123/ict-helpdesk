@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        
        {{-- Navigation Header --}}
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('tickets.index') }}" class="flex items-center text-slate-500 hover:text-slate-800 transition font-bold text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Tickets List
            </a>
            <div class="text-right text-xs text-slate-400 font-mono hidden sm:block">
                <div>Doc Ref: KSU-ICTO-QF-06</div>
                <div>Rev: 3.0 | March 24, 2026</div>
            </div>
        </div>

        <form action="{{ route('tickets.store') }}" method="POST" class="bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-200">
            @csrf

            {{-- Form Header Branding --}}
            <div class="bg-slate-900 px-8 py-6 text-white border-b border-slate-800">
                <div class="flex items-center gap-4">
                    <div class="bg-rose-500 p-2.5 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <span class="text-[10px] uppercase tracking-widest font-bold text-rose-400 block font-mono">Kalinga State University • ICTO</span>
                        <h2 class="text-xl font-black tracking-tight">File an Incident Report</h2>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-8">
                
                {{-- 1. EMPLOYEE INFORMATION --}}
                <section>
                    <div class="flex items-center gap-2 mb-4 border-b pb-2">
                        <span class="bg-slate-800 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold font-mono">1</span>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider">Employee Information</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">User's Full Name</label>
                            <input type="text" name="reporter_name" value="{{ old('reporter_name', Auth::user()->name ?? '') }}" required
                                class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-800 focus:border-rose-500 focus:ring-rose-500 font-medium text-sm p-3 shadow-inner border">
                            @error('reporter_name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Email Address</label>
                            <input type="email" name="reporter_email" value="{{ old('reporter_email', Auth::user()->email ?? '') }}" required
                                class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-800 focus:border-rose-500 focus:ring-rose-500 font-medium text-sm p-3 shadow-inner border">
                            @error('reporter_email') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Office Name & Position</label>
                            <input type="text" name="office_name_position" value="{{ old('office_name_position') }}" placeholder="e.g., CARES - Instructor I" required
                                class="w-full rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500 text-sm p-3 border">
                            @error('office_name_position') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Contact Number</label>
                            <input type="text" name="contact_number" value="{{ old('contact_number') }}" placeholder="e.g., 09123456789" required
                                class="w-full rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500 font-mono text-sm p-3 border">
                            @error('contact_number') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>


                {{-- 2. INCIDENT DETAILS --}}
                <section>
                    <div class="flex items-center gap-2 mb-4 border-b pb-2">
                        <span class="bg-slate-800 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold font-mono">2</span>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider">Incident Occurrence Details</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Date of Incident</label>
                            <input type="date" name="incident_date" value="{{ old('incident_date', date('Y-m-d')) }}" required
                                class="w-full rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500 text-sm p-3 border">
                            @error('incident_date') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Time of Incident</label>
                            <input type="time" name="incident_time" value="{{ old('incident_time', date('H:i')) }}" required
                                class="w-full rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500 text-sm p-3 border">
                            @error('incident_time') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Specific Location</label>
                            <input type="text" name="location" value="{{ old('location') }}" placeholder="e.g., Library Computer Room" required
                                class="w-full rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500 text-sm p-3 border">
                            @error('location') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>


                {{-- 3. INCIDENT DESCRIPTION --}}
                <section>
                    <div class="flex items-center gap-2 mb-4 border-b pb-2">
                        <span class="bg-slate-800 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold font-mono">3</span>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider">Incident Description</h3>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Detailed Narrative of the Event</label>
                        <textarea name="description" rows="4" placeholder="Describe clearly how the incident happened, indicators observed, or errors seen..." required
                            class="w-full rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500 text-sm p-3 border">{{ old('description') }}</textarea>
                        @error('description') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </section>


                {{-- 4. DAMAGED/STOLEN EQUIPMENT INFORMATION --}}
                <section class="bg-slate-50 border border-slate-200/60 p-5 rounded-xl shadow-inner">
                    <div class="flex items-center gap-2 mb-4 border-b border-slate-200 pb-2">
                        <span class="bg-slate-800 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold font-mono">4</span>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider">Damaged / Stolen Equipment Details <span class="text-[10px] font-normal text-slate-400 italic lowercase">(optional)</span></h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Equipment Name / Type</label>
                            <input type="text" name="equipment_details[type]" value="{{ old('equipment_details.type') }}" placeholder="e.g., EPSON L3210 Printer / Smart TV"
                                class="w-full rounded-xl border-slate-300 bg-white focus:border-rose-500 focus:ring-rose-500 text-sm p-3 border">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Quantity</label>
                            <input type="number" name="equipment_details[quantity]" value="{{ old('equipment_details.quantity') }}" placeholder="1" min="1"
                                class="w-full rounded-xl border-slate-300 bg-white focus:border-rose-500 focus:ring-rose-500 text-sm p-3 border">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Equipment Serial Number</label>
                            <input type="text" name="equipment_details[serial_number]" value="{{ old('equipment_details.serial_number') }}" placeholder="e.g., X2CF094321"
                                class="w-full rounded-xl border-slate-300 bg-white focus:border-rose-500 focus:ring-rose-500 font-mono text-sm p-3 border">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wide mb-1">Equipment State / Remarks</label>
                            <input type="text" name="equipment_details[remarks]" value="{{ old('equipment_details.remarks') }}" placeholder="e.g., Physical damage on port, stolen asset, or non-responsive hardware"
                                class="w-full rounded-xl border-slate-300 bg-white focus:border-rose-500 focus:ring-rose-500 text-sm p-3 border">
                        </div>
                    </div>
                </section>


                {{-- 5. ACTIONS TAKEN (Conditional Access for Staff/Admin logs) --}}
                @hasanyrole('admin|it_staff')
                <section class="border-l-4 border-amber-400 bg-amber-50/40 p-5 rounded-r-xl border border-slate-200">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="bg-amber-500 text-white w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold font-mono">5</span>
                        <h3 class="text-sm font-black text-amber-900 uppercase tracking-wider">Initial Actions Taken <span class="text-[10px] text-amber-600 lowercase font-bold font-sans">(ICT Staff Field Only)</span></h3>
                    </div>
                    <textarea name="actions_taken" rows="2" placeholder="Record immediate mitigation steps applied..."
                        class="w-full rounded-xl border-slate-300 bg-white focus:border-amber-500 focus:ring-amber-500 text-sm p-3 border">{{ old('actions_taken') }}</textarea>
                </section>
                @endhasanyrole


                {{-- Immediate Validation Consent Checkbox --}}
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 flex items-start gap-3">
                    <input type="checkbox" id="compliance_check" required class="mt-1 h-4 w-4 text-rose-600 focus:ring-rose-500 border-slate-300 rounded cursor-pointer">
                    <label for="compliance_check" class="text-xs text-slate-500 leading-relaxed select-none cursor-pointer">
                        By checking this box and submitting this digital report form, you certify and acknowledge that all statement logs, device descriptions, and associated event asset details provided are accurate, verified, and complete under standard Kalinga State University parameters.
                    </label>
                </div>

            </div>

            {{-- Form Submit Action Bar --}}
            <div class="bg-slate-50 px-8 py-5 border-t border-slate-200 flex flex-col sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('tickets.index') }}" class="w-full sm:w-auto px-5 py-3 rounded-xl border border-slate-300 text-slate-700 bg-white hover:bg-slate-100 font-bold text-center text-sm transition">
                    Cancel
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-center text-sm shadow-md hover:shadow-lg active:scale-95 transition cursor-pointer">
                    File Incident Form
                </button>
            </div>

        </form>
    </div>
</div>
@endsection