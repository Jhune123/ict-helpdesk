<div class="mt-6 border-t pt-4">
    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
        <span class="mr-2">🛠️</span> IT Internal Processing
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assign Personnel</label>
            <select name="assigned_to" id="assigned_to" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Select IT Staff --</option>
                @foreach($it_personnel as $person)
                    <option value="{{ $person->id }}" {{ old('assigned_to', $ticket->assigned_to ?? '') == $person->id ? 'selected' : '' }}>
                        {{ $person->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Ticket Status</label>
            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @php 
                    $statuses = ['Open', 'In Progress', 'Pending', 'Closed', 'Condemned'];
                    $currentStatus = old('status', $ticket->status ?? 'Open');
                @endphp
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ $currentStatus == $status ? 'selected' : '' }}>
                        {{ $status }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-4">
        <label for="remarks" class="block text-sm font-medium text-gray-700">Internal Remarks / Notes</label>
        <textarea name="remarks" id="remarks" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Diagnostic notes, parts needed, etc...">{{ old('remarks', $ticket->remarks ?? '') }}</textarea>
    </div>
</div>