@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-4xl space-y-5">
    <div>
        <p class="text-sm font-semibold text-green-700">Staff Services / Leave Management</p>
        <h1 class="mt-1 text-2xl font-bold text-gray-900">Edit Leave Application</h1>
    </div>

    @if($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
            <ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('staff.leaves.update', $leave) }}" class="space-y-5 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
        @csrf
        @method('PUT')

        <h2 class="font-bold text-gray-900">Applicant Details</h2>
        <div class="grid gap-4 sm:grid-cols-3">
            <label class="text-sm font-medium text-gray-700">Last Name
                <input name="applicant_last_name" type="text" required maxlength="255" value="{{ old('applicant_last_name', $leave->applicant_last_name) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
            <label class="text-sm font-medium text-gray-700">First Name
                <input name="applicant_first_name" type="text" required maxlength="255" value="{{ old('applicant_first_name', $leave->applicant_first_name) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
            <label class="text-sm font-medium text-gray-700">Middle Name
                <input name="applicant_middle_name" type="text" maxlength="255" value="{{ old('applicant_middle_name', $leave->applicant_middle_name) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
            <label class="text-sm font-medium text-gray-700">Office / Department
                <input name="office_department" type="text" maxlength="255" value="{{ old('office_department', $leave->office_department) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
            <label class="text-sm font-medium text-gray-700">Position
                <input name="position" type="text" maxlength="255" value="{{ old('position', $leave->position) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
            <label class="text-sm font-medium text-gray-700">Designation
                <input name="designation" type="text" maxlength="255" value="{{ old('designation', $leave->designation) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
            <label class="text-sm font-medium text-gray-700">Monthly Basic Salary
                <input name="monthly_basic_salary" type="number" min="0" max="9999999999.99" step="0.01" value="{{ old('monthly_basic_salary', $leave->monthly_basic_salary) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
            <label class="text-sm font-medium text-gray-700 sm:col-span-2">Applicant Signature (typed)
                <input name="applicant_signature" type="text" required maxlength="255" value="{{ old('applicant_signature', $leave->applicant_signature) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
        </div>

        <h2 class="border-t border-gray-200 pt-5 font-bold text-gray-900">Application Details</h2>
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="text-sm font-medium text-gray-700">Type of Leave
                <select name="leave_type" required class="mt-1 w-full rounded-lg border-gray-300">
                    @foreach(\App\Models\LeaveApplication::TYPES as $type)
                        <option value="{{ $type }}" @selected(old('leave_type', $leave->leave_type) === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-medium text-gray-700">Other leave type (if applicable)
                <input name="other_leave_type" type="text" maxlength="255" value="{{ old('other_leave_type', $leave->other_leave_type) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
            <label class="text-sm font-medium text-gray-700">Start Date
                <input name="start_date" type="date" required value="{{ old('start_date', $leave->start_date->toDateString()) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
            <label class="text-sm font-medium text-gray-700">End Date
                <input name="end_date" type="date" required value="{{ old('end_date', $leave->end_date->toDateString()) }}" class="mt-1 w-full rounded-lg border-gray-300">
            </label>
            <label class="text-sm font-medium text-gray-700 sm:col-span-2">6.B Details of Leave
                <textarea name="details" rows="5" maxlength="5000" class="mt-1 w-full rounded-lg border-gray-300">{{ old('details', $leave->details) }}</textarea>
            </label>
            <fieldset class="sm:col-span-2">
                <legend class="text-sm font-medium text-gray-700">6.D Commutation</legend>
                <div class="mt-2 flex gap-5">
                    <label class="flex items-center gap-2 text-sm"><input type="radio" name="commutation_requested" value="0" @checked(! old('commutation_requested', (string) (int) $leave->commutation_requested))> Not Requested</label>
                    <label class="flex items-center gap-2 text-sm"><input type="radio" name="commutation_requested" value="1" @checked(old('commutation_requested', (string) (int) $leave->commutation_requested) === '1')> Requested</label>
                </div>
            </fieldset>
        </div>

        <div class="flex flex-wrap justify-end gap-3 border-t border-gray-200 pt-4">
            <a href="{{ route('staff.leaves.show', $leave) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-bold text-gray-700">Cancel</a>
            <button type="submit" class="rounded-lg bg-green-700 px-5 py-2 text-sm font-bold text-white hover:bg-green-800">Save Changes</button>
        </div>
    </form>
</div>
@endsection
