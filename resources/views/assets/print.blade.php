@extends('layouts.print')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-2 text-center">ICTO Assets Inventory</h2>

    <!-- Display Applied Filters -->
    @if(!empty($filters))
        <div class="mb-4 text-center">
            <strong>Filters:</strong>
            @foreach($filters as $key => $value)
                @if($value)
                    {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }};&nbsp;
                @endif
            @endforeach
        </div>
    @endif

    <table class="w-full border-collapse border border-gray-300 text-sm">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-2 py-1">#</th>
                <th class="border px-2 py-1">Entity Name</th>
                <th class="border px-2 py-1">Fund Cluster</th>
                <th class="border px-2 py-1">PAR No.</th>
                <th class="border px-2 py-1">Asset Name</th>
                <th class="border px-2 py-1">Category</th>
                <th class="border px-2 py-1">Department</th>
                <th class="border px-2 py-1">Quantity</th>
                <th class="border px-2 py-1">Unit</th>
                <th class="border px-2 py-1">Description</th>
                <th class="border px-2 py-1">Property No.</th>
                <th class="border px-2 py-1">Date Acquired</th>
                <th class="border px-2 py-1">Amount</th>
                <th class="border px-2 py-1">Purpose</th>
                <th class="border px-2 py-1">Approved for Issuance</th>
                <th class="border px-2 py-1">Received From</th>
                <th class="border px-2 py-1">Received By</th>
                <th class="border px-2 py-1">Date Counted</th>
            </tr>
        </thead>

        <tbody>
            @foreach($assets as $index => $asset)
            <tr>
                <td class="border px-2 py-1 text-center">{{ $index + 1 }}</td>

                <td class="border px-2 py-1">{{ $asset->entity_name }}</td>
                <td class="border px-2 py-1">{{ $asset->fund_cluster }}</td>
                <td class="border px-2 py-1">{{ $asset->par_no }}</td>
                <td class="border px-2 py-1">{{ $asset->name }}</td>

                <td class="border px-2 py-1">{{ $asset->category }}</td>
                <td class="border px-2 py-1">{{ $asset->department }}</td>

                <td class="border px-2 py-1 text-center">{{ $asset->quantity ?? 1 }}</td>
                <td class="border px-2 py-1">{{ $asset->unit }}</td>

                <td class="border px-2 py-1">{{ $asset->description }}</td>
                <td class="border px-2 py-1">{{ $asset->property_no }}</td>

                <td class="border px-2 py-1">
                    {{ $asset->date_acquired ? $asset->date_acquired->format('d/m/Y') : 'N/A' }}
                </td>

                <td class="border px-2 py-1">
                    {{ $asset->amount ? '₱' . number_format($asset->amount, 2) : 'N/A' }}
                </td>

                <td class="border px-2 py-1">{{ $asset->purpose }}</td>
                <td class="border px-2 py-1">{{ $asset->approved_for_issuance }}</td>
                <td class="border px-2 py-1">{{ $asset->received_from }}</td>
                <td class="border px-2 py-1">{{ $asset->received_by }}</td>

                <td class="border px-2 py-1">
                    {{ $asset->date_counted ? $asset->date_counted->format('d/m/Y') : 'N/A' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    window.onload = function() {
        window.print();
    };
</script>
@endsection
