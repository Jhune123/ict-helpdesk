<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Borrower's Form - {{ $ticket->ticket_id ?? 'KSU-ICTO-QF-09' }}</title>
    <style>
        /* =========================================
           PURE CSS LAYOUT (No Tailwind Needed)
           Guarantees perfect layout for Print & PDF
           ========================================= */
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #000;
            background-color: #f3f4f6;
            margin: 0;
            padding: 40px 20px;
        }
        
        /* Container */
        .document-container {
            max-width: 850px;
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        /* Print Button */
        .print-header { text-align: right; margin-bottom: 20px; }
        .btn-print {
            background-color: #2563eb; color: #fff;
            border: none; padding: 10px 20px; font-size: 14px;
            font-weight: bold; border-radius: 5px; cursor: pointer;
        }
        .btn-print:hover { background-color: #1d4ed8; }

        /* General Tables */
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th, td { border: 1px solid #000; padding: 0; }
        
        /* 1. Header Section */
        .header-table td { text-align: center; vertical-align: middle; }
        .header-logo { width: 20%; padding: 10px; }
        .header-logo img { width: 90px; height: 90px; object-fit: contain; }
        
        .header-title { width: 50%; padding: 10px; line-height: 1.3; }
        .header-title h1 { font-size: 18px; margin: 0; font-weight: bold; }
        .header-title h2 { font-size: 16px; margin: 5px 0 0; font-weight: bold; }
        .header-title h3 { font-size: 14px; margin: 5px 0 0; font-weight: normal; }
        
        .header-meta { width: 30%; vertical-align: top !important; }
        .meta-table { width: 100%; height: 100%; border: none; }
        .meta-table td { 
            border: none; border-bottom: 1px solid #000; border-left: 1px solid #000; 
            padding: 6px 8px; text-align: left; font-size: 12px; 
        }
        .meta-table tr:last-child td { border-bottom: none; }
        .meta-table td:first-child { width: 45%; white-space: nowrap; }

        /* 2. Main Content Section */
        .main-table th { padding: 10px; font-size: 14px; text-transform: uppercase; font-weight: bold; text-align: center; }
        
        .info-table { border: none; width: 100%; height: 100%; }
        .info-table td { border: none; border-bottom: 1px solid #000; padding: 8px 10px; vertical-align: top; }
        .info-table tr:last-child td { border-bottom: none; }
        .info-table td:first-child { border-right: 1px solid #000; width: 35%; }
        .info-table td:last-child { font-weight: bold; }

        /* 3. Terms & Conditions */
        .terms-box { padding: 12px 15px; vertical-align: top; }
        .terms-box p { margin: 0 0 5px 0; font-weight: normal; text-transform: uppercase; }
        .terms-box ul { margin: 0; padding-left: 25px; font-size: 13px; line-height: 1.4; }

        /* 4. Signatures */
        .signature-box { padding: 12px 15px; vertical-align: top; height: 110px; }
        .signature-area { width: 80%; margin: 40px auto 0; text-align: center; }
        .signature-line { 
            border-bottom: 1px solid #000; font-weight: bold; 
            text-transform: uppercase; font-size: 13px; padding-bottom: 3px; 
        }
        .signature-label { font-size: 12px; margin-top: 4px; }

        /* 5. Print Adjustments */
        @media print {
            body { background-color: #fff; padding: 0; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .document-container { padding: 0; box-shadow: none; max-width: 100%; border: none; }
            .print-header { display: none; }
        }
    </style>
</head>
<body>

    <div class="document-container">
        
        {{-- Print Button (Hidden on actual print) --}}
        <div class="print-header">
            <button onclick="window.print()" class="btn-print">🖨️ Print / Save as PDF</button>
        </div>

        {{-- Formal Header Table --}}
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    {{-- Updated path with an onerror fallback just in case Laragon's routing acts up --}}
                    <img src="{{ asset('image/school-logo.jpg') }}" onerror="this.src='/image/school-logo.jpg'" alt="KSU Logo">
                </td>
                <td class="header-title">
                    <h1>Kalinga State University</h1>
                    <h2>Quality Management System</h2>
                    <h3>Equipment Borrower's Form</h3>
                </td>
                <td class="header-meta">
                    <table class="meta-table">
                        <tr>
                            <td>Doc. Ref No.:</td>
                            <td>KSU-ICTO-QF-09</td>
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
                </td>
            </tr>
        </table>

        {{-- Main Body Table --}}
        <table class="main-table">
            <tr>
                <th style="width: 50%;">BORROWER'S INFORMATION</th>
                <th style="width: 50%;">EQUIPMENT DETAILS</th>
            </tr>
            
            <tr>
                {{-- Left Side Info --}}
                <td style="vertical-align: top;">
                    <table class="info-table">
                        <tr><td>Full Name</td><td>{{ $ticket->meta['full_name'] ?? '' }}</td></tr>
                        <tr><td>Office Name</td><td>{{ $ticket->meta['office_name'] ?? '' }}</td></tr>
                        <tr><td>Contact Number</td><td>{{ $ticket->contact_number ?? '' }}</td></tr>
                        <tr>
                            {{-- This cell stretches naturally to match the right side height --}}
                            <td style="height: 100%;">Email Address</td>
                            <td style="height: 100%;">{{ $ticket->meta['email_address'] ?? '' }}</td>
                        </tr>
                    </table>
                </td>

                {{-- Right Side Info --}}
                <td style="vertical-align: top;">
                    <table class="info-table">
                        <tr><td>Equipment Name/Type</td><td>{{ $ticket->meta['equipment_type'] ?? '' }}</td></tr>
                        <tr><td>Quantity</td><td>{{ $ticket->meta['quantity'] ?? '' }}</td></tr>
                        <tr><td>Serial Number</td><td>{{ $ticket->meta['serial_no'] ?? '' }}</td></tr>
                        <tr><td>Date Borrowed</td><td>{{ $ticket->meta['date_borrowed'] ?? '' }}</td></tr>
                        <tr><td>Expected Return Date</td><td>{{ $ticket->meta['expected_return_date'] ?? '' }}</td></tr>
                    </table>
                </td>
            </tr>

            {{-- Terms & Conditions Row --}}
            <tr>
                <td colspan="2" class="terms-box">
                    <p>TERMS AND CONDITIONS:</p>
                    <ul>
                        <li>I am responsible for properly handling and caring for the borrowed ICT equipment.</li>
                        <li>I will return the equipment with all accompanying accessories in the same condition as received.</li>
                        <li>I will be held liable for any damage, loss, or theft of the equipment while it is in my possession.</li>
                        <li>I will notify the ICT department immediately in case of any issues or concerns with the equipment.</li>
                        <li>I understand that failure to return the equipment on the agreed return date may result in penalties or restrictions on future borrowing privileges.</li>
                    </ul>
                </td>
            </tr>

            {{-- Signatures Row 1 --}}
            <tr>
                <td class="signature-box">
                    Borrower:
                    <div class="signature-area">
                        <div class="signature-line">
                            {{ $ticket->meta['full_name'] ?? '&nbsp;' }}
                        </div>
                        <div class="signature-label">Signature over printed name</div>
                    </div>
                </td>
                <td class="signature-box">
                    Staff-in-charge:
                    <div class="signature-area">
                        <div class="signature-line" style="color: transparent;">&nbsp;</div>
                        <div class="signature-label">Signature over printed name</div>
                    </div>
                </td>
            </tr>

            {{-- Signatures Row 2 --}}
            <tr>
                <td colspan="2" class="signature-box">
                    Received By:
                    <div class="signature-area" style="width: 40%; margin-left: auto; margin-right: auto;">
                        <div class="signature-line" style="color: transparent;">&nbsp;</div>
                        <div class="signature-label">Staff-in-charge / Date</div>
                    </div>
                </td>
            </tr>
        </table>

    </div>

</body>
</html>