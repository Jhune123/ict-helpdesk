<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>KSU - Preventive Maintenance Summary Report</title>
    <style>
        @page { margin: 20px; }
        body { font-family: sans-serif; font-size: 10px; margin: 0; padding: 0; color: #333; }
        
        /* QMS Header Table Style */
        .qms-header { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .qms-header td { border: 1px solid #000; padding: 5px; vertical-align: middle; }
        .logo-cell { width: 80px; text-align: center; }
        .logo { width: 60px; height: auto; }
        .ksu-title { font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase; }
        .qms-label { font-size: 11px; text-align: center; font-weight: bold; }
        .form-title { font-size: 12px; text-align: center; font-weight: bold; background: #f2f2f2; }
        
        .info-table { font-size: 9px; }
        .info-label { font-weight: bold; border-right: none !important; }
        .info-value { border-left: none !important; }

        /* Main Data Table */
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 6px 4px; text-align: left; word-wrap: break-word; }
        table.data-table th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 8px; text-align: center; }
        
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .overdue { color: #dc2626; font-weight: bold; }
        
        .footer { margin-top: 30px; width: 100%; }
        .footer-text { font-size: 8px; text-align: right; color: #666; }
    </style>
</head>
<body>

    <table class="qms-header">
        <tr>
            <td rowspan="3" class="logo-cell">
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
            <td class="ksu-title">Kalinga State University</td>
            <td width="100" class="info-label">Doc. Ref No.:</td>
            <td width="120" class="info-value">KSU-ICTO-QF-06</td>
        </tr>
        <tr>
            <td class="qms-label">Quality Management System</td>
            <td class="info-label">Effectivity Date:</td>
            <td class="info-value">October 14, 2025</td>
        </tr>
        <tr>
            <td class="form-title">PREVENTIVE MAINTENANCE SUMMARY REPORT</td>
            <td class="info-label">Revision No.:</td>
            <td class="info-value">2.0</td>
        </tr>
    </table>

    <div style="margin-bottom: 5px; font-weight: bold; font-size: 9px;">
        Generated Date: {{ date('F d, Y h:i A') }}
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 12%;">PMS ID</th>
                <th style="width: 20%;">Office/College</th>
                <th style="width: 20%;">Device/Brand/Model</th>
                <th style="width: 10%;">Frequency</th>
                <th style="width: 12%;">Last Performed</th>
                <th style="width: 12%;">Next Schedule</th>
                <th style="width: 14%;">ICT In-Charge</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
            <tr>
                <td class="text-center font-bold">PMS-{{ str_pad($schedule->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $schedule->office_college }}</td>
                <td>
                    <strong>{{ $schedule->title }}</strong><br>
                    <small>{{ $schedule->device_model ?? '---' }}</small>
                </td>
                <td class="text-center">{{ strtoupper($schedule->frequency) }}</td>
                <td class="text-center">{{ $schedule->last_run_date ? $schedule->last_run_date->format('m/d/Y') : 'N/A' }}</td>
                <td class="text-center">
                    <span class="{{ $schedule->next_run_date && $schedule->next_run_date->isPast() ? 'overdue' : '' }}">
                        {{ $schedule->next_run_date ? $schedule->next_run_date->format('m/d/Y') : 'N/A' }}
                    </span>
                </td>
                <td>
                    @if($schedule->assignees->isNotEmpty())
                        {{ $schedule->assignees->pluck('name')->implode(', ') }}
                    @else
                        <span style="color: #999 italic;">Unassigned</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-text">
            Printed by: {{ Auth::user()->name ?? 'System Administrator' }} | Page 1 of 1
        </div>
    </div>

</body>
</html>