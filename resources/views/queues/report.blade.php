<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ICTO Queue Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 6px; text-align: center; }
        h1, h2 { text-align: center; margin: 0; }
    </style>
</head>
<body>

    <h1>ICTO-MIS Queuing System</h1>
    <h2>Detailed Queue Report</h2>
    <p style="text-align:center;">Date & Time: {{ $date }} (PH Time)</p>
    <hr>

    <table>
        <thead>
            <tr>
                <th>Queue #</th>
                <th>Status</th>
                <th>Counter</th>
            </tr>
        </thead>
        <tbody>
            @foreach($queues as $queue)
            <tr>
                <td>{{ $queue->queue_number }}</td>
                <td>{{ ucfirst($queue->status) }}</td>
                <td>{{ $queue->served_by ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
