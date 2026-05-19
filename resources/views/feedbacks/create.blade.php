@extends('layouts.app')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="bg-white shadow-lg rounded-lg p-8 border-t-4 border-blue-600">
        
        <div class="text-center mb-6 border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-800 uppercase">Client Satisfaction Survey Form</h2>
            <p class="text-sm text-gray-600 mt-2">This Client Satisfaction Measurement (CSM) tracks the customer experience of government offices. Your feedback on your recently concluded transaction will help this office provide a better service.</p>
        </div>

        <form action="{{ route('feedbacks.store', $ticket->id) }}" method="POST">
            @csrf

           {{-- 1. Client Info --}}
            <div class="mb-8 p-6 bg-gray-50 rounded border border-gray-200 shadow-sm">
                <h3 class="font-bold text-xl mb-4 text-gray-800 border-b pb-2">I. Client Information</h3>
                
                {{-- NEW FIELDS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name (Optional):</label>
                        <input type="text" name="client_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Office/College Visited:</label>
                        <input type="text" name="office_visited" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service/s received:</label>
                    <input type="text" name="services_received" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name of staff / frontliner who assisted you:</label>
                        <input type="text" name="staff_assisted" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Other staff involved (if any):</label>
                        <input type="text" name="other_staff" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                {{-- EXISTING CLIENT TYPE & DEMOGRAPHICS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-gray-200">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Client type:</label>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <label class="flex items-center"><input type="radio" name="client_type" value="Citizen" required class="mr-2 text-blue-600 focus:ring-blue-500"> Citizen</label>
                            <label class="flex items-center"><input type="radio" name="client_type" value="Business" class="mr-2 text-blue-600 focus:ring-blue-500"> Business</label>
                            <label class="flex items-center"><input type="radio" name="client_type" value="Government" class="mr-2 text-blue-600 focus:ring-blue-500"> Government (Employee or another agency)</label>
                        </div>
                        <div class="mt-3">
                            <label class="block text-xs text-gray-500 mb-1">If Government – External, please specify name of agency:</label>
                            <input type="text" name="agency_name" class="block w-full text-sm rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="bg-white p-3 rounded border shadow-inner">
                        <div class="mb-3">
                            <label class="block text-sm font-bold text-gray-800 mb-1">Sex:</label>
                            <div class="flex gap-4 text-sm">
                                <label class="flex items-center"><input type="radio" name="sex" value="Male" required class="mr-2 text-blue-600 focus:ring-blue-500"> Male</label>
                                <label class="flex items-center"><input type="radio" name="sex" value="Female" class="mr-2 text-blue-600 focus:ring-blue-500"> Female</label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-1">Age:</label>
                            <input type="number" name="age" required min="1" class="block w-24 text-sm rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Citizen's Charter --}}
            <div class="mb-8">
                <h3 class="font-bold text-lg mb-2">INSTRUCTIONS: Checkmark your answer to the Citizen’s Charter (CC) questions.</h3> <p class="text-sm text-gray-600 mt-2">The Citizen’s Charter is an official document that reflects the services of a government agency/office including its requirements, fees, and processing times among others.</p>
                
                <div class="mb-4">
                    <p class="font-semibold text-gray-800">CC1: Which of the following best describes your awareness of a CC?</p>
                    <div class="ml-4 space-y-1 mt-2 text-sm">
                        <label class="block"><input type="radio" name="cc1" value="1" required> 1. I know what a CC is and I saw this office’s CC.</label>
                        <label class="block"><input type="radio" name="cc1" value="2"> 2. I know what a CC is but I did NOT see this office’s CC.</label>
                        <label class="block"><input type="radio" name="cc1" value="3"> 3. I learned of the CC only when I saw this office’s CC.</label>
                        <label class="block"><input type="radio" name="cc1" value="4"> 4. I do not know what a CC is and I did not see one in this office.</label>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="font-semibold text-gray-800">CC2: If aware of CC (answered 1-3 in CC1), would you say that the CC of this office was …?</p>
                    <div class="ml-4 space-y-1 mt-2 text-sm grid grid-cols-2 gap-2">
                        <label><input type="radio" name="cc2" value="1" required> 1. Easy to see</label>
                        <label><input type="radio" name="cc2" value="4"> 4. Not visible at all</label>
                        <label><input type="radio" name="cc2" value="2"> 2. Somewhat easy to see</label>
                        <label><input type="radio" name="cc2" value="5"> 5. N/A</label>
                        <label><input type="radio" name="cc2" value="3"> 3. Difficult to see</label>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="font-semibold text-gray-800">CC3: If aware of CC (answered codes 1-3 in CC1), how much did the CC help you in your transaction?</p>
                    <div class="ml-4 space-y-1 mt-2 text-sm grid grid-cols-2 gap-2">
                        <label><input type="radio" name="cc3" value="1" required> 1. Helped very much</label>
                        <label><input type="radio" name="cc3" value="3"> 3. Did not help</label>
                        <label><input type="radio" name="cc3" value="2"> 2. Somewhat helped</label>
                        <label><input type="radio" name="cc3" value="4"> 4. N/A</label>
                    </div>
                </div>
            </div>

            {{-- 3. SQD Matrix --}}
            <div class="mb-8">
                <h3 class="font-bold text-lg mb-2">INSTRUCTIONS: For SQD 0-8, please select the column that best corresponds to your answer.</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300 text-sm">
                        <thead class="bg-gray-100 text-center">
                            <tr>
                                <th class="py-2 px-4 border-b border-r text-left w-1/2">Questions</th>
                                <th class="py-2 px-2 border-b border-r">Strongly Agree<br>(5)</th>
                                <th class="py-2 px-2 border-b border-r">Agree<br>(4)</th>
                                <th class="py-2 px-2 border-b border-r">Neither<br>(3)</th>
                                <th class="py-2 px-2 border-b border-r">Disagree<br>(2)</th>
                                <th class="py-2 px-2 border-b border-r">Strongly Disagree<br>(1)</th>
                                <th class="py-2 px-2 border-b">N/A<br>(0)</th>
                            </tr>
                        </thead>
                        <tbody class="text-center text-gray-700">
                            @php
                                $sqd_questions = [
                                    "SQD0. I am satisfied with the service that I availed.",
                                    "SQD1. I spent a reasonable amount of time for my transaction.",
                                    "SQD2. The office followed the transaction’s requirements and steps based on the information provided.",
                                    "SQD3. The steps (including payment) I needed to do for my transaction were easy and simple.",
                                    "SQD4. I easily found information about my transaction from the office or its website.",
                                    "SQD5. I paid a reasonable amount of fees for my transaction. (If service was free, mark the ‘N/A’ column)",
                                    "SQD6. I feel the office was fair to everyone, or “walang palakasan”, during my transaction.",
                                    "SQD7. I was treated courteously by the staff, and (if asked for help) the staff was helpful.",
                                    "SQD8. I got what I needed from the government office, or (if denied) denial of request was sufficiently explained to me."
                                ];
                            @endphp

                            @foreach($sqd_questions as $index => $question)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4 border-b border-r text-left font-medium">{{ $question }}</td>
                                <td class="py-3 px-2 border-b border-r"><input type="radio" name="sqd{{ $index }}" value="5" required></td>
                                <td class="py-3 px-2 border-b border-r"><input type="radio" name="sqd{{ $index }}" value="4"></td>
                                <td class="py-3 px-2 border-b border-r"><input type="radio" name="sqd{{ $index }}" value="3"></td>
                                <td class="py-3 px-2 border-b border-r"><input type="radio" name="sqd{{ $index }}" value="2"></td>
                                <td class="py-3 px-2 border-b border-r"><input type="radio" name="sqd{{ $index }}" value="1"></td>
                                <td class="py-3 px-2 border-b"><input type="radio" name="sqd{{ $index }}" value="0"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 4. Suggestions --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Suggestions on how we can further improve our services (optional):</label>
                <textarea name="suggestions" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-pink-500 text-white font-bold rounded-lg shadow-lg hover:bg-pink-600 transition text-lg">
                    Submit Feedback
                </button>
            </div>
        </form>
    </div>
</div>
@endsection