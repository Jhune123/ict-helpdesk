@extends('layouts.app')

@section('content')
@php
    // Fallback data mapping from your incident model or json payload
    $equipment = json_decode($incident->equipment_details, true) ?? [];
@endphp

<style>
    /* Document View Styling */
    .doc-wrapper { min-height: 100vh; background-color: #f8fafc; padding: 2rem 1rem; }
    .doc-container { max-width: 850px; margin: 0 auto; background: #fff; padding: 40px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); font-family: Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #000; }
    
    .doc-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .doc-table th, .doc-table td { border: 1px solid #000; padding: 6px 8px; }
    
    .header-table td { vertical-align: middle; }
    .logo { width: 55px; height: auto; display: inline-block; margin: 0 3px; vertical-align: middle; }
    
    .section-title { background: #f2f2f2; font-weight: bold; text-transform: uppercase; padding: 5px 8px; border: 1px solid #000; border-bottom: none; }
    .section-body { border: 1px solid #000; padding: 12px; margin-bottom: 15px; }
    
    /* Interactive elements styled as blank form entries */
    .doc-data { border-bottom: 1px solid #000; display: inline-block; width: 100%; font-weight: bold; padding: 2px 4px; min-height: 18px; }
    
    /* Print Media Overrides */
    @media print {
        .doc-wrapper { background-color: transparent; padding: 0; }
        .doc-container { box-shadow: none; max-width: 100%; padding: 0; margin: 0; }
        .no-print { display: none !important; }
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>

<div class="doc-wrapper">
    {{-- Floating Action Bar (Hidden on Print) --}}
    <div class="no-print max-w-4xl mx-auto mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 rounded-lg shadow-sm border border-slate-200">
        <a href="{{ route('tickets.index') }}" class="flex items-center text-slate-500 hover:text-blue-600 transition font-bold text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Tickets
        </a>
        <div class="flex flex-wrap gap-3">
            <button onclick="window.print()" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-xl font-bold shadow-sm transition flex items-center text-sm cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Incident Report
            </button>
            @if(Auth::user()->id === $incident->user_id || Auth::user()->hasAnyRole('admin|it_staff'))
            <a href="{{ route('tickets.edit', $incident->id) }}" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-xl font-bold shadow-sm transition flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Report
            </a>
            @endif
        </div>
    </div>

    <div class="doc-container">
        {{-- KSU Document Header Layout --}}
        <table class="doc-table header-table">
            <tr>
                <td rowspan="4" style="width: 140px; text-align: center; white-space: nowrap;">
                    @if(file_exists(public_path('image/school-logo.jpg')))
                        <img src="{{ asset('image/school-logo.jpg') }}" class="logo" alt="KSU Logo">
                    @endif
                    @if(file_exists(public_path('image/Bagong-Pilipinas.png')))
                        <img src="{{ asset('image/Bagong-Pilipinas.png') }}" class="logo" alt="Bagong Pilipinas Logo">
                    @endif
                </td>
                <td style="font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase;">Kalinga State University</td>
                <td style="width: 180px; font-size: 9px;"><strong>Doc. Ref No.:</strong> KSU-ICTO-QF-06</td>
            </tr>
            <tr>
                <td style="font-size: 10px; text-align: center; font-weight: bold; text-transform: uppercase;">INFORMATION AND COMMUNICATIONS TECHNOLOGY OFFICE</td>
                <td style="font-size: 9px;"><strong>Effectivity Date:</strong> March 24, 2026</td>
            </tr>
            <tr>
                <td style="background:#f2f2f2; font-size: 11px; text-align: center; font-weight: bold; text-transform: uppercase;">Incident Report Form</td>
                <td style="font-size: 9px;"><strong>Revision No.:</strong> 3.0</td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 9px; font-weight: bold;">ICT CENTER</td>
                <td style="font-size: 9px;"><strong>Page No.:</strong> 1</td>
            </tr>
        </table>

        <div style="margin-bottom: 15px; text-align: right; font-size: 12px;">
            <strong>Request No.:</strong> <span style="border-bottom: 1px solid #000; padding: 0 15px; font-weight: bold;">{{ $incident->ticket_number ?? str_pad($incident->id, 6, '0', STR_PAD_LEFT) }}</span>
            <div style="font-size: 8.5px; color: #555; font-style: italic; margin-right: 5px;">To be filled by KSU ICT User</div>
        </div>

        {{-- 1. EMPLOYEE INFORMATION --}}
        <div class="section-title">1. Employee Information</div>
        <div class="section-body">
            <table style="width: 100%; border: none; border-collapse: collapse;">
                <tr>
                    <td style="width: 15%; padding: 5px 0;">User's Full Name:</td>
                    <td colspan="3" style="padding: 5px;"><span class="doc-data">{{ $incident->user->name ?? $incident->reporter_name }}</span></td>
                </tr>
                <tr>
                    <td style="width: 15%; padding: 5px 0;">Office & Position:</td>
                    <td style="width: 40%; padding: 5px;"><span class="doc-data">{{ $incident->office_name_position ?? 'N/A' }}</span></td>
                    <td style="width: 12%; padding: 5px 0; text-align: right;">Contact No.:</td>
                    <td style="width: 33%; padding: 5px;"><span class="doc-data">{{ $incident->contact_number ?? 'N/A' }}</span></td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;">Email Address:</td>
                    <td colspan="3" style="padding: 5px;"><span class="doc-data">{{ $incident->user->email ?? $incident->reporter_email }}</span></td>
                </tr>
            </table>
        </div>

        {{-- 2. INCIDENT DETAILS --}}
        <div class="section-title">2. Incident Details</div>
        <div class="section-body">
            <table style="width: 100%; border: none; border-collapse: collapse;">
                <tr>
                    <td style="width: 15%; padding: 5px 0;">Date of Incident:</td>
                    <td style="width: 25%; padding: 5px;"><span class="doc-data">{{ \Carbon\Carbon::parse($incident->incident_date)->format('F d, Y') }}</span></td>
                    <td style="width: 15%; padding: 5px 0; text-align: right;">Time of Incident:</td>
                    <td style="width: 20%; padding: 5px;"><span class="doc-data">{{ \Carbon\Carbon::parse($incident->incident_time)->format('h:i A') }}</span></td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;">Location:</td>
                    <td colspan="3" style="padding: 5px;"><span class="doc-data">{{ $incident->location ?? 'N/A' }}</span></td>
                </tr>
            </table>
        </div>

        {{-- 3. INCIDENT DESCRIPTION --}}
        <div class="section-title">3. Incident Description</div>
        <div class="section-body" style="min-height: 90px; white-space: pre-line; line-height: 1.6;"><strong>{{ $incident->description }}</strong></div>

        {{-- 4. DAMAGED/STOLEN EQUIPMENT INFORMATION --}}
        <div class="section-title">4. Damaged/Stolen Equipment Information</div>
        <div class="section-body">
            <table style="width: 100%; border: none; border-collapse: collapse;">
                <tr>
                    <td style="width: 18%; padding: 5px 0;">Equipment Name/Type:</td>
                    <td style="width: 47%; padding: 5px;"><span class="doc-data">{{ $equipment['type'] ?? 'N/A' }}</span></td>
                    <td style="width: 10%; padding: 5px 0; text-align: right;">Quantity:</td>
                    <td style="width: 25%; padding: 5px;"><span class="doc-data">{{ $equipment['quantity'] ?? 'N/A' }}</span></td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;">Serial Number:</td>
                    <td colspan="3" style="padding: 5px;"><span class="doc-data">{{ $equipment['serial_number'] ?? 'N/A' }}</span></td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; vertical-align: top;">Remarks:</td>
                    <td colspan="3" style="padding: 5px;"><span class="doc-data" style="min-height: 35px;">{{ $equipment['remarks'] ?? 'N/A' }}</span></td>
                </tr>
            </table>
        </div>

        {{-- 5. ACTIONS TAKEN --}}
        <div class="section-title">5. Actions Taken</div>
        <div class="section-body" style="min-height: 70px; white-space: pre-line; line-height: 1.6;"><strong>{{ $incident->actions_taken ?: 'Pending IT Staff investigation and initial deployment steps.' }}</strong></div>

        {{-- Signatures Matrix --}}
        <div style="font-size: 9.5px; font-style: italic; margin-bottom: 15px; color: #333;">
            By submitting this form, you acknowledge that the information provided is accurate and complete.
        </div>

        <table style="width: 100%; border-collapse: collapse; border: none; margin-top: 15px;">
            <tr>
                {{-- Prepared By --}}
                <td style="width: 50%; border: none; padding-right: 20px; vertical-align: top;">
                    <strong>Prepared by:</strong>
                    <div style="margin-top: 35px; text-align: center;">
                        <div style="border-bottom: 1px solid #000; width: 90%; margin: 0 auto; font-weight: bold; text-transform: uppercase; padding-bottom: 2px;">
                            {{ $incident->user->name ?? $incident->reporter_name }}
                        </div>
                        <div style="font-size: 9px; margin-top: 3px;">Employee Name, Signature & Date</div>
                    </div>
                </td>
                {{-- Conformed By --}}
                <td style="width: 50%; border: none; padding-left: 20px; vertical-align: top;">
                    <strong>Conformed by:</strong>
                    <div style="margin-top: 35px; text-align: center;">
                        <div style="border-bottom: 1px solid #000; width: 90%; margin: 0 auto; font-weight: bold; text-transform: uppercase; padding-bottom: 2px;">
                            {{ $incident->supervisor_name ?? '' }}
                        </div>
                        <div style="font-size: 9px; margin-top: 3px;">Supervisor Name, Signature & Date</div>
                    </div>
                </td>
            </tr>
            <tr>
                {{-- User Acknowledgement Block --}}
                <td colspan="2" style="border: 1px solid #000; margin-top: 30px; padding: 12px; vertical-align: top;">
                    <strong style="display: block; margin-bottom: 5px; text-transform: uppercase; font-size: 10px;">User Acknowledgement</strong>
                    <p style="font-size: 9px; margin: 0 0 25px 0; line-height: 1.4;">
                        I, the undersigned, hereby acknowledge that the ICT services requested have been completed to my satisfaction.
                    </p>
                    <table style="width: 100%; border: none; border-collapse: collapse;">
                        <tr>
                            <td style="width: 65%; border: none; padding: 0; text-align: center;">
                                <div style="border-bottom: 1px solid #000; width: 85%; margin: 0 auto; min-height: 15px;"></div>
                                <span style="font-size: 8.5px; color: #444;">Signature over Printed Name</span>
                            </td>
                            <td style="width: 35%; border: none; padding: 0; text-align: center;">
                                <div style="border-bottom: 1px solid #000; width: 85%; margin: 0 auto; min-height: 15px;"></div>
                                <span style="font-size: 8.5px; color: #444;">Date</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection