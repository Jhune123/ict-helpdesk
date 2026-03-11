<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Schedule PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px; /* Slightly smaller for better landscape fit */
            color: #333;
        }
        h1 {
            text-align: center;
            margin-bottom: 5px;
            color: #1E40AF;
        }
        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px 6px;
            text-align: left;
            word-wrap: break-word;
        }
        th {
            background-color: #1E40AF;
            color: #fff;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        td {
            vertical-align: top;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <h1>🗓 Task Schedule Report</h1>
    <div class="subtitle">Kalinga State University - ICTO</div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">Date</th>
                <th style="width: 20%;">Description</th>
                <th style="width: 15%;">Requested By</th>
                <th style="width: 15%;">Location / Dept</th>
                <th style="width: 15%;">Time Range</th>
                <th style="width: 12%;">IT Personnel</th>
                <th style="width: 13%;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ \Carbon\Carbon::parse($task->date)->format('M d, Y') }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->requested_by }}</td>
                <td>
                    {{ $task->location }}<br>
                    <small>({{ $task->department->name ?? 'No Dept' }})</small>
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($task->start_time)->format('h:i A') }} -
                    {{ \Carbon\Carbon::parse($task->end_time)->format('h:i A') }}
                </td>
                <td>{{ $task->assigned_to ?? 'Unassigned' }}</td>
                <td>{{ $task->remarks ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Printed on: {{ now()->format('M d, Y h:i A') }} by {{ Auth::user()->name }}
    </div>
</body>
</html>