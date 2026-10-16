<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { size: A4 landscape; margin: 10mm 12mm; }
        body { font-family: DejaVu Serif, serif; font-size: 9pt; color: #222; }
        .letterhead { width: 88%; margin: 0 auto 4px; border-collapse: collapse; table-layout: fixed; }
        .letterhead td { border: 0; padding: 0; vertical-align: middle; }
        .letterhead .logo-cell { width: 15%; text-align: right; padding-right: 8px; }
        .letterhead .text-cell { width: 85%; text-align: center; }
        .letterhead img { width: 64px; height: 76px; }
        .letterhead-copy { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .letterhead-copy td { margin: 0; padding: 0; text-align: center; line-height: 1.05; }
        .letterhead-copy .university { font-size: 13pt; font-weight: bold; }
        .letterhead-copy .office { font-size: 11pt; font-weight: bold; }
        .letterhead-copy .receipt-title { padding-top: 3px; font-size: 14pt; font-weight: bold; font-style: italic; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #777; padding: 5px; vertical-align: top; overflow-wrap: break-word; }
        th { width: 17%; background: #f2f4f7; text-align: left; }
        td { width: 33%; white-space: pre-wrap; }
        .asset-record { page-break-inside: avoid; margin-bottom: 8px; }
    </style>
</head>
<body>
    <table class="letterhead">
        <tr>
            <td class="logo-cell">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('image/KSU-logo.png'))) }}" alt="Kalinga State University logo">
            </td>
            <td class="text-cell">
                <table class="letterhead-copy">
                    <tr><td>Republic of the Philippines</td></tr>
                    <tr><td class="university">KALINGA STATE UNIVERSITY</td></tr>
                    <tr><td class="office">Supply and Property Management Office</td></tr>
                    <tr><td>Bulanao, Tabuk City, Kalinga</td></tr>
                    <tr><td class="receipt-title">PROPERTY ACKNOWLEDGEMENT RECEIPT</td></tr>
                </table>
            </td>
        </tr>
    </table>

    @forelse($assets as $asset)
        <section class="asset-record">
            <table>
                <tbody>
                    @foreach(array_chunk([
                        ['Entity Name', $asset->entity_name],
                        ['Fund Cluster', $asset->fund_cluster],
                        ['PAR No.', $asset->par_no],
                        ['Quantity', $asset->quantity],
                        ['Unit', $asset->unit],
                        ['Property No.', $asset->property_no],
                        ['Date Acquired', $asset->date_acquired?->format('M d, Y')],
                        ['Amount', $asset->amount],
                        ['Unit Status', $asset->unit_status],
                        ['Description / Item Details', $asset->description],
                        ['Purpose', $asset->purpose],
                        ['Approved for Issuance', $asset->approved_for_issuance],
                        ['Received From', $asset->received_from],
                        ['Received By', $asset->received_by],
                        ['Date Counted', $asset->date_counted?->format('M d, Y')],
                        ['PO. NO.', $asset->po_no],
                    ], 2) as $row)
                        <tr>
                            @foreach($row as [$label, $value])
                                <th>{{ $label }}</th>
                                <td>{{ filled($value) ? $value : '—' }}</td>
                            @endforeach
                            @if(count($row) === 1)
                                <th></th>
                                <td></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @empty
        <p>No assets have been added.</p>
    @endforelse
</body>
</html>
