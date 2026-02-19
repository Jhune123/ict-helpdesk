@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
/* 1. Force full-screen width & override layout constraints */
.container, .container-fluid, #app, main { 
    max-width: 100% !important; 
    width: 100% !important; 
    padding-left: 10px !important; 
    padding-right: 10px !important; 
}

/* 2. Tighten table font and structure */
table.dataTable { 
    font-size: 11.5px !important; 
    width: 100% !important; 
    table-layout: fixed !important;
}

table.dataTable thead th { 
    background-color: #1E40AF; 
    color: #fff; 
    padding: 6px 4px !important;
    white-space: nowrap;
    text-transform: uppercase;
    font-size: 10.5px;
}

table.dataTable tbody td { 
    padding: 4px !important; 
    vertical-align: top;
    border-bottom: 1px solid #e2e8f0;
}

/* ✅ FIXED DESCRIPTION WRAPPING */
#assetsTable th:nth-child(6), 
#assetsTable td:nth-child(6) {
    width: 250px !important;
    max-width: 250px !important;

    white-space: normal !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
    word-break: break-word !important;

    line-height: 1.4;
}

/* Ensure inner content wraps */
.desc-wrap {
    display: block;
    width: 100%;
    white-space: normal !important;
    word-break: break-word;
    overflow-wrap: break-word;
}

/* Buttons */
.btn-view { background-color: #3b82f6; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600; text-decoration: none; }
.btn-edit { background-color: #10b981; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600; text-decoration: none; }
.btn-delete { background-color: #ef4444; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600; border: none; cursor: pointer; }

.status-badge { padding: 2px 4px; font-size: 9px; font-weight: 700; border-radius: 4px; color: #fff; display: inline-block; white-space: nowrap; }
.bg-success { background-color: #198754; }
.bg-warning { background-color: #ffc107; color: #000; }
.bg-danger { background-color: #dc3545; }
.bg-info { background-color: #0dcaf0; color: #000; }
.bg-secondary { background-color: #6c757d; }

.high-value { color: #DC2626; font-weight: bold; }
.recent-asset { background-color: #f0fff4 !important; }
.table-responsive { width: 100%; overflow-x: auto; }
</style>
@endsection

@section('content')
<div class="w-full">
    <div class="flex justify-between mb-4 items-center px-2">
        <h1 class="text-xl font-bold text-blue-900">💼 ICTO Assets & Equipment</h1>

        <div class="flex gap-2">
            @role('admin|it_staff')
            <a href="{{ route('assets.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white font-semibold py-1 px-3 rounded shadow text-xs">
                Add Asset
            </a>
            @endrole

            <a href="{{ route('assets.export.pdf') }}" target="_blank"
               class="bg-slate-700 hover:bg-slate-800 text-white font-semibold py-1 px-3 rounded shadow text-xs">
                Export PDF (Long)
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success text-center py-2 mb-4 text-sm">{{ session('success') }}</div>
    @endif

    <div class="table-responsive bg-white shadow-sm border border-gray-200">
        <table id="assetsTable" class="display table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width: 100px;">Entity Name</th>
                    <th style="width: 80px;">Fund</th>
                    <th style="width: 120px;">PAR No.</th>
                    <th style="width: 40px;">Qty</th>
                    <th style="width: 50px;">Unit</th>
                    <th>Description</th>
                    <th style="width: 100px;">Property No.</th>
                    <th style="width: 80px;">Status</th>
                    <th style="width: 80px;">Acquired</th>
                    <th style="width: 80px;">Amount</th>
                    <th style="width: 100px;">Purpose</th>
                    <th style="width: 80px;">Issuance</th>
                    <th style="width: 80px;">From</th>
                    <th style="width: 80px;">By</th>
                    <th style="width: 80px;">Counted</th>
                    <th style="width: 110px;">Actions</th>
                    <th style="display:none;">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                <tr class="{{ (isset($asset->created_at) && $asset->created_at->gt(now()->subDays(7))) ? 'recent-asset' : '' }}">
                    <td>{{ $asset->entity_name }}</td>
                    <td>{{ $asset->fund_cluster }}</td>
                    <td>{{ $asset->par_no }}</td>
                    <td>{{ $asset->quantity }}</td>
                    <td>{{ $asset->unit }}</td>

                    <!-- ✅ WRAPPED DESCRIPTION -->
                    <td>
                        <div class="desc-wrap">
                            {{ $asset->description }}
                        </div>
                    </td>

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
                    <td class="{{ $asset->amount > 100000 ? 'high-value' : '' }}">{{ number_format($asset->amount, 2) }}</td>
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

<script>
$(document).ready(function() {
    $('#assetsTable').DataTable({
        responsive: false,
        scrollX: true,
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'print'],
        order: [[16, 'desc']],
        autoWidth: false,
        columnDefs: [
            { targets: 5, width: "250px" }
        ]
    });
});
</script>
@endsection
