<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { size: A4 portrait; margin: 8mm; }
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            line-height: 1.15;
            margin: 0;
            font-size: 9pt;
            color: #000;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 7px;
            text-align: center;
        }
        .header-table td {
            border: 1px solid #000;
            padding: 4px;
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
        .form-section { margin-top: 7px; }
        .form-section h3 { 
            margin-bottom: 3px;
            font-size: 10pt;
            font-weight: bold; 
            text-transform: uppercase;
        }
        .field-row { margin-bottom: 3px; }
        .underline-value {
            display: inline-block;
            border-bottom: 1px solid #000;
            padding: 0 5px;
            font-weight: bold;
        }
        .checklist-container { margin-left: 20px; margin-top: 4px; }
        .checklist-table td { padding: 2px 0; vertical-align: middle; }
        .check-box-cell {
            width: 13px;
            height: 13px;
            border: 1px solid #000;
            text-align: center;
            display: inline-block;
            line-height: 13px;
            font-size: 10px;
        }
        .check-label-cell { padding-left: 10px !important; }
        .footer-note {
            margin-top: 8px;
            text-align: justify;
            font-size: 8pt;
        }
        
        /* Signature Layout Elements */
        .signature-block {
            text-align: center;
            width: 280px;
            margin-top: 10px;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            height: 14px;
            margin-bottom: 3px;
        }
        .signature-subtitle {
            font-size: 12px;
            color: #333;
        }
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .form-table td {
            padding: 2px 0;
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
            min-height: 12px;
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
                <div style="font-size: 16px;">Kalinga State University</div>
                <div style="font-size: 12px; font-weight: bold; text-transform: uppercase; margin-top: 3px;">Information and Communications Technology Office</div>
            </td>
            <td class="header-meta">Doc. Ref No.: <strong>KSU-ICTO-QF-02</strong></td>
        </tr>
        <tr>
            <td class="header-meta">Effectivity Date: March 24, 2026</td>
        </tr>
        <tr>
            <td rowspan="2" class="header-title" style="font-size: 14px; text-transform: uppercase;">Information System Request Form</td>
            <td class="header-meta">Revision No.: 3.0</td>
        </tr>
        <tr>
            <td class="header-meta">Page No.: 1</td>
        </tr>
    </table>

    <div class="field-row">
        Request No.: <span class="underline-value" style="min-width: 250px;">{{ $ticket->ticket_number }}</span><br>
        <small><em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To be filled by KSU ICT User</em></small>
    </div>

    <div class="form-section">
        <h3>1. REQUESTOR INFORMATION</h3>
        <div class="field-row">Date Requested: <span class="underline-value" style="width: 70%;">{{ data_get($ticket, 'form_data.date_requested', $ticket->created_at?->format('F d, Y')) }}</span></div>
        <div class="field-row">User’s Full Name: <span class="underline-value" style="width: 70%;" style="vertical-align: bottom;">{{ $ticket->client_name }}</span></div>
        <div class="field-row">User’s Office Name & Address: <span class="underline-value" style="width: 58%;">{{ data_get($ticket, 'form_data.office_address', $ticket->department) }}</span></div>
        <div class="field-row">User’s Contact Number: <span class="underline-value" style="width: 65%;">{{ $ticket->contact_number }}</span></div>
        <div class="field-row">Email Address: <span class="underline-value" style="width: 75%;">{{ data_get($ticket, 'form_data.email_address', data_get($ticket, 'form_data.email', $ticket->user->email ?? '')) }}</span></div>
    </div>

    @php
        /** * Safely format incoming requested categories */
        $reqTypes = data_get($ticket, 'form_data.request_type', []);
        $reqTypes = is_array($reqTypes) ? $reqTypes : explode(',', (string)$reqTypes);
        $check = '&#10004;'; 
    @endphp

    <div class="form-section">
        <h3>2. REQUEST DETAILS</h3>
        <div class="field-row">I.S. Name: <span class="underline-value" style="width: 80%;">{{ $ticket->title }}</span></div>
        <div class="field-row" style="margin-top: 10px;">Type of Request/Purpose:</div>
        
        <div class="checklist-container">
            <table class="checklist-table">
                <tr>
                    <td><div class="check-box-cell">{!! in_array('Account Management', $reqTypes) ? $check : '' !!}</div></td>
                    <td class="check-label-cell">Account Management</td>
                </tr>
                <tr>
                    <td><div class="check-box-cell">{!! in_array('Bug Report', $reqTypes) ? $check : '' !!}</div></td>
                    <td class="check-label-cell">Bug Report</td>
                </tr>
                <tr>
                    <td><div class="check-box-cell">{!! in_array('System Installation/Repair', $reqTypes) ? $check : '' !!}</div></td>
                    <td class="check-label-cell">System Installation/Repair</td>
                </tr>
                <tr>
                    <td><div class="check-box-cell">{!! in_array('Technical Support/Assistance', $reqTypes) ? $check : '' !!}</div></td>
                    <td class="check-label-cell">Technical Support/Assistance</td>
                </tr>
                <tr>
                    <td><div class="check-box-cell">{!! in_array('Others', $reqTypes) ? $check : '' !!}</div></td>
                    <td class="check-label-cell">Others: <span class="underline-value" style="min-width: 300px;">{{ data_get($ticket, 'form_data.other_request_type', '') }}</span></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="form-section">
        <h3>3. PROJECT REQUEST TIMELINE</h3>
        <div class="field-row">Start Date: <span class="underline-value" style="width: 80%;">{{ data_get($ticket, 'form_data.requested_start_date', data_get($ticket, 'form_data.start_date', '')) }}</span></div>
        <div class="field-row">Completion Date: <span class="underline-value" style="width: 72%;">{{ data_get($ticket, 'form_data.requested_completion_date', data_get($ticket, 'form_data.completion_date', '')) }}</span></div>
        <div class="field-row">Status/Remarks: <span class="underline-value" style="width: 75%;">{{ $ticket->remarks ?? '' }}</span></div>
        <div class="field-row">Request Details: <span class="underline-value" style="width: 75%;">{{ $ticket->description ?? '' }}</span></div>
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
                <strong>{{ $ticket->client_name }}</strong>
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