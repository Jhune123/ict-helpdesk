<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Equipment Borrower's Form - {{ $ticket->ticket_id ?? 'KSU-ICTO-QF-09' }}</title>
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
            width: 70%;
            margin-bottom: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <div class="print-header">
        <button onclick="window.print()" class="btn-print">Print / Save as PDF</button>
    </div>

    {{-- HEADER TABLE --}}
    <table class="grid-table">
        <tr>
            <td rowspan="4" style="width: 15%; text-align: center; padding: 10px;">
                {{-- Use asset() for browser preview, public_path() only for PDF generation engines --}}
                <img src="{{ asset('image/KSU-logo.png') }}" alt="KSU Logo" style="max-width: 80px; max-height: 80px; object-fit: contain;">
            </td>
            <td rowspan="4" style="width: 45%; text-align: center; line-height: 1.4;">
                <span style="font-size: 16px; font-weight: bold;">Kalinga State University</span><br>
                <span style="font-size: 15px; font-weight: bold;">Quality Management System</span><br>
                <span style="font-size: 14px;">Equipment Borrower's Form</span>
            </td>
            <td style="width: 20%;">Doc. Ref No.:</td>
            <td style="width: 20%;">KSU-ICTO-QF-09</td>
        </tr>
        <tr>
            <td>Effectivity Date:</td>
            <td>October 14, 2025</td>
        </tr>
        <tr>
            <td>Revision No.:</td>
            <td>2.0</td>
        </tr>
        <tr>
            <td>Page No.:</td>
            <td>1</td>
        </tr>
    </table>

    {{-- MAIN CONTENT TABLE --}}
    <table class="grid-table">
        <tr>
            <th colspan="2" style="width: 50%;">BORROWER’S INFORMATION</th>
            <th colspan="2" style="width: 50%;">EQUIPMENT DETAILS</th>
        </tr>
        
        <tr>
            <td style="width: 15%;">Full Name</td>
            {{-- data_get is safer than array access to prevent 500 errors --}}
            <td style="width: 35%; font-weight: bold;">{{ data_get($ticket, 'meta.full_name', 'N/A') }}</td>
            <td style="width: 20%;">Equipment Name/Type</td>
            <td style="width: 30%; font-weight: bold;">{{ data_get($ticket, 'meta.equipment_type', 'N/A') }}</td>
        </tr>
        <tr>
            <td>Office Name</td>
            <td style="font-weight: bold;">{{ data_get($ticket, 'meta.office_name', 'N/A') }}</td>
            <td>Quantity</td>
            <td style="font-weight: bold;">{{ data_get($ticket, 'meta.quantity', 'N/A') }}</td>
        </tr>
        <tr>
            <td>Contact Number</td>
            <td style="font-weight: bold;">{{ $ticket->contact_number ?? 'N/A' }}</td>
            <td>Serial Number</td>
            <td style="font-weight: bold;">{{ data_get($ticket, 'meta.serial_no', 'N/A') }}</td>
        </tr>
        <tr>
            <td rowspan="2" class="align-top">Email Address</td>
            <td rowspan="2" class="align-top" style="font-weight: bold;">{{ data_get($ticket, 'meta.email_address', 'N/A') }}</td>
            <td>Date Borrowed</td>
            <td style="font-weight: bold;">{{ data_get($ticket, 'meta.date_borrowed', 'N/A') }}</td>
        </tr>
        <tr>
            <td>Expected Return Date</td>
            <td style="font-weight: bold;">{{ data_get($ticket, 'meta.expected_return_date', 'N/A') }}</td>
        </tr>

        <tr>
            <td colspan="4" class="align-top" style="padding: 12px;">
                <p style="margin: 0 0 5px 0;">TERMS AND CONDITIONS:</p>
                <ul style="margin: 0; padding-left: 20px; line-height: 1.3;">
                    <li>I am responsible for properly handling and caring for the borrowed ICT equipment.</li>
                    <li>I will return the equipment with all accompanying accessories in the same condition as received.</li>
                    <li>I will be held liable for any damage, loss, or theft of the equipment while it is in my possession.</li>
                    <li>I will notify the ICT department immediately in case of any issues or concerns with the equipment.</li>
                    <li>I understand that failure to return the equipment on the agreed return date may result in penalties or restrictions on future borrowing privileges.</li>
                </ul>
            </td>
        </tr>

        {{-- Signatures --}}
        <tr>
            <td colspan="2" class="align-top" style="height: 100px; padding: 10px;">
                <p style="margin: 0 0 40px 0;">Borrower:</p>
                <div class="text-center">
                    <span class="signature-line">{{ data_get($ticket, 'meta.full_name', '') }}</span><br>
                    <span style="font-size: 11px;">Signature over printed name</span>
                </div>
            </td>
            <td colspan="2" class="align-top" style="height: 100px; padding: 10px;">
                <p style="margin: 0 0 40px 0;">Staff-in-charge:</p>
                <div class="text-center">
                    <span class="signature-line" style="color: transparent;">&nbsp;</span><br>
                    <span style="font-size: 11px;">Signature over printed name</span>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="4" class="align-top" style="height: 90px; padding: 10px;">
                <p style="margin: 0 0 40px 0;">Received By:</p>
                <div class="text-center">
                    <span class="signature-line" style="width: 40%; color: transparent;">&nbsp;</span><br>
                    <span style="font-size: 11px;">Staff-in-charge / Date</span>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>