@php
    $formData = is_string($ticket->form_data ?? null) 
        ? json_decode($ticket->form_data, true) 
        : ($ticket->form_data ?? []);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incident Report Job Order - {{ $ticket->ticket_number ?? '' }}</title>
    <style>
        /* Strict DomPDF & Printer Page Setup */
        @page { 
            size: A4 portrait; 
            margin: 10mm 12mm 10mm 12mm; 
        }

        body { 
            font-family: 'DejaVu Sans', 'Times New Roman', Times, serif; 
            font-size: 9.5pt; 
            color: #000; 
            margin: 0; 
            padding: 0; 
            width: 100%;
            line-height: 1.2;
        }

        /* Screen Print Button (Hidden on PDF render and Print) */
        .controls { 
            text-align: center;
            margin-bottom: 15px; 
        }
        
        .btn { 
            background-color: #28a745; 
            color: white; 
            border: none; 
            padding: 8px 18px; 
            font-size: 14px; 
            font-weight: bold;
            cursor: pointer; 
            border-radius: 4px; 
        }

        /* Pure Table Layout Rules for DomPDF Compatibility */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0;
            padding: 0;
        }

        td {
            vertical-align: middle;
            box-sizing: border-box;
            padding: 0;
        }

        /* Header Block */
        .header-table { 
            border: 1px solid #000; 
            margin-bottom: 8px; 
        }
        
        .header-table td { 
            border-right: 1px solid #000; 
        }

        .header-table td:last-child {
            border-right: none;
        }
        
        .logo-cell {
            width: 22%;
            text-align: center;
            padding: 4px 2px;
        }

        .logo-cell img {
            height: 40px;
            width: auto;
            display: inline-block;
            vertical-align: middle;
        }

        .title-cell {
            width: 48%;
            text-align: center;
            padding: 4px;
        }

        .univ-name {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .office-name {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
        }

        .form-name {
            font-size: 11pt;
            font-weight: bold;
        }

        .meta-cell {
            width: 30%;
        }

        .meta-nested-table td {
            border-bottom: 1px solid #000;
            padding: 2px 4px;
            font-size: 8pt;
        }

        .meta-nested-table tr:last-child td {
            border-bottom: none;
        }

        /* Section Headings */
        .section-title { 
            font-weight: bold; 
            margin-top: 6px; 
            margin-bottom: 3px; 
            text-transform: uppercase; 
            font-size: 9.5pt;
        }

        /* Form Row Grids */
        .form-grid {
            margin-bottom: 3px;
        }

        .lbl {
            font-size: 9.5pt;
            white-space: nowrap;
            padding-right: 5px;
        }

        .val-line {
            border-bottom: 1px solid #000;
            font-weight: bold;
            padding-left: 4px;
            height: 16px;
            font-size: 9.5pt;
        }

        .text-box-line {
            border-bottom: 1px solid #000;
            min-height: 20px;
            padding-left: 4px;
            font-weight: bold;
            font-size: 9.5pt;
        }

        .disclaimer { 
            font-size: 8.5pt; 
            font-style: italic;
            margin: 8px 0 6px 0; 
        }

        /* Signature Blocks */
        .sig-grid {
            margin-top: 12px;
            text-align: center;
        }

        .sig-grid td {
            vertical-align: bottom;
            font-size: 8.5pt;
        }

        .sig-line {
            border-bottom: 1px solid #000;
            font-weight: bold;
            padding-bottom: 2px;
            margin-bottom: 2px;
            min-height: 14px;
        }

        @media print {
            .controls { display: none !important; }
            body { background: white; }
        }
    </style>
</head>
<body>

    <div class="controls">
        <button class="btn" onclick="window.print()">Print Job Order PDF</button>
    </div>

    <!-- HEADER TABLE -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('image/KSU-logo.png') }}" onerror="this.src='{{ asset('image/KSU-logo.png') }}'" alt="KSU">
                <img src="{{ public_path('image/Bagong-Pilipinas.png') }}" onerror="this.src='{{ asset('image/Bagong-Pilipinas.png') }}'" alt="BP">
            </td>
            
            <td class="title-cell">
                <div class="univ-name">Kalinga State University</div>
                <div class="office-name">Information and Communications Technology Office</div>
                <div class="form-name">Incident Report Form</div>
            </td>
            
            <td class="meta-cell">
                <table class="meta-nested-table">
                    <tr>
                        <td style="width: 45%; border-right: 1px solid #000; font-weight: bold;">Doc. Ref No.:</td>
                        <td style="width: 55%; text-align: center; font-weight: bold;">KSU-ICTO-QF-06</td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #000;">Effectivity Date:</td>
                        <td style="text-align: center;">March 1, 2024</td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #000;">Revision No.:</td>
                        <td style="text-align: center;">00</td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #000;">Page No.:</td>
                        <td style="text-align: center;">1 of 1</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- REQUEST NUMBER ROW -->
    <table style="margin-bottom: 6px;">
        <tr>
            <td style="width: 60%; font-weight: bold;">
                Request No.: 
                <span style="border-bottom: 1px solid #000; padding: 0 10px; font-weight: bold;">
                    {{ $ticket->ticket_number ?? '' }}
                </span>
            </td>
            <td style="width: 40%; text-align: right; font-style: italic; font-size: 8.5pt; color: #333;">
                To be filled by KSU ICT User
            </td>
        </tr>
    </table>

    <!-- 1. EMPLOYEE INFORMATION -->
    <div class="section-title">1. EMPLOYEE INFORMATION</div>
    <table class="form-grid">
        <tr>
            <td class="lbl" style="width: 20%;">User's Full Name:</td>
            <td class="val-line" style="width: 80%;">{{ $ticket->client_name ?? '' }}</td>
        </tr>
    </table>
    <table class="form-grid">
        <tr>
            <td class="lbl" style="width: 28%;">User's Office Name & Position:</td>
            <td class="val-line" style="width: 72%;">{{ $ticket->department ?? '' }}</td>
        </tr>
    </table>
    <table class="form-grid">
        <tr>
            <td class="lbl" style="width: 24%;">User's Contact Number:</td>
            <td class="val-line" style="width: 76%;">{{ $ticket->contact_number ?? '' }}</td>
        </tr>
    </table>
    <table class="form-grid">
        <tr>
            <td class="lbl" style="width: 16%;">Email Address:</td>
            <td class="val-line" style="width: 84%;">{{ $formData['email'] ?? $ticket->user->email ?? '' }}</td>
        </tr>
    </table>

    <!-- 2. INCIDENT DETAILS -->
    <div class="section-title">2. INCIDENT DETAILS:</div>
    <table class="form-grid">
        <tr>
            <td class="lbl" style="width: 18%;">Date of Incident:</td>
            <td class="val-line" style="width: 82%;">{{ $formData['incident_date'] ?? $formData['date_of_incident'] ?? (isset($ticket->created_at) ? $ticket->created_at->format('Y-m-d') : '') }}</td>
        </tr>
    </table>
    <table class="form-grid">
        <tr>
            <td class="lbl" style="width: 18%;">Time of Incident:</td>
            <td class="val-line" style="width: 82%;">{{ $formData['incident_time'] ?? $formData['time_of_incident'] ?? (isset($ticket->created_at) ? $ticket->created_at->format('H:i') : '') }}</td>
        </tr>
    </table>
    <table class="form-grid">
        <tr>
            <td class="lbl" style="width: 21%;">Location of Incident:</td>
            <td class="val-line" style="width: 79%;">{{ $formData['location'] ?? $ticket->department ?? '' }}</td>
        </tr>
    </table>

    <!-- 3. INCIDENT DESCRIPTION -->
    <div class="section-title">3. INCIDENT DESCRIPTION:</div>
    <table class="form-grid">
        <tr>
            <td class="text-box-line" style="height: 32px; vertical-align: top;">
                {{ $ticket->description ?? '' }}
            </td>
        </tr>
    </table>

    <!-- 4. DAMAGED/STOLEN EQUIPMENT INFORMATION -->
    <div class="section-title">4. DAMAGED/STOLEN EQUIPMENT INFORMATION:</div>
    <table class="form-grid">
        <tr>
            <td class="lbl" style="width: 26%;">Equipment Name/Type:</td>
            <td class="val-line" style="width: 74%;">{{ $ticket->equipment_type ?? 'N/A' }}</td>
        </tr>
    </table>
    <table class="form-grid">
        <tr>
            <td class="lbl" style="width: 12%;">Quantity:</td>
            <td class="val-line" style="width: 88%;">{{ $formData['quantity'] ?? '1' }}</td>
        </tr>
    </table>
    <table class="form-grid">
        <tr>
            <td class="lbl" style="width: 27%;">Equipment Serial Number:</td>
            <td class="val-line" style="width: 73%;">{{ $ticket->serial_no ?? 'N/A' }}</td>
        </tr>
    </table>
    <table class="form-grid">
        <tr>
            <td class="lbl" style="width: 12%;">Remarks:</td>
            <td class="val-line" style="width: 88%;">{{ $ticket->remarks ?? '' }}</td>
        </tr>
    </table>

    <!-- 5. ACTIONS TAKEN -->
    <div class="section-title">5. ACTIONS TAKEN:</div>
    <table class="form-grid">
        <tr>
            <td class="text-box-line" style="height: 32px; vertical-align: top;">
                {{ $formData['actions_taken'] ?? '' }}
            </td>
        </tr>
    </table>

    <div class="disclaimer">By submitting this form, you acknowledge that the information provided is accurate and complete.</div>

    <!-- PREPARED BY SIGNATURES -->
    <div style="font-weight: bold; font-size: 9pt; margin-top: 6px;">Prepared by:</div>
    <table class="sig-grid">
        <tr>
            <td style="width: 36%;">
                <div class="sig-line">{{ $ticket->client_name ?? '' }}</div>
                Employee Name
            </td>
            <td style="width: 6%;"></td>
            <td style="width: 36%;">
                <div class="sig-line">&nbsp;</div>
                Employee Signature
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 18%;">
                <div class="sig-line">{{ isset($ticket->created_at) ? $ticket->created_at->format('M d, Y') : '' }}</div>
                Date
            </td>
        </tr>
    </table>

    <!-- CONFORMED BY SIGNATURES -->
    <div style="font-weight: bold; font-size: 9pt; margin-top: 10px;">Conformed by:</div>
    <table class="sig-grid">
        <tr>
            <td style="width: 36%;">
                <div class="sig-line">&nbsp;</div>
                Supervisor Name
            </td>
            <td style="width: 6%;"></td>
            <td style="width: 36%;">
                <div class="sig-line">&nbsp;</div>
                Supervisor Signature
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 18%;">
                <div class="sig-line">&nbsp;</div>
                Date
            </td>
        </tr>
    </table>

    <!-- USER ACKNOWLEDGEMENT SECTION -->
    <div style="border-top: 1px dashed #000; margin-top: 12px; padding-top: 6px;">
        <div class="section-title" style="margin-top: 0;">USER ACKNOWLEDGEMENT</div>
        <div style="text-indent: 15px; font-size: 8.5pt; margin-bottom: 6px;">
            I, the undersigned, hereby acknowledge that the ICT services requested have been completed to my satisfaction.
        </div>
        
        <table class="form-grid">
            <tr>
                <td class="lbl" style="width: 28%;">Signature over printed name:</td>
                <td class="val-line" style="width: 44%;">&nbsp;</td>
                <td class="lbl" style="width: 8%; text-align: right; padding-right: 4px;">Date:</td>
                <td class="val-line" style="width: 20%;">&nbsp;</td>
            </tr>
        </table>
    </div>

</body>
</html>