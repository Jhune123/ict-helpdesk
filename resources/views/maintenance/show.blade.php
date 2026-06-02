@extends('layouts.app')

@section('content')
@php
    $data = json_decode($maintenance->description, true);
    $tasks = $data['tasks'] ?? [];
    $remarks = $data['remarks'] ?? 'No specific remarks or findings provided.';
    
    // Formatting assigned staff into a comma-separated string for the printed line
    $assigned_names = $maintenance->assignees ? implode(', ', $maintenance->assignees->pluck('name')->toArray()) : 'Unassigned';
@endphp

<style>
    /* Document View Styling */
    .doc-wrapper { min-height: 100vh; background-color: #f8fafc; padding: 2rem 1rem; }
    .doc-container { max-width: 850px; margin: 0 auto; background: #fff; padding: 40px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); font-family: Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #000; }
    
    .doc-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .doc-table th, .doc-table td { border: 1px solid #000; padding: 5px; }
    
    .header-table td { vertical-align: middle; }
    .logo { width: 55px; height: auto; display: inline-block; margin: 0 3px; vertical-align: middle; }
    
    .info-box { border: 1px solid #000; padding: 10px; margin-bottom: 15px; }
    
    /* Data presentation to mimic filled blanks */
    .doc-data { border-bottom: 1px solid #000; display: inline-block; width: 100%; font-weight: bold; padding: 2px 4px; min-height: 18px; text-transform: uppercase;}
    .check-box { width: 14px; height: 14px; border: 1px solid #000; display: inline-block; text-align: center; line-height: 14px; font-weight: bold; font-size: 12px; }
    
    /* Print Media Overrides */
    @media print {
        .doc-wrapper { background-color: transparent; padding: 0; }
        .doc-container { box-shadow: none; max-width: 100%; padding: 0; margin: 0; }
        .no-print { display: none !important; }
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>

<div class="doc-wrapper">
    {{-- Floating Action Bar (Hidden on Print) --}}
    <div class="no-print max-w-4xl mx-auto mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-4 rounded-lg shadow-sm border border-slate-200">
        <a href="{{ route('maintenance.index') }}" class="flex items-center text-slate-500 hover:text-blue-600 transition font-bold text-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Schedules
        </a>
        <div class="flex flex-wrap gap-3">
            <button onclick="window.print()" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl font-bold shadow-sm transition flex items-center text-sm cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Document
            </button>
            @hasanyrole('admin|it_staff')
            <a href="{{ route('maintenance.edit', $maintenance->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl font-bold shadow-sm transition flex items-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Record
            </a>
            @endhasanyrole
        </div>
    </div>

    <div class="doc-container">
        {{-- Header Layout --}}
        <table class="doc-table header-table">
            <tr>
                <td rowspan="4" style="width: 140px; text-align: center; white-space: nowrap;">
                    @if(file_exists(public_path('image/school-logo.jpg')))
                        <img src="{{ asset('image/school-logo.jpg') }}" class="logo" alt="KSU Logo">
                    @endif
                    @if(file_exists(public_path('image/Bagong-Pilipinas.png')))
                        <img src="{{ asset('image/Bagong-Pilipinas.png') }}" class="logo" alt="Bagong Pilipinas Logo">
                    @endif
                </td>
                <td style="font-size: 14px; font-weight: bold; text-align: center; text-transform: uppercase;">Kalinga State University</td>
                <td style="width: 180px; font-size: 9px;"><strong>Doc. Ref No.:</strong> KSU-ICTO-QF-05</td>
            </tr>
            <tr>
                <td style="font-size: 10px; text-align: center; font-weight: bold; text-transform: uppercase;">INFORMATION AND COMMUNICATIONS TECHNOLOGY OFFICE</td>
                <td style="font-size: 9px;"><strong>Effectivity Date:</strong> March 24, 2026</td>
            </tr>
            <tr>
                <td style="background:#f2f2f2; font-size: 11px; text-align: center; font-weight: bold; text-transform: uppercase;">Preventive Maintenance Form</td>
                <td style="font-size: 9px;"><strong>Revision No.:</strong> 3.0</td>
            </tr>
            <tr>
                <td style="text-align: center; font-size: 9px; font-weight: bold;">ICT CENTER</td>
                <td style="font-size: 9px;"><strong>Page No.:</strong> 1 of 1</td>
            </tr>
        </table>

        {{-- Document Info Fields --}}
        <div class="info-box">
            <table style="width: 100%; border: none; border-collapse: collapse;">
                <tr>
                    <td style="width: 17%; padding: 6px 0;"><strong>Name of Office/College:</strong></td>
                    <td style="width: 33%; padding: 6px 5px;"><span class="doc-data">{{ $maintenance->office_college }}</span></td>
                    <td style="width: 15%; padding: 6px 0; text-align: right;"><strong>Frequency:</strong></td>
                    <td style="width: 35%; padding: 6px 0 6px 5px;"><span class="doc-data">{{ $maintenance->frequency }}</span></td>
                </tr>
                <tr>
                    <td style="padding: 6px 0; vertical-align: top;"><strong>Name of ICT in charge:</strong></td>
                    <td style="padding: 6px 5px; vertical-align: top;"><span class="doc-data" style="white-space: normal;">{{ $assigned_names }}</span></td>
                    <td style="padding: 6px 0; text-align: right; vertical-align: top;"><strong>Devices/Brand & Model:</strong></td>
                    <td style="padding: 6px 0 6px 5px; vertical-align: top;">
                        <span class="doc-data">
                            {{ $maintenance->title }} {{ $maintenance->device_model ? ' / ' . $maintenance->device_model : '' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 6px 0;"><strong>Property No.:</strong></td>
                    <td style="padding: 6px 5px;"><span class="doc-data">{{ $maintenance->property_number ?: 'N/A' }}</span></td>
                    <td style="padding: 6px 0; text-align: right;"><strong>Serial No.:</strong></td>
                    <td style="padding: 6px 0 6px 5px;"><span class="doc-data">{{ $maintenance->serial_number ?: 'N/A' }}</span></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="no-print" style="font-size: 9px; color: #f59e0b; font-weight: bold;">Next Due: {{ $maintenance->next_run_date->format('M d, Y') }}</span>
                    </td>
                    <td style="padding: 6px 0; text-align: right;"><strong>Date Performed:</strong></td>
                    <td style="padding: 6px 0 6px 5px;">
                        <span class="doc-data">
                            {{ $maintenance->last_run_date ? \Carbon\Carbon::parse($maintenance->last_run_date)->format('F d, Y') : 'N/A' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Checklist Table --}}
        <table class="doc-table">
            <thead>
                <tr style="background: #f2f2f2; text-align: center;">
                    <th style="width: 6%;">No.</th>
                    <th style="width: 64%;">
                        Preventive Maintenance Task<br>
                        <span style="font-size: 8.5px; font-weight: normal; text-transform: none;">Tasks to be performed by ICT In-charge</span>
                    </th>
                    <th style="width: 30%;">Done / Checked</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $all_tasks = [
                        'SOFTWARE APPLICATION' => [
                            'Empty the Recycle Bin', 'Delete .temp files', 'Delete the files that begin with a tilde',
                            'Delete the .check files, and switch the file', 'Run Scandisk and defrag the drive as needed',
                            'Check browser history and cache files', 'Clean out Windows temporary Internet files',
                            'Confirm that backups are already done', 'Update drivers as needed',
                            'Check the Operating system and Applications', 'Update the anti-virus software if needed'
                        ],
                        'HARDWARE' => [
                            'Check cable connections', 'Check the power sources', 'Clean the mouse',
                            'Clean the keyboard', 'Clean the screen/monitor', 'Clean the CD/DVD -ROM Drive',
                            'Check the fan', 'Check the network hardware'
                        ],
                        'OTHER DEVICES' => [
                            'Check nozzle', 'Check head cleaning', 'Check power flush ink', 'Check ink waste pad',
                            'Check ink level/toner', 'Clean Projector headlamp', 'Check projector power sources and cable',
                            'Check projector fan', 'Check network switches, cable port', 'Check network cable crimp head (rj45 etc.)',
                            'Check network switches fan', 'Check network switches, power sources', 'Check access point (AP) port connection',
                            'Check access point (AP) cable crimp head', 'Check the router cable port', 'Check router crimp head (rj45 and etc.)',
                            'Check network radio antenna UTP cable', 'Check network radio antenna alignment'
                        ]
                    ];
                    $globalIndex = 1;
                @endphp

                @foreach($all_tasks as $category => $items)
                    <tr style="background: #e9ecef;">
                        <td colspan="3" style="padding: 4px 6px;">
                            <strong>{{ $category }}</strong>
                        </td>
                    </tr>
                    @foreach($items as $task)
                        @if($category === 'HARDWARE' && $task === 'Clean the CD/DVD -ROM Drive')
                            @php $globalIndex = 19; @endphp
                        @endif
                        <tr>
                            <td style="text-align: center;">{{ $globalIndex }}</td>
                            <td>{{ $task }}</td>
                            <td style="text-align: center;">
                                @if(in_array($task, $tasks))
                                    <div class="check-box">&#10004;</div>
                                @else
                                    <div class="check-box" style="border-color: #ccc;"></div>
                                @endif
                            </td>
                        </tr>
                        @php $globalIndex++; @endphp
                    @endforeach
                @endforeach
                
                <tr>
                    <td colspan="3" style="padding: 10px;">
                        <strong>Remarks:</strong>
                        <div style="border-bottom: 1px dotted #000; min-height: 40px; margin-top: 5px; font-style: italic; white-space: pre-line;">
                            {{ $remarks }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Footer Signatures --}}
        <table style="width: 100%; margin-top: 25px; border-collapse: collapse; border: none;">
            <tr>
                <td style="width: 50%; vertical-align: top; padding-right: 20px; border: none;">
                    <strong>Performed by:</strong>
                    <div style="margin-top: 35px; text-align: center;">
                        <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto 3px auto; font-weight: bold; text-transform: uppercase;">
                            {{ $assigned_names !== 'Unassigned' ? $assigned_names : '' }}
                        </div>
                        <span style="font-size: 9.5px;">Signature over printed name, ICTO Staff/in-charge</span>
                    </div>
                </td>
                <td style="width: 50%; vertical-align: top; border: 1px solid #000; padding: 10px;">
                    <strong>USER ACKNOWLEDGEMENT</strong>
                    <p style="font-size: 9px; margin: 5px 0 25px 0;">I, the undersigned, hereby acknowledge that the ICT Office has rendered & completed a service to my satisfaction.</p>
                    <table style="width: 100%; border: none;">
                        <tr>
                            <td style="width: 60%; text-align: center; border: none; padding: 0;">
                                <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto;"></div>
                                <span style="font-size: 9px;">Signature</span>
                            </td>
                            <td style="width: 40%; text-align: center; border: none; padding: 0;">
                                <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto;"></div>
                                <span style="font-size: 9px;">Date</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 25px; border: none;">
                    <strong>Confirmed by:</strong>
                    <div style="margin-top: 35px; width: 45%;">
                        <div style="border-top: 1px solid #000; width: 100%; margin-bottom: 3px;"></div>
                        <span style="font-size: 9.5px; display: block; text-align: center;">Signature over printed name, ICTO Supervisor</span>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
@endsection