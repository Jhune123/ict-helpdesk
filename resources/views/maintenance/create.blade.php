@extends('layouts.app')

@section('content')
<style>
    /* Document Form Styling */
    .doc-wrapper { min-height: 100vh; background-color: #f8fafc; padding: 2rem 1rem; }
    .doc-container { max-width: 850px; margin: 0 auto; background: #fff; padding: 40px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); font-family: Arial, sans-serif; font-size: 11px; line-height: 1.4; color: #000; }
    
    .doc-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
    .doc-table th, .doc-table td { border: 1px solid #000; padding: 5px; }
    
    .header-table td { vertical-align: middle; }
    .logo { width: 55px; height: auto; display: inline-block; margin: 0 3px; vertical-align: middle; }
    
    .info-box { border: 1px solid #000; padding: 10px; margin-bottom: 15px; }
    
    /* Input field styling to mimic form blanks */
    .doc-input { border: none; border-bottom: 1px solid #000; width: 100%; outline: none; font-size: 11px; font-family: inherit; background: transparent; padding: 2px; }
    .doc-input:focus { border-bottom: 2px solid #2563eb; background-color: #f0f9ff; }
    .doc-select { border: 1px solid #ccc; width: 100%; font-size: 11px; padding: 4px; outline: none; }
    .doc-select:focus { border-color: #2563eb; }
    
    /* Print Media Overrides */
    @media print {
        .doc-wrapper { background-color: transparent; padding: 0; }
        .doc-container { box-shadow: none; max-width: 100%; padding: 0; margin: 0; }
        .no-print { display: none !important; }
        .doc-input { border-bottom: 1px solid #000 !important; background-color: transparent !important; }
    }
</style>

<div class="doc-wrapper">
    <form action="{{ route('maintenance.store') }}" method="POST">
        @csrf
        
        {{-- Floating Action Bar (Hidden on Print) --}}
        <div class="no-print max-w-4xl mx-auto mb-6 flex justify-between items-center bg-white p-4 rounded-lg shadow-sm border border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                New Maintenance Schedule
            </h2>
            <div class="space-x-3 flex">
                <a href="{{ route('maintenance.index') }}" class="px-5 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-md transition-colors">Cancel</a>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow transition-colors">Save Document</button>
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

            {{-- Document Info Inputs --}}
            <div class="info-box">
                <table style="width: 100%; border: none; border-collapse: collapse;">
                    <tr>
                        <td style="width: 17%; padding: 6px 0;"><strong>Name of Office/College:</strong></td>
                        <td style="width: 33%; padding: 6px 5px;"><input type="text" name="office_college" class="doc-input" required></td>
                        <td style="width: 15%; padding: 6px 0; text-align: right;"><strong>Frequency:</strong></td>
                        <td style="width: 35%; padding: 6px 0 6px 5px;">
                            <select name="frequency" class="doc-input">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="quarterly">Quarterly</option>
                                <option value="semi-annual" selected>SEMI-ANNUAL</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; vertical-align: top;"><strong>Name of ICT in charge:</strong></td>
                        <td style="padding: 6px 5px; vertical-align: top;">
                            <select name="assigned_to[]" multiple class="doc-select" style="height: 45px;" required>
                                @foreach($staff as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <div class="no-print" style="font-size: 8.5px; color: #64748b; margin-top: 2px;">* Hold Ctrl/Cmd to select multiple assignees</div>
                        </td>
                        <td style="padding: 6px 0; text-align: right; vertical-align: top;"><strong>Devices/Brand & Model:</strong></td>
                        <td style="padding: 6px 0 6px 5px; vertical-align: top;">
                            <div style="display: flex; gap: 8px;">
                                <input type="text" name="title" placeholder="Brand / Title" class="doc-input" style="width: 50%;" required>
                                <input type="text" name="device_model" placeholder="Model No." class="doc-input" style="width: 50%;">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0;"><strong>Property No.:</strong></td>
                        <td style="padding: 6px 5px;"><input type="text" name="property_number" class="doc-input"></td>
                        <td style="padding: 6px 0; text-align: right;"><strong>Serial No.:</strong></td>
                        <td style="padding: 6px 0 6px 5px;"><input type="text" name="serial_number" class="doc-input"></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td style="padding: 6px 0; text-align: right;"><strong>Date Performed:</strong></td>
                        <td style="padding: 6px 0 6px 5px;"><input type="date" name="last_run_date" value="{{ date('Y-m-d') }}" class="doc-input" required></td>
                    </tr>
                </table>
            </div>

            {{-- Checklist Table Layout --}}
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
                        $tasks = [
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

                    @foreach($tasks as $category => $items)
                        <tr style="background: #e9ecef;">
                            <td colspan="3" style="padding: 4px 6px;">
                                <strong>{{ $category }}</strong>
                                <button type="button" onclick="toggleCat('{{ \Illuminate\Support\Str::slug($category) }}')" class="no-print" style="float: right; font-size: 9px; cursor: pointer; padding: 2px 6px; background: #fff; border: 1px solid #ccc; border-radius: 3px;">Select All</button>
                            </td>
                        </tr>
                        @foreach($items as $task)
                            @if($category === 'HARDWARE' && $task === 'Clean the CD/DVD -ROM Drive')
                                @php $globalIndex = 19; @endphp
                            @endif
                            <tr>
                                <td style="text-align: center;">{{ $globalIndex }}</td>
                                <td>
                                    <label for="task_{{ $globalIndex }}" style="display: block; cursor: pointer; margin: 0; width: 100%;">{{ $task }}</label>
                                </td>
                                <td style="text-align: center;">
                                    <input type="checkbox" id="task_{{ $globalIndex }}" name="checklist[]" value="{{ $task }}" class="check-{{ \Illuminate\Support\Str::slug($category) }}" style="width: 14px; height: 14px; cursor: pointer; margin: 0;">
                                </td>
                            </tr>
                            @php $globalIndex++; @endphp
                        @endforeach
                    @endforeach
                    
                    <tr>
                        <td colspan="3" style="padding: 10px;">
                            <strong>Remarks:</strong>
                            <textarea name="remarks" rows="2" class="doc-input" style="border: none; border-bottom: 1px dotted #000; resize: none; margin-top: 5px;" placeholder="Enter any findings or issues here..."></textarea>
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
                            <div style="border-top: 1px solid #000; width: 85%; margin: 0 auto 3px auto;"></div>
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
    </form>
</div>

<script>
    function toggleCat(catClass) {
        const boxes = document.querySelectorAll('.check-' + catClass);
        const allSet = Array.from(boxes).every(b => b.checked);
        boxes.forEach(b => b.checked = !allSet);
    }
</script>
@endsection