@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-slate-200">
            <div class="bg-slate-800 px-8 py-6 text-white flex justify-between items-center">
                <h2 class="text-2xl font-bold flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    New Maintenance Schedule
                </h2>
                <a href="{{ route('maintenance.index') }}" class="text-slate-400 hover:text-white text-sm transition">Cancel</a>
            </div>

            <form action="{{ route('maintenance.store') }}" method="POST" class="p-8">
                @csrf
                
                {{-- Office & Frequency --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Office/College:</label>
                        <input type="text" name="office_college" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Frequency:</label>
                        <select name="frequency" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                            <option value="quarterly">Quarterly</option>
                            <option value="semi-annual">Semi-Annual</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>
                </div>

                {{-- ICT In Charge --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-blue-700 mb-1">ICT In Charge (Select Multiple):</label>
                    <select name="assigned_to[]" multiple class="w-full rounded-xl border-slate-300 shadow-sm min-h-[100px] focus:border-blue-500 focus:ring-blue-500" required>
                        @foreach($staff as $user)
                            <option value="{{ $user->id }}" class="p-2 hover:bg-slate-100 rounded">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-slate-400 mt-1 block">Hold Ctrl (Windows) or Cmd (Mac) to select multiple staff members.</small>
                </div>

                {{-- Device Details --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-slate-50 p-5 rounded-xl border border-slate-200 mb-6 shadow-inner">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Brand / Title</label>
                        <input type="text" name="title" placeholder="e.g. Epson L3110" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Model</label>
                        <input type="text" name="device_model" placeholder="Model No." class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Property No.</label>
                        <input type="text" name="property_number" placeholder="Property ID" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-xs font-bold text-slate-500 mb-1 uppercase tracking-wider">Serial No.</label>
                        <input type="text" name="serial_number" placeholder="Serial ID" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                {{-- Date Performed --}}
                <div class="mb-8 w-full md:w-1/2">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Date Performed / Scheduled:</label>
                    <input type="date" name="last_run_date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <h3 class="text-sm font-black text-slate-800 uppercase mb-4 border-b pb-2 tracking-widest flex items-center">
                    <span class="bg-blue-600 w-2 h-5 rounded mr-2 block"></span>
                    Task Checklist
                </h3>
                
                @php
                // Using your specific KSU-ICTO task list
                $categories = [
                    'sw' => ['label' => 'SOFTWARE APPLICATION', 'tasks' => ['Empty the Recycle Bin', 'Delete .temp files', 'Delete the files that begin with a tilde', 'Delete the .check files, and switch the file', 'Run Scandisk and defrag the drive as needed', 'Check browser history and cache files', 'Clean out Windows temporary Internet files', 'Confirm that backups are already done', 'Update drivers as needed', 'Check the Operating system and Applications', 'Update the anti-virus software if needed']],
                    'hw' => ['label' => 'HARDWARE', 'tasks' => ['Check cable connections', 'Check the power sources', 'Clean the Mouse', 'Clean the Keyboard', 'Clean the Screen/ Monitor', 'Clean the CD/DVD -ROM Drive', 'Check the Fan', 'Check the Network Hardware']],
                    'ot' => ['label' => 'OTHER DEVICES', 'tasks' => ['Check Nozzle', 'Check Head Cleaning', 'Check Power Flush Ink', 'Check ink waste pad', 'Check ink level/ toner', 'Clean Projector headlamp', 'Check projector power sources and cable', 'Check projector fan', 'Check network switches cable port', 'Check network cable crimp head (rj45 etc.)', 'Check network switches fan', 'Check network switches power sources', 'Check access point (AP) port connection', 'Check access point (AP) cable crimp head', 'Check the router cable port', 'Check router crimp head (rj45 and etc.)', 'Check network radio antenna UTP cable', 'Check network radio antenna alignment']]
                ];
                @endphp

                @foreach($categories as $id => $cat)
                <div class="mb-6 bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50 px-4 py-3 flex justify-between items-center border-b border-slate-200">
                        <span class="text-[11px] font-bold text-blue-600 uppercase tracking-wider">{{ $cat['label'] }}</span>
                        <button type="button" onclick="toggleCat('{{$id}}')" class="text-[10px] font-bold bg-white border border-slate-300 px-3 py-1.5 rounded shadow-sm hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-colors">
                            SELECT ALL
                        </button>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-3 bg-white">
                        @foreach($cat['tasks'] as $task)
                        <label class="flex items-start space-x-3 text-sm text-slate-600 cursor-pointer hover:bg-slate-50 p-1.5 rounded transition">
                            <input type="checkbox" name="checklist[]" value="{{ $task }}" class="check-{{$id}} mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 shadow-sm">
                            <span class="leading-tight">{{ $task }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                <div class="mt-8 border-t pt-6">
                    <label class="block text-sm font-bold text-slate-700 mb-1">Remarks / Findings:</label>
                    <textarea name="remarks" rows="3" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Enter any findings, issues, or specific notes here..."></textarea>
                </div>

                <div class="mt-8 flex items-center justify-end gap-4">
                    <a href="{{ route('maintenance.index') }}" class="px-6 py-2 text-slate-500 font-bold hover:text-slate-800 transition">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-10 py-3 rounded-xl font-bold shadow-lg hover:bg-blue-700 transition-all transform active:scale-95">
                        Save Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleCat(id) {
    const boxes = document.querySelectorAll('.check-' + id);
    const allSet = Array.from(boxes).every(b => b.checked);
    boxes.forEach(b => b.checked = !allSet);
}
</script>
@endsection