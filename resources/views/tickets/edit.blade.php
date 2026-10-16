@extends('layouts.app')

@section('content')
@php
    $formData = is_array($ticket->form_data) ? $ticket->form_data : [];
    $formType = strtolower($formData['form_type'] ?? '');
    $formType = $formType ?: match ($formData['original_form_type'] ?? null) {
        'KSU-ICTO-QF-01' => 'equipment_repair',
        'KSU-ICTO-QF-02' => 'information_system_request',
        'KSU-ICTO-QF-03' => 'multimedia_request',
        'KSU-ICTO-QF-04' => 'network_request',
        'KSU-ICTO-QF-06' => 'incident_report',
        'KSU-ICTO-QF-09' => 'equipment_borrower',
        default => '',
    };
    $formFields = [
        'equipment_repair' => [
            'full_name' => 'User’s Full Name',
            'date_requested' => 'Date Requested',
            'office_address' => 'Office Name & Address',
            'email_address' => 'Email Address',
            'property_no' => 'Property No.',
            'acquisition_date' => 'Acquisition Date',
            'acquisition_cost' => 'Acquisition Cost',
            'last_repair_date' => 'Date of Last Repair',
            'last_repair_nature' => 'Nature of Last Repair',
        ],
        'information_system_request' => [
            'date_requested' => 'Date Requested',
            'full_name' => 'User’s Full Name',
            'office_address' => 'Office Name & Address',
            'email_address' => 'Email Address',
            'request_type' => 'Type of Request/Purpose',
            'other_request_type' => 'Other Request Type',
            'requested_start_date' => 'Requested Start Date',
            'requested_completion_date' => 'Requested Completion Date',
        ],
        'multimedia_request' => [
            'date_requested' => 'Date Requested',
            'full_name' => 'User’s Full Name',
            'office_address' => 'Office Name & Address',
            'email_address' => 'Email Address',
            'request_type' => 'Type of Request/Purpose',
            'other_request_type' => 'Other Request Type',
            'location' => 'Location',
            'date_time' => 'Date and Time',
            'duration' => 'Duration',
            'requested_start_date' => 'Requested Start Date',
            'requested_completion_date' => 'Requested Completion Date',
            'multimedia_request_details' => 'Request Details',
        ],
        'network_request' => [
            'date_requested' => 'Date Requested',
            'full_name' => 'User’s Full Name',
            'office_address' => 'Office Name & Address',
            'email_address' => 'Email Address',
            'request_type' => 'Type of Request/Purpose',
            'request_type_other' => 'Other Request Type',
            'location' => 'Location',
            'device_type' => 'Device',
            'device_other' => 'Other Device',
            'mac_address' => 'MAC Address',
            'requested_start_date' => 'Requested Start Date',
            'requested_completion_date' => 'Requested Completion Date',
        ],
        'incident_report' => [
            'full_name' => 'User’s Full Name',
            'office_name_position' => 'User’s Office Name & Position',
            'email_address' => 'Email Address',
            'incident_date' => 'Date of Incident',
            'incident_time' => 'Time of Incident',
            'location' => 'Location of Incident',
            'equipment_type' => 'Equipment Name/Type',
            'equipment_qty' => 'Equipment Quantity',
            'equipment_serial' => 'Equipment Serial Number',
            'equipment_remarks' => 'Equipment Remarks',
            'actions_taken' => 'Actions Taken',
            'client_signature_acknowledged' => 'Prepared By Digital Signature Acknowledged',
            'employee_name_signature' => 'Prepared By Employee Name',
            'supervisor_name' => 'Supervisor Name',
        ],
        'equipment_borrower' => [
            'full_name' => 'Full Name',
            'office_name' => 'Office Name',
            'email_address' => 'Email Address',
            'equipment_type' => 'Equipment Name/Type',
            'quantity' => 'Quantity',
            'serial_no' => 'Serial Number',
            'date_borrowed' => 'Date Borrowed',
            'expected_return_date' => 'Expected Return Date',
            'agreed_to_terms' => 'Agreed to Terms and Conditions',
        ],
        'equipment_borrow' => [
            'full_name' => 'Full Name',
            'office_name' => 'Office Name',
            'email_address' => 'Email Address',
            'equipment_type' => 'Equipment Name/Type',
            'quantity' => 'Quantity',
            'serial_no' => 'Serial Number',
            'date_borrowed' => 'Date Borrowed',
            'expected_return_date' => 'Expected Return Date',
            'agreed_to_terms' => 'Agreed to Terms and Conditions',
        ],
        'generic' => [
            'equipment_type' => 'Equipment Type',
            'brand_model' => 'Brand / Model',
            'serial_no' => 'Serial Number',
        ],
    ];
    $fieldLabels = $formFields[$formType] ?? [];
    foreach ($formData as $key => $value) {
        if (!in_array($key, ['form_type', 'original_form_type'], true) && !array_key_exists($key, $fieldLabels)) {
            $fieldLabels[$key] = \Illuminate\Support\Str::headline(str_replace('_', ' ', $key));
        }
    }
    $checkboxOptions = [
        'information_system_request.request_type' => ['Account Management', 'Bug Report', 'System Installation/Repair', 'Technical Support/Assistance', 'Others'],
        'multimedia_request.request_type' => ['Photography/Documentation', 'Videography', 'Graphic Design', 'Audio Recording', 'Live Streaming', 'Technical Support/Assistance', 'Others'],
        'network_request.request_type' => ['Network Access', 'Network Troubleshooting', 'VPN Access', 'Wireless Network Access', 'Technical Support/Assistance'],
        'network_request.device_type' => ['System Unit', 'Laptop', 'Mobile Device'],
    ];
    $formTitle = \Illuminate\Support\Str::headline(str_replace('_', ' ', $formType ?: 'general'));
@endphp

<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 my-8 border border-gray-200">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800">Edit {{ $formTitle }} Ticket #{{ $ticket->id }}</h2>
        <a href="{{ route('tickets.index') }}" class="text-gray-500 hover:text-gray-700">Cancel</a>
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

    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="form_type" value="{{ $formData['form_type'] ?? '' }}">
        <input type="hidden" name="form_data[form_type]" value="{{ $formData['form_type'] ?? '' }}">

        <section class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Ticket Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Title</label>
                    <input type="text" name="title" value="{{ old('title', $ticket->title) }}" class="w-full border border-gray-300 rounded p-2" required>
                </div>
                @if($formType !== 'multimedia_request')
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">{{ $formType === 'equipment_borrower' ? 'Purpose / Request Description' : 'Description / Remarks' }}</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 rounded p-2" required>{{ old('description', $ticket->description) }}</textarea>
                    </div>
                @else
                    <input type="hidden" name="description" value="{{ old('description', $ticket->description) }}">
                @endif
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Client / Requestor Name</label>
                    <input type="text" name="client_name" value="{{ old('client_name', $ticket->client_name) }}" class="w-full border border-gray-300 rounded p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $ticket->getRawOriginal('contact_number')) }}" class="w-full border border-gray-300 rounded p-2">
                </div>
                @if($formType === 'equipment_repair' || !$formType)
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">Equipment Type</label>
                        <input type="text" name="equipment_type" value="{{ old('equipment_type', $ticket->getRawOriginal('equipment_type')) }}" class="w-full border border-gray-300 rounded p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">Brand & Model</label>
                        <input type="text" name="brand_model" value="{{ old('brand_model', $ticket->getRawOriginal('brand_model')) }}" class="w-full border border-gray-300 rounded p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-600">Serial Number</label>
                        <input type="text" name="serial_no" value="{{ old('serial_no', $ticket->getRawOriginal('serial_no')) }}" class="w-full border border-gray-300 rounded p-2">
                    </div>
                @endif
            </div>
        </section>

        @if($fieldLabels)
            <section class="mb-6 bg-gray-50 p-5 rounded border">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Submitted {{ $formTitle }} Form Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($fieldLabels as $key => $label)
                        @php
                            $value = old("form_data.$key", $formData[$key] ?? '');
                            $options = $checkboxOptions["{$formType}.{$key}"] ?? null;
                        @endphp
                        <div class="{{ in_array($key, ['actions_taken', 'multimedia_request_details']) ? 'md:col-span-2' : '' }}">
                            <label class="block text-sm font-semibold text-gray-600 mb-1">{{ $label }}</label>
                            @if($options)
                                @php($selectedValues = is_array($value) ? $value : (filled($value) ? explode(',', $value) : []))
                                <input type="hidden" name="form_data[{{ $key }}][]" value="">
                                @foreach($options as $option)
                                    <label class="inline-flex items-center mr-4 mb-2 text-sm">
                                        <input type="checkbox" name="form_data[{{ $key }}][]" value="{{ $option }}" class="mr-2" {{ in_array($option, $selectedValues) ? 'checked' : '' }}>
                                        {{ $option }}
                                    </label>
                                @endforeach
                            @elseif(is_array($value))
                                <textarea name="form_data[{{ $key }}]" rows="2" class="w-full border border-gray-300 rounded p-2">{{ implode(', ', $value) }}</textarea>
                            @elseif(in_array($key, ['actions_taken', 'multimedia_request_details']))
                                <textarea name="form_data[{{ $key }}]" rows="3" class="w-full border border-gray-300 rounded p-2">{{ $value }}</textarea>
                            @elseif($key === 'client_signature_acknowledged' || $key === 'agreed_to_terms')
                                <input type="hidden" name="form_data[{{ $key }}]" value="">
                                <label class="inline-flex items-center text-sm">
                                    <input type="checkbox" name="form_data[{{ $key }}]" value="Yes" class="mr-2" {{ $value === 'Yes' ? 'checked' : '' }}>
                                    Yes
                                </label>
                            @else
                                <input type="{{ str_ends_with($key, '_date') || in_array($key, ['date_requested', 'date_borrowed', 'incident_date', 'acquisition_date']) ? 'date' : ($key === 'incident_time' ? 'time' : 'text') }}"
                                       name="form_data[{{ $key }}]" value="{{ $value }}" class="w-full border border-gray-300 rounded p-2">
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="mb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Ticket Processing</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Category</label>
                    <select name="category_id" class="w-full border border-gray-300 rounded p-2">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $ticket->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="category_manual" value="{{ old('category_manual') }}" placeholder="Or type new category" class="w-full border border-gray-300 rounded p-2 mt-2">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Department</label>
                    <select name="department" class="w-full border border-gray-300 rounded p-2">
                        <option value="">-- Select Department --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->name }}" {{ old('department', $ticket->department) == $dept->name ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Assign To</label>
                    <select name="assigned_to" class="w-full border border-gray-300 rounded p-2">
                        <option value="">-- Select IT Personnel --</option>
                        @foreach($it_personnel as $person)
                            <option value="{{ $person->id }}" {{ old('assigned_to', $ticket->assigned_to) == $person->id ? 'selected' : '' }}>{{ $person->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Priority</label>
                    <select name="priority" class="w-full border border-gray-300 rounded p-2">
                        @foreach(['Low', 'Normal', 'High', 'Urgent', 'Critical'] as $priority)
                            <option value="{{ $priority }}" {{ old('priority', $ticket->priority) == $priority ? 'selected' : '' }}>{{ $priority }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded p-2" required>
                        @foreach(['Open', 'In Progress', 'Closed', 'Finished', 'Condemned'] as $status)
                            <option value="{{ $status }}" {{ old('status', $ticket->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-600">Internal Remarks</label>
                    <textarea name="remarks" rows="3" class="w-full border border-gray-300 rounded p-2">{{ old('remarks', $ticket->remarks) }}</textarea>
                </div>
            </div>
        </section>

        <div class="flex justify-end gap-3 border-t pt-5">
            <a href="{{ route('tickets.show', $ticket) }}" class="px-5 py-2 border rounded text-gray-700">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded">Save Changes</button>
        </div>
    </form>
</div>
@endsection
