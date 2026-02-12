<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>KSU - Preventive Maintenance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; margin: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .logo { width: 70px; height: auto; margin-bottom: 5px; }
        .univ-name { font-size: 16px; font-weight: bold; margin: 0; text-transform: uppercase; }
        .sub-header { font-size: 12px; margin: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; word-wrap: break-word; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        
        .pms-id { font-family: monospace; font-weight: bold; color: #333; }
        .text-center { text-align: center; }
        .priority-critical { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        @php
            /* Path updated to point to public/image/school-logo.jpg */
            $logoPath = public_path('image/school-logo.jpg');
            $logoData = "";
            if (file_exists($logoPath)) {
                $logoData = base64_encode(file_get_contents($logoPath));
            }
        @endphp
        
        @if($logoData)
            <img src="data:image/jpeg;base64,{{ $logoData }}" class="logo">
        @else
            <div style="font-size: 14px; font-weight: bold; color: red;">[LOGO NOT FOUND AT: public/image/school-logo.jpg]</div>
        @endif
        
        <div class="univ-name">Kalinga State University</div>
        <div class="sub-header">Preventive Maintenance Schedule Report</div>
        <div>Generated on: {{ date('F d, Y h:i A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">PMS ID #</th>
                <th style="width: 20%;">Title / Device</th>
                <th style="width: 15%;">Office / College</th>
                <th style="width: 10%;">Frequency</th>
                <th style="width: 10%;">Priority</th>
                <th style="width: 11%;">Last Run</th>
                <th style="width: 11%;">Next Run</th>
                <th style="width: 11%;">Assigned Staff</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
            <tr>
                <td class="pms-id">PMS-{{ str_pad($schedule->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <strong>{{ $schedule->title }}</strong><br>
                    <small>{{ $schedule->device_model ?? 'N/A' }}</small>
                </td>
                <td>{{ $schedule->office_college }}</td>
                <td class="text-center">{{ ucfirst($schedule->frequency) }}</td>
                <td class="text-center {{ $schedule->priority == 'Critical' ? 'priority-critical' : '' }}">
                    {{ $schedule->priority }}
                </td>
                <td>{{ $schedule->last_run_date ? $schedule->last_run_date->format('m/d/Y') : 'N/A' }}</td>
                <td>
                    <span style="{{ $schedule->next_run_date && $schedule->next_run_date->isPast() ? 'color: red; font-weight: bold;' : '' }}">
                        {{ $schedule->next_run_date ? $schedule->next_run_date->format('m/d/Y') : 'N/A' }}
                    </span>
                </td>
                <td>
                    {{-- Updated to show multiple staff names --}}
                    @if($schedule->assignees->isNotEmpty())
                        {{ $schedule->assignees->pluck('name')->implode(', ') }}
                    @else
                        <span style="color: #999;">Unassigned</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 9px; text-align: right;">
        <p>Printed by: {{ Auth::user()->name ?? 'System' }}</p>
    </div>

</body>
</html>