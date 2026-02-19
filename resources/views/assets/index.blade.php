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

/* 2. Strict Table Layout */
table.dataTable { 
    font-size: 11px !important; 
    width: 100% !important; 
    table-layout: fixed !important; 
}

table.dataTable thead th { 
    background-color: #1E40AF !important; 
    color: #ffffff !important; 
    padding: 10px 5px !important;
    white-space: nowrap;
}

/* ✅ DESCRIPTION COLUMN WRAPPING */
#assetsTable th:nth-child(6), 
#assetsTable td:nth-child(6) {
    width: 300px !important; 
    white-space: normal !important;
}

/* 3. Button Fallbacks (Inline Style helpers) */
.btn-solid {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 4px;
    color: white !important;
    font-weight: bold;
    text-decoration: none !important;
    font-size: 11px;
    border: none;
}
</style>
@endsection

@section('content')
<div class="w-full">
    <div class="flex justify-between mb-4 items-center px-2">
        <h1 class="text-xl font-bold text-blue-900">💼 ICTO Assets & Equipment</h1>
        
        <div class="flex gap-2">
            @role('admin|it_staff')
            <a href="{{ route('assets.create') }}" 
               class="btn-solid" style="background-color: #16a34a;">
               + Add Asset
            </a>
            @endrole

            <a href="{{ route('assets.export.pdf') }}" target="_blank" 
               class="btn-solid" style="background-color: #334155;">
               PDF Export
            </a>
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
                    <th style="width: 140px;">Actions</th>
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
                        <span style="padding: 2px 5px; border-radius: 4px; color: white; background-color: {{ $asset->unit_status == 'Active' ? '#198754' : '#6c757d' }}; font-size: 9px;">
                            {{ $asset->unit_status }}
                        </span>
                    </td>
                    <td>{{ $asset->date_acquired }}</td>
                    <td>{{ number_format($asset->amount, 2) }}</td>
                    <td>{{ $asset->approved_for_issuance }}</td>
                    <td>{{ $asset->received_from }}</td>
                    <td>{{ $asset->received_by }}</td>
                    <td>{{ $asset->date_counted }}</td>
                    <td>
                        <div class="flex gap-1">
                            <a href="{{ route('assets.show', $asset) }}" class="btn-solid" style="background-color: #3b82f6; padding: 3px 6px;">View</a>
                            
                            @role('admin|it_staff')
                            <a href="{{ route('assets.edit', $asset) }}" class="btn-solid" style="background-color: #10b981; padding: 3px 6px;">Edit</a>
                            
                            <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline" onsubmit="return confirm('Delete?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-solid" style="background-color: #ef4444; padding: 3px 6px; cursor: pointer;">Del</button>
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
        "pageLength": 20,
        "responsive": false,
        "scrollX": true,
        "autoWidth": false,
        "order": [[15, 'desc']],
        "dom": 'Bfrtip',
        "columnDefs": [
            { "width": "300px", "targets": 5 }
        ]
    });
});
</script>
@endsection