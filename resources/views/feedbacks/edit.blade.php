@extends('layouts.app')

@section('content')
<div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                ✏️ Edit Client Feedback (Ticket #{{ $feedback->ticket->ticket_id ?? $feedback->ticket_id }})
            </h2>
            <a href="{{ route('feedbacks.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Back to List
            </a>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('feedbacks.update', $feedback->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">👤 Client Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Client Name (Optional)</label>
                        <input type="text" name="client_name" value="{{ old('client_name', $feedback->client_name) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Office/College Visited</label>
                        <input type="text" name="office_visited" value="{{ old('office_visited', $feedback->office_visited) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Services Received</label>
                        <input type="text" name="services_received" value="{{ old('services_received', $feedback->services_received) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Staff / Frontliner Assisted</label>
                        <input type="text" name="staff_assisted" value="{{ old('staff_assisted', $feedback->staff_assisted) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Other Staff Involved (Optional)</label>
                        <input type="text" name="other_staff" value="{{ old('other_staff', $feedback->other_staff) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Client Type</label>
                        <select name="client_type" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="Citizen" {{ old('client_type', $feedback->client_type) == 'Citizen' ? 'selected' : '' }}>Citizen</option>
                            <option value="Business" {{ old('client_type', $feedback->client_type) == 'Business' ? 'selected' : '' }}>Business</option>
                            <option value="Government (Employee or another agency)" {{ old('client_type', $feedback->client_type) == 'Government (Employee or another agency)' ? 'selected' : '' }}>Government (Employee/Agency)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Agency Name (If Government)</label>
                        <input type="text" name="agency_name" value="{{ old('agency_name', $feedback->agency_name) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sex</label>
                            <select name="sex" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="Male" {{ old('sex', $feedback->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('sex', $feedback->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Age</label>
                            <input type="number" name="age" value="{{ old('age', $feedback->age) }}" required min="1" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">📜 Citizen's Charter (CC) Questions</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CC1. Awareness of Citizen's Charter</label>
                        <select name="cc1" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="1" {{ old('cc1', $feedback->cc1) == 1 ? 'selected' : '' }}>1. I know what a CC is and I saw this office's CC.</option>
                            <option value="2" {{ old('cc1', $feedback->cc1) == 2 ? 'selected' : '' }}>2. I know what a CC is but I did NOT see this office's CC.</option>
                            <option value="3" {{ old('cc1', $feedback->cc1) == 3 ? 'selected' : '' }}>3. I learned of the CC only when I saw this office's CC.</option>
                            <option value="4" {{ old('cc1', $feedback->cc1) == 4 ? 'selected' : '' }}>4. I do not know what a CC is and I did not see one in this office.</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CC2. Visibility of Citizen's Charter</label>
                        <select name="cc2" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="1" {{ old('cc2', $feedback->cc2) == 1 ? 'selected' : '' }}>1. Easy to see</option>
                            <option value="2" {{ old('cc2', $feedback->cc2) == 2 ? 'selected' : '' }}>2. Somewhat easy to see</option>
                            <option value="3" {{ old('cc2', $feedback->cc2) == 3 ? 'selected' : '' }}>3. Difficult to see</option>
                            <option value="4" {{ old('cc2', $feedback->cc2) == 4 ? 'selected' : '' }}>4. Not visible at all</option>
                            <option value="5" {{ old('cc2', $feedback->cc2) == 5 ? 'selected' : '' }}>5. N/A</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CC3. Usefulness of Citizen's Charter</label>
                        <select name="cc3" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="1" {{ old('cc3', $feedback->cc3) == 1 ? 'selected' : '' }}>1. Helped very much</option>
                            <option value="2" {{ old('cc3', $feedback->cc3) == 2 ? 'selected' : '' }}>2. Somewhat helped</option>
                            <option value="3" {{ old('cc3', $feedback->cc3) == 3 ? 'selected' : '' }}>3. Did not help</option>
                            <option value="4" {{ old('cc3', $feedback->cc3) == 4 ? 'selected' : '' }}>4. N/A</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-4">📊 Service Quality Dimensions (Scores 1-5, or 0 for N/A)</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 text-sm text-left">
                        <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-4 py-3 border">Dimension</th>
                                <th class="px-4 py-3 border text-center w-36">Rating (0 - 5)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach([
                                'sqd0' => 'SQD0. General Satisfaction with the availed service.',
                                'sqd1' => 'SQD1. Responsiveness (Reasonable amount of time spent).',
                                'sqd2' => 'SQD2. Reliability (Followed requirements/steps correctly).',
                                'sqd3' => 'SQD3. Access and Facilities (Steps were easy and simple).',
                                'sqd4' => 'SQD4. Communication (Easily found clear information).',
                                'sqd5' => 'SQD5. Costs (Reasonable fees or explicitly free).',
                                'sqd6' => 'SQD6. Integrity (Fair treatment, "walang palakasan").',
                                'sqd7' => 'SQD7. Assurance (Courteous, polite, and helpful staff).',
                                'sqd8' => 'SQD8. Outcome (Got what was requested or explained clearly).'
                            ] as $field => $label)
                                <tr>
                                    <td class="px-4 py-3 border font-medium text-gray-800">{{ $label }}</td>
                                    <td class="px-4 py-3 border text-center">
                                        <select name="{{ $field }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="5" {{ old($field, $feedback->$field) == 5 ? 'selected' : '' }}>5 - Strongly Agree</option>
                                            <option value="4" {{ old($field, $feedback->$field) == 4 ? 'selected' : '' }}>4 - Agree</option>
                                            <option value="3" {{ old($field, $feedback->$field) == 3 ? 'selected' : '' }}>3 - Neither</option>
                                            <option value="2" {{ old($field, $feedback->$field) == 2 ? 'selected' : '' }}>2 - Disagree</option>
                                            <option value="1" {{ old($field, $feedback->$field) == 1 ? 'selected' : '' }}>1 - Strongly Disagree</option>
                                            <option value="0" {{ old($field, $feedback->$field) == 0 ? 'selected' : '' }}>0 - N/A (Not Applicable)</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pt-2">
                <label class="block text-sm font-medium text-gray-700">💡 Suggestions on how we can further improve our services (Optional)</label>
                <textarea name="suggestions" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('suggestions', $feedback->suggestions) }}</textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('feedbacks.index') }}" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition shadow-sm">
                    💾 Save Changes
                </button>
            </div>
        </form>

    </div>
</div>
@endsection