@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        
        <div class="flex items-center mb-6 border-b pb-4">
            <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-800">Create New Ticket</h2>
        </div>

        <div class="mb-8 p-5 bg-blue-50 border border-blue-200 rounded-lg">
            <label class="block text-blue-800 font-semibold mb-2 text-lg">Step 1: Select Service Category</label>
            <select id="category_selector" class="w-full border-gray-300 rounded-md p-3 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-gray-700" onchange="toggleForm()">
                <option value="none" selected disabled>-- Choose a Form Type --</option>
                <option value="equipment-repair">Equipment Repair Form (KSU-ICTO-QF-01)</option>
                <option value="information-system">Information System Request Form (KSU-ICTO-QF-02)</option>
                <option value="multimedia">Multimedia Request Form (KSU-ICTO-QF-03)</option>
                <option value="network">Network Request Form (KSU-ICTO-QF-04)</option>
                <option value="generic">Standard / General Ticket</option>
            </select>
        </div>

        {{-- ========================================================== --}}
        {{-- 🛠️ 1. EQUIPMENT REPAIR FORM (KSU-ICTO-QF-01) --}}
        {{-- ========================================================== --}}
        <div id="form-equipment-repair" class="form-section hidden border-t border-gray-200 pt-6">
            <div class="mb-6 bg-blue-50 p-3 rounded border border-blue-200 text-sm text-blue-800 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <strong>Official Form:</strong> Equipment Repair Form (KSU-ICTO-QF-01)
            </div>
            
            <form action="{{ route('tickets.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="form_type" value="equipment_repair">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6 space-y-6">
                    <div>
                        <h3 class="font-bold text-lg text-gray-700 mb-3 border-b border-gray-300 pb-1">1. REQUESTOR INFORMATION</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2"><label class="block font-semibold text-gray-600 text-sm mb-1">User's Full Name:</label><input type="text" name="meta[full_name]" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Date Requested:</label><input type="date" name="meta[date_requested]" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Office Name & Address:</label><input type="text" name="meta[office_address]" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Contact Number:</label><input type="text" name="contact_number" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Email Address:</label><input type="email" name="meta[email_address]" class="w-full border border-gray-300 rounded-md p-2"></div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-700 mb-3 border-b border-gray-300 pb-1">2. REQUEST DETAILS</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Equipment Type:</label><input type="text" name="equipment_type" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Brand & Model No:</label><input type="text" name="brand_model" class="w-full border border-gray-300 rounded-md p-2"></div>
                        </div>
                    </div>
                </div>
                @include('tickets.partials.inline-lower-fields', ['btnColor' => 'blue', 'btnText' => 'Submit Repair Request'])
            </form>
        </div>

        {{-- ========================================================== --}}
        {{-- 💻 2. INFORMATION SYSTEM REQUEST FORM (KSU-ICTO-QF-02) --}}
        {{-- ========================================================== --}}
        <div id="form-information-system" class="form-section hidden border-t border-gray-200 pt-6">
            <div class="mb-6 bg-teal-50 p-3 rounded border border-teal-200 text-sm text-teal-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
                    <strong>Official Form:</strong> Information System Request Form (KSU-ICTO-QF-02)
                </div>
                <div class="text-xs font-mono bg-white px-2 py-1 rounded border">Request No.: ________________</div>
            </div>
            <form action="{{ route('tickets.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="form_type" value="information_system_request">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6 space-y-6">
                    <div>
                        <h3 class="font-bold text-lg text-gray-700 mb-3 border-b border-gray-300 pb-1">1. REQUESTOR INFORMATION</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Date Requested:</label><input type="date" name="meta[date_requested]" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-md p-2"></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">User's Full Name:</label><input type="text" name="meta[full_name]" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div class="md:col-span-2"><label class="block font-semibold text-gray-600 text-sm mb-1">Office Name & Address:</label><input type="text" name="meta[office_address]" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Contact Number:</label><input type="text" name="contact_number" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Email Address:</label><input type="email" name="meta[email_address]" class="w-full border border-gray-300 rounded-md p-2"></div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-700 mb-3 border-b border-gray-300 pb-1">2. REQUEST DETAILS</h3>
                        <div class="mb-4"><label class="block font-semibold text-gray-600 text-sm mb-1">I.S. Name / Project Title:</label><input type="text" name="title" class="w-full border border-gray-300 rounded-md p-2" required></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 ml-4 mb-4">
                            @foreach(['Account Management', 'Bug Report', 'System Installation/Repair', 'Technical Support/Assistance'] as $type)
                                <label class="flex items-center space-x-2 text-sm"><input type="checkbox" name="meta[request_type][]" value="{{ $type }}" class="rounded text-teal-600"> <span>{{ $type }}</span></label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-700 mb-3 border-b border-gray-300 pb-1">3. PROJECT REQUEST TIMELINE</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Requested Start Date:</label><input type="date" name="meta[requested_start_date]" class="w-full border border-gray-300 rounded-md p-2"></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Requested Completion Date:</label><input type="date" name="meta[requested_completion_date]" class="w-full border border-gray-300 rounded-md p-2"></div>
                        </div>
                        <label class="block font-semibold text-gray-600 text-sm mb-1">Status/Remarks:</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-md p-2" required placeholder="Detailed description..."></textarea>
                    </div>
                </div>
                @include('tickets.partials.inline-lower-fields', ['btnColor' => 'teal', 'btnText' => 'Submit IS Request'])
            </form>
        </div>

        {{-- ========================================================== --}}
        {{-- 🎥 3. MULTIMEDIA REQUEST FORM (KSU-ICTO-QF-03) --}}
        {{-- ========================================================== --}}
        <div id="form-multimedia" class="form-section hidden border-t border-gray-200 pt-6">
            <div class="mb-6 bg-purple-50 p-3 rounded border border-purple-200 text-sm text-purple-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    <strong>Official Form:</strong> Multimedia Request Form (KSU-ICTO-QF-03)
                </div>
                <div class="text-xs font-mono bg-white px-2 py-1 rounded border">Request No.: ________________</div>
            </div>
            <form action="{{ route('tickets.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="form_type" value="multimedia_request">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6 space-y-6">
                    <div>
                        <h3 class="font-bold text-lg text-gray-700 mb-3 border-b border-gray-300 pb-1">1. REQUESTOR INFORMATION</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Date Requested:</label><input type="date" name="meta[date_requested]" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-md p-2"></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">User's Full Name:</label><input type="text" name="meta[full_name]" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div class="md:col-span-2"><label class="block font-semibold text-gray-600 text-sm mb-1">Office Name & Address:</label><input type="text" name="meta[office_address]" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Contact Number:</label><input type="text" name="contact_number" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Email Address:</label><input type="email" name="meta[email_address]" class="w-full border border-gray-300 rounded-md p-2"></div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-700 mb-3 border-b border-gray-300 pb-1">2. REQUEST DETAILS</h3>
                        <div class="mb-4"><label class="block font-semibold text-gray-600 text-sm mb-1">Project Name:</label><input type="text" name="title" class="w-full border border-gray-300 rounded-md p-2" required></div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 ml-4 mb-4">
                            @foreach(['Photography/Documentation', 'Videography', 'Graphic Design', 'Audio Recording', 'Live Streaming', 'Technical Support/Assistance'] as $type)
                                <label class="flex items-center space-x-2 text-sm"><input type="checkbox" name="meta[request_type][]" value="{{ $type }}" class="rounded text-purple-600"> <span>{{ $type }}</span></label>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Location:</label><input type="text" name="meta[location]" class="w-full border border-gray-300 rounded-md p-2"></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Date and Time:</label><input type="text" name="meta[date_time]" class="w-full border border-gray-300 rounded-md p-2"></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Duration:</label><input type="text" name="meta[duration]" class="w-full border border-gray-300 rounded-md p-2"></div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-700 mb-3 border-b border-gray-300 pb-1">3. PROJECT REQUEST TIMELINE</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Requested Start Date:</label><input type="date" name="meta[requested_start_date]" class="w-full border border-gray-300 rounded-md p-2"></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Requested Completion Date:</label><input type="date" name="meta[requested_completion_date]" class="w-full border border-gray-300 rounded-md p-2"></div>
                        </div>
                        <label class="block font-semibold text-gray-600 text-sm mb-1">Status/Remarks:</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-md p-2" required placeholder="Detailed remarks..."></textarea>
                    </div>
                </div>
                @include('tickets.partials.inline-lower-fields', ['btnColor' => 'purple', 'btnText' => 'Submit Multimedia Request'])
            </form>
        </div>

        {{-- ========================================================== --}}
        {{-- 🌐 4. NETWORK REQUEST FORM (KSU-ICTO-QF-04) --}}
        {{-- ========================================================== --}}
        <div id="form-network" class="form-section hidden border-t border-gray-200 pt-6">
            <div class="mb-6 bg-green-50 p-3 rounded border border-green-200 text-sm text-green-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    <strong>Official Form:</strong> Network Request Form (KSU-ICTO-QF-04)
                </div>
                <div class="text-xs font-mono bg-white px-2 py-1 rounded border">Request No.: ________________</div>
            </div>
            <form action="{{ route('tickets.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="form_type" value="network_request">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mb-6 space-y-6">
                    <div>
                        <h3 class="font-bold text-lg text-gray-700 mb-3 border-b border-gray-300 pb-1">1. REQUESTOR INFORMATION</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Date Requested:</label><input type="date" name="meta[date_requested]" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-md p-2"></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">User's Full Name:</label><input type="text" name="meta[full_name]" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div class="md:col-span-2"><label class="block font-semibold text-gray-600 text-sm mb-1">Office Name & Address:</label><input type="text" name="meta[office_address]" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Contact Number:</label><input type="text" name="contact_number" class="w-full border border-gray-300 rounded-md p-2" required></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Email Address:</label><input type="email" name="meta[email_address]" class="w-full border border-gray-300 rounded-md p-2"></div>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-700 mb-3 border-b border-gray-300 pb-1">2. REQUEST DETAILS</h3>
                        <div class="mb-4">
                            <label class="block font-semibold text-gray-600 text-sm mb-2">Type of Request/Purpose:</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 ml-4">
                                @foreach(['Network Access', 'Network Troubleshooting', 'VPN Access', 'Wireless Network Access', 'Technical Support/Assistance'] as $type)
                                    <label class="flex items-center space-x-2 text-sm">
                                        <input type="checkbox" name="meta[request_type][]" value="{{ $type }}" class="rounded text-green-600">
                                        <span>{{ $type }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-2 ml-4 flex items-center gap-2">
                                <span class="text-sm text-gray-600">Others:</span>
                                <input type="text" name="meta[request_type_other]" class="flex-1 border-b border-gray-300 focus:border-green-500 outline-none text-sm p-1">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold text-gray-600 text-sm mb-1">Location:</label>
                            <input type="text" name="meta[location]" class="w-full border border-gray-300 rounded-md p-2" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold text-gray-600 text-sm mb-2">Device:</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 ml-4">
                                @foreach(['System Unit', 'Laptop', 'Mobile Device'] as $device)
                                    <label class="flex items-center space-x-2 text-sm">
                                        <input type="checkbox" name="meta[device_type][]" value="{{ $device }}" class="rounded text-green-600">
                                        <span>{{ $device }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mt-2 ml-4 flex items-center gap-2">
                                <span class="text-sm text-gray-600">Others:</span>
                                <input type="text" name="meta[device_other]" class="flex-1 border-b border-gray-300 focus:border-green-500 outline-none text-sm p-1">
                            </div>
                        </div>

                        <div>
                            <label class="block font-semibold text-gray-600 text-sm mb-1">MAC Address:</label>
                            <input type="text" name="meta[mac_address]" class="w-full border border-gray-300 rounded-md p-2" placeholder="e.g., 00:1A:2B:3C:4D:5E">
                        </div>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-700 mb-3 border-b border-gray-300 pb-1">3. PROJECT REQUEST TIMELINE</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Requested Start Date:</label><input type="date" name="meta[requested_start_date]" class="w-full border border-gray-300 rounded-md p-2"></div>
                            <div><label class="block font-semibold text-gray-600 text-sm mb-1">Requested Completion Date:</label><input type="date" name="meta[requested_completion_date]" class="w-full border border-gray-300 rounded-md p-2"></div>
                        </div>
                        <label class="block font-semibold text-gray-600 text-sm mb-1">Status/Remarks:</label>
                        <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-md p-2" required placeholder="Detailed description..."></textarea>
                    </div>
                </div>
                @include('tickets.partials.inline-lower-fields', ['btnColor' => 'green', 'btnText' => 'Submit Network Request'])
            </form>
        </div>

        {{-- ========================================================== --}}
        {{-- 📝 5. GENERIC / STANDARD FORM (ONLY THIS FORM UPDATED) --}}
        {{-- ========================================================== --}}
        <div id="form-generic" class="form-section hidden border-t border-gray-200 pt-6">
            <form action="{{ route('tickets.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="form_type" value="generic">
                
                <div class="space-y-4">
                    {{-- Updated Label to 'Title' only --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" class="w-full border border-gray-300 rounded-md px-3 py-2" required placeholder="Subject">
                    </div>

                    {{-- Combined Contact Box --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Contact Number & E-mail Address</label>
                        <input type="text" name="contact_info" class="w-full border border-gray-300 rounded-md px-3 py-2" required placeholder="e.g. 09123456789 / user@ksu.edu.ph">
                    </div>

                    {{-- Equipment Information Section --}}
                    <div class="pt-4 border-t border-gray-100">
                        <h3 class="text-sm font-bold text-gray-800 mb-3">EQUIPMENT INFORMATION</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Equipment Type</label>
                                <input type="text" name="meta[equipment_type]" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="e.g. Laptop">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Brand & Model No.</label>
                                <input type="text" name="meta[brand_model]" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="e.g. HP LaserJet">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase">Serial No.</label>
                                <input type="text" name="meta[serial_no]" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm" placeholder="S/N: 12345">
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Message / Description</label>
                        <textarea name="description" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2" required placeholder="Describe your request or issue here..."></textarea>
                    </div>
                </div>

                @include('tickets.partials.inline-lower-fields', ['btnColor' => 'green', 'btnText' => 'Submit Ticket'])
            </form>
        </div>

    </div>
</div>

<script>
    function toggleForm() {
        const selector = document.getElementById('category_selector');
        const sections = document.querySelectorAll('.form-section');
        sections.forEach(s => s.classList.add('hidden'));
        
        const target = document.getElementById('form-' + selector.value);
        if (target) { target.classList.remove('hidden'); }
    }
</script>
@endsection