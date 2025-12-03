<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Schedule PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #1E40AF;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        td {
            vertical-align: top;
        }
    </style>
</head>
<body>
    <h1>🗓 Task Schedule</h1>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Requested By</th>
                <th>Location</th>
                <th>Time Range</th>
                <th>IT Personnel</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ \Carbon\Carbon::parse($task->date)->format('M d, Y') }}</td>
                <td>{{ $task->description }}</td>
                <td>{{ $task->requested_by }}</td>
                <td>{{ $task->location }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($task->start_time)->format('h:i A') }} -
                    {{ \Carbon\Carbon::parse($task->end_time)->format('h:i A') }}
                </td>
                <td>{{ $task->assigned_to ?? 'N/A' }}</td>
                <td>{{ $task->remarks }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
