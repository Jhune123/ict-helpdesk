<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: 13in 8.5in; margin: 0.3in; }
        body { font-family: sans-serif; font-size: 7.5pt; color: #333; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 3px; word-wrap: break-word; vertical-align: top; }
        th { background-color: #1e3a8a; color: white; text-transform: uppercase; font-size: 7pt; }
        
        /* Precise Column Widths (Total 100%) */
        .c1 { width: 8%; }  /* Entity */
        .c2 { width: 5%; }  /* Fund */
        .c3 { width: 6%; }  /* PAR */
        .c4 { width: 3%; }  /* Qty */
        .c5 { width: 3%; }  /* Unit */
        .c6 { width: 15%; } /* Description */
        .c7 { width: 8%; }  /* Property No */
        .c8 { width: 7%; }  /* Status */
        .c9 { width: 7%; }  /* Acquired */
        .col-amt { width: 7%; text-align: right; }
        .c11 { width: 8%; } /* Purpose */
        .c12 { width: 6%; } /* Appr. Iss. */
        .c13 { width: 6%; } /* Received From */
        .c14 { width: 6%; } /* Received By */
        .c15 { width: 5%; } /* Date Counted */
    </style>
</head>
<body>
    <h2 style="text-align:center;">💼 ICTO ASSETS & EQUIPMENT INVENTORY</h2>
    <table>
        <thead>
            <tr>
                <th class="c1">Entity Name</th>
                <th class="c2">Fund</th>
                <th class="c3">PAR No.</th>
                <th class="c4">Qty</th>
                <th class="c5">Unit</th>
                <th class="c6">Description</th>
                <th class="c7">Property No.</th>
                <th class="c8">Unit Status</th>
                <th class="c9">Acquired</th>
                <th class="col-amt">Amount</th>
                <th class="c11">Purpose</th>
                <th class="c12">Approved</th>
                <th class="c13">From</th>
                <th class="c14">By</th>
                <th class="c15">Counted</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $asset)
            <tr>
                <td>{{ $asset->entity_name }}</td>
                <td>{{ $asset->fund_cluster }}</td>
                <td>{{ $asset->par_no }}</td>
                <td>{{ $asset->quantity }}</td>
                <td>{{ $asset->unit }}</td>
                <td>{{ $asset->description }}</td>
                <td>{{ $asset->property_no }}</td>
                <td>{{ $asset->unit_status }}</td>
                <td>{{ $asset->date_acquired }}</td>
                <td style="text-align:right;">{{ number_format($asset->amount, 2) }}</td>
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