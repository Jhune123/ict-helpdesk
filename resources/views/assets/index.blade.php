@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    /* Force full-screen width */
    .container, .container-fluid { 
        max-width: 100% !important; 
        width: 100% !important; 
        padding-left: 20px; 
        padding-right: 20px; 
    }

    /* Tighten table spacing and font size */
    table.dataTable { font-size: 12px !important; width: 100% !important; }
    table.dataTable thead th { 
        background-color: #1E40AF; 
        color: #fff; 
        padding: 8px 4px !important; /* Reduced padding */
        white-space: nowrap;
    }
    table.dataTable tbody td { 
        padding: 4px 4px !important; /* Tightened padding */
        vertical-align: middle;
    }

    table.dataTable tbody tr:hover { background-color: #E0F2FE; }
    .high-value { color: #DC2626; font-weight: bold; }
    .recent-asset { background-color: #DCFCE7 !important; }
    
    /* Action Button Styles - Compact */
    .btn-view { background-color: #3b82f6; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-decoration: none; }
    .btn-edit { background-color: #10b981; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-decoration: none; }
    .btn-delete { background-color: #ef4444; color: white; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; border: none; cursor: pointer; }
    
    /* Status Badges */
    .status-badge { padding: 2px 5px; font-size: 10px; font-weight: 700; border-radius: 4px; color: #fff; display: inline-block; }
    .bg-success { background-color: #198754; }
    .bg-warning { background-color: #ffc107; color: #000; }
    .bg-danger { background-color: #dc3545; }
    .bg-info { background-color: #0dcaf0; color: #000; }
    .bg-secondary { background-color: #6c757d; }

    .table-responsive { width: 100%; overflow-x: auto; border-radius: 8px; }
</style>
@endsection

@section('content')
<div class="w-full px-2">
    <div class="flex justify-between mb-4 items-center">
        <h1 class="text-xl font-bold">💼 ICTO Assets & Equipment</h1>

        <div class="flex gap-2">
            @role('admin|it_staff')
            <a href="{{ route('assets.create') }}" 
               class="bg-green-500 hover:bg-green-600 text-white font-semibold py-1.5 px-3 rounded shadow text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Asset
            </a>
            @endrole

            <a href="{{ route('assets.export.pdf') }}" target="_blank"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-1.5 px-3 rounded shadow text-sm flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10l-4 4m0 0l-4-4m4 4V4m0 12h8" />
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center py-2 mb-4">{{ session('success') }}</div>
    @endif

    <div class="table-responsive bg-white p-2 shadow-sm">
        <table id="assetsTable" class="display nowrap table table-striped table-hover">
            <thead>
                <tr>
                    <th>Entity Name</th>
                    <th>Fund Cluster</th>
                    <th>PAR No.</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Description</th>
                    <th>Property No.</th>
                    <th>Status</th> 
                    <th>Acquired</th>
                    <th>Amount</th>
                    <th>Purpose</th>
                    <th>Issuance</th>
                    <th>From</th>
                    <th>Received By</th>
                    <th>Counted</th>
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
                            $badgeClass = match($asset->unit_status) {
                                'Active' => 'bg-success',
                                'Under Repair' => 'bg-warning',
                                'Condemned', 'Not Found in the Station' => 'bg-danger',
                                'For Replacement' => 'bg-info',
                                default => 'bg-secondary'
                            };
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
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline" onsubmit="return confirm('Delete?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">Del</button>
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

<script>
$(document).ready(function() {
    $('#assetsTable').DataTable({
        responsive: false, // Turned off to allow full width expansion without hiding columns
        scrollX: true,
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'print'],
        order: [[16, 'desc']], 
        autoWidth: false
    });
});
</script>
@endsection