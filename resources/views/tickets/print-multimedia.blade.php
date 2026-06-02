<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            /* DejaVu Sans ensures Unicode checkmarks render correctly in DomPDF */
            font-family: "DejaVu Sans", Arial, sans-serif;
            line-height: 1.6;
            margin: 40px;
            font-size: 13px;
            color: #000;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            text-align: center;
        }
        .header-table th, .header-table td {
            border: 1px solid #000;
            padding: 8px;
        }
        .header-logo {
            width: 26%;
            vertical-align: middle;
            white-space: nowrap;
        }
        .header-logo img {
            max-width: 65px;
            height: auto;
            display: inline-block;
            vertical-align: middle;
            margin: 0 4px;
        }
        .header-title {
            width: 44%;
            font-weight: bold;
            vertical-align: middle;
        }
        .header-meta {
            width: 30%;
            text-align: left;
            font-size: 11px;
            vertical-align: middle;
            padding-left: 10px;
        }
        .form-section {
            margin-top: 20px;
        }
        .form-section h3 {
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .field-row {
            margin-bottom: 6px;
        }
        .underline-value {
            display: inline-block;
            border-bottom: 1px solid #000;
            padding: 0 5px;
            font-weight: bold;
        }
        .checklist-container {
            margin-left: 40px;
            margin-top: 10px;
        }
        .checklist-table {
            border-collapse: collapse;
        }
        .checklist-table td {
            padding: 5px 0;
            vertical-align: middle;
        }
        .check-box-cell {
            width: 18px;
            height: 18px;
            border: 1px solid #000;
            text-align: center;
            display: inline-block;
            font-weight: bold;
            line-height: 18px;
            font-size: 14px;
        }
        .check-label-cell {
            padding-left: 10px !important;
        }
        .footer-note {
            margin-top: 25px;
            text-align: justify;
            font-size: 12px;
        }
        
        /* Signature Layout Elements */
        .signature-block {
            text-align: center;
            width: 280px;
            margin-top: 10px;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            height: 25px;
            margin-bottom: 5px;
        }
        .signature-subtitle {
            font-size: 12px;
            color: #333;
        }
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .form-table td {
            padding: 5px 0;
            vertical-align: bottom;
        }
        .form-label {
            white-space: nowrap;
            padding-right: 10px;
        }
        .form-line {
            border-bottom: 1px solid #000;
            width: 100%;
            display: inline-block;
            min-height: 18px;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td rowspan="4" class="header-logo">
                <img src="{{ public_path('image/school-logo.jpg') }}" alt="KSU Logo">
                <img src="{{ public_path('image/Bagong-Pilipinas.png') }}" alt="Bagong Pilipinas Logo">
            </td>
            <td rowspan="2" class="header-title">
                <div style="font-size: 18px;">Kalinga State University</div>
                <div style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-top: 3px;">Information and Communications Technology Office</div>
            </td>
            <td class="header-meta">Doc. Ref No.: <strong>KSU-ICTO-QF-03</strong></td>
        </tr>
        <tr>
            <td class="header-meta">Effectivity Date: March 24, 2026</td>
        </tr>
        <tr>
            <td rowspan="2" class="header-title" style="font-size: 14px; text-transform: uppercase;">Multimedia Request Form</td>
            <td class="header-meta">Revision No.: 3.0</td>
        </tr>
        <tr>
            <td class="header-meta">Page No.: 1</td>
        </tr>
    </table>

    <div class="field-row">
        Request No.: <span class="underline-value" style="min-width: 250px;">{{ $ticket->ticket_number ?? '' }}</span><br>
        <small><em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To be filled by KSU ICT User</em></small>
    </div>

    <div class="form-section">
        <h3>1. REQUESTOR INFORMATION</h3>
        <div class="field-row">Date Requested: <span class="underline-value" style="width: 70%;">{{ $ticket->created_at ? $ticket->created_at->format('F d, Y') : '' }}</span></div>
        <div class="field-row">User’s Full Name: <span class="underline-value" style="width: 70%;">{{ $ticket->client_name ?? '' }}</span></div>
        <div class="field-row">User’s Office Name & Address: <span class="underline-value" style="width: 60%;">{{ $ticket->department ?? '' }}</span></div>
        <div class="field-row">User’s Contact Number: <span class="underline-value" style="width: 65%;">{{ $ticket->contact_number ?? '' }}</span></div>
        <div class="field-row">Email Address: <span class="underline-value" style="width: 72%;">{{ $ticket->form_data['email'] ?? ($ticket->user->email ?? '') }}</span></div>
    </div>

    @php
        /** * FIX: Ensuring $reqTypes is safely handled even if missing */
        $reqTypes = $ticket->form_data['request_type'] ?? [];
        $reqTypesStr = is_array($reqTypes) ? implode(',', $reqTypes) : (string)$reqTypes;
        $checkMark = '&#10004;'; 
    @endphp

    <div class="form-section">
        <h3>2. REQUEST DETAILS</h3>
        <div class="field-row">Project Name: <span class="underline-value" style="width: 75%;">{{ $ticket->title ?? '' }}</span></div>
        <div class="field-row" style="margin-top: 10px;">Type of Request/Purpose:</div>
        
        <div class="checklist-container">
            <table class="checklist-table">
                <tr>
                    <td><div class="check-box-cell">{!! str_contains($reqTypesStr, 'Photography') ? $checkMark : '&nbsp;' !!}</div></td>
                    <td class="check-label-cell">Photography/Documentation</td>
                </tr>
                <tr>
                    <td><div class="check-box-cell">{!! str_contains($reqTypesStr, 'Videography') ? $checkMark : '&nbsp;' !!}</div></td>
                    <td class="check-label-cell">Videography</td>
                </tr>
                <tr>
                    <td><div class="check-box-cell">{!! str_contains($reqTypesStr, 'Graphic Design') ? $checkMark : '&nbsp;' !!}</div></td>
                    <td class="check-label-cell">Graphic Design</td>
                </tr>
                <tr>
                    <td><div class="check-box-cell">{!! str_contains($reqTypesStr, 'Audio Recording') ? $checkMark : '&nbsp;' !!}</div></td>
                    <td class="check-label-cell">Audio Recording</td>
                </tr>
                <tr>
                    <td><div class="check-box-cell">{!! str_contains($reqTypesStr, 'Live Streaming') ? $checkMark : '&nbsp;' !!}</div></td>
                    <td class="check-label-cell">Live Streaming</td>
                </tr>
                <tr>
                    <td><div class="check-box-cell">{!! str_contains($reqTypesStr, 'Technical Support') ? $checkMark : '&nbsp;' !!}</div></td>
                    <td class="check-label-cell">Technical Support/Assistance</td>
                </tr>
                <tr>
                    <td><div class="check-box-cell">{!! str_contains($reqTypesStr, 'Others') ? $checkMark : '&nbsp;' !!}</div></td>
                    <td class="check-label-cell">Others: <span class="underline-value" style="min-width: 300px;">{{ $ticket->form_data['other_request_type'] ?? '' }}</span></td>
                </tr>
            </table>
        </div>

        <div class="field-row" style="margin-top: 15px;">Location: <span class="underline-value" style="width: 80%;">{{ $ticket->form_data['location'] ?? '' }}</span></div>
        <div class="field-row">Date and Time: <span class="underline-value" style="width: 75%;">{{ $ticket->form_data['schedule_datetime'] ?? '' }}</span></div>
        <div class="field-row">Duration: <span class="underline-value" style="width: 79%;">{{ $ticket->form_data['duration'] ?? '' }}</span></div>
    </div>

    <div class="form-section">
        <h3>3. PROJECT TIMELINE</h3>
        <div class="field-row">Requested Start Date: <span class="underline-value" style="width: 65%;">{{ $ticket->form_data['start_date'] ?? '' }}</span></div>
        <div class="field-row">Requested Completion Date: <span class="underline-value" style="width: 60%;">{{ $ticket->form_data['completion_date'] ?? '' }}</span></div>
        <div class="field-row">Status/Remarks: <span class="underline-value" style="width: 70%;">{{ $ticket->remarks ?? ($ticket->status ?? '') }}</span></div>
        @if(empty($ticket->remarks))
            <div class="field-row" style="border-bottom: 1px solid #000; height: 20px; margin-top: 10px; width: 100%;"></div>
        @endif
    </div>

    <div class="footer-note">
        By submitting this form, you acknowledge that the information provided is accurate and complete. The approval of this request is subject to the approval of the Information and Communications Technology Office.
    </div>

    <div style="margin-top: 20px; margin-bottom: 25px;">
        <span style="font-weight: bold; display: block; margin-bottom: 5px;">Requested by:</span>
        <div class="signature-block">
            <div class="signature-line" style="vertical-align: bottom;">
                <strong>{{ $ticket->client_name ?? '' }}</strong>
            </div>
            <div class="signature-subtitle">User signature over printed name</div>
        </div>
    </div>

    <div class="form-section" style="border-top: 1px dashed #000; padding-top: 15px;">
        <h3 style="font-size: 14px; font-weight: bold;">USER ACKNOWLEDGEMENT</h3>
        <div style="margin-bottom: 15px; font-style: italic; text-align: justify; margin-top: 5px;">
            I, the undersigned, hereby acknowledge that the ICT services requested have been completed to my satisfaction.
        </div>
        
        <table class="form-table">
            <tr>
                <td class="form-label" style="width: 210px;">Signature over printed name:</td>
                <td style="width: 45%; padding-right: 30px;"><div class="form-line"></div></td>
                <td class="form-label" style="width: 45px;">Date:</td>
                <td><div class="form-line"></div></td>
            </tr>
        </table>
    </div>

</body>
</html>