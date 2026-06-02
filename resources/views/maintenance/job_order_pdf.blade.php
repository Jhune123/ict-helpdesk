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
        .logo-box { width: 120px; text-align: center; white-space: nowrap; }
        .logo { width: 50px; height: auto; display: inline-block; margin: 0 2px; vertical-align: middle; }
        .univ-text { font-size: 13px; font-weight: bold; text-align: center; text-transform: uppercase; }
        .qms-text { font-size: 10px; text-align: center; font-weight: bold; text-transform: uppercase; }
        .doc-details { width: 180px; font-size: 8px; }

        /* Information Section */
        .info-section { width: 100%; margin-bottom: 15px; border: 1px solid #000; padding: 10px; box-sizing: border-box; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { border: none; padding: 4px 2px; font-size: 9.5px; vertical-align: bottom; }
        .info-label { font-weight: bold; text-transform: uppercase; white-space: nowrap; }
        .underline { border-bottom: 1px solid #000; padding-left: 5px; width: 100%; display: block; min-height: 14px; }

        /* Checklist Table */
        .checklist-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .checklist-table th, .checklist-table td { border: 1px solid #000; padding: 5px; font-size: 8.5px; }
        .checklist-table th { background: #f2f2f2; text-transform: uppercase; text-align: center; font-weight: bold; }
        .category-row { background: #e9ecef; font-weight: bold; text-align: left; font-size: 9px; padding-left: 8px; }
        
        .check-box { width: 15px; height: 15px; border: 1px solid #000; text-align: center; font-family: DejaVu Sans, sans-serif; }
        .checked { font-size: 12px; font-weight: bold; }

        /* Footer Signatures */
        .footer-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .footer-table td { border: 1px solid #000; padding: 10px; vertical-align: top; font-size: 9px; }
        .sig-container { text-align: center; margin-top: 35px; }
        .sig-line { border-top: 1px solid #000; width: 85%; margin: 0 auto 3px auto; }
        .sig-subtitle { font-size: 8.5px; color: #333; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td rowspan="4" class="logo-box">
                @php
                    $logoPath = public_path('image/school-logo.jpg');
                    $bagongPilipinasPath = public_path('image/Bagong-Pilipinas.png');
                    $logoData = "";
                    $bpData = "";
                    if (file_exists($logoPath)) {
                        $logoData = base64_encode(file_get_contents($logoPath));
                    }
                    if (file_exists($bagongPilipinasPath)) {
                        $bpData = base64_encode(file_get_contents($bagongPilipinasPath));
                    }
                @endphp
                @if($logoData)
                    <img src="data:image/jpeg;base64,{{ $logoData }}" class="logo">
                @endif
                @if($bpData)
                    <img src="data:image/png;base64,{{ $bpData }}" class="logo">
                @endif
            </td>
            <td class="univ-text">Kalinga State University</td>
            <td class="doc-details"><strong>Doc. Ref No.:</strong> KSU-ICTO-QF-05</td>
        </tr>
        <tr>
            <td class="qms-text">INFORMATION AND COMMUNICATIONS TECHNOLOGY OFFICE</td>
            <td class="doc-details"><strong>Effectivity Date:</strong> March 24, 2026</td>
        </tr>
        <tr>
            <td class="qms-text" style="background:#f2f2f2; font-size: 11px;">PREVENTIVE MAINTENANCE FORM</td>
            <td class="doc-details"><strong>Revision No.:</strong> 3.0</td>
        </tr>
        <tr>
            <td style="text-align: center; font-size: 8px; font-weight: bold;">ICT CENTER</td>
            <td class="doc-details"><strong>Page No.:</strong> 1 of 1</td>
        </tr>
    </table>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td style="width: 14%;"><span class="info-label">Name of Office/College:</span></td>
                <td style="width: 46%;"><span class="underline">{{ $maintenance->office_college }}</span></td>
                <td style="width: 8%; text-align: right;"><span class="info-label">Frequency:</span></td>
                <td style="width: 32%;"><span class="underline">{{ strtoupper($maintenance->frequency) ?? 'SEMI-ANNUAL' }}</span></td>
            </tr>
            <tr>
                <td><span class="info-label">Name of ICT in charge:</span></td>
                <td><span class="underline">{{ $maintenance->assignees->pluck('name')->implode(', ') }}</span></td>
                <td style="text-align: right;"><span class="info-label">Devices/Brand/Model:</span></td>
                <td><span class="underline">{{ $maintenance->title }} / {{ $maintenance->device_model }}</span></td>
            </tr>
            <tr>
                <td><span class="info-label">Property No.:</span></td>
                <td><span class="underline">{{ $maintenance->property_number ?? 'N/A' }}</span></td>
                <td style="text-align: right;"><span class="info-label">Serial No.:</span></td>
                <td><span class="underline">{{ $maintenance->serial_number ?? 'N/A' }}</span></td>
            </tr>
        </table>
    </div>

    <table class="checklist-table">
        <thead>
            <tr>
                <th rowspan="2" width="25">No.</th>
                <th rowspan="2">Preventive Maintenance Task<br><span style="font-size:7.5px; font-weight:normal; text-transform:none;">Tasks to be performed by ICT In-charge</span></th>
                <th colspan="2" width="160">Date and Year of Maintenance<br><span style="font-size:7.5px; font-weight:normal; text-transform:none;">(mm/dd/yyyy)</span></th>
            </tr>
            <tr>
                <th width="80">Done</th>
                <th width="80">Date Executed</th>
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
                        'Check cable connections', 'Check the power sources', 'Clean the mouse',
                        'Clean the keyboard', 'Clean the screen/monitor', 'Clean the CD/DVD -ROM Drive',
                        'Check the fan', 'Check the network hardware'
                    ],
                    'OTHER DEVICES' => [
                        'Check nozzle', 'Check head cleaning', 'Check power flush ink', 'Check ink waste pad',
                        'Check ink level/toner', 'Clean Projector headlamp', 'Check projector power sources and cable',
                        'Check projector fan', 'Check network switches, cable port', 'Check network cable crimp head (rj45 etc.)',
                        'Check network switches fan', 'Check network switches, power sources', 'Check access point (AP) port connection',
                        'Check access point (AP) cable crimp head', 'Check the router cable port', 'Check router crimp head (rj45 and etc.)',
                        'Check network radio antenna UTP cable', 'Check network radio antenna alignment'
                    ]
                ];

                $globalIndex = 1;
            @endphp

            @foreach($tasks as $category => $items)
                <tr class="category-row">
                    <td colspan="4">{{ $category }}</td>
                </tr>
                @foreach($items as $task)
                    @if($category === 'HARDWARE' && $task === 'Clean the CD/DVD -ROM Drive')
                        @php $globalIndex = 19; @endphp {{-- Fixes step jump requested in sequence --}}
                    @endif
                <tr>
                    <td style="text-align: center;">{{ $globalIndex }}</td>
                    <td>{{ $task }}</td>
                    <td class="check-box">
                        @if(in_array($task, $doneTasks))
                            <span class="checked">✔</span>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        {{ in_array($task, $doneTasks) && $maintenance->last_run_date ? $maintenance->last_run_date->format('m/d/Y') : '' }}
                    </td>
                </tr>
                @php $globalIndex++; @endphp
                @endforeach
            @endforeach
            
            <tr>
                <td colspan="4" style="padding: 10px; font-size: 9.5px;">
                    <strong>Remarks:</strong> {{ $stored['remarks'] ?? 'None' }}
                </td>
            </tr>
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td style="width: 50%;">
                <strong>Performed by:</strong>
                <div class="sig-container">
                    <div class="sig-line"></div>
                    <span style="font-weight: bold;">{{ $maintenance->assignees->first()->name ?? '' }}</span><br>
                    <span class="sig-subtitle">Signature over printed name, ICTO Staff/in-charge</span>
                </div>
            </td>
            <td style="width: 50%;">
                <strong>USER ACKNOWLEDGEMENT:</strong>
                <p style="font-size: 8.5px; margin: 5px 0 15px 0;">I, the undersigned, hereby acknowledge that the ICT Office has rendered & completed a service to my satisfaction.</p>
                <table style="width: 100%; border: none;">
                    <tr>
                        <td style="border: none; padding: 0; width: 60%;">
                            <div style="border-top: 1px solid #000; margin-right: 10px; margin-top: 15px; text-align: center;" class="sig-subtitle">Signature</div>
                        </td>
                        <td style="border: none; padding: 0; width: 40%;">
                            <div style="border-top: 1px solid #000; margin-top: 15px; text-align: center;" class="sig-subtitle">Date</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Confirmed by:</strong>
                <div class="sig-container" style="margin-top: 25px;">
                    <div class="sig-line" style="width: 42%;"></div>
                    <span class="sig-subtitle">Signature over printed name, ICTO Supervisor</span>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>