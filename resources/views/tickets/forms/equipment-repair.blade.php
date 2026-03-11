<div class="max-w-4xl mx-auto bg-white p-8 shadow-md rounded-lg">
    <div class="mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-wide">Equipment Repair Request</h2>
        <p class="text-sm text-gray-500">KSU-ICTO-QF-01 Quality Management System</p>
    </div>

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        
        {{-- Hidden Category ID (Ensure this matches your Equipment Repair Category ID in the DB) --}}
        <input type="hidden" name="category_id" value="1"> 

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b">1. Standard Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ticket Title / Short Summary</label>
                    <input type="text" name="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="e.g. Printer won't turn on">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Your Contact Number</label>
                    <input type="text" name="contact_number" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="09123456789 or Email">
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b">2. Equipment Details</h3>
            
            

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Notice the name="form_data[...]" syntax! --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Equipment Type</label>
                    <input type="text" name="form_data[equipment_type]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Brand & Model No.</label>
                    <input type="text" name="form_data[brand_model]" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Serial No.</label>
                    <input type="text" name="form_data[serial_no]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Property No.</label>
                    <input type="text" name="form_data[property_no]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Acquisition Date</label>
                    <input type="date" name="form_data[acquisition_date]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Acquisition Cost</label>
                    <input type="text" name="form_data[acquisition_cost]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="₱">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Date of Last Repair</label>
                    <input type="date" name="form_data[last_repair_date]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nature of Last Repair</label>
                    <input type="text" name="form_data[last_repair_nature]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-3 border-b">3. Problem Description</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700">Please describe the issue in detail</label>
                <textarea name="description" rows="5" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
        </div>

        <div class="mb-6 bg-gray-50 p-4 rounded border border-gray-200">
            <h4 class="font-bold text-sm mb-2 text-red-600">MUST READ: Liability Release</h4>
            <p class="text-xs text-gray-600 text-justify mb-4">
                I authorize the KSU ICT Office to perform maintenance service to my ICT equipment. I understand that KSU ICT Office is not in any way responsible for any data loss or damage to my equipment... (Any equipment left behind for over 30 days will be delivered to the supply office).
            </p>
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="terms" name="terms" type="checkbox" required class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="terms" class="font-medium text-gray-700">I have read and agree to the ICTO Service terms and conditions.</label>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-md transition duration-150 ease-in-out">
                Submit Repair Request
            </button>
        </div>
    </form>
</div>