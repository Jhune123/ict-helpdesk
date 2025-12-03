<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ICTO Assets & Equipment</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 4px; text-align: left; }
        th { background-color: #1E40AF; color: #fff; }
        .high-value { color: #DC2626; font-weight: bold; }
        .recent-asset { background-color: #DCFCE7; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">💼 ICTO Assets & Equipment</h2>
    <table>
        <thead>
            <tr>
                <th>Entity Name</th>
                <th>Fund Cluster</th>
                <th>PAR No.</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Description</th>
                <th>Property No.</th>
                <th>Date Acquired</th>
                <th>Amount</th>
                <th>Purpose</th>
                <th>Approved for Issuance</th>
                <th>Received From</th>
                <th>Received By</th>
                <th>Date Counted</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $asset)
            @php
                $isHighValue = $asset->amount > 100000;
                $isRecent = isset($asset->created_at) && \Carbon\Carbon::parse($asset->created_at)->greaterThan(\Carbon\Carbon::now()->subDays(7));
            @endphp
            <tr class="{{ $isRecent ? 'recent-asset' : '' }}">
                <td>{{ $asset->entity_name }}</td>
                <td>{{ $asset->fund_cluster }}</td>
                <td>{{ $asset->par_no }}</td>
                <td>{{ $asset->quantity }}</td>
                <td>{{ $asset->unit }}</td>
                <td>{{ $asset->description }}</td>
                <td>{{ $asset->property_no }}</td>
                <td>{{ $asset->date_acquired }}</td>
                <td class="{{ $isHighValue ? 'high-value' : '' }}">{{ number_format($asset->amount, 2) }}</td>
                <td>{{ $asset->purpose }}</td>
                <td>{{ $asset->approved_for_issuance }}</td>
                <td>{{ $asset->received_from }}</td>
                <td>{{ $asset->received_by }}</td>
                <td>{{ $asset->date_counted }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
