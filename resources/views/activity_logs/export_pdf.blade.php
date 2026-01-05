<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Activity Logs</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>System Activity Logs</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Action</th>
                <th>Subject</th>
                <th>Subject ID</th>
                <th>Description</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>{{ $log->user?->name ?? 'System' }}</td>
                    <td>{{ ucfirst($log->action) }}</td>
                    <td>{{ $log->subject?->title ?? '-' }}</td>
                    <td>{{ $log->subject_id ?? '-' }}</td>
                    <td>{{ $log->description ?? '-' }}</td>
                    <td>{{ $log->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
