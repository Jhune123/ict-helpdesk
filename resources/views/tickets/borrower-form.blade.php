<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Borrower's Form - {{ $ticket->ticket_id ?? 'KSU-ICTO-QF-09' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            @page {
                size: letter portrait;
                margin: 0.5in;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-100 py-8">

    {{-- Print Button (Hidden when printing) --}}
    <div class="max-w-4xl mx-auto mb-4 flex justify-end no-print">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            🖨️ Print / Save as PDF
        </button>
    </div>

    {{-- Formal Document Container --}}
    <div class="max-w-4xl mx-auto bg-white p-8 border border-gray-300 shadow-lg text-black text-sm">
        
        {{-- Header Table --}}
        <table class="w-full border-collapse border border-black mb-2">
            <tr>
                <td class="w-1/4 border border-black p-2 text-center align-middle">
                    {{-- Replace with actual KSU Logo path --}}
                    <img src="{{ asset('images/ksu-logo.png') }}" alt="KSU Logo" class="w-24 h-24 mx-auto object-contain">
                </td>
                <td class="w-1/2 border border-black p-2 text-center align-middle leading-snug">
                    <h1 class="font-bold text-lg">Kalinga State University</h1>
                    <h2 class="font-bold text-base">Quality Management System</h2>
                    <h3 class="text-base">Equipment Borrower's Form</h3>
                </td>
                <td class="w-1/4 border border-black p-0 align-top">
                    <table class="w-full h-full text-xs text-left">
                        <tr>
                            <td class="border-b border-r border-black p-1.5 whitespace-nowrap">Doc. Ref No.:</td>
                            <td class="border-b border-black p-1.5">KSU-ICTO-QF-09</td>
                        </tr>
                        <tr>
                            <td class="border-b border-r border-black p-1.5 whitespace-nowrap">Effectivity Date:</td>
                            <td class="border-b border-black p-1.5">October 14, 2025</td>
                        </tr>
                        <tr>
                            <td class="border-b border-r border-black p-1.5 whitespace-nowrap">Revision No.:</td>
                            <td class="border-b border-black p-1.5">2.0</td>
                        </tr>
                        <tr>
                            <td class="border-r border-black p-1.5 whitespace-nowrap">Page No.:</td>
                            <td class="p-1.5">1</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        {{-- Body Table --}}
        <table class="w-full border-collapse border border-black text-sm">
            {{-- Section Headers --}}
            <tr>
                <th class="w-1/2 border border-black p-2 text-center uppercase font-bold">BORROWER'S INFORMATION</th>
                <th class="w-1/2 border border-black p-2 text-center uppercase font-bold">EQUIPMENT DETAILS</th>
            </tr>
            
            {{-- Content Row --}}
            <tr>
                {{-- Left Column: Borrower Info --}}
                <td class="w-1/2 border border-black p-0 align-top">
                    <table class="w-full h-full">
                        <tr>
                            <td class="w-1/3 border-b border-r border-black p-2">Full Name</td>
                            <td class="w-2/3 border-b border-black p-2 font-semibold">{{ $ticket->meta['full_name'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="w-1/3 border-b border-r border-black p-2">Office Name</td>
                            <td class="w-2/3 border-b border-black p-2 font-semibold">{{ $ticket->meta['office_name'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="w-1/3 border-b border-r border-black p-2">Contact Number</td>
                            <td class="w-2/3 border-b border-black p-2 font-semibold">{{ $ticket->contact_number ?? '' }}</td>
                        </tr>
                        <tr>
                            {{-- Increased height to match right side --}}
                            <td class="w-1/3 border-r border-black p-2 align-top h-[72px]">Email Address</td>
                            <td class="w-2/3 border-black p-2 font-semibold align-top">{{ $ticket->meta['email_address'] ?? '' }}</td>
                        </tr>
                    </table>
                </td>

                {{-- Right Column: Equipment Details --}}
                <td class="w-1/2 border border-black p-0 align-top">
                    <table class="w-full h-full">
                        <tr>
                            <td class="w-1/2 border-b border-r border-black p-2">Equipment Name/Type</td>
                            <td class="w-1/2 border-b border-black p-2 font-semibold">{{ $ticket->meta['equipment_type'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="w-1/2 border-b border-r border-black p-2">Quantity</td>
                            <td class="w-1/2 border-b border-black p-2 font-semibold">{{ $ticket->meta['quantity'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="w-1/2 border-b border-r border-black p-2">Serial Number</td>
                            <td class="w-1/2 border-b border-black p-2 font-semibold">{{ $ticket->meta['serial_no'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="w-1/2 border-b border-r border-black p-2">Date Borrowed</td>
                            <td class="w-1/2 border-b border-black p-2 font-semibold">{{ $ticket->meta['date_borrowed'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="w-1/2 border-r border-black p-2">Expected Return Date</td>
                            <td class="w-1/2 border-black p-2 font-semibold">{{ $ticket->meta['expected_return_date'] ?? '' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>

            {{-- Terms and Conditions --}}
            <tr>
                <td colspan="2" class="border border-black p-3 align-top">
                    <p class="font-medium mb-1">TERMS AND CONDITIONS:</p>
                    <ul class="list-disc pl-8 space-y-0.5 text-[13px] leading-tight">
                        <li>I am responsible for properly handling and caring for the borrowed ICT equipment.</li>
                        <li>I will return the equipment with all accompanying accessories in the same condition as received.</li>
                        <li>I will be held liable for any damage, loss, or theft of the equipment while it is in my possession.</li>
                        <li>I will notify the ICT department immediately in case of any issues or concerns with the equipment.</li>
                        <li>I understand that failure to return the equipment on the agreed return date may result in penalties or restrictions on future borrowing privileges.</li>
                    </ul>
                </td>
            </tr>

            {{-- First Signature Row --}}
            <tr>
                <td class="w-1/2 border border-black p-3 h-28 align-top relative">
                    <p class="mb-6">Borrower:</p>
                    <div class="text-center w-[80%] mx-auto mt-6">
                        <div class="border-b border-black font-semibold uppercase text-sm pb-1">
                            {{ $ticket->meta['full_name'] ?? '__________________________________' }}
                        </div>
                        <p class="text-xs mt-1">Signature over printed name</p>
                    </div>
                </td>
                <td class="w-1/2 border border-black p-3 h-28 align-top relative">
                    <p class="mb-6">Staff-in-charge:</p>
                    <div class="text-center w-[80%] mx-auto mt-6">
                        <div class="border-b border-black font-semibold uppercase text-sm pb-1 text-transparent">
                            __________________________________
                        </div>
                        <p class="text-xs mt-1">Signature over printed name</p>
                    </div>
                </td>
            </tr>

            {{-- Second Signature Row --}}
            <tr>
                <td colspan="2" class="border border-black p-3 h-28 align-top relative">
                    <p class="mb-6">Received By:</p>
                    <div class="text-center w-[50%] mx-auto mt-6">
                        <div class="border-b border-black font-semibold uppercase text-sm pb-1 text-transparent">
                            ____________________________________________________
                        </div>
                        <p class="text-xs mt-1">Staff-in-charge / Date</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>