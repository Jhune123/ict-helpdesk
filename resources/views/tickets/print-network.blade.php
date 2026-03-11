<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Network Request Form - {{ $ticket->ticket_number }}</title>
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
        .doc-title { font-size: 16px; font-weight: bold; text-transform: uppercase; }
        
        .info-cell { width: 30%; text-align: left; font-size: 10px; padding-left: 10px; }
        
        /* ---------------- Document Body ---------------- */
        /* ALIGNED TO THE LEFT */
        .req-no { text-align: left; font-size: 14px; margin-bottom: 2px; }
        .req-no-line { border-bottom: 1px solid #000; display: inline-block; width: 250px; text-align: center; font-weight: bold;}
        .sub-header { text-align: left; font-style: italic; font-size: 11px; margin-bottom: 25px; padding-left: 125px; }
        
        .section-title { font-weight: bold; font-size: 12px; margin-top: 15px; margin-bottom: 8px; text-transform: uppercase; }
        
        /* ---------------- Form Rows & Lines ---------------- */
        .form-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .form-table td { padding: 4px 0; vertical-align: bottom; }
        .label-cell { white-space: nowrap; padding-right: 5px; }
        .line-cell { border-bottom: 1px solid #000; width: 100%; text-align: left; padding-left: 5px; font-family: 'Courier New', Courier, monospace; font-size: 13px; }
        
        /* ---------------- Checkboxes ---------------- */
        .checkbox-container { margin-left: 20px; margin-bottom: 15px; }
        .checkbox-table { width: 100%; }
        .checkbox-table td { padding: 3px 0; font-size: 11px; vertical-align: middle; }
        .box { display: inline-block; width: 12px; height: 12px; border: 1px solid #000; text-align: center; line-height: 12px; font-size: 10px; font-weight: bold; margin-right: 6px; vertical-align: middle; }
        
        /* ---------------- Footer ---------------- */
        .footer-note { margin-top: 30px; text-align: justify; font-size: 11px; font-style: italic; padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9; }
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
            <td class="info-cell">Doc. Ref No.:<br><strong>KSU-ICTO-QF-04</strong></td>
        </tr>
        <tr>
            <td class="info-cell">Effectivity Date:<br><strong>October 14, 2025</strong></td>
        </tr>
        <tr>
            <td rowspan="2" class="title-cell">
                <div class="doc-title">Network Request Form</div>
            </td>
            <td class="info-cell">Revision No.:<br><strong>2.0</strong></td>
        </tr>
        <tr>
            <td class="info-cell">Page No.:<br><strong>1</strong></td>
        </tr>
    </table>

    <div class="req-no">
        Request No.: <span class="req-no-line">{{ $ticket->ticket_number }}</span>
    </div>
    <div class="sub-header">To be filled by KSU ICT User</div>

    <div class="section-title">1. REQUESTOR INFORMATION</div>
    <table class="form-table">
        <tr>
            <td class="label-cell">User's Full Name:</td>
            <td class="line-cell">{{ $ticket->client_name }}</td>
        </tr>
    </table>
    <table class="form-table">
        <tr>
            <td class="label-cell">User's Office Name & Address:</td>
            <td class="line-cell">{{ $ticket->department }} {{ isset($ticket->form_data['office_address']) && $ticket->form_data['office_address'] ? '- ' . $ticket->form_data['office_address'] : '' }}</td>
        </tr>
    </table>
    <table class="form-table">
        <tr>
            <td class="label-cell">User's Contact Number:</td>
            <td class="line-cell">{{ $ticket->contact_number }}</td>
        </tr>
    </table>
    <table class="form-table">
        <tr>
            <td class="label-cell">Email Address:</td>
            <td class="line-cell"></td>
        </tr>
    </table>

    @php
        $reqTypes = $ticket->form_data['request_type'] ?? [];
        $deviceTypes = $ticket->form_data['device_type'] ?? [];
    @endphp

    <div class="section-title">2. REQUEST DETAILS</div>
    <div style="margin-bottom: 5px;">Type of Request/Purpose:</div>
    
    <div class="checkbox-container">
        <table class="checkbox-table">
            <tr>
                <td style="width: 50%;"><span class="box">{{ in_array('Network Access', $reqTypes) ? 'X' : '' }}</span> Network Access</td>
                <td style="width: 50%;"><span class="box">{{ in_array('Technical Support/Assistance', $reqTypes) ? 'X' : '' }}</span> Technical Support/Assistance</td>
            </tr>
            <tr>
                <td><span class="box">{{ in_array('Network Troubleshooting', $reqTypes) ? 'X' : '' }}</span> Network Troubleshooting</td>
                <td>
                    <span class="box">{{ in_array('Others', $reqTypes) ? 'X' : '' }}</span> Others: 
                    <span style="border-bottom: 1px solid #000; padding: 0 10px; display: inline-block; width: 180px;">
                        {{ $ticket->form_data['request_type_others'] ?? '' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td><span class="box">{{ in_array('VPN Access', $reqTypes) ? 'X' : '' }}</span> VPN Access</td>
                <td></td>
            </tr>
            <tr>
                <td><span class="box">{{ in_array('Wireless Network Access', $reqTypes) ? 'X' : '' }}</span> Wireless Network Access</td>
                <td></td>
            </tr>
        </table>
    </div>

    <table class="form-table">
        <tr>
            <td class="label-cell">Location:</td>
            <td class="line-cell">{{ $ticket->form_data['location'] ?? '' }}</td>
        </tr>
    </table>
    
    <div style="margin-top: 10px; margin-bottom: 5px;">Device:</div>
    <div class="checkbox-container">
        <table class="checkbox-table">
            <tr>
                <td style="width: 50%;"><span class="box">{{ in_array('System Unit', $deviceTypes) ? 'X' : '' }}</span> System Unit</td>
                <td style="width: 50%;"><span class="box">{{ in_array('Mobile Device', $deviceTypes) ? 'X' : '' }}</span> Mobile Device</td>
            </tr>
            <tr>
                <td><span class="box">{{ in_array('Laptop', $deviceTypes) ? 'X' : '' }}</span> Laptop</td>
                <td>
                    <span class="box">{{ in_array('Others', $deviceTypes) ? 'X' : '' }}</span> Others: 
                    <span style="border-bottom: 1px solid #000; padding: 0 10px; display: inline-block; width: 180px;">
                        {{ $ticket->form_data['device_others'] ?? '' }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <table class="form-table">
        <tr>
            <td class="label-cell">MAC Address:</td>
            <td class="line-cell">{{ $ticket->form_data['mac_address'] ?? '' }}</td>
        </tr>
    </table>

    <div class="section-title">3. PROJECT REQUEST TIMELINE</div>
    <table class="form-table">
        <tr>
            <td class="label-cell">Start Date:</td>
            <td class="line-cell">{{ !empty($ticket->form_data['start_date']) ? \Carbon\Carbon::parse($ticket->form_data['start_date'])->format('F d, Y') : '' }}</td>
        </tr>
    </table>
    <table class="form-table">
        <tr>
            <td class="label-cell">Completion Date:</td>
            <td class="line-cell">{{ !empty($ticket->form_data['completion_date']) ? \Carbon\Carbon::parse($ticket->form_data['completion_date'])->format('F d, Y') : '' }}</td>
        </tr>
    </table>
    <table class="form-table">
        <tr>
            <td class="label-cell">Status/Remarks:</td>
            <td class="line-cell">{{ $ticket->description ?? '' }}</td>
        </tr>
    </table>

    <div class="footer-note">
        By submitting this form, you acknowledge that the information provided is accurate and complete. The approval of this request is subject to the approval of the Information and Communications Technology Office.
    </div>

</body>
</html>