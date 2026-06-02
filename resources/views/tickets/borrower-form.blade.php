<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        .table-container { width: 100%; border-collapse: collapse; }
        .table-container th, .table-container td { border: 1px solid black; padding: 8px; vertical-align: top; }
        .header-title { text-align: center; font-weight: bold; vertical-align: middle; }
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
            <td style="width: 22%; text-align: center; vertical-align: middle; padding: 5px 2px;">
                @if(file_exists(public_path('image/KSU-logo.png')))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('image/KSU-logo.png'))) }}" width="55" style="display: inline-block; vertical-align: middle; margin-right: 4px;">
                @endif
                @if(file_exists(public_path('image/Bagong-Pilipinas.png')))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('image/Bagong-Pilipinas.png'))) }}" width="55" style="display: inline-block; vertical-align: middle;">
                @endif
            </td>
            <td style="width: 43%;" class="header-title">
                <div style="font-size: 15px;">Kalinga State University</div>
                <div style="font-size: 11px; font-weight: bold; margin: 3px 0; text-transform: uppercase;">Information and Communications Technology Office</div>
                <div style="font-size: 13px; font-weight: normal; font-style: italic;">Equipment Borrower's Form</div>
            </td>
            <td style="width: 35%; padding: 0;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr><td style="border: none; border-bottom: 1px solid black; border-right: 1px solid black; padding: 5px;">Doc. Ref No.:</td><td style="border: none; border-bottom: 1px solid black; padding: 5px; font-weight: bold;">KSU-ICTO-QF-08</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black; border-right: 1px solid black; padding: 5px;">Effectivity Date:</td><td style="border: none; border-bottom: 1px solid black; padding: 5px;">March 24, 2026</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black; border-right: 1px solid black; padding: 5px;">Revision No.:</td><td style="border: none; border-bottom: 1px solid black; padding: 5px;">3.0</td></tr>
                    <tr><td style="border: none; padding: 5px;">Page No.:</td><td style="border: none; padding: 5px;">1</td></tr>
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
                    <tr><td style="border: none; border-bottom: 1px solid black; width: 35%; padding: 7px 8px;">Full Name</td><td style="border: none; border-bottom: 1px solid black; padding: 7px 8px;">{{ $ticket->client_name }}</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black; padding: 7px 8px;">Office Name</td><td style="border: none; border-bottom: 1px solid black; padding: 7px 8px;">{{ $ticket->department ?? 'N/A' }}</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black; padding: 7px 8px;">Contact Number</td><td style="border: none; border-bottom: 1px solid black; padding: 7px 8px;">{{ $ticket->contact_number ?? 'N/A' }}</td></tr>
                    <tr><td style="border: none; height: 35px; padding: 7px 8px;">Email Address</td><td style="border: none; padding: 7px 8px;">{{ $ticket->client_email ?? 'N/A' }}</td></tr>
                </table>
            </td>
            <td style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse; border: none;">
                    <tr><td style="border: none; border-bottom: 1px solid black; width: 50%; padding: 6px 8px;">Equipment Name/Type</td><td style="border: none; border-bottom: 1px solid black; padding: 6px 8px;">{{ $ticket->equipment_type }}</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black; padding: 6px 8px;">Quantity</td><td style="border: none; border-bottom: 1px solid black; padding: 6px 8px;">1</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black; padding: 6px 8px;">Serial Number</td><td style="border: none; border-bottom: 1px solid black; padding: 6px 8px;">{{ $ticket->serial_no ?? 'N/A' }}</td></tr>
                    <tr><td style="border: none; border-bottom: 1px solid black; padding: 6px 8px;">Date Borrowed</td><td style="border: none; border-bottom: 1px solid black; padding: 6px 8px;">{{ now()->format('F d, Y') }}</td></tr>
                    <tr><td style="border: none; padding: 6px 8px;">Expected Return Date</td><td style="border: none; padding: 6px 8px;">{{ $ticket->expected_return_date ?? '' }}</td></tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="3" style="font-size: 11px; padding: 10px;">
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
            <td colspan="2" style="padding: 10px; height: 75px;">
                Borrower:
                <div style="margin-top: 35px; text-align: center;">
                    <span style="border-top: 1px solid black; padding: 0 40px; display: inline-block;">Signature over printed name</span>
                </div>
            </td>
            <td style="padding: 10px; height: 75px;">
                Released by (Staff-in-charge):
                <div style="margin-top: 35px; text-align: center;">
                    <span style="border-top: 1px solid black; padding: 0 40px; display: inline-block;">Signature over printed name</span>
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="3" style="padding: 10px; height: 75px;">
                Received By (Staff-in-charge):
                <table style="width: 100%; border: none; margin-top: 35px; border-collapse: collapse;">
                    <tr>
                        <td style="border: none; text-align: center; width: 50%; padding: 0;">
                            <span style="border-top: 1px solid black; padding: 0 60px; display: inline-block;">Signature</span>
                        </td>
                        <td style="border: none; text-align: center; width: 50%; padding: 0;">
                            <span style="border-top: 1px solid black; padding: 0 60px; display: inline-block;">Date</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>