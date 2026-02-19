@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    table.dataTable thead { background-color: #1E40AF; color: #fff; }
    table.dataTable tbody tr:hover { background-color: #E0F2FE; }
    .high-value { color: #DC2626; font-weight: bold; }
    .recent-asset { background-color: #DCFCE7 !important; }
    table.dataTable td .btn { white-space: nowrap; margin-right: 2px; }
    .table-responsive { overflow-x: auto; }
    
    /* Action Button Styles */
    .btn-view { background-color: #3b82f6; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; text-decoration: none; }
    .btn-edit { background-color: #10b981; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; text-decoration: none; }
    .btn-delete { background-color: #ef4444; color: white; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; }
    .btn-view:hover, .btn-edit:hover, .btn-delete:hover { opacity: 0.8; color: white; }

    /* Custom Badge Styles */
    .status-badge { padding: 0.25em 0.6em; font-size: 75%; font-weight: 700; border-radius: 0.25rem; color: #fff; text-align: center; white-space: nowrap; }
    .bg-success { background-color: #198754; }
    .bg-warning { background-color: #ffc107; color: #000; }
    .bg-danger { background-color: #dc3545; }
    .bg-info { background-color: #0dcaf0; color: #000; }
    .bg-secondary { background-color: #6c757d; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4" style="max-width: 1200px;">
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

            <a href="{{ route('assets.export.pdf') }}" target="_blank"
               class="btn bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow-lg flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10l-4 4m0 0l-4-4m4 4V4m0 12h8" />
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center mb-4">{{ session('success') }}</div>
    @endif

    <div class="table-responsive mx-auto bg-white p-4 rounded-lg shadow">
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
                    <th>Unit Status</th> 
                    <th>Date Acquired</th>
                    <th>Amount</th>
                    <th>Purpose</th>
                    <th>Approved for Issuance</th>
                    <th>Received From</th>
                    <th>Received By</th>
                    <th>Date Counted</th>
                    <th>Actions</th>
                    <th style="display:none;">Created At</th>
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
                    
                    <td>
                        @php
                            $badgeClass = 'bg-secondary';
                            if($asset->unit_status == 'Active') $badgeClass = 'bg-success';
                            elseif($asset->unit_status == 'Under Repair') $badgeClass = 'bg-warning';
                            elseif(in_array($asset->unit_status, ['Condemned', 'Not Found in the Station'])) $badgeClass = 'bg-danger';
                            elseif($asset->unit_status == 'For Replacement') $badgeClass = 'bg-info';
                        @endphp
                        <span class="status-badge {{ $badgeClass }}">{{ $asset->unit_status }}</span>
                    </td>

                    <td>{{ $asset->date_acquired }}</td>
                    <td class="{{ $isHighValue ? 'high-value' : '' }}">{{ number_format($asset->amount, 2) }}</td>
                    <td>{{ $asset->purpose }}</td>
                    <td>{{ $asset->approved_for_issuance }}</td>
                    <td>{{ $asset->received_from }}</td>
                    <td>{{ $asset->received_by }}</td>
                    <td>{{ $asset->date_counted }}</td>
                    <td>
                        <div class="flex gap-1">
                            <a href="{{ route('assets.show', $asset) }}" class="btn-view">View</a>
                            @role('admin|it_staff')
                            <a href="{{ route('assets.edit', $asset) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline" onsubmit="return confirm('Delete this asset?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                            @endrole
                        </div>
                    </td>
                    <td style="display:none;">{{ $asset->created_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
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
        pageLength: 15,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
        order: [[16, 'desc']], // Sort by Created At hidden column
        columnDefs: [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 15 } // Actions column
        ]
    });
});
</script>
@endsection