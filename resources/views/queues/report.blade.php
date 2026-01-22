<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ICTO Queue Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: center; }
        h1, h2 { text-align: center; margin: 0; padding: 0; }
        .signature { margin-top: 60px; text-align: center; }
        .signature div { display: inline-block; width: 40%; }
    </style>
</head>
<body>

    <h1>ICTO-MIS Queuing System</h1>
    <h2>PDF Report</h2>
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

    <div class="signature">
        <div>
            <p><strong>Prepared by</strong></p>
            <p>______________________</p>
            <p>{{ $prepared_by }}</p>
        </div>
        <div>
            <p><strong>Approved by</strong></p>
            <p>______________________</p>
            <p>{{ $approved_by }}</p>
        </div>
    </div>

</body>
</html>
