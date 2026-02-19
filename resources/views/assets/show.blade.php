@extends('layouts.app')

@section('styles')
<style>
    .info-label {
        font-weight: 600;
        color: #1E40AF;
    }
    .info-value {
        background-color: #F3F4F6;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        margin-top: 0.25rem;
    }
    .card {
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    /* Custom Badge Styles */
    .status-badge { padding: 0.25em 0.6em; font-size: 85%; font-weight: 700; border-radius: 0.25rem; color: #fff; text-align: center; white-space: nowrap; display: inline-block; }
    .bg-success { background-color: #198754; }
    .bg-warning { background-color: #ffc107; color: #000; }
    .bg-danger { background-color: #dc3545; }
    .bg-info { background-color: #0dcaf0; color: #000; }
    .bg-secondary { background-color: #6c757d; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4" style="max-width: 800px;">
    <div class="card">
        <h2 class="text-2xl font-bold mb-6">📄 Asset Details</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="info-label">Entity Name:</span>
                <div class="info-value">{{ $asset->entity_name }}</div>
            </div>
            <div>
                <span class="info-label">Fund Cluster:</span>
                <div class="info-value">{{ $asset->fund_cluster }}</div>
            </div>
            <div>
                <span class="info-label">PAR No.:</span>
                <div class="info-value">{{ $asset->par_no }}</div>
            </div>
            <div>
                <span class="info-label">Quantity:</span>
                <div class="info-value">{{ $asset->quantity }}</div>
            </div>
            <div>
                <span class="info-label">Unit:</span>
                <div class="info-value">{{ $asset->unit }}</div>
            </div>
            <div>
                <span class="info-label">Date Acquired:</span>
                <div class="info-value">{{ $asset->date_acquired }}</div>
            </div>
            <div>
                <span class="info-label">Amount:</span>
                <div class="info-value">{{ number_format($asset->amount, 2) }}</div>
            </div>
            <div>
                <span class="info-label">Property No.:</span>
                <div class="info-value">{{ $asset->property_no }}</div>
            </div>

            <div>
                <span class="info-label">Unit Status:</span>
                <div class="info-value">
                    @if($asset->unit_status == 'Active')
                        <span class="status-badge bg-success">Active</span>
                    @elseif($asset->unit_status == 'Under Repair')
                        <span class="status-badge bg-warning">Under Repair</span>
                    @elseif($asset->unit_status == 'Condemned' || $asset->unit_status == 'Not Found in the Station')
                        <span class="status-badge bg-danger">{{ $asset->unit_status }}</span>
                    @elseif($asset->unit_status == 'For Replacement')
                        <span class="status-badge bg-info">For Replacement</span>
                    @else
                        <span class="status-badge bg-secondary">{{ $asset->unit_status ?? 'N/A' }}</span>
                    @endif
                </div>
            </div>

            <div class="col-span-1 md:col-span-2">
                <span class="info-label">Description:</span>
                <div class="info-value">{{ $asset->description }}</div>
            </div>
            <div class="col-span-1 md:col-span-2">
                <span class="info-label">Purpose:</span>
                <div class="info-value">{{ $asset->purpose }}</div>
            </div>
            <div>
                <span class="info-label">Approved for Issuance:</span>
                <div class="info-value">{{ $asset->approved_for_issuance }}</div>
            </div>
            <div>
                <span class="info-label">Received From:</span>
                <div class="info-value">{{ $asset->received_from }}</div>
            </div>
            <div>
                <span class="info-label">Received By:</span>
                <div class="info-value">{{ $asset->received_by }}</div>
            </div>
            <div>
                <span class="info-label">Date Counted:</span>
                <div class="info-value">{{ $asset->date_counted }}</div>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-8 border-t pt-6">
            <a href="{{ route('assets.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded shadow transition duration-200">
                Back to List
            </a>
            <a href="{{ route('assets.edit', $asset->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded shadow transition duration-200">
                Edit Asset
            </a>
        </div>
    </div>
</div>
@endsection