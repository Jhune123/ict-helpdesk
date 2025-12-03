@extends('layouts.app')

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    table.dataTable thead {
        background-color: #1E40AF; /* Blue header */
        color: #fff;
    }
    table.dataTable tbody tr:hover {
        background-color: #E0F2FE; /* Light blue hover */
    }
    .high-value {
        color: #DC2626; /* Red text for high-value assets */
        font-weight: bold;
    }
    .recent-asset {
        background-color: #DCFCE7 !important; /* Light green for recent assets */
    }
    table.dataTable td .btn {
        white-space: nowrap;
        margin-right: 2px;
    }
    .table-responsive {
        overflow-x: auto;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4" style="max-width: 1200px;">
    <!-- Header and Add Asset button -->
   <div class="d-flex justify-content-between mb-6 items-center">
        <h1 class="text-2xl font-bold text-center">💼 ICTO Assets & Equipment</h1>

        <div class="flex gap-2">
            @role('admin|it_staff')
            <a href="{{ route('assets.create') }}" 
            class="btn bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded shadow-lg flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Asset
            </a>
            @endrole

            <!-- Export PDF Button -->
            <a href="{{ route('assets.export.pdf') }}" target="_blank"
               class="btn bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow-lg flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Success message -->
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <!-- Assets Table -->
    <div class="table-responsive mx-auto">
        <table id="assetsTable" class="display nowrap table table-striped table-hover" style="width:100%">
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
                    <th>Actions</th>
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
                    <td>
                        <a href="{{ route('assets.show', $asset) }}" class="btn-view">View</a>

                        @role('admin|it_staff')
                            <a href="{{ route('assets.edit', $asset) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete">Delete</button>
                            </form>
                        @endrole
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<!-- Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    $('#assetsTable').DataTable({
        responsive: true,
        scrollX: true,
        paging: true,
        searching: true,
        ordering: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        dom: 'Bfrtip',
        buttons: [
            'copy', 
            'csv', 
            'excel',
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                orientation: 'landscape',  // ✅ Landscape
                pageSize: 'A4',           // ✅ A4 size
                exportOptions: { columns: ':visible' },
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 8; // Fit all columns
                    doc.styles.tableHeader.fontSize = 9;
                    // Make all columns auto-fit
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                }
            },
            'print'
        ],
        order: [[0, 'asc']],
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 1 },
            { responsivePriority: 3, targets: -1 },
            { responsivePriority: 4, targets: -2 },
            { responsivePriority: 5, targets: -3 },
            { responsivePriority: 6, targets: -4 }
        ]
    });
});
</script>
@endsection
