<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .table-container { width: 100%; border-collapse: collapse; }
        .table-container th, .table-container td { border: 1px solid black; padding: 8px; vertical-align: top; }
        .header-title { text-align: center; font-weight: bold; }
        .section-header { text-align: center; font-weight: bold; background-color: #f2f2f2; text-transform: uppercase; }
        .no-border-right { border-right: none !important; }
        .no-border-left { border-left: none !important; }
        .terms { padding: 10px; border: 1px solid black; border-top: none; }
        .signature-section { width: 100%; border-collapse: collapse; margin-top: -1px; }
        .signature-section td { border: 1px solid black; height: 80px; width: 50%; vertical-align: top; }
        ul { margin: 5px 0; padding-left: 20px; }
        li { margin-bottom: 3px; }
    </style>
</head>
<body>

    <table class="table-container">
        <tr>
            <td style="width: 15%; text-align: center;">
                @if(file_exists(public_path('image/KSU-logo.png')))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('image/KSU-logo.png'))) }}" width="70">
                @endif
            </td>
            <td style="width: 45%;" class="header-title">
                <div style="font-size: 16px;">Kalinga State University</div>
                <div style="font-size: 14px;">Quality Management System</div>
                <div style="font-size: 13px; font-weight: normal;">Equipment Borrower's Form</div>
            </td>
            <td style="width: 40%; padding: 0;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr><td style="border: none; border-bottom: 1px solid black; border-right: 1px solid black;">Doc. Ref No.:</td><td style="border: none; border-bottom: 1px solid black;">KSU-ICTO-QF-09</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black; border-right: 1px solid black;">Effectivity Date:</td><td style="border: none; border-bottom: 1px solid black;">October 14, 2025</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black; border-right: 1px solid black;">Revision No.:</td><td style="border: none; border-bottom: 1px solid black;">2.0</td></tr>
                    <tr><td style="border: none;">Page No.:</td><td style="border: none;">1</td></tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="2" class="section-header">BORROWER'S INFORMATION</td>
            <td class="section-header">EQUIPMENT DETAILS</td>
        </tr>

        <tr>
            <td colspan="2" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr><td style="border: none; border-bottom: 1px solid black; width: 35%;">Full Name</td><td style="border: none; border-bottom: 1px solid black;">{{ $ticket->client_name }}</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black;">Office Name</td><td style="border: none; border-bottom: 1px solid black;">{{ $ticket->department ?? 'N/A' }}</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black;">Contact Number</td><td style="border: none; border-bottom: 1px solid black;">{{ $ticket->contact_number ?? 'N/A' }}</td></tr>
                    <tr><td style="border: none; height: 40px;">Email Address</td><td style="border: none;">{{ $ticket->client_email ?? 'N/A' }}</td></tr>
                </table>
            </td>
            <td style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr><td style="border: none; border-bottom: 1px solid black; width: 50%;">Equipment Name/Type</td><td style="border: none; border-bottom: 1px solid black;">{{ $ticket->equipment_type }}</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black;">Quantity</td><td style="border: none; border-bottom: 1px solid black;">1</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black;">Serial Number</td><td style="border: none; border-bottom: 1px solid black;">{{ $ticket->serial_no ?? 'N/A' }}</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black;">Date Borrowed</td><td style="border: none; border-bottom: 1px solid black;">{{ now()->format('F d, Y') }}</td></tr>
                    <tr><td style="border: none;">Expected Return Date</td><td style="border: none;"></td></tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="3" style="font-size: 11px;">
                <strong>TERMS AND CONDITIONS:</strong>
                <ul>
                    <li>I am responsible for properly handling and caring for the borrowed ICT equipment.</li>
                    <li>I will return the equipment with all accompanying accessories in the same condition as received.</li>
                    <li>I will be held liable for any damage, loss, or theft of the equipment while it is in my possession.</li>
                    <li>I will notify the ICT department immediately in case of any issues or concerns with the equipment.</li>
                    <li>I understand that failure to return the equipment on the agreed return date may result in penalties or restrictions on future borrowing privileges.</li>
                </ul>
            </td>
        </tr>

        <tr>
            <td colspan="2">
                Borrower:
                <div style="margin-top: 30px; text-align: center;">
                    <span style="border-top: 1px solid black; padding: 0 40px;">Signature over printed name</span>
                </div>
            </td>
            <td>
                Staff-in-charge:
                <div style="margin-top: 30px; text-align: center;">
                    <span style="border-top: 1px solid black; padding: 0 40px;">Signature over printed name</span>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                Received By:
                <div style="margin-top: 20px; text-align: center;">
                    <span style="border-top: 1px solid black; padding: 0 60px;">Staff-in-charge / Date</span>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>