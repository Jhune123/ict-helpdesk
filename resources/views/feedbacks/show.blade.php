@extends('layouts.app')

@section('content')
<div class="p-6 max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📄 Client Feedback Details</h2>
        <a href="{{ route('feedbacks.index') }}" class="px-4 py-2 bg-gray-500 text-white font-medium rounded-lg shadow hover:bg-gray-600 transition">
            ⬅ Back to Feedbacks
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden border-t-4 border-blue-600">
        
        {{-- Header Info --}}
        <div class="bg-blue-50 p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-blue-900">Ticket Reference: #{{ $feedback->ticket->ticket_number ?? 'N/A' }}</h3>
                <p class="text-sm text-blue-700 mt-1">Submitted on: {{ $feedback->created_at->format('F d, Y - h:i A') }}</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- 👇 SECURED: Only Admin and IT Staff can see the Edit Action Button --}}
                @hasanyrole('admin|it_staff')
                    <a href="{{ route('feedbacks.edit', $feedback->id) }}" class="px-4 py-2 bg-amber-500 text-white text-sm font-bold rounded shadow hover:bg-amber-600 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        ✏️ Edit Feedback
                    </a>
                @endhasanyrole

                <a href="{{ route('feedbacks.download-pdf', $feedback->id) }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-bold rounded shadow hover:bg-blue-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>

        <div class="p-6">
            {{-- I. Client Information --}}
            <div class="mb-8">
                <h4 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">I. Client Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 p-3 rounded border">
                        <span class="block text-gray-500 text-xs uppercase font-bold mb-1">Name (Optional)</span>
                        <span class="font-medium text-gray-900">{{ $feedback->client_name ?? 'Anonymous' }}</span>
                    </div>
                    <div class="bg-gray-50 p-3 rounded border">
                        <span class="block text-gray-500 text-xs uppercase font-bold mb-1">Office/College Visited</span>
                        <span class="font-medium text-gray-900">{{ $feedback->office_visited ?? 'N/A' }}</span>
                    </div>
                    <div class="bg-gray-50 p-3 rounded border md:col-span-2">
                        <span class="block text-gray-500 text-xs uppercase font-bold mb-1">Service/s Received</span>
                        <span class="font-medium text-gray-900">{{ $feedback->services_received ?? 'N/A' }}</span>
                    </div>
                    <div class="bg-gray-50 p-3 rounded border">
                        <span class="block text-gray-500 text-xs uppercase font-bold mb-1">Staff Assisted</span>
                        <span class="font-medium text-gray-900">{{ $feedback->staff_assisted ?? 'N/A' }}</span>
                    </div>
                    <div class="bg-gray-50 p-3 rounded border">
                        <span class="block text-gray-500 text-xs uppercase font-bold mb-1">Other Staff Involved</span>
                        <span class="font-medium text-gray-900">{{ $feedback->other_staff ?? 'None' }}</span>
                    </div>
                    <div class="bg-gray-50 p-3 rounded border">
                        <span class="block text-gray-500 text-xs uppercase font-bold mb-1">Client Type</span>
                        <span class="font-medium text-gray-900">
                            {{ $feedback->client_type ?? 'N/A' }} 
                            @if($feedback->agency_name) ({{ $feedback->agency_name }}) @endif
                        </span>
                    </div>
                    <div class="bg-gray-50 p-3 rounded border">
                        <span class="block text-gray-500 text-xs uppercase font-bold mb-1">Demographics</span>
                        <span class="font-medium text-gray-900">Sex: {{ $feedback->sex ?? 'N/A' }} | Age: {{ $feedback->age ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- II. Citizen's Charter --}}
            <div class="mb-8">
                <h4 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">II. Citizen's Charter (CC) Responses</h4>
                
                @php
                    $cc1_choices = [
                        1 => "1. I know what a CC is and I saw this office's CC.",
                        2 => "2. I know what a CC is but I did NOT see this office's CC.",
                        3 => "3. I learned of the CC only when I saw this office's CC.",
                        4 => "4. I do not know what a CC is and I did not see one in this office."
                    ];
                    $cc2_choices = [1 => "Easy to see", 2 => "Somewhat easy to see", 3 => "Difficult to see", 4 => "Not visible at all", 5 => "N/A"];
                    $cc3_choices = [1 => "Helped very much", 2 => "Somewhat helped", 3 => "Did not help", 4 => "N/A"];
                @endphp

                <div class="space-y-4 text-sm bg-blue-50 p-4 rounded border border-blue-100">
                    <div>
                        <p class="font-bold text-gray-700">CC1: Awareness of CC?</p>
                        <p class="text-blue-800 mt-1 flex items-center gap-2">
                            <span class="bg-blue-200 text-blue-800 p-1 rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                            {{ $cc1_choices[$feedback->cc1] ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700">CC2: Visibility of CC?</p>
                        <p class="text-blue-800 mt-1 flex items-center gap-2">
                            <span class="bg-blue-200 text-blue-800 p-1 rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                            {{ $cc2_choices[$feedback->cc2] ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="font-bold text-gray-700">CC3: Helpfulness of CC?</p>
                        <p class="text-blue-800 mt-1 flex items-center gap-2">
                            <span class="bg-blue-200 text-blue-800 p-1 rounded-full"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                            {{ $cc3_choices[$feedback->cc3] ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- III. SQD Matrix --}}
            <div class="mb-8">
                <h4 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">III. Service Quality Dimensions (SQD)</h4>
                
                @php
                    $sqd_choices = [
                        5 => ['text' => 'Strongly Agree', 'color' => 'bg-green-100 text-green-800'],
                        4 => ['text' => 'Agree', 'color' => 'bg-blue-100 text-blue-800'],
                        3 => ['text' => 'Neither', 'color' => 'bg-yellow-100 text-yellow-800'],
                        2 => ['text' => 'Disagree', 'color' => 'bg-orange-100 text-orange-800'],
                        1 => ['text' => 'Strongly Disagree', 'color' => 'bg-red-100 text-red-800'],
                        0 => ['text' => 'N/A', 'color' => 'bg-gray-100 text-gray-800'],
                    ];

                    $sqd_questions = [
                        "SQD0. General Service Satisfaction",
                        "SQD1. Reasonable time spent",
                        "SQD2. Followed requirements and steps",
                        "SQD3. Steps were easy and simple",
                        "SQD4. Information easily found",
                        "SQD5. Paid reasonable amount of fees",
                        "SQD6. Fairness observed (walang palakasan)",
                        "SQD7. Treated courteously by helpful staff",
                        "SQD8. Got what was needed / Denial explained"
                    ];
                @endphp

                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse border border-gray-200 rounded">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="p-3 border border-gray-300 text-left">Dimension / Question</th>
                                <th class="p-3 border border-gray-300 text-center w-40">Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sqd_questions as $index => $question)
                                @php
                                    $rating = $feedback->{'sqd'.$index};
                                    $display = $sqd_choices[$rating] ?? null;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 border border-gray-200 text-gray-800 font-medium">{{ $question }}</td>
                                    <td class="p-3 border border-gray-200 text-center">
                                        @if($display)
                                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $display['color'] }}">
                                                {{ $display['text'] }} ({{ $rating }})
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- IV. Suggestions --}}
            <div>
                <h4 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">IV. Suggestions & Comments</h4>
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded text-gray-800 italic min-h-[80px]">
                    @if($feedback->suggestions)
                        "{!! nl2br(e($feedback->suggestions)) !!}"
                    @else
                        <span class="text-gray-500">No suggestions or comments provided.</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection