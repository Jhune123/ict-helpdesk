<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: "DejaVu Sans", Arial, sans-serif; line-height: 1.6; margin: 40px; font-size: 13px; color: #000; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; text-align: center; }
        .header-table td { border: 1px solid #000; padding: 8px; }
        .header-logo { width: 20%; vertical-align: middle; }
        .header-title { width: 50%; font-weight: bold; vertical-align: middle; }
        .header-meta { width: 30%; text-align: left; font-size: 11px; vertical-align: middle; padding-left: 10px; }
        .form-section { margin-top: 20px; }
        .form-section h3 { margin-bottom: 5px; font-size: 14px; border-bottom: 1px solid #000; padding-bottom: 2px; }
        .field-row { margin-bottom: 8px; }
        .underline-value { display: inline-block; border-bottom: 1px solid #000; padding: 0 5px; font-weight: bold; }
        .footer-note { margin-top: 40px; text-align: justify; font-size: 11px; font-style: italic; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td rowspan="4" class="header-logo"><img src="{{ public_path('image/school-logo.jpg') }}" width="80"></td>
            <td rowspan="2" class="header-title">
                <div style="font-size: 16px;">Kalinga State University</div>
                <div style="font-size: 14px;">Quality Management System</div>
            </td>
            <td class="header-meta">Doc. Ref No.: <strong>KSU-ICTO-QF-01</strong></td>
        </tr>
        <tr><td class="header-meta">Effectivity Date: October 14, 2025</td></tr>
        <tr><td rowspan="2" class="header-title" style="font-size: 14px;">General Service Request Form</td><td class="header-meta">Revision No.: 2.0</td></tr>
        <tr><td class="header-meta">Page No.: 1</td></tr>
    </table>

    <div class="field-row">Request No.: <span class="underline-value" style="min-width: 250px;">{{ $ticket->ticket_number }}</span></div>

    <div class="form-section">
        <h3>1. REQUESTOR INFORMATION</h3>
        <div class="field-row">User’s Full Name: <span class="underline-value" style="width: 70%;">{{ $ticket->client_name }}</span></div>
        <div class="field-row">Office/Department: <span class="underline-value" style="width: 70%;">{{ $ticket->department }}</span></div>
        <div class="field-row">Contact Number: <span class="underline-value" style="width: 71%;">{{ $ticket->contact_number }}</span></div>
    </div>

    <div class="form-section">
        <h3>2. REQUEST DETAILS</h3>
        <div class="field-row">Subject/Service: <span class="underline-value" style="width: 74%;">{{ $ticket->title }}</span></div>
        <div class="field-row">Category: <span class="underline-value" style="width: 82%;">{{ $ticket->category->name ?? 'Uncategorized' }}</span></div>
        
        <div style="margin-top: 15px;">
            <strong>Additional Form Data:</strong>
            @if(!empty($ticket->form_data))
                <table style="width: 100%; margin-top: 5px;">
                    @foreach($ticket->form_data as $key => $value)
                        @if($key !== 'original_form_type' && !empty($value))
                            <tr>
                                <td style="width: 30%; text-transform: capitalize; padding: 4px 0;">{{ str_replace('_', ' ', $key) }}:</td>
                                <td style="border-bottom: 1px solid #ddd; font-weight: bold;">
                                    {{ is_array($value) ? implode(', ', $value) : $value }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            @else
                <div class="field-row">Description: <span class="underline-value" style="width: 78%;">{{ $ticket->description }}</span></div>
            @endif
        </div>
    </div>

    <div class="form-section">
        <h3>3. STATUS & REMARKS</h3>
        <div class="field-row">Current Status: <span class="underline-value" style="width: 75%;">{{ $ticket->status }}</span></div>
        <div class="field-row">Remarks: <span class="underline-value" style="width: 80%;">{{ $ticket->remarks ?? 'None' }}</span></div>
    </div>

    <div class="footer-note">
        This is a system-generated document based on the Information and Communications Technology Office (ICTO) Ticketing System.
    </div>

</body>
</html>