<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>System Activity Logs</title>
    <style>
        @page { margin: 2cm; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 10px; 
            color: #333; 
            line-height: 1.4;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #444; 
            padding-bottom: 10px;
        }
        .header h2 { margin: 0; text-transform: uppercase; color: #1a202c; }
        .header p { margin: 5px 0 0; color: #718096; font-size: 11px; }
        
        table { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: fixed; /* Prevents long text from pushing columns off-page */
        }
        th, td { 
            border: 1px solid #e2e8f0; 
            padding: 8px 6px; 
            text-align: left; 
            word-wrap: break-word; 
        }
        th { 
            background-color: #edf2f7; 
            color: #4a5568; 
            font-weight: bold; 
            text-transform: uppercase;
            font-size: 9px;
        }
        tr:nth-child(even) { background-color: #f7fafc; }
        
        .badge {
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .action-create { color: #2f855a; }
        .action-update { color: #2b6cb0; }
        .action-delete { color: #c53030; }
        
        .footer {
            position: fixed;
            bottom: -1cm;
            left: 0;
            right: 0;
            text-align: right;
            font-size: 8px;
            color: #a0aec0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>System Activity Logs</h2>
        <p>Generated on: {{ now()->timezone('Asia/Manila')->format('F d, Y h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 15%;">User</th>
                <th style="width: 10%;">Action</th>
                <th style="width: 20%;">Subject / Title</th>
                <th style="width: 30%;">Description</th>
                <th style="width: 20%;">Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td><strong>{{ $log->user?->name ?? 'System' }}</strong></td>
                    <td class="action-{{ strtolower($log->action) }}">
                        {{ ucfirst($log->action) }}
                    </td>
                    <td>
                        {{-- Shows the Ticket/Subject title or falls back to ID --}}
                        {{ $log->subject?->title ?? $log->subject?->name ?? 'ID: ' . $log->subject_id }}
                    </td>
                    <td>{{ $log->description ?? '-' }}</td>
                    <td>{{ $log->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Page {PAGENO} | MIS Ticket System Activity Report
    </div>
</body>
</html>