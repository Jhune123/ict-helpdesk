<form action="{{ $action }}" method="POST">
    @csrf
    @if(isset($method)) @method($method) @endif

    <div class="card p-4">

        <div class="row">

            <div class="col-md-6 mb-3">
                <label>Entity Name</label>
                <input type="text" name="entity_name" class="form-control"
                       value="{{ old('entity_name', $asset->entity_name ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Fund Cluster</label>
                <input type="text" name="fund_cluster" class="form-control"
                       value="{{ old('fund_cluster', $asset->fund_cluster ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>PAR No.</label>
                <input type="text" name="par_no" class="form-control"
                       value="{{ old('par_no', $asset->par_no ?? '') }}">
            </div>

            <div class="col-md-3 mb-3">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control"
                       value="{{ old('quantity', $asset->quantity ?? '') }}">
            </div>

            <div class="col-md-3 mb-3">
                <label>Unit</label>
                <input type="text" name="unit" class="form-control"
                       value="{{ old('unit', $asset->unit ?? '') }}">
            </div>

            <div class="col-md-12 mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $asset->description ?? '') }}</textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label>Property No.</label>
                <input type="text" name="property_no" class="form-control"
                       value="{{ old('property_no', $asset->property_no ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Date Acquired</label>
                <input type="date" name="date_acquired" class="form-control"
                       value="{{ old('date_acquired', $asset->date_acquired ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" class="form-control"
                       value="{{ old('amount', $asset->amount ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Purpose</label>
                <input type="text" name="purpose" class="form-control"
                       value="{{ old('purpose', $asset->purpose ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Approved for Issuance</label>
                <input type="text" name="approved_for_issuance" class="form-control"
                       value="{{ old('approved_for_issuance', $asset->approved_for_issuance ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Received From</label>
                <input type="text" name="received_from" class="form-control"
                       value="{{ old('received_from', $asset->received_from ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Received By</label>
                <input type="text" name="received_by" class="form-control"
                       value="{{ old('received_by', $asset->received_by ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label>Date Counted</label>
                <input type="date" name="date_counted" class="form-control"
                       value="{{ old('date_counted', $asset->date_counted ?? '') }}">
            </div>

        </div>

        <button class="btn btn-success mt-3">Save</button>
        <a href="{{ route('assets.index') }}" class="btn btn-secondary mt-3">Cancel</a>

    </div>
</form>
