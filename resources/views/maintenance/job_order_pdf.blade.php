<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Job Order - PMS-{{ str_pad($schedule->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; line-height: 1.5; color: #333; margin: 10px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .logo { width: 80px; height: auto; margin-bottom: 5px; }
        .univ-name { font-size: 18px; font-weight: bold; text-transform: uppercase; margin: 0; }
        
        .job-order-title { font-size: 20px; font-weight: bold; text-align: center; margin-top: 15px; text-decoration: underline; }
        .pms-number { text-align: center; font-family: monospace; font-size: 14px; margin-bottom: 15px; }
        
        .section { margin-bottom: 15px; }
        .section-title { font-weight: bold; background-color: #f2f2f2; padding: 5px; border: 1px solid #000; text-transform: uppercase; font-size: 11px; }
        .content-table { width: 100%; border-collapse: collapse; margin-top: 0px; }
        .content-table td { border: 1px solid #000; padding: 7px; vertical-align: top; }
        .label { font-weight: bold; width: 30%; background-color: #fafafa; }
        
        .footer-table { width: 100%; margin-top: 20px; }
        .footer-table td { width: 50%; text-align: center; vertical-align: top; }
        .signature-block { margin-top: 25px; }
        .signature-line { border-top: 1px solid #000; width: 200px; margin: 30px auto 5px auto; }
    </style>
</head>
<body>

    <div class="header">
        @php
            $logoPath = public_path('image/school-logo.jpg');
            $logoData = "";
            if (file_exists($logoPath)) {
                $logoData = base64_encode(file_get_contents($logoPath));
            }
        @endphp
        
        @if($logoData)
            <img src="data:image/jpeg;base64,{{ $logoData }}" class="logo">
        @else
            <div style="font-size: 12px; font-weight: bold; color: red;">[LOGO NOT FOUND]</div>
        @endif
        
        <div class="univ-name">Kalinga State University</div>
        <div>Information and Communications Technology Center</div>
    </div>

    <div class="job-order-title">PREVENTIVE MAINTENANCE JOB ORDER</div>
    <div class="pms-number">PMS ID: <strong>PMS-{{ str_pad($schedule->id, 5, '0', STR_PAD_LEFT) }}</strong></div>

    <div class="section">
        <div class="section-title">Schedule & Assignment Information</div>
        <table class="content-table">
            <tr>
                <td class="label">Maintenance Title:</td>
                <td>{{ $schedule->title }}</td>
            </tr>
            <tr>
                <td class="label">Assigned Staff:</td>
                <td style="font-weight: bold; color: #1a56db;">
                    @if($schedule->assignees->isNotEmpty())
                        {{ $schedule->assignees->pluck('name')->implode(', ') }}
                    @else
                        Unassigned
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Office / College:</td>
                <td>{{ $schedule->office_college }}</td>
            </tr>
            <tr>
                <td class="label">Frequency / Priority:</td>
                <td>{{ ucfirst($schedule->frequency) }} / <strong>{{ $schedule->priority }}</strong></td>
            </tr>
            <tr>
                <td class="label">Last Maintenance:</td>
                <td>{{ $schedule->last_run_date ? $schedule->last_run_date->format('F d, Y') : 'Never Conducted' }}</td>
            </tr>
            <tr>
                <td class="label">Next Scheduled Run:</td>
                <td style="font-weight: bold;">{{ $schedule->next_run_date ? $schedule->next_run_date->format('F d, Y') : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Device Identification</div>
        <table class="content-table">
            <tr>
                <td class="label">Device / Model:</td>
                <td>{{ $schedule->device_model ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Property Number:</td>
                <td>{{ $schedule->property_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Serial Number:</td>
                <td>{{ $schedule->serial_number ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Scope of Work / Instructions</div>
        <div style="border: 1px solid #000; border-top: none; padding: 10px; min-height: 80px;">
            {!! nl2br(e($schedule->description)) !!}
        </div>
    </div>

    <div class="section">
        <div class="section-title">Technician Remarks (To be filled by Staff)</div>
        <div style="border: 1px solid #000; border-top: none; padding: 10px; min-height: 60px; color: #777; font-style: italic;">
            Write any findings or parts replaced here...
        </div>
    </div>

    <div class="section-title">Signatories</div>
    <table class="footer-table">
        <tr>
            <td>
                @foreach($schedule->assignees as $staff)
                    <div class="signature-block">
                        <div class="signature-line"></div>
                        <strong>{{ $staff->name }}</strong><br>
                        Technician
                    </div>
                @endforeach
                @if($schedule->assignees->isEmpty())
                    <div class="signature-block">
                        <div class="signature-line"></div>
                        <strong>__________________________</strong><br>
                        Technician Signature
                    </div>
                @endif
            </td>
            <td>
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <strong>Date Accomplished</strong>
                </div>
                <div class="signature-block" style="margin-top: 50px;">
                    <div class="signature-line"></div>
                    <strong>ICT Director / Representative</strong><br>
                    Verified and Approved By
                </div>
            </td>
        </tr>
    </table>

    <div style="position: fixed; bottom: 0; width: 100%; font-size: 8px; text-align: center; color: #777;">
        KSU-PMS | System Generated: {{ date('Y-m-d H:i') }} | Page 1 of 1
    </div>

</body>
</html>