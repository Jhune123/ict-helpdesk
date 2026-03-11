<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Equipment Repair Form - {{ $ticket->ticket_number }}</title>
    <style>
        @page { margin: 40px 50px; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #000; line-height: 1.4; }
        
        /* ---------------- Header Table ---------------- */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; border: 2px solid #000; }
        .header-table th, .header-table td { border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; }
        
        .logo-cell { width: 18%; }
        .logo-img { width: 75px; height: auto; display: block; margin: 0 auto; }
        
        .title-cell { width: 52%; }
        .inst-name { font-size: 16px; font-weight: bold; }
        .qms-text { font-size: 14px; margin-bottom: 15px; }
        .doc-title { font-size: 16px; font-weight: bold; }
        
        .info-cell { width: 30%; text-align: left; font-size: 10px; padding-left: 10px; }
        
        /* ---------------- Document Body ---------------- */
        .req-no-container { margin-bottom: 20px; font-size: 14px; }
        .req-wrapper { display: inline-block; text-align: center; vertical-align: bottom; }
        .req-no-line { border-bottom: 1px solid #000; width: 250px; display: block; font-weight: bold; font-family: 'Courier New', Courier, monospace; font-size: 13px; padding-bottom: 2px;}
        .sub-header { font-style: italic; font-size: 10px; margin-top: 2px; }
        
        .section-container { position: relative; margin-top: 15px; margin-bottom: 8px; }
        .section-number { position: absolute; left: 0; font-size: 13px; top: 0; }
        .section-title { padding-left: 20px; font-size: 13px; text-transform: uppercase; }
        
        /* ---------------- Form Rows & Lines ---------------- */
        .form-table { width: 95%; margin-left: 20px; border-collapse: collapse; margin-bottom: 15px; }
        .form-table td { padding: 4px 0; vertical-align: bottom; }
        .label-cell { white-space: nowrap; padding-right: 5px; font-size: 12px; width: 25%; }
        .line-cell { border-bottom: 1px solid #000; width: 75%; text-align: left; padding-left: 5px; font-family: 'Courier New', Courier, monospace; font-size: 13px; }
        
        /* Two Column Layout for Request Details */
        .two-col-table { width: 95%; margin-left: 20px; border-collapse: collapse; margin-bottom: 15px; }
        .two-col-table td { padding: 5px 0; vertical-align: bottom; font-size: 12px; }
        .col-left-label { width: 18%; white-space: nowrap; }
        .col-left-line { width: 30%; border-bottom: 1px solid #000; padding-left: 5px; font-family: 'Courier New', Courier, monospace; font-size: 12px; }
        .col-right-label { width: 20%; white-space: nowrap; padding-left: 10px; }
        .col-right-line { width: 32%; border-bottom: 1px solid #000; padding-left: 5px; font-family: 'Courier New', Courier, monospace; font-size: 12px; }

        /* Problem Description Lines */
        .problem-table { width: 95%; margin-left: 20px; border-collapse: collapse; margin-top: 5px; }
        .problem-table td { border-bottom: 1px solid #000; height: 22px; vertical-align: bottom; font-family: 'Courier New', Courier, monospace; font-size: 12px; padding-left: 5px; }
        
        /* ---------------- Footer Disclaimer ---------------- */
        .disclaimer-note { margin-top: 30px; text-align: justify; font-size: 10px; font-style: italic; line-height: 1.2; padding: 0 10px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td rowspan="4" class="logo-cell">
                <img src="{{ public_path('image/school-logo.jpg') }}" class="logo-img" alt="KSU Logo">
            </td>
            <td rowspan="2" class="title-cell">
                <div class="inst-name">Kalinga State University</div>
                <div class="qms-text">Quality Management System</div>
            </td>
            <td class="info-cell">Doc. Ref No.:<br><strong>KSU-ICTO-QF-01</strong></td>
        </tr>
        <tr>
            <td class="info-cell">Effectivity Date:<br><strong>October 14, 2025</strong></td>
        </tr>
        <tr>
            <td rowspan="2" class="title-cell">
                <div class="doc-title">Equipment Repair Form</div>
            </td>
            <td class="info-cell">Revision No.:<br><strong>2.0</strong></td>
        </tr>
        <tr>
            <td class="info-cell">Page No.:<br><strong>1</strong></td>
        </tr>
    </table>

    <div class="req-no-container">
        Request No.: 
        <div class="req-wrapper">
            <span class="req-no-line">{{ $ticket->ticket_number }}</span>
            <div class="sub-header">To be filled by KSU ICT User</div>
        </div>
    </div>

    <div class="section-container">
        <span class="section-number">1.</span>
        <div class="section-title">REQUESTOR INFORMATION</div>
    </div>
    <table class="form-table">
        <tr>
            <td class="label-cell">Date Requested:</td>
            <td class="line-cell">{{ $ticket->created_at ? \Carbon\Carbon::parse($ticket->created_at)->format('F d, Y') : '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">User's Full Name:</td>
            <td class="line-cell">{{ $ticket->client_name }}</td>
        </tr>
        <tr>
            <td class="label-cell">User's Office Name & Address:</td>
            <td class="line-cell">{{ $ticket->department }} {{ isset($ticket->form_data['office_address']) && $ticket->form_data['office_address'] ? '- ' . $ticket->form_data['office_address'] : '' }}</td>
        </tr>
        <tr>
            <td class="label-cell">User's Contact Number:</td>
            <td class="line-cell">{{ $ticket->contact_number }}</td>
        </tr>
        <tr>
            <td class="label-cell">Email Address:</td>
            <td class="line-cell"></td>
        </tr>
    </table>

    <div class="section-container">
        <span class="section-number">2.</span>
        <div class="section-title">REQUEST DETAILS</div>
    </div>
    <table class="two-col-table">
        <tr>
            <td class="col-left-label">Equipment Type:</td>
            <td class="col-left-line">{{ $ticket->equipment_type ?? '' }}</td>
            <td class="col-right-label">Brand & Model No:</td>
            <td class="col-right-line">{{ $ticket->brand_model ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-left-label">Serial No:</td>
            <td class="col-left-line">{{ $ticket->serial_no ?? '' }}</td>
            <td class="col-right-label">Property No.:</td>
            <td class="col-right-line">{{ $ticket->form_data['property_no'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-left-label">Acquisition Date:</td>
            <td class="col-left-line">{{ $ticket->form_data['acquisition_date'] ?? '' }}</td>
            <td class="col-right-label">Acquisition Cost:</td>
            <td class="col-right-line">{{ $ticket->form_data['acquisition_cost'] ?? '' }}</td>
        </tr>
        <tr>
            <td class="col-left-label">Date of Last Repair:</td>
            <td class="col-left-line">{{ $ticket->form_data['date_last_repair'] ?? '' }}</td>
            <td class="col-right-label">Nature of Last Repair :</td>
            <td class="col-right-line">{{ $ticket->form_data['nature_last_repair'] ?? '' }}</td>
        </tr>
    </table>

    <div class="section-container">
        <span class="section-number">3.</span>
        <div class="section-title">PROBLEM DESCRIPTION</div>
    </div>
    <table class="problem-table">
        <tr><td>{{ $ticket->description }}</td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
        <tr><td></td></tr>
    </table>

    <div class="disclaimer-note">
        <strong>Must Read:</strong> I now authorize the KSU ICT Office to perform maintenance service to my ICT equipment. I understand that KSU ICT Office is not in any way responsible for any data loss or damage to my equipment. I know that if the equipment was not working correctly at the time of the release, I release KSU ICT Office from any liability as a result of further damages in the event of any equipment-related failure due to hardware wear and tear, application conflicts, faulty applications, virus/malware infections, incompatible third-party devices, or system/operating system bugs. During the servicing, KSU ICT Office may need certain media to continue the repair process. If I do not have the media for the installation on my equipment, KSU ICT Office is not required to make available those applications that require physical media, serial numbers, or product keys free of charge, and not having the media may slow or halt the servicing of the equipment until the correct media or information is obtained. Any equipment left behind for over 30 days will be delivered to the supply office. I agree that any hardware I leave behind may be delivered to the supply office for proper action. All personal data will be irrevocably destroyed to protect my privacy. KSU ICT Office will make every effort to contact me, but if they cannot reach me within the timeline, regardless of the reason, KSU ICT Office assumes that I do not want whatever equipment I have left behind in the KSU ICT Office.
    </div>

</body>
</html>