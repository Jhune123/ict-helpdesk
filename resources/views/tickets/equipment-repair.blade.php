<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Order - {{ $ticket->ticket_number ?? 'KSU-ICTO-TIC' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        /* Header Table */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table th, .header-table td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }
        .header-logo {
            width: 15%;
            text-align: center;
        }
        .header-logo img {
            max-width: 80px;
            height: auto;
        }
        .header-title {
            width: 55%;
        }
        .header-title h2, .header-title h3, .header-title h4 {
            margin: 5px 0;
        }
        .header-meta {
            width: 30%;
            text-align: left !important;
            font-size: 12px;
        }
        .header-meta-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #000;
            padding: 5px 0;
        }
        .header-meta-row:last-child {
            border-bottom: none;
        }

        /* Form Sections */
        .request-no {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        
        /* Table-based form layout for better PDF compatibility */
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
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

        /* Problem Description Area */
        .problem-lines {
            width: 100%;
            border-bottom: 1px solid #000;
            height: 25px;
            margin-bottom: 5px;
        }

        /* Disclaimer Text */
        .disclaimer {
            font-size: 11px;
            font-style: italic;
            text-align: justify;
            margin-top: 30px;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <div class="container">
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    <img src="{{ public_path('image/KSU-logo.png') }}" alt="KSU Logo">
                </td>
                <td class="header-title">
                    <h2>Kalinga State University</h2>
                    <h3>Quality Management System</h3>
                    <h4>Equipment Repair Form</h4>
                </td>
                <td class="header-meta" style="padding: 0;">
                    <div style="padding: 5px; border-bottom: 1px solid #000;">Doc. Ref No.: KSU-ICTO-QF-01</div>
                    <div style="padding: 5px; border-bottom: 1px solid #000;">Effectivity Date: October 14, 2025</div>
                    <div style="padding: 5px; border-bottom: 1px solid #000;">Revision No.: 2.0</div>
                    <div style="padding: 5px;">Page No.: 1</div>
                </td>
            </tr>
        </table>

        <div class="request-no">
            Request No.: <span style="border-bottom: 1px solid #000; display: inline-block; width: 300px; text-align: center;">{{ $ticket->ticket_number ?? '' }}</span>
            <div style="font-size: 10px; font-weight: normal; margin-left: 90px; margin-top: 5px;">To be filled by KSU ICT User</div>
        </div>

        <div class="section-title">1. REQUESTOR INFORMATION</div>
        <div style="padding-left: 20px;">
            <table class="form-table">
                <tr>
                    <td class="form-label" style="width: 120px;">Date Requested:</td>
                    <td><div class="form-line">{{ isset($ticket->created_at) ? \Carbon\Carbon::parse($ticket->created_at)->format('F d, Y') : '' }}</div></td>
                </tr>
                <tr>
                    <td class="form-label">User's Full Name:</td>
                    <td><div class="form-line">{{ $ticket->user->name ?? ($ticket->client_name ?? '') }}</div></td>
                </tr>
                <tr>
                    <td class="form-label">User's Office Name & Address:</td>
                    <td><div class="form-line">{{ $ticket->department ?? '' }}</div></td>
                </tr>
                <tr>
                    <td class="form-label">User's Contact Number:</td>
                    <td><div class="form-line">{{ $ticket->contact_number ?? '' }}</div></td>
                </tr>
                <tr>
                    <td class="form-label">Email Address:</td>
                    <td><div class="form-line">{{ $ticket->user->email ?? '' }}</div></td>
                </tr>
            </table>
        </div>

        <div class="section-title">2. REQUEST DETAILS</div>
        <div style="padding-left: 20px;">
            <table class="form-table">
                <tr>
                    <td class="form-label" style="width: 110px;">Equipment Type:</td>
                    <td style="width: 35%; padding-right: 15px;"><div class="form-line">{{ $ticket->equipment_type ?? '' }}</div></td>
                    <td class="form-label" style="width: 120px;">Brand & Model No:</td>
                    <td><div class="form-line">{{ $ticket->brand_model ?? '' }}</div></td>
                </tr>
                <tr>
                    <td class="form-label">Serial No:</td>
                    <td style="padding-right: 15px;"><div class="form-line">{{ $ticket->serial_no ?? '' }}</div></td>
                    <td class="form-label">Property No.:</td>
                    <td><div class="form-line">{{ $ticket->form_data['property_no'] ?? '' }}</div></td>
                </tr>
                <tr>
                    <td class="form-label">Acquisition Date:</td>
                    <td style="padding-right: 15px;"><div class="form-line">{{ $ticket->form_data['acquisition_date'] ?? '' }}</div></td>
                    <td class="form-label">Acquisition Cost:</td>
                    <td><div class="form-line">{{ $ticket->form_data['acquisition_cost'] ?? '' }}</div></td>
                </tr>
                <tr>
                    <td class="form-label">Date of Last Repair:</td>
                    <td style="padding-right: 15px;"><div class="form-line">{{ $ticket->form_data['date_last_repair'] ?? '' }}</div></td>
                    <td class="form-label">Nature of Last Repair:</td>
                    <td><div class="form-line">{{ $ticket->form_data['nature_last_repair'] ?? '' }}</div></td>
                </tr>
            </table>
        </div>

        <div class="section-title">3. PROBLEM DESCRIPTION</div>
        <div style="padding-left: 20px;">
            <div style="margin-bottom: 10px; font-family: monospace; min-height: 20px;">
                {{ $ticket->description ?? '' }}
            </div>
            <div class="problem-lines"></div>
            <div class="problem-lines"></div>
            <div class="problem-lines"></div>
            <div class="problem-lines"></div>
        </div>

        <div class="disclaimer">
            <strong>Must Read:</strong> I now authorize the KSU ICT Office to perform maintenance service to my ICT equipment. I understand that KSU ICT Office is not in any way responsible for any data loss or damage to my equipment. I know that if the equipment was not working correctly at the time of the release, I release KSU ICT Office from any liability as a result of further damages in the event of any equipment-related failure due to hardware wear and tear, application conflicts, faulty applications, virus/malware infections, incompatible third-party devices, or system/operating system bugs. During the servicing, KSU ICT Office may need certain media to continue the repair process. If I do not have the media for the installation on my equipment, KSU ICT Office is not required to make available those applications that require physical media, serial numbers, or product keys free of charge, and not having the media may slow or halt the servicing of the equipment until the correct media or information is obtained. Any equipment left behind for over 30 days will be delivered to the supply office. I agree that any hardware I leave behind may be delivered to the supply office for proper action. All personal data will be irrevocably destroyed to protect my privacy. KSU ICT Office will make every effort to contact me, but if they cannot reach me within the timeline, regardless of the reason, KSU ICT Office assumes that I do not want whatever equipment I have left behind in the KSU ICT Office.
        </div>
    </div>

</body>
</html>