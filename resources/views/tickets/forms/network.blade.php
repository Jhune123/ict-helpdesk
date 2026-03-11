@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto bg-white p-8 md:p-12 shadow-xl rounded-lg border border-gray-200">
        
        <div class="border-2 border-gray-800 mb-8">
            <div class="grid grid-cols-3 divide-x-2 divide-gray-800 border-b-2 border-gray-800 text-sm text-center font-bold">
                <div class="p-4 flex items-center justify-center">
                    Kalinga State University
                </div>
                <div class="p-4 flex items-center justify-center uppercase tracking-wide">
                    Quality Management System
                </div>
                <div class="p-4 flex items-center justify-center text-lg">
                    Network Request Form
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x-2 divide-gray-800 text-xs text-center font-medium bg-gray-50">
                <div class="p-2">Doc. Ref No.: <br> <span class="text-red-600 font-bold">KSU-ICTO-QF-04</span></div>
                <div class="p-2">Effectivity Date: <br> <span>October 14, 2025</span></div>
                <div class="p-2">Revision No.: <br> <span>2.0</span></div>
                <div class="p-2">Page No.: <br> <span>1</span></div>
            </div>
        </div>

        <form action="{{ route('tickets.store') }}" method="POST">
            @csrf
            
            <input type="hidden" name="category_id" value="NETWORK_CATEGORY_ID">

            <div class="flex justify-end mb-6">
                <p class="font-bold text-gray-700">Request No.: <span class="text-red-600 underline">TO BE GENERATED</span></p>
            </div>
            <p class="italic text-gray-500 mb-6 border-b pb-2">To be filled by KSU ICT User</p>

            <fieldset class="mb-8">
                <legend class="text-lg font-bold text-gray-900 mb-4 bg-gray-100 px-3 py-1 rounded w-full">1. REQUESTOR INFORMATION</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">User's Full Name</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 bg-gray-50" readonly required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">User's Office Name & Address</label>
                        <input type="text" name="office" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">User's Contact Number</label>
                        <input type="text" name="contact_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Email Address</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 bg-gray-50" readonly required>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-8" x-data="{ requestType: '', deviceType: '' }">
                <legend class="text-lg font-bold text-gray-900 mb-4 bg-gray-100 px-3 py-1 rounded w-full">2. REQUEST DETAILS</legend>
                
                <div class="px-3 mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Type of Request/Purpose:</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                        <label class="inline-flex items-center"><input type="radio" x-model="requestType" name="request_type" value="Network Access" class="text-green-600 focus:ring-green-500"> <span class="ml-2">Network Access</span></label>
                        <label class="inline-flex items-center"><input type="radio" x-model="requestType" name="request_type" value="Network Troubleshooting" class="text-green-600 focus:ring-green-500"> <span class="ml-2">Network Troubleshooting</span></label>
                        <label class="inline-flex items-center"><input type="radio" x-model="requestType" name="request_type" value="VPN Access" class="text-green-600 focus:ring-green-500"> <span class="ml-2">VPN Access</span></label>
                        <label class="inline-flex items-center"><input type="radio" x-model="requestType" name="request_type" value="Wireless Network Access" class="text-green-600 focus:ring-green-500"> <span class="ml-2">Wireless Network Access</span></label>
                        <label class="inline-flex items-center"><input type="radio" x-model="requestType" name="request_type" value="Technical Support/Assistance" class="text-green-600 focus:ring-green-500"> <span class="ml-2">Technical Support/Assistance</span></label>
                        <label class="inline-flex items-center">
                            <input type="radio" x-model="requestType" name="request_type" value="Others" class="text-green-600 focus:ring-green-500"> 
                            <span class="ml-2">Others:</span>
                            <input type="text" name="request_type_others" x-bind:disabled="requestType !== 'Others'" class="ml-2 border-b border-gray-400 focus:border-green-500 focus:ring-0 outline-none w-full max-w-xs text-sm" placeholder="Specify here...">
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-3">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Location</label>
                        <input type="text" name="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="e.g., Room 204, Admin Bldg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">MAC Address (If applicable)</label>
                        <input type="text" name="mac_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="00:1A:2B:3C:4D:5E">
                    </div>
                </div>

                <div class="px-3 mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Device:</label>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        <label class="inline-flex items-center"><input type="radio" x-model="deviceType" name="device" value="System Unit" class="text-green-600 focus:ring-green-500"> <span class="ml-2">System Unit</span></label>
                        <label class="inline-flex items-center"><input type="radio" x-model="deviceType" name="device" value="Laptop" class="text-green-600 focus:ring-green-500"> <span class="ml-2">Laptop</span></label>
                        <label class="inline-flex items-center"><input type="radio" x-model="deviceType" name="device" value="Mobile Device" class="text-green-600 focus:ring-green-500"> <span class="ml-2">Mobile Device</span></label>
                        <label class="inline-flex items-center">
                            <input type="radio" x-model="deviceType" name="device" value="Others" class="text-green-600 focus:ring-green-500"> 
                            <span class="ml-2">Others:</span>
                            <input type="text" name="device_others" x-bind:disabled="deviceType !== 'Others'" class="ml-2 border-b border-gray-400 focus:border-green-500 focus:ring-0 outline-none w-24 text-sm" placeholder="Specify">
                        </label>
                    </div>
                </div>
            </fieldset>

            <fieldset class="mb-8">
                <legend class="text-lg font-bold text-gray-900 mb-4 bg-gray-100 px-3 py-1 rounded w-full">3. PROJECT REQUEST TIMELINE</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-3 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Start Date</label>
                        <input type="date" name="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Completion Date (Requested)</label>
                        <input type="date" name="completion_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>
                <div class="px-3">
                    <label class="block text-sm font-semibold text-gray-700">Status/Remarks</label>
                    <textarea name="remarks" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"></textarea>
                </div>
            </fieldset>

            <div class="mt-10 bg-gray-50 p-4 border rounded-md text-sm text-gray-700">
                <p class="mb-6">By submitting this form, you acknowledge that the information provided is accurate and complete. The approval of this request is subject to the approval of the Information and Communications Technology Office.</p>
                
                <div class="flex justify-end mt-8 text-center">
                    <div>
                        <p class="font-bold border-b border-black pb-1 px-8">{{ auth()->user()->name }}</p>
                        <p class="mt-1 text-xs">Requested By (Signature over printed name)</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <button type="submit" class="bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-8 rounded-lg shadow-md transition transform hover:scale-105">
                    Submit Network Request Form
                </button>
            </div>
        </form>
    </div>
</div>
@endsection