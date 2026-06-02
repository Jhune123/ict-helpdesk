<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Equipment Borrower's Form - {{ $ticket->ticket_id ?? 'KSU-ICTO-QF-08' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }

        .print-header { text-align: right; margin-bottom: 20px; }
        .btn-print {
            background-color: #2563eb; color: #fff;
            border: none; padding: 10px 20px; font-size: 14px;
            font-weight: bold; border-radius: 5px; cursor: pointer;
        }
        @media print { .print-header { display: none; } }

        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .grid-table th, .grid-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .grid-table th {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            padding: 10px;
        }
        
        .text-center { text-align: center; }
        .align-top { vertical-align: top !important; }
        
        .signature-line {
            display: inline-block;
            border-bottom: 1px solid #000;
            width: 80%;
            margin-bottom: 5px;
            font-weight: bold;
            text-transform: uppercase;
            min-height: 18px;
        }
        
        .inner-sig-table {
            width: 100%;
            border-collapse: collapse;
        }
        .inner-sig-table td {
            border: none !important;
            padding: 0 !important;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="print-header">
        <button onclick="window.print()" class="btn-print">Print / Save as PDF</button>
    </div>

    {{-- HEADER TABLE WITH CO-BRANDED LOGOS --}}
    <table class="grid-table">
        <tr>
            <td rowspan="4" style="width: 26%; text-align: center; padding: 10px; white-space: nowrap;">
                <img src="{{ asset('image/KSU-logo.png') }}" alt="KSU Logo" style="max-width: 65px; max-height: 65px; object-fit: contain; display: inline-block; vertical-align: middle; margin-right: 6px;">
                <img src="{{ asset('image/Bagong-Pilipinas.png') }}" alt="Bagong Pilipinas Logo" style="max-width: 65px; max-height: 65px; object-fit: contain; display: inline-block; vertical-align: middle;">
            </td>
            <td rowspan="4" style="width: 44%; text-align: center; line-height: 1.4;">
                <span style="font-size: 16px; font-weight: bold;">Kalinga State University</span><br>
                <span style="font-size: 11px; font-weight: bold; text-transform: uppercase;">Information and Communications Technology Office</span><br>
                <span style="font-size: 14px; font-weight: bold; display: block; margin-top: 5px;">Equipment Borrower's Form</span>
            </td>
            <td style="width: 15%; font-size: 11px;">Doc. Ref No.:</td>
            <td style="width: 15%; font-weight: bold; font-size: 11px;">KSU-ICTO-QF-08</td>
        </tr>
        <tr>
            <td style="font-size: 11px;">Effectivity Date:</td>
            <td style="font-weight: bold; font-size: 11px;">March 24, 2026</td>
        </tr>
        <tr>
            <td style="font-size: 11px;">Revision No.:</td>
            <td style="font-weight: bold; font-size: 11px;">3.0</td>
        </tr>
        <tr>
            <td style="font-size: 11px;">Page No.:</td>
            <td style="font-weight: bold; font-size: 11px;">1</td>
        </tr>
    </table>

    {{-- MAIN CONTENT MATRIX --}}
    <table class="grid-table">
        <tr>
            <th colspan="2" style="width: 50%;">BORROWER’S INFORMATION</th>
            <th colspan="2" style="width: 50%;">EQUIPMENT DETAILS</th>
        </tr>
        
        <tr>
            <td style="width: 15%;">Full Name</td>
            <td style="width: 35%; font-weight: bold;">{{ data_get($ticket, 'meta.full_name', '') }}</td>
            <td style="width: 20%;">Equipment Name/Type</td>
            <td style="width: 30%; font-weight: bold;">{{ data_get($ticket, 'meta.equipment_type', '') }}</td>
        </tr>
        <tr>
            <td>Office Name</td>
            <td style="font-weight: bold;">{{ data_get($ticket, 'meta.office_name', '') }}</td>
            <td>Quantity</td>
            <td style="font-weight: bold;">{{ data_get($ticket, 'meta.quantity', '') }}</td>
        </tr>
        <tr>
            <td>Contact Number</td>
            <td style="font-weight: bold;">{{ $ticket->contact_number ?? (data_get($ticket, 'meta.contact_number', '')) }}</td>
            <td>Serial Number</td>
            <td style="font-weight: bold;">{{ data_get($ticket, 'meta.serial_no', '') }}</td>
        </tr>
        <tr>
            <td rowspan="2" class="align-top">Email Address</td>
            <td rowspan="2" class="align-top" style="font-weight: bold;">{{ data_get($ticket, 'meta.email_address', ($ticket->user->email ?? '')) }}</td>
            <td>Date Borrowed</td>
            <td style="font-weight: bold;">{{ data_get($ticket, 'meta.date_borrowed', '') }}</td>
        </tr>
        <tr>
            <td>Expected Return Date</td>
            <td style="font-weight: bold;">{{ data_get($ticket, 'meta.expected_return_date', '') }}</td>
        </tr>

        {{-- Terms Block --}}
        <tr>
            <td colspan="4" class="align-top" style="padding: 12px;">
                <p style="margin: 0 0 8px 0; font-weight: bold;">TERMS AND CONDITIONS:</p>
                <ul style="margin: 0; padding-left: 20px; line-height: 1.4; list-style-type: disc;">
                    <li>I am responsible for properly handling and caring for the borrowed ICT equipment.</li>
                    <li>I will return the equipment with all accompanying accessories in the same condition as received.</li>
                    <li>I will be held liable for any damage, loss, or theft of the equipment while it is in my possession.</li>
                    <li>I will notify the ICT department immediately in case of any issues or concerns with the equipment.</li>
                    <li>I understand that failure to return the equipment on the agreed return date may result in penalties or restrictions on future borrowing privileges.</li>
                </ul>
            </td>
        </tr>

        {{-- Process Handshake Signatures Area --}}
        <tr>
            <td colspan="2" class="align-top" style="height: 95px; padding: 10px;">
                <p style="margin: 0 0 35px 0; font-weight: bold;">Borrower:</p>
                <div class="text-center">
                    <span class="signature-line">{{ data_get($ticket, 'meta.full_name', '') }}</span><br>
                    <span style="font-size: 11px; color: #333;">Signature over printed name</span>
                </div>
            </td>
            <td colspan="2" class="align-top" style="height: 95px; padding: 10px;">
                <p style="margin: 0 0 35px 0; font-weight: bold;">Released by (Staff-in-charge):</p>
                <div class="text-center">
                    <span class="signature-line" style="color: transparent;">&nbsp;</span><br>
                    <span style="font-size: 11px; color: #333;">Signature over printed name</span>
                </div>
            </td>
        </tr>

        {{-- Closeout Signatures Row --}}
        <tr>
            <td colspan="4" class="align-top" style="height: 95px; padding: 10px;">
                <p style="margin: 0 0 35px 0; font-weight: bold;">Received By (Staff-in-charge):</p>
                <table class="inner-sig-table">
                    <tr>
                        <td style="width: 50%;">
                            <span class="signature-line" style="width: 75%; color: transparent;">&nbsp;</span><br>
                            <span style="font-size: 11px; color: #333;">Signature</span>
                        </td>
                        <td style="width: 50%;">
                            <span class="signature-line" style="width: 75%; color: transparent;">&nbsp;</span><br>
                            <span style="font-size: 11px; color: #333;">Date</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>