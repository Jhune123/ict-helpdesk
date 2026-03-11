<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipment Repair Form - {{ $ticket->ticket_id ?? 'Draft' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Print-specific styles to ensure it looks like a physical document */
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none; }
        }
        body { font-family: 'Times New Roman', Times, serif; }
    </style>
</head>
<body class="bg-gray-100 flex justify-center py-8">

    <div class="fixed top-4 right-4 no-print">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
            🖨️ Print Form
        </button>
    </div>

    <div class="bg-white w-[210mm] min-h-[297mm] p-10 shadow-lg border border-gray-300 relative">
        
        <div class="flex border-2 border-black mb-6">
            <div class="w-2/3 p-4 flex flex-col items-center justify-center border-r-2 border-black text-center">
                <h1 class="text-xl font-bold uppercase">Kalinga State University</h1>
                <h2 class="text-lg font-semibold mt-1">Quality Management System</h2>
                <h3 class="text-xl font-bold mt-4 uppercase tracking-wider">Equipment Repair Form</h3>
            </div>
            <div class="w-1/3 flex flex-col text-sm font-semibold">
                <div class="flex border-b border-black">
                    <div class="w-1/2 p-2 border-r border-black">Doc. Ref No.:</div>
                    <div class="w-1/2 p-2">KSU-ICTO-QF-01</div>
                </div>
                <div class="flex border-b border-black">
                    <div class="w-1/2 p-2 border-r border-black">Effectivity Date:</div>
                    <div class="w-1/2 p-2">October 14, 2025</div>
                </div>
                <div class="flex border-b border-black">
                    <div class="w-1/2 p-2 border-r border-black">Revision No.:</div>
                    <div class="w-1/2 p-2">2.0</div>
                </div>
                <div class="flex">
                    <div class="w-1/2 p-2 border-r border-black">Page No.:</div>
                    <div class="w-1/2 p-2">1</div>
                </div>
            </div>
        </div>

        <div class="text-right font-bold text-lg mb-4">
            Request No.: <span class="underline">{{ $ticket->id ?? '________________' }}</span>
        </div>
        <div class="text-center italic text-sm mb-6">To be filled by KSU ICT User</div>

        <h4 class="font-bold text-md mb-2">1. REQUESTOR INFORMATION</h4>
        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            <div class="col-span-2">
                <span class="font-semibold">Date Requested:</span> 
                <span class="border-b border-black px-4">{{ $ticket->created_at ? $ticket->created_at->format('F d, Y') : '____________________' }}</span>
            </div>
            <div class="col-span-2">
                <span class="font-semibold">User’s Full Name:</span> 
                <span class="border-b border-black px-4">{{ $ticket->user->name ?? '_________________________________________________' }}</span>
            </div>
            <div class="col-span-2">
                <span class="font-semibold">User’s Office Name & Address:</span> 
                <span class="border-b border-black px-4">{{ $ticket->user->office ?? '_________________________________________________' }}</span>
            </div>
            <div>
                <span class="font-semibold">User’s Contact Number:</span> 
                <span class="border-b border-black px-4">{{ $ticket->user->contact ?? '_________________________' }}</span>
            </div>
            <div>
                <span class="font-semibold">Email Address:</span> 
                <span class="border-b border-black px-4">{{ $ticket->user->email ?? '_________________________' }}</span>
            </div>
        </div>

        <h4 class="font-bold text-md mb-2">2. REQUEST DETAILS</h4>
        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
            {{-- Note: We will pull these from a JSON column called 'form_data' later --}}
            <div><span class="font-semibold">Equipment Type:</span> <span class="border-b border-black px-2">{{ $ticket->form_data['equipment_type'] ?? '________________' }}</span></div>
            <div><span class="font-semibold">Brand & Model No:</span> <span class="border-b border-black px-2">{{ $ticket->form_data['brand_model'] ?? '________________' }}</span></div>
            
            <div><span class="font-semibold">Serial No:</span> <span class="border-b border-black px-2">{{ $ticket->form_data['serial_no'] ?? '________________' }}</span></div>
            <div><span class="font-semibold">Property No.:</span> <span class="border-b border-black px-2">{{ $ticket->form_data['property_no'] ?? '________________' }}</span></div>
            
            <div><span class="font-semibold">Acquisition Date:</span> <span class="border-b border-black px-2">{{ $ticket->form_data['acquisition_date'] ?? '________________' }}</span></div>
            <div><span class="font-semibold">Acquisition Cost:</span> <span class="border-b border-black px-2">{{ $ticket->form_data['acquisition_cost'] ?? '________________' }}</span></div>
            
            <div><span class="font-semibold">Date of Last Repair:</span> <span class="border-b border-black px-2">{{ $ticket->form_data['last_repair_date'] ?? '________________' }}</span></div>
            <div><span class="font-semibold">Nature of Last Repair:</span> <span class="border-b border-black px-2">{{ $ticket->form_data['last_repair_nature'] ?? '________________' }}</span></div>
        </div>

        <h4 class="font-bold text-md mb-2">3. PROBLEM DESCRIPTION</h4>
        <div class="min-h-[100px] border-b border-black mb-6 text-sm p-2">
            {{ $ticket->description ?? '________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________' }}
        </div>

        <div class="text-[11px] text-justify leading-tight mb-8">
            <span class="font-bold">Must Read:</span> I now authorize the KSU ICT Office to perform maintenance service to my ICT equipment. I understand that KSU ICT Office is not in any way responsible for any data loss or damage to my equipment. I know that if the equipment was not working correctly at the time of the release, I release KSU ICT Office from any liability as a result of further damages in the event of any equipment-related failure due to hardware wear and tear, application conflicts, faulty applications, virus/malware infections, incompatible third-party devices, or system/operating system bugs. During the servicing, KSU ICT Office may need certain media to continue the repair process. If I do not have the media for the installation on my equipment, KSU ICT Office is not required to make available those applications that require physical media, serial numbers, or product keys free of charge, and not having the media may slow or halt the servicing of the equipment until the correct media or information is obtained. Any equipment left behind for over 30 days will be delivered to the supply office. I agree that any hardware I leave behind may be delivered to the supply office for proper action. All personal data will be irrevocably destroyed to protect my privacy. KSU ICT Office will make every effort to contact me, but if they cannot reach me within the timeline, regardless of the reason, KSU ICT Office assumes that I do not want whatever equipment I have left behind in the KSU ICT Office.
        </div>

        <div class="mt-12 text-sm flex justify-start">
            <div class="w-1/2">
                <p class="mb-8">Requested by:</p>
                <div class="border-b border-black w-3/4 mb-1 text-center font-bold uppercase">
                    {{ $ticket->user->name ?? '' }}
                </div>
                <p class="text-xs">User signature over printed name</p>
            </div>
        </div>

    </div>
</body>
</html>