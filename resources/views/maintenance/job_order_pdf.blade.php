<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>KSU-ICTO - Preventive Maintenance Form</title>
    <style>
        @page { margin: 30px; }
        body { font-family: sans-serif; font-size: 10px; line-height: 1.4; color: #000; }
        
        /* Header Table */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header-table td { border: 1px solid #000; padding: 5px; vertical-align: middle; }
        .logo-box { width: 70px; text-align: center; }
        .logo { width: 60px; height: auto; }
        .univ-text { font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase; }
        .qms-text { font-size: 10px; text-align: center; font-weight: bold; }
        .doc-details { width: 180px; font-size: 8px; }

        /* Information Section */
        .info-section { width: 100%; margin-bottom: 15px; border: 1px solid #000; padding: 10px; }
        .info-row { margin-bottom: 5px; clear: both; }
        .info-label { font-weight: bold; text-transform: uppercase; font-size: 9px; }
        .underline { border-bottom: 1px solid #000; display: inline-block; padding-left: 5px; }

        /* Checklist Table */
        .checklist-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .checklist-table th, .checklist-table td { border: 1px solid #000; padding: 4px; font-size: 8.5px; }
        .checklist-table th { background: #f2f2f2; text-transform: uppercase; }
        .category-row { background: #e9ecef; font-weight: bold; text-align: center; font-size: 9px; }
        
        .check-box { width: 15px; height: 15px; border: 1px solid #000; text-align: center; font-family: DejaVu Sans, sans-serif; }
        .checked { font-size: 12px; font-weight: bold; }

        .footer-sig { margin-top: 30px; width: 100%; }
        .sig-box { width: 33%; text-align: center; vertical-align: top; }
        .sig-line { border-top: 1px solid #000; width: 80%; margin: 40px auto 5px auto; font-weight: bold; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td rowspan="4" class="logo-box">
                @php
                    $logoPath = public_path('image/school-logo.jpg');
                    $logoData = "";
                    if (file_exists($logoPath)) {
                        $logoData = base64_encode(file_get_contents($logoPath));
                    }
                @endphp
                @if($logoData)
                    <img src="data:image/jpeg;base64,{{ $logoData }}" class="logo">
                @endif
            </td>
            <td class="univ-text">Kalinga State University</td>
            <td class="doc-details"><strong>Doc. Ref No.:</strong> KSU-ICTO-QF-06</td>
        </tr>
        <tr>
            <td class="qms-text">Quality Management System</td>
            <td class="doc-details"><strong>Effectivity Date:</strong> October 14, 2025</td>
        </tr>
        <tr>
            <td class="qms-text" style="background:#f2f2f2; font-size: 11px;">PREVENTIVE MAINTENANCE FORM</td>
            <td class="doc-details"><strong>Revision No.:</strong> 2.0</td>
        </tr>
        <tr>
            <td style="text-align: center; font-size: 8px;">ICT CENTER</td>
            <td class="doc-details"><strong>Page No.:</strong> 1 of 1</td>
        </tr>
    </table>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Name of Office/College:</span>
            <span class="underline" style="width: 350px;">{{ $maintenance->office_college }}</span>
            <span class="info-label">Frequency:</span>
            <span class="underline" style="width: 150px;">{{ strtoupper($maintenance->frequency) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Name of ICT In-charge:</span>
            <span class="underline" style="width: 334px;">{{ $maintenance->assignees->pluck('name')->implode(', ') }}</span>
            <span class="info-label">Devices/Brand/Model:</span>
            <span class="underline" style="width: 140px;">{{ $maintenance->title }} / {{ $maintenance->device_model }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Property No.:</span>
            <span class="underline" style="width: 388px;">{{ $maintenance->property_number ?? 'N/A' }}</span>
            <span class="info-label">Serial No.:</span>
            <span class="underline" style="width: 167px;">{{ $maintenance->serial_number ?? 'N/A' }}</span>
        </div>
    </div>

    <table class="checklist-table">
        <thead>
            <tr>
                <th width="30">Check</th>
                <th>Preventive Maintenance Task (Tasks to be performed by ICT In-charge)</th>
                <th width="120">Date of Maintenance</th>
            </tr>
        </thead>
        <tbody>
            @php
                $stored = json_decode($maintenance->description, true);
                $doneTasks = $stored['tasks'] ?? [];
                
                $tasks = [
                    'SOFTWARE APPLICATION' => [
                        'Empty the Recycle Bin', 'Delete .temp files', 'Delete the files that begin with a tilde',
                        'Delete the .check files, and switch the file', 'Run Scandisk and defrag the drive as needed',
                        'Check browser history and cache files', 'Clean out Windows temporary Internet files',
                        'Confirm that backups are already done', 'Update drivers as needed',
                        'Check the Operating system and Applications', 'Update the anti-virus software if needed'
                    ],
                    'HARDWARE' => [
                        'Check cable connections', 'Check the power sources', 'Clean the Mouse',
                        'Clean the Keyboard', 'Clean the Screen/ Monitor', 'Clean the CD/DVD -ROM Drive',
                        'Check the Fan', 'Check the Network Hardware'
                    ],
                    'OTHER DEVICES' => [
                        'Check Nozzle', 'Check Head Cleaning', 'Check Power Flush Ink', 'Check ink waste pad',
                        'Check ink level/ toner', 'Clean Projector headlamp', 'Check projector power sources and cable',
                        'Check projector fan', 'Check network switches cable port', 'Check network cable crimp head (rj45 etc.)',
                        'Check network switches fan', 'Check network switches power sources', 'Check access point (AP) port connection',
                        'Check access point (AP) cable crimp head', 'Check the router cable port', 'Check router crimp head (rj45 and etc.)',
                        'Check network radio antenna UTP cable', 'Check network radio antenna alignment'
                    ]
                ];
            @endphp

            @foreach($tasks as $category => $items)
                <tr class="category-row">
                    <td colspan="3">{{ $category }}</td>
                </tr>
                @foreach($items as $task)
                <tr>
                    <td class="check-box">
                        @if(in_array($task, $doneTasks))
                            <span class="checked">✔</span>
                        @endif
                    </td>
                    <td>{{ $task }}</td>
                    <td style="text-align: center;">{{ in_array($task, $doneTasks) ? $maintenance->last_run_date->format('m/d/Y') : '' }}</td>
                </tr>
                @endforeach
            @endforeach
            
            <tr>
                <td colspan="3" style="padding: 10px;">
                    <strong>Remarks:</strong> {{ $stored['remarks'] ?? 'None' }}
                </td>
            </tr>
        </tbody>
    </table>

    <table class="footer-sig">
        <tr>
            <td class="sig-box">
                <div class="sig-line">{{ $maintenance->assignees->first()->name ?? 'ICT Staff' }}</div>
                Performed By
            </td>
            <td class="sig-box">
                <div class="sig-line"></div>
                End-User Signature
            </td>
            <td class="sig-box">
                <div class="sig-line">ICT DIRECTOR / HEAD</div>
                Noted By
            </td>
        </tr>
    </table>

</body>
</html>