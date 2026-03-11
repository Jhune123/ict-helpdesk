<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Order - {{ $ticket->ticket_number }}</title>
    <style>
        /* DOMPDF Safe CSS */
        body { font-family: Arial, sans-serif; font-size: 14px; color: #000; line-height: 1.3; }
        
        /* Master Form Header Table */
        .qms-header-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .qms-header-table td, .qms-header-table th { border: 1px solid #000; padding: 5px; text-align: center; vertical-align: middle; }
        
        /* Inner Meta Table for Header */
        .meta-table { width: 100%; border-collapse: collapse; }
        .meta-table td { border-bottom: 1px solid #000; border-right: 1px solid #000; border-left: none; border-top: none; text-align: left; padding: 3px 5px; font-size: 12px;}
        .meta-table tr:last-child td { border-bottom: none; }
        .meta-table td:last-child { border-right: none; }

        /* General Layout Classes */
        .section-title { font-weight: bold; margin-top: 15px; margin-bottom: 5px; }
        .indent-1 { margin-left: 30px; }
        
        /* Form Fill Lines */
        .fill-line { border-bottom: 1px solid #000; display: inline-block; padding-bottom: 2px; }
        
        /* Layout Tables for Content */
        .content-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .content-table td { padding: 4px 0; border: none; vertical-align: bottom; }
        .label-cell { white-space: nowrap; width: 1%; padding-right: 10px !important; }
        .line-cell { border-bottom: 1px solid #000 !important; width: 99%; }

        /* Checkbox styling aligned with text */
        .checkbox { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 16px; 
            line-height: 1; 
            display: inline-block;
            vertical-align: middle;
            margin-right: 4px;
            position: relative;
            top: -1px; 
        }
        
        .checkbox-container td {
            vertical-align: middle;
            padding: 3px 0; /* adds slight breathing room between checkbox rows */
        }
        
        .footer-note { margin-top: 20px; text-align: justify; font-size: 13px; }
        .must-read { margin-top: 30px; text-align: justify; font-size: 11px; font-style: italic; line-height: 1.2; }
        
        /* Description Box */
        .desc-box { min-height: 80px; border-bottom: 1px solid #000; line-height: 1.5; padding-top: 5px; }
    </style>
</head>
<body>

    @php
        // Moved to the top so it's available globally for all templates
        if (!function_exists('isBoxChecked')) {
            function isBoxChecked($val, $current) {
                // Allows matching against an array of selections or a single string
                if (is_array($current)) {
                    return in_array($val, $current) ? '<span class="checkbox">&#9745;</span>' : '<span class="checkbox">&#9744;</span>';
                }
                return $val === $current ? '<span class="checkbox">&#9745;</span>' : '<span class="checkbox">&#9744;</span>';
            }
        }
    @endphp

    @if($ticket->networkRequest)
        <table class="qms-header-table">
            <tr>
                <td style="width: 20%;">
                    <img src="{{ public_path('image/school-logo.jpg') }}" width="80" alt="KSU Logo">
                </td>
                <td style="width: 50%;">
                    <h3 style="margin: 0; padding-bottom: 10px;">Kalinga State University</h3>
                    <h3 style="margin: 0; padding-bottom: 10px;">Quality Management System</h3>
                    <span style="font-size: 16px;">Network Request Form</span>
                </td>
                <td style="width: 30%; padding: 0;">
                    <table class="meta-table">
                        <tr>
                            <td style="width: 40%;">Doc. Ref No.:</td>
                            <td style="width: 60%;">KSU-ICTO-QF-04</td>
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

        <div style="margin-bottom: 20px;">
            Request No.: <span class="fill-line" style="width: 250px; font-weight: bold;">{{ $ticket->ticket_number }}</span>
            <div style="font-size: 10px; font-style: italic; margin-left: 90px;">To be filled by KSU ICT User</div>
        </div>

        <div class="section-title">1. REQUESTOR INFORMATION</div>
        <div class="indent-1">
            <table class="content-table">
                <tr>
                    <td class="label-cell">User's Full Name:</td>
                    <td class="line-cell">{{ $ticket->client_name }}</td>
                </tr>
                <tr>
                    <td class="label-cell">User's Office Name & Address:</td>
                    <td class="line-cell">{{ $ticket->networkRequest->office ?? $ticket->department }}</td>
                </tr>
                <tr>
                    <td class="label-cell">User's Contact Number:</td>
                    <td class="line-cell">{{ $ticket->contact_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Email Address:</td>
                    <td class="line-cell">{{ $ticket->user->email ?? '' }}</td>
                </tr>
            </table>
        </div>

        @php
            $netReqType = $ticket->networkRequest->request_type ?? '';
            $netDevType = $ticket->networkRequest->device ?? '';
        @endphp

        <div class="section-title">2. REQUEST DETAILS</div>
        <div class="indent-1">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 25%; vertical-align: top; padding-top: 5px;">Type of Request/Purpose:</td>
                    <td style="width: 75%; vertical-align: top;">
                        <table style="width: 100%;" class="checkbox-container">
                            <tr><td>{!! isBoxChecked('Network Access', $netReqType) !!} Network Access</td></tr>
                            <tr><td>{!! isBoxChecked('Network Troubleshooting', $netReqType) !!} Network Troubleshooting</td></tr>
                            <tr><td>{!! isBoxChecked('VPN Access', $netReqType) !!} VPN Access</td></tr>
                            <tr><td>{!! isBoxChecked('Wireless Network Access', $netReqType) !!} Wireless Network Access</td></tr>
                            <tr><td>{!! isBoxChecked('Technical Support/Assistance', $netReqType) !!} Technical Support/Assistance</td></tr>
                            <tr>
                                <td>
                                    {!! isBoxChecked('Others', $netReqType) !!} Others: 
                                    <span class="fill-line" style="width: 300px;">
                                        {{ $netReqType === 'Others' ? $ticket->networkRequest->request_type_others : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table class="content-table" style="margin-top: 10px;">
                <tr>
                    <td class="label-cell" style="padding-left: 200px;">Location:</td>
                    <td class="line-cell">{{ $ticket->networkRequest->location ?? '' }}</td>
                </tr>
            </table>

            <table style="width: 100%; border: none; margin-top: 5px;">
                <tr>
                    <td style="width: 20%; vertical-align: top; padding-top: 5px;">Device:</td>
                    <td style="width: 80%; vertical-align: top;">
                        <table style="width: 100%;" class="checkbox-container">
                            <tr><td>{!! isBoxChecked('System Unit', $netDevType) !!} System Unit</td></tr>
                            <tr><td>{!! isBoxChecked('Laptop', $netDevType) !!} Laptop</td></tr>
                            <tr><td>{!! isBoxChecked('Mobile Device', $netDevType) !!} Mobile Device</td></tr>
                            <tr>
                                <td>
                                    {!! isBoxChecked('Others', $netDevType) !!} Others: 
                                    <span class="fill-line" style="width: 250px;">
                                        {{ $netDevType === 'Others' ? $ticket->networkRequest->device_others : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table class="content-table" style="margin-top: 10px;">
                <tr>
                    <td class="label-cell">MAC Address:</td>
                    <td class="line-cell">{{ $ticket->networkRequest->mac_address ?? '' }}</td>
                </tr>
            </table>
        </div>

        <div class="section-title">3. PROJECT REQUEST TIMELINE</div>
        <div class="indent-1">
            <table class="content-table">
                <tr>
                    <td class="label-cell">Start Date:</td>
                    <td class="line-cell">
                        {{ $ticket->networkRequest->start_date ? \Carbon\Carbon::parse($ticket->networkRequest->start_date)->format('F d, Y') : '' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-cell">Completion Date:</td>
                    <td class="line-cell">
                        {{ $ticket->networkRequest->completion_date ? \Carbon\Carbon::parse($ticket->networkRequest->completion_date)->format('F d, Y') : '' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-cell">Status/Remarks:</td>
                    <td class="line-cell">
                        <strong>{{ $ticket->status }}</strong> {{ $ticket->remarks ? '- ' . $ticket->remarks : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="line-cell" style="padding-top: 20px;"></td>
                </tr>
            </table>
        </div>

        <div class="footer-note">
            By submitting this form, you acknowledge that the information provided is accurate and complete. The approval of this request is subject to the approval of the Information and Communications Technology Office.
        </div>

    @elseif(isset($ticket->form_data['original_form_type']) && $ticket->form_data['original_form_type'] === 'KSU-ICTO-QF-03')
        <table class="qms-header-table">
            <tr>
                <td style="width: 20%;">
                    <img src="{{ public_path('image/school-logo.jpg') }}" width="80" alt="KSU Logo">
                </td>
                <td style="width: 50%;">
                    <h3 style="margin: 0; padding-bottom: 5px;">Kalinga State University</h3>
                    <h4 style="margin: 0; padding-bottom: 5px;">Quality Management System</h4>
                    <span style="font-size: 16px; font-weight: bold;">Multimedia Request Form</span>
                </td>
                <td style="width: 30%; padding: 0;">
                    <table class="meta-table">
                        <tr>
                            <td style="width: 40%;">Doc. Ref No.:</td>
                            <td style="width: 60%; font-weight: bold;">KSU-ICTO-QF-03</td>
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

        <div style="margin-bottom: 15px;">
            Request No.: <span class="fill-line" style="width: 200px; font-weight: bold;">{{ $ticket->ticket_number }}</span>
            <div style="font-size: 10px; font-style: italic; margin-left: 80px;">To be filled by KSU ICT User</div>
        </div>

        <div class="section-title">I. REQUESTOR INFORMATION</div>
        <table style="width: 100%; border-collapse: collapse; margin-left: 15px; margin-bottom: 10px; font-size: 13px;">
            <tr>
                <td style="width: 15%; padding-top: 5px;">Name:</td>
                <td style="width: 45%; border-bottom: 1px solid #000; font-weight: bold;">{{ $ticket->client_name }}</td>
                <td style="width: 12%; padding-top: 5px; padding-left: 10px;">Date:</td>
                <td style="width: 28%; border-bottom: 1px solid #000;">{{ \Carbon\Carbon::parse($ticket->created_at)->format('F d, Y') }}</td>
            </tr>
            <tr>
                <td style="padding-top: 5px;">Office/College:</td>
                <td style="border-bottom: 1px solid #000;">{{ $ticket->department ?? '' }}</td>
                <td style="padding-top: 5px; padding-left: 10px;">Contact No.:</td>
                <td style="border-bottom: 1px solid #000;">{{ $ticket->contact_number ?? 'N/A' }}</td>
            </tr>
        </table>

        <div class="section-title">II. ACTIVITY DETAILS</div>
        <table style="width: 100%; border-collapse: collapse; margin-left: 15px; font-size: 13px;">
            <tr>
                <td style="width: 18%; padding-top: 5px;">Title of Activity:</td>
                <td colspan="3" style="border-bottom: 1px solid #000; font-weight: bold;">{{ $ticket->title }}</td>
            </tr>
            <tr>
                <td style="padding-top: 5px;">Date of Activity:</td>
                <td style="width: 32%; border-bottom: 1px solid #000;">{{ $ticket->form_data['activity_date'] ?? $ticket->form_data['date'] ?? '' }}</td>
                <td style="width: 15%; padding-top: 5px; padding-left: 10px;">Time/Duration:</td>
                <td style="width: 35%; border-bottom: 1px solid #000;">{{ $ticket->form_data['timeline'] ?? $ticket->form_data['duration'] ?? '' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 5px;">Venue/Location:</td>
                <td colspan="3" style="border-bottom: 1px solid #000;">{{ $ticket->form_data['location'] ?? $ticket->form_data['venue'] ?? '' }}</td>
            </tr>
        </table>

        <table style="width: 100%; margin-left: 15px; margin-top: 15px; margin-bottom: 15px;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <strong style="display: block; margin-bottom: 5px;">Type of Activity:</strong>
                    <table class="checkbox-container" style="width: 100%; font-size: 13px;">
                        @php $actType = $ticket->form_data['type_of_activity'] ?? ''; @endphp
                        <tr><td>{!! isBoxChecked('Meeting', $actType) !!} Meeting</td></tr>
                        <tr><td>{!! isBoxChecked('Seminar/Training', $actType) !!} Seminar/Training</td></tr>
                        <tr><td>{!! isBoxChecked('Program/Event', $actType) !!} Program/Event</td></tr>
                        <tr>
                            <td>
                                {!! isBoxChecked('Others', $actType) !!} Others: 
                                <span class="fill-line" style="width: 150px;">{{ $actType == 'Others' ? ($ticket->form_data['type_of_activity_others'] ?? '') : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%; vertical-align: top;">
                    <strong style="display: block; margin-bottom: 5px;">Services Required:</strong>
                    <table class="checkbox-container" style="width: 100%; font-size: 13px;">
                        @php $servReq = $ticket->form_data['services_required'] ?? ''; @endphp
                        <tr><td>{!! isBoxChecked('Sound System', $servReq) !!} Sound System</td></tr>
                        <tr><td>{!! isBoxChecked('LCD Projector/Screen', $servReq) !!} LCD Projector/Screen</td></tr>
                        <tr><td>{!! isBoxChecked('Photo/Video Documentation', $servReq) !!} Photo/Video Documentation</td></tr>
                        <tr><td>{!! isBoxChecked('Video Conferencing', $servReq) !!} Video Conferencing Setup</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="section-title">III. REMARKS / SPECIAL INSTRUCTIONS</div>
        <div style="margin-left: 15px; min-height: 40px; border-bottom: 1px solid #000; padding-top: 5px; font-size: 13px;">
            {!! nl2br(e($ticket->description)) !!}
        </div>

        <table style="width: 100%; margin-top: 30px; text-align: center; border-collapse: collapse; font-size: 13px;">
            <tr>
                <td style="width: 33%; padding: 0 15px;">
                    <div style="border-bottom: 1px solid #000; height: 30px; font-weight: bold; position: relative;">
                        <span style="position: absolute; bottom: 2px; left: 0; right: 0;">{{ $ticket->client_name }}</span>
                    </div>
                    <div style="font-size: 11px; margin-top: 3px;">Requested by</div>
                    <div style="font-size: 10px; color: #555;">(Signature over Printed Name)</div>
                </td>
                <td style="width: 33%; padding: 0 15px;">
                    <div style="border-bottom: 1px solid #000; height: 30px;"></div>
                    <div style="font-size: 11px; margin-top: 3px;">Noted by</div>
                    <div style="font-size: 10px; color: #555;">(Dean / Office Head)</div>
                </td>
                <td style="width: 33%; padding: 0 15px;">
                    <div style="border-bottom: 1px solid #000; height: 30px;"></div>
                    <div style="font-size: 11px; margin-top: 3px;">Approved by</div>
                    <div style="font-size: 10px; color: #555;">(ICTO Director)</div>
                </td>
            </tr>
        </table>

        <div style="border: 2px solid #000; margin-top: 25px; padding: 10px; font-size: 13px;">
            <strong style="font-size: 12px; display: block; margin-bottom: 10px;">TO BE FILLED OUT BY ICTO PERSONNEL:</strong>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 15%; padding: 5px 0;">Action Taken:</td>
                    <td style="border-bottom: 1px solid #000; font-weight: bold;">{{ $ticket->remarks }}</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;">Assigned To:</td>
                    <td style="border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;">Date Completed:</td>
                    <td style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
        </div>

        <div class="footer-note" style="margin-top: 15px; font-size: 11px; text-align: center; color: #444;">
            * Requests must be submitted to the ICT Office at least three (3) days prior to the schedule of the activity.
        </div>

    @elseif(isset($ticket->form_data['original_form_type']) && $ticket->form_data['original_form_type'] === 'KSU-ICTO-QF-02' || $ticket->category === 'Information System')
        <table class="qms-header-table">
            <tr>
                <td style="width: 20%;">
                    <img src="{{ public_path('image/school-logo.jpg') }}" width="80" alt="KSU Logo">
                </td>
                <td style="width: 50%;">
                    <h3 style="margin: 0; padding-bottom: 10px;">Kalinga State University</h3>
                    <h3 style="margin: 0; padding-bottom: 10px;">Quality Management System</h3>
                    <span style="font-size: 16px;">Information System Request Form</span>
                </td>
                <td style="width: 30%; padding: 0;">
                    <table class="meta-table">
                        <tr>
                            <td style="width: 40%;">Doc. Ref No.:</td>
                            <td style="width: 60%;">KSU-ICTO-QF-02</td>
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

        <div style="margin-bottom: 20px;">
            Request No.: <span class="fill-line" style="width: 250px; font-weight: bold;">{{ $ticket->ticket_number }}</span>
            <div style="font-size: 10px; font-style: italic; margin-left: 90px;">To be filled by KSU ICT User</div>
        </div>

        <div class="section-title">1. REQUESTOR INFORMATION</div>
        <div class="indent-1">
            <table class="content-table">
                <tr>
                    <td class="label-cell">User's Full Name:</td>
                    <td class="line-cell">{{ $ticket->client_name }}</td>
                </tr>
                <tr>
                    <td class="label-cell">User's Office Name & Address:</td>
                    <td class="line-cell">{{ $ticket->department ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">User's Contact Number:</td>
                    <td class="line-cell">{{ $ticket->contact_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Email Address:</td>
                    <td class="line-cell">{{ $ticket->user->email ?? '' }}</td>
                </tr>
            </table>
        </div>

        @php
            $isReqType = $ticket->form_data['request_type'] ?? '';
            $isName = $ticket->form_data['is_name'] ?? $ticket->title ?? '';
        @endphp

        <div class="section-title">2. REQUEST DETAILS</div>
        <div class="indent-1">
            <table class="content-table" style="margin-bottom: 10px;">
                <tr>
                    <td class="label-cell">I.S. Name:</td>
                    <td class="line-cell">{{ $isName }}</td>
                </tr>
            </table>

            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 25%; vertical-align: top; padding-top: 5px;">Type of Request/Purpose:</td>
                    <td style="width: 75%; vertical-align: top;">
                        <table style="width: 100%;" class="checkbox-container">
                            <tr><td>{!! isBoxChecked('Account Management', $isReqType) !!} Account Management</td></tr>
                            <tr><td>{!! isBoxChecked('Bug Report', $isReqType) !!} Bug Report</td></tr>
                            <tr><td>{!! isBoxChecked('System Installation/Repair', $isReqType) !!} System Installation/Repair</td></tr>
                            <tr><td>{!! isBoxChecked('Technical Support/Assistance', $isReqType) !!} Technical Support/Assistance</td></tr>
                            <tr>
                                <td>
                                    {!! isBoxChecked('Others', $isReqType) !!} Others: 
                                    <span class="fill-line" style="width: 300px;">
                                        {{ $isReqType === 'Others' ? ($ticket->form_data['request_type_others'] ?? '') : '' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-title">3. PROJECT REQUEST TIMELINE</div>
        <div class="indent-1">
            <table class="content-table">
                <tr>
                    <td class="label-cell">Start Date:</td>
                    <td class="line-cell">
                        {{ !empty($ticket->form_data['start_date']) ? \Carbon\Carbon::parse($ticket->form_data['start_date'])->format('F d, Y') : '' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-cell">Completion Date:</td>
                    <td class="line-cell">
                        {{ !empty($ticket->form_data['completion_date']) ? \Carbon\Carbon::parse($ticket->form_data['completion_date'])->format('F d, Y') : '' }}
                    </td>
                </tr>
                <tr>
                    <td class="label-cell">Status/Remarks:</td>
                    <td class="line-cell">
                        <strong>{{ $ticket->status }}</strong> {{ $ticket->remarks ? '- ' . $ticket->remarks : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="line-cell" style="padding-top: 20px;"></td>
                </tr>
            </table>
        </div>

        <div class="footer-note">
            By submitting this form, you acknowledge that the information provided is accurate and complete. The approval of this request is subject to the approval of the Information and Communications Technology Office.
        </div>

    @else
        <table class="qms-header-table">
            <tr>
                <td style="width: 20%;">
                    <img src="{{ public_path('image/school-logo.jpg') }}" width="80" alt="KSU Logo">
                </td>
                <td style="width: 50%;">
                    <h3 style="margin: 0; padding-bottom: 10px;">Kalinga State University</h3>
                    <h3 style="margin: 0; padding-bottom: 10px;">Quality Management System</h3>
                    <span style="font-size: 16px;">Equipment Repair Form</span>
                </td>
                <td style="width: 30%; padding: 0;">
                    <table class="meta-table">
                        <tr>
                            <td style="width: 40%;">Doc. Ref No.:</td>
                            <td style="width: 60%;">KSU-ICTO-QF-01</td>
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

        <div style="margin-bottom: 20px;">
            Request No.: <span class="fill-line" style="width: 250px; font-weight: bold;">{{ $ticket->ticket_number }}</span>
            <div style="font-size: 10px; font-style: italic; margin-left: 90px;">To be filled by KSU ICT User</div>
        </div>

        <div class="section-title">1. REQUESTOR INFORMATION</div>
        <div class="indent-1">
            <table class="content-table">
                <tr>
                    <td class="label-cell">Date Requested:</td>
                    <td class="line-cell">{{ \Carbon\Carbon::parse($ticket->created_at)->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td class="label-cell">User's Full Name:</td>
                    <td class="line-cell">{{ $ticket->client_name }}</td>
                </tr>
                <tr>
                    <td class="label-cell">User's Office Name & Address:</td>
                    <td class="line-cell">{{ $ticket->department ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">User's Contact Number:</td>
                    <td class="line-cell">{{ $ticket->contact_number ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Email Address:</td>
                    <td class="line-cell">{{ $ticket->user->email ?? '' }}</td>
                </tr>
            </table>
        </div>

        <div class="section-title">2. REQUEST DETAILS</div>
        <div class="indent-1">
            <table class="content-table" style="margin-top: 10px;">
                <tr>
                    <td class="label-cell">Equipment Type:</td>
                    <td class="line-cell" style="width: 30%;">{{ $ticket->equipment_type }}</td>
                    <td class="label-cell" style="padding-left: 10px;">Brand & Model No:</td>
                    <td class="line-cell">{{ $ticket->brand_model }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Serial No:</td>
                    <td class="line-cell" style="width: 30%;">{{ $ticket->serial_no }}</td>
                    <td class="label-cell" style="padding-left: 10px;">Property No.:</td>
                    <td class="line-cell">{{ $ticket->form_data['property_no'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Acquisition Date:</td>
                    <td class="line-cell" style="width: 30%;">{{ $ticket->form_data['acquisition_date'] ?? '' }}</td>
                    <td class="label-cell" style="padding-left: 10px;">Acquisition Cost:</td>
                    <td class="line-cell">{{ $ticket->form_data['acquisition_cost'] ?? '' }}</td>
                </tr>
                <tr>
                    <td class="label-cell">Date of Last Repair:</td>
                    <td class="line-cell" style="width: 30%;">{{ $ticket->form_data['date_last_repair'] ?? '' }}</td>
                    <td class="label-cell" style="padding-left: 10px;">Nature of Last Repair:</td>
                    <td class="line-cell">{{ $ticket->form_data['nature_last_repair'] ?? '' }}</td>
                </tr>
            </table>
        </div>

        <div class="section-title">3. PROBLEM DESCRIPTION</div>
        <div class="indent-1">
            <div class="desc-box">
                <strong>{{ $ticket->title }}</strong><br>
                {!! nl2br(e($ticket->description)) !!}
            </div>
        </div>

        <div class="must-read">
            Must Read: I now authorize the KSU ICT Office to perform maintenance service to my ICT equipment. I understand that KSU ICT Office is not in any way responsible for any data loss or damage to my equipment. I know that if the equipment was not working correctly at the time of the release, I release KSU ICT Office from any liability as a result of further damages in the event of any equipment-related failure due to hardware wear and tear, application conflicts, faulty applications, virus/malware infections, incompatible third-party devices, or system/operating system bugs. During the servicing, KSU ICT Office may need certain media to continue the repair process. If I do not have the media for the installation on my equipment, KSU ICT Office is not required to make available those applications that require physical media, serial numbers, or product keys free of charge, and not having the media may slow or halt the servicing of the equipment until the correct media or information is obtained. Any equipment left behind for over 30 days will be delivered to the supply office. I agree that any hardware I leave behind may be delivered to the supply office for proper action. All personal data will be irrevocably destroyed to protect my privacy. KSU ICT Office will make every effort to contact me, but if they cannot reach me within the timeline, regardless of the reason, KSU ICT Office assumes that I do not want whatever equipment I have left behind in the KSU ICT Office.
        </div>
    @endif

</body>
</html>