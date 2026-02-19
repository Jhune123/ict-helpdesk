@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
/* 1. Force full-screen width */
.container, .container-fluid, #app, main { 
    max-width: 100% !important; 
    width: 100% !important; 
    padding-left: 10px !important; 
    padding-right: 10px !important; 
}

/* 2. Strict Table Layout to prevent horizontal overflow */
table.dataTable { 
    font-size: 11px !important; 
    width: 100% !important; 
    table-layout: fixed !important; 
}

table.dataTable thead th { 
    background-color: #1E40AF; 
    color: #fff; 
    padding: 8px 4px !important;
    white-space: nowrap;
    text-align: left;
}

table.dataTable tbody td { 
    padding: 6px 4px !important; 
    vertical-align: top;
    word-wrap: break-word;
    overflow-wrap: break-word;
    border-bottom: 1px solid #edf2f7;
}

/* ✅ DESCRIPTION COLUMN WRAPPING */
#assetsTable th:nth-child(6), 
#assetsTable td:nth-child(6) {
    width: 300px !important; 
    white-space: normal !important;
}

/* Status Badges */
.status-badge { padding: 2px 5px; font-size: 9px; font-weight: 700; border-radius: 4px; color: #fff; display: inline-block; }
.bg-success { background-color: #198754; }
.bg-warning { background-color: #ffc107; color: #000; }
.bg-danger { background-color: #dc3545; }
.bg-info { background-color: #0dcaf0; color: #000; }
.bg-secondary { background-color: #6c757d; }

.table-responsive { width: 100%; overflow-x: auto; }
</style>
@endsection

@section('content')
<div class="w-full">
    <div class="flex justify-between mb-4 items-center px-2">
        <h1 class="text-xl font-bold text-blue-900">💼 ICTO Assets & Equipment</h1>
        <div class="flex gap-2">
            @role('admin|it_staff')
            <a href="{{ route('assets.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-1.5 px-3 rounded shadow text-xs">Add Asset</a>
            @endrole
            <a href="{{ route('assets.export.pdf') }}" target="_blank" class="bg-slate-700 hover:bg-slate-800 text-white font-semibold py-1.5 px-3 rounded shadow text-xs">Export PDF</a>
        </div>
    </div>

    <div class="table-responsive bg-white shadow-sm border border-gray-200">
        <table id="assetsTable" class="display table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 100px;">Entity</th>
                    <th style="width: 80px;">Fund</th>
                    <th style="width: 110px;">PAR No.</th>
                    <th style="width: 40px;">Qty</th>
                    <th style="width: 50px;">Unit</th>
                    <th>Description</th>
                    <th style="width: 110px;">Property No.</th>
                    <th style="width: 90px;">Status</th>
                    <th style="width: 80px;">Acquired</th>
                    <th style="width: 80px;">Amount</th>
                    <th style="width: 90px;">Issuance</th>
                    <th style="width: 90px;">From</th>
                    <th style="width: 90px;">Received By</th>
                    <th style="width: 80px;">Counted</th>
                    <th style="width: 130px;">Actions</th>
                    <th style="display:none;">Created At</th>
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
                    <td>{{ number_format($asset->amount, 2) }}</td>
                    <td>{{ $asset->approved_for_issuance }}</td>
                    <td>{{ $asset->received_from }}</td>
                    <td>{{ $asset->received_by }}</td>
                    <td>{{ $asset->date_counted }}</td>
                    <td>
                        <div class="flex gap-1">
                            <a href="{{ route('assets.show', $asset) }}" class="bg-blue-500 text-white px-2 py-1 rounded text-[10px] font-bold no-underline">View</a>
                            @role('admin|it_staff')
                            <a href="{{ route('assets.edit', $asset) }}" class="bg-emerald-500 text-white px-2 py-1 rounded text-[10px] font-bold no-underline">Edit</a>
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline" onsubmit="return confirm('Delete this asset?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-[10px] font-bold">Del</button>
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

<script>
$(document).ready(function() {
    $('#assetsTable').DataTable({
        "pageLength": 20,       // ✅ Fixed to 20 items per page
        "lengthMenu": [10, 20, 50, 100], 
        "responsive": false,
        "scrollX": true,
        "autoWidth": false,
        "order": [[15, 'desc']], // Sort by Created At hidden column
        "dom": 'Bfrtip',
        "columnDefs": [
            { "width": "300px", "targets": 5 } // Enforce Description width
        ]
    });
});
</script>
@endsection