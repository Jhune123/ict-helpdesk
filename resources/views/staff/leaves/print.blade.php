@extends('layouts.print')

@section('content')
@php
    $applicantName = trim(implode(' ', array_filter([
        $leave->applicant_first_name,
        $leave->applicant_middle_name,
        $leave->applicant_last_name,
    ]))) ?: $leave->user->name;
@endphp
<style>
    @page { size: A4 portrait; margin: 12mm; }
    * { box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 9px; margin: 0 auto; max-width: 186mm; color: #111; }
    h1, h2, h3, p { margin: 2px 0; }
    h1, h2, .center { text-align: center; }
    h1 { font-size: 18px; }
    h2 { font-size: 13px; }
    .form-header { position: relative; min-height: 43mm; padding-top: 2mm; text-align: center; }
    .form-reference { position: absolute; top: 0; left: 0; text-align: left; font-weight: bold; font-style: italic; line-height: 1.5; }
    .annex { position: absolute; top: 0; right: 0; font-weight: bold; }
    .university { padding-top: 9mm; font-size: 18px; font-weight: bold; line-height: 1.2; }
    .republic { font-size: 10px; font-weight: normal; }
    .header-logo { position: absolute; top: 11mm; width: 25mm; height: 25mm; object-fit: contain; }
    .ksu-logo { left: 27mm; }
    .bagong-logo { right: 27mm; }
    .campus { font-size: 16px; }
    .address { margin-top: 2px; font-size: 12px; }
    .receipt-stamp { position: absolute; right: 0; bottom: 11mm; width: 25mm; height: 17mm; border: 1px solid #aaa; color: #666; text-align: center; font-size: 8px; font-style: italic; padding: 3px; }
    .form-title { margin: 0; padding: 2mm 0 1mm; border-bottom: 1px solid #222; }
    .filing { margin: 5px 0; display: flex; justify-content: space-between; }
    table { width: 100%; border-collapse: collapse; margin: 4px 0; table-layout: fixed; }
    th, td { border: 1px solid #222; padding: 4px; vertical-align: top; overflow-wrap: anywhere; }
    th { text-align: left; background: #f3f4f6; }
    .signature { margin: 18px auto 0; width: 85%; border-top: 1px solid #222; padding-top: 3px; text-align: center; font-style: italic; }
    .section-seven { margin-top: 6px; border: 2px solid #111; }
    .section-seven-title { padding: 5px; border-bottom: 2px solid #111; text-align: center; font-size: 12px; font-weight: bold; }
    .section-seven-top { display: grid; grid-template-columns: 1.2fr 1fr; min-height: 68mm; }
    .section-seven-bottom { display: grid; grid-template-columns: 1.2fr 1fr; min-height: 47mm; border-top: 2px solid #111; }
    .seven-cell { position: relative; padding: 5px; }
    .section-seven-top .seven-cell:first-child, .section-seven-bottom .seven-cell:first-child { border-right: 2px solid #111; }
    .seven-heading { font-size: 10px; font-weight: bold; margin-bottom: 5px; }
    .credit-date { margin: 6px 0 4px 18px; }
    .credit-table { width: 92%; margin: 0 auto; }
    .credit-table th, .credit-table td { border: 2px solid #111; padding: 4px; }
    .credit-table thead th { text-align: center; }
    .credit-table .row-label { background: #e5e5e5; font-style: italic; }
    .seven-signature { position: absolute; right: 5px; bottom: 5px; left: 5px; text-align: center; font-style: italic; }
    .signature-line { border-top: 1px solid #555; margin: 0 auto 3px; width: 75%; }
    .recommendation { margin: 12mm 0 0 8mm; font-size: 10px; }
    .action-lines { margin: 5px 5mm 0 12mm; }
    .action-line { height: 7mm; border-bottom: 1px solid #777; }
    .approved-line { margin: 5px 0; font-size: 10px; }
    .approval-options { margin: 4px 0 0 12mm; font-style: italic; font-size: 9px; }
    .officer-signature { margin: 12mm auto 0; width: 80%; text-align: center; font-style: italic; }
    .action { margin-bottom: 12px; }
    tr, th, td, .section-seven { break-inside: avoid; }
    @media print {
        .action { display: none; }
        body { width: auto; max-width: none; margin: 0; }
    }
</style>
<div class="action"><button type="button" onclick="window.print()">Print this application</button></div>
<header class="form-header">
    <div class="form-reference">Civil Service Form No. 6<br>Revised 2020<br><small>KSU-QF-HRMO-6</small></div>
    <div class="annex">ANNEX A</div>
    <img class="header-logo ksu-logo" src="{{ asset('image/KSU-logo.png') }}" alt="Kalinga State University logo">
    <img class="header-logo bagong-logo" src="{{ asset('image/Bagong-Pilipinas.png') }}" alt="Bagong Pilipinas logo">
    <div class="university"><span class="republic">Republic of the Philippines</span><br>KALINGA STATE UNIVERSITY<br><span class="campus">MAIN CAMPUS</span></div>
    <p class="address">National Highway, Purok 6, Bulanao, City of Tabuk, Kalinga</p>
    <div class="receipt-stamp">Stamp of Date of<br>Receipt</div>
</header>
<h1 class="form-title">APPLICATION FOR LEAVE</h1>
<div class="filing"><span>3. DATE OF FILING: {{ $leave->filed_at->format('m/d/Y') }}</span><span>STATUS: {{ strtoupper($leave->status) }}</span></div>

<table>
    <tr>
        <th>1. OFFICE / DEPARTMENT</th><th>2. NAME (Last, First, Middle)</th><th>4. POSITION / 4.A DESIGNATION</th><th>5. MONTHLY BASIC SALARY</th>
    </tr>
    <tr>
        <td>{{ $leave->office_department ?: '—' }}</td>
        <td>{{ $leave->applicant_last_name }}, {{ $leave->applicant_first_name }} {{ $leave->applicant_middle_name }}</td>
        <td>{{ $leave->position ?: '—' }}<br>{{ $leave->designation ?: '—' }}</td>
        <td>{{ $leave->monthly_basic_salary ? '₱'.number_format((float) $leave->monthly_basic_salary, 2) : '—' }}</td>
    </tr>
</table>
<table>
    <tr><th>6.A TYPE OF LEAVE TO BE AVAILED OF</th><th>6.B DETAILS OF LEAVE</th></tr>
    <tr>
        <td>{{ $leave->leave_type }}@if($leave->other_leave_type)<br>{{ $leave->other_leave_type }}@endif</td>
        <td style="white-space: pre-line">{{ $leave->details ?: '—' }}</td>
    </tr>
</table>
<table>
    <tr><th>6.C NUMBER OF WORKING DAYS APPLIED FOR</th><th>INCLUSIVE DATES</th><th>6.D COMMUTATION</th></tr>
    <tr>
        <td>{{ $leave->working_days }}</td>
        <td>{{ $leave->start_date->format('m/d/Y') }} – {{ $leave->end_date->format('m/d/Y') }}</td>
        <td>{{ $leave->commutation_requested ? 'Requested' : 'Not Requested' }}<div class="signature">{{ $leave->applicant_signature }}<br>Signature of Applicant</div></td>
    </tr>
</table>
<section class="section-seven">
    <h2 class="section-seven-title">7. DETAILS OF ACTION ON APPLICATION</h2>
    <div class="section-seven-top">
        <div class="seven-cell">
            <h3 class="seven-heading">7.A CERTIFICATION OF LEAVE CREDITS</h3>
            <p class="credit-date">As of <em>(mm/dd/yyyy)</em> __________________</p>
            <table class="credit-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Vacation Leave</th>
                        <th>Sick Leave</th>
                        <th>Teacher's Leave</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(['Total Earned', 'Less this application', 'Balance'] as $label)
                        <tr>
                            <th class="row-label">{{ $label }}</th>
                            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                        </tr>
                    @endforeach
                    <tr><th class="row-label">Total (VL+SL+TL)</th><td colspan="3">&nbsp;</td></tr>
                </tbody>
            </table>
            <div class="seven-signature"><div class="signature-line"></div>Human Resource Management Officer</div>
        </div>
        <div class="seven-cell">
            <h3 class="seven-heading">7.B RECOMMENDATION</h3>
            <div class="recommendation">
                <p>☐ &nbsp; For approval</p>
                <p>☐ &nbsp; For disapproval due to __________________________</p>
            </div>
            <div class="action-lines"><div class="action-line"></div><div class="action-line"></div></div>
            <div class="seven-signature"><div class="signature-line"></div>Signature over complete printed name of immediate supervisor</div>
        </div>
    </div>
    <div class="section-seven-bottom">
        <div class="seven-cell">
            <h3 class="seven-heading">7.C APPROVED FOR:</h3>
            <p class="approved-line">______ days with pay</p>
            <p class="approved-line">______ days without pay</p>
            <p class="approved-line">______ others <em>(specify or check details below)</em> __________________</p>
            <div class="approval-options">
                <p>[ &nbsp; ] Leave credit/s deduction</p>
                <p>[ &nbsp; ] Salary deduction</p>
                <p>[ &nbsp; ] Withholding of salary</p>
                <p><em>(CSC Res. No. 920302, d. 02/20/1992)</em></p>
            </div>
            <div class="officer-signature"><div class="signature-line"></div>Signature over printed name of Authorized Officer</div>
        </div>
        <div class="seven-cell">
            <h3 class="seven-heading">7.D DISAPPROVED DUE TO: __________________________</h3>
            <div class="action-lines"><div class="action-line"></div><div class="action-line"></div><div class="action-line"></div></div>
        </div>
    </div>
</section>
@endsection

@section('title', 'Print Leave Application')
