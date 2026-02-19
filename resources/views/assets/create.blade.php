@extends('layouts.app')

@section('styles')
<style>
    .form-label {
        font-weight: 600;
    }
    .form-input {
        border-radius: 0.5rem;
        border: 1px solid #cbd5e0;
        padding: 0.5rem 0.75rem;
        width: 100%;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    }
    .form-select {
        border-radius: 0.5rem;
        border: 1px solid #cbd5e0;
        padding: 0.5rem 0.75rem;
        width: 100%;
        background-color: white;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-textarea {
        border-radius: 0.5rem;
        border: 1px solid #cbd5e0;
        padding: 0.5rem 0.75rem;
        width: 100%;
        resize: vertical;
        min-height: 100px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
    }
    .btn-primary {
        background-color: #10b981;
        color: #fff;
    }
    .btn-primary:hover {
        background-color: #059669;
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4" style="max-width: 800px;">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-6">➕ Add New Asset</h2>

        <form action="{{ route('assets.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <label class="form-label" for="entity_name">Entity Name</label>
                    <input type="text" name="entity_name" id="entity_name" value="{{ old('entity_name') }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="fund_cluster">Fund Cluster</label>
                    <input type="text" name="fund_cluster" id="fund_cluster" value="{{ old('fund_cluster') }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="par_no">PAR No.</label>
                    <input type="text" name="par_no" id="par_no" value="{{ old('par_no') }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity') }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="unit">Unit</label>
                    <input type="text" name="unit" id="unit" value="{{ old('unit') }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="date_acquired">Date Acquired</label>
                    <input type="date" name="date_acquired" id="date_acquired" value="{{ old('date_acquired') }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount') }}" class="form-input" step="0.01">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="property_no">Property No.</label>
                    <input type="text" name="property_no" id="property_no" value="{{ old('property_no') }}" class="form-input">
                </div>
                
                <div class="flex flex-col">
                    <label class="form-label" for="unit_status">Unit Status <span class="text-red-500">*</span></label>
                    <select name="unit_status" id="unit_status" class="form-select" required>
                        <option value="Active" {{ old('unit_status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Under Repair" {{ old('unit_status') == 'Under Repair' ? 'selected' : '' }}>Under Repair</option>
                        <option value="Condemned" {{ old('unit_status') == 'Condemned' ? 'selected' : '' }}>Condemned</option>
                        <option value="For Replacement" {{ old('unit_status') == 'For Replacement' ? 'selected' : '' }}>For Replacement</option>
                        <option value="Not Found in the Station" {{ old('unit_status') == 'Not Found in the Station' ? 'selected' : '' }}>Not Found in the Station</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col mt-4">
                <label class="form-label" for="description">Description</label>
                <textarea name="description" id="description" class="form-textarea">{{ old('description') }}</textarea>
            </div>

            <div class="flex flex-col mt-4">
                <label class="form-label" for="purpose">Purpose</label>
                <textarea name="purpose" id="purpose" class="form-textarea">{{ old('purpose') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="flex flex-col">
                    <label class="form-label" for="approved_for_issuance">Approved for Issuance</label>
                    <input type="text" name="approved_for_issuance" id="approved_for_issuance" value="{{ old('approved_for_issuance') }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="received_from">Received From</label>
                    <input type="text" name="received_from" id="received_from" value="{{ old('received_from') }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="received_by">Received By</label>
                    <input type="text" name="received_by" id="received_by" value="{{ old('received_by') }}" class="form-input">
                </div>

                <div class="flex flex-col">
                    <label class="form-label" for="date_counted">Date Counted</label>
                    <input type="date" name="date_counted" id="date_counted" value="{{ old('date_counted') }}" class="form-input">
                </div>
            </div>

            <div class="flex justify-end mt-6 gap-2">
                <a href="{{ route('assets.index') }}" class="btn bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded shadow flex items-center gap-2">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Asset
                </button>
            </div>
        </form>
    </div>
</div>
@endsection