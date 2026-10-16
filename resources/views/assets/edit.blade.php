@extends('layouts.app')

@section('styles')
<style>
    .form-label { font-weight: 600; margin-bottom: 0.25rem; }
    .form-input, .form-select, .form-textarea {
        border-radius: 0.5rem;
        border: 1px solid #cbd5e0;
        padding: 0.5rem 0.75rem;
        width: 100%;
        transition: all 0.2s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    }
    .form-textarea { min-height: 80px; resize: vertical; }
    .btn-primary { background-color: #10b981; color: #fff; border: none; padding: 0.5rem 1.25rem; border-radius: 0.5rem; cursor: pointer; font-weight: 600; transition: background 0.2s; }
    .btn-primary:hover { background-color: #059669; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6" style="max-width: 900px;">
    <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit Asset</h2>
            <a href="{{ route('assets.index') }}" class="text-blue-600 hover:underline text-sm font-medium">← Back to List</a>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                <p class="font-bold">Please correct the following errors:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('assets.update', $asset) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="flex flex-col">
                    <label class="form-label" for="entity_name">Entity Name</label>
                    <input type="text" name="entity_name" id="entity_name" value="{{ old('entity_name', $asset->entity_name) }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="fund_cluster">Fund Cluster</label>
                    <input type="text" name="fund_cluster" id="fund_cluster" value="{{ old('fund_cluster', $asset->fund_cluster) }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="par_no">PAR No.</label>
                    <input type="text" name="par_no" id="par_no" value="{{ old('par_no', $asset->par_no) }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $asset->quantity) }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="unit">Unit</label>
                    <input type="text" name="unit" id="unit" value="{{ old('unit', $asset->unit) }}" class="form-input" placeholder="e.g. pc, unit, set">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="property_no">Property No.</label>
                    <input type="text" name="property_no" id="property_no" value="{{ old('property_no', $asset->property_no) }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="date_acquired">Date Acquired</label>
                    <input type="date" name="date_acquired" id="date_acquired" value="{{ old('date_acquired', $asset->date_acquired?->format('Y-m-d')) }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount', $asset->amount) }}" class="form-input" step="0.01">
                </div>

                <div class="flex flex-col">
                    <label class="form-label text-blue-700" for="unit_status">Unit Status <span class="text-red-500">*</span></label>
                    <select name="unit_status" id="unit_status" class="form-select border-blue-200" required>
                        <option value="">-- Select Status --</option>
                        @foreach(['Active', 'Under Repair', 'Condemned', 'For Replacement', 'Not Found in the Station'] as $status)
                            <option value="{{ $status }}" {{ old('unit_status', $asset->unit_status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <label class="form-label" for="description">Description / Item Details</label>
                <textarea name="description" id="description" class="form-textarea">{{ old('description', $asset->description) }}</textarea>
            </div>

            <div class="mt-4">
                <label class="form-label" for="purpose">Purpose</label>
                <textarea name="purpose" id="purpose" class="form-textarea">{{ old('purpose', $asset->purpose) }}</textarea>
            </div>

            <hr class="my-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="form-label" for="approved_for_issuance">Approved for Issuance</label>
                    <input type="text" name="approved_for_issuance" id="approved_for_issuance" value="{{ old('approved_for_issuance', $asset->approved_for_issuance) }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="received_from">Received From</label>
                    <input type="text" name="received_from" id="received_from" value="{{ old('received_from', $asset->received_from) }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="received_by">Received By</label>
                    <input type="text" name="received_by" id="received_by" value="{{ old('received_by', $asset->received_by) }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="date_counted">Date Counted</label>
                    <input type="date" name="date_counted" id="date_counted" value="{{ old('date_counted', $asset->date_counted?->format('Y-m-d')) }}" class="form-input">
                </div>
            </div>

            <div class="mt-4">
                <label class="form-label" for="po_no">PO. NO.:</label>
                <textarea name="po_no" id="po_no" class="form-textarea">{{ old('po_no', $asset->po_no) }}</textarea>
            </div>

            <div class="flex justify-end mt-10 gap-3">
                <a href="{{ route('assets.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 px-6 rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="btn-primary shadow-lg shadow-emerald-100 flex items-center gap-2">
                    Save Asset Record
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
