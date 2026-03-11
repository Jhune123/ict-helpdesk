<div>
    <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
    <input type="text" name="client_name" id="client_name" value="{{ auth()->check() ? auth()->user()->name : old('client_name') }}" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-{{ $btnColor }}-500 focus:border-{{ $btnColor }}-500" required>
</div>

<div>
    <label for="department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
    <select name="department" id="department" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-{{ $btnColor }}-500 focus:border-{{ $btnColor }}-500">
        <option value="">-- Select Department --</option>
        @foreach($departments as $dept)
            <option value="{{ $dept->name }}">{{ $dept->name }}</option>
        @endforeach
    </select>
</div>

<div>
    <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
    <select name="priority" id="priority" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-{{ $btnColor }}-500 focus:border-{{ $btnColor }}-500">
        <option value="Low">Low</option>
        <option value="Normal" selected>Normal</option>
        <option value="High">High</option>
        <option value="Urgent">Urgent</option>
        <option value="Critical">Critical</option>
    </select>
</div>

@hasanyrole('admin|it_staff')
<div class="mt-6 border-t border-gray-200 pt-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
        <span class="mr-2">🛡️</span> IT Internal Processing
    </h3>
    
    <div class="mb-4">
        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
        <select name="status" id="status" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-{{ $btnColor }}-500 focus:border-{{ $btnColor }}-500">
            <option value="Open">Open</option>
            <option value="In Progress">In Progress</option>
            <option value="Pending">Pending</option>
            <option value="Closed">Closed</option>
            <option value="Condemned">Condemned</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
        <select name="category_id" id="category_id" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 mb-2 focus:ring-{{ $btnColor }}-500 focus:border-{{ $btnColor }}-500">
            <option value="">-- Select Category --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <input type="text" name="category_manual" placeholder="Or enter new category" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-{{ $btnColor }}-500 focus:border-{{ $btnColor }}-500">
    </div>

    <div class="mb-4">
        <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assign To (IT Personnel)</label>
        <select name="assigned_to" id="assigned_to" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-{{ $btnColor }}-500 focus:border-{{ $btnColor }}-500">
            <option value="">-- Select IT Personnel --</option>
            @foreach($it_personnel as $person)
                <option value="{{ $person->id }}">{{ $person->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="remarks" class="block text-sm font-medium text-gray-700 mb-1">Internal Remarks</label>
        <textarea name="remarks" id="remarks" rows="2" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-{{ $btnColor }}-500 focus:border-{{ $btnColor }}-500"></textarea>
    </div>
</div>
@else
    <input type="hidden" name="status" value="Open">
@endhasanyrole

<div class="pt-6">
    <button type="submit" class="w-full bg-{{ $btnColor }}-600 hover:bg-{{ $btnColor }}-700 text-white font-bold py-3 px-6 rounded-md shadow transition duration-150 ease-in-out">
        {{ $btnText }}
    </button>
</div>