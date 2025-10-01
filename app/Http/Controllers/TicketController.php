<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\TicketAssignedNotification;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['category'])->latest()->paginate(10);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = Category::all();
        $departments = Department::all();

        $it_personnel = User::where('role', 'it_staff')
            ->select('id', 'name')
            ->groupBy('name', 'id')
            ->orderBy('name', 'asc')
            ->get()
            ->unique('name');

        return view('tickets.create', compact('categories', 'departments', 'it_personnel'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'priority'          => 'nullable|string',
            'category_id'       => 'nullable|integer',
            'category_manual'   => 'nullable|string|max:255',
            'client_name'       => 'required|string|max:255',
            'department'        => 'nullable|string|max:255',
            'department_manual' => 'nullable|string|max:255',
            'contact_number'    => 'nullable|string|max:20',
            'assigned_to'       => 'nullable',
            'remarks'           => 'nullable|string',
        ]);

        // Handle manual category
        $categoryId = $validated['category_id'] ?? null;
        if (!$categoryId && !empty($validated['category_manual'])) {
            $category = Category::firstOrCreate(['name' => $validated['category_manual']]);
            $categoryId = $category->id;
        }

        // Handle manual department
        $departmentName = $validated['department'] ?? null;
        if (!$departmentName && !empty($validated['department_manual'])) {
            $department = Department::firstOrCreate(['name' => $validated['department_manual']]);
            $departmentName = $department->name;
        }

        $manilaNow = Carbon::now('Asia/Manila');

        $ticket = Ticket::create([
            'title'          => $validated['title'],
            'description'    => $validated['description'],
            'priority'       => $validated['priority'] ?? 'Normal',
            'category_id'    => $categoryId,
            'department'     => $departmentName,
            'assigned_to'    => $validated['assigned_to'] ?? null,
            'status'         => 'Open',
            'remarks'        => $validated['remarks'] ?? null,
            'client_name'    => $validated['client_name'],
            'contact_number' => $validated['contact_number'] ?? null,
            'date_submitted' => $manilaNow,
            'created_by'     => Auth::id(),
        ]);

        // Notify IT personnel if assigned
        if (!empty($validated['assigned_to'])) {
            $itPersonnel = User::find($validated['assigned_to']);
            if ($itPersonnel) {
                $itPersonnel->notify(new TicketAssignedNotification($ticket));
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully ✅');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['category']);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $categories = Category::all();
        $departments = Department::all();

        $it_personnel = User::where('role', 'it_staff')
            ->select('id', 'name')
            ->groupBy('name', 'id')
            ->orderBy('name', 'asc')
            ->get()
            ->unique('name');

        return view('tickets.edit', compact('ticket', 'categories', 'departments', 'it_personnel'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'priority'          => 'nullable|string',
            'category_id'       => 'nullable|exists:categories,id',
            'category_manual'   => 'nullable|string|max:255',
            'department'        => 'nullable|string|max:255',
            'department_manual' => 'nullable|string|max:255',
            'assigned_to'       => 'nullable',
            'contact_number'    => 'nullable|string|max:20',
            'remarks'           => 'nullable|string|max:500',
            'status'            => 'required|string',
        ]);

        // Handle manual category
        $categoryId = $request->category_id;
        if (!$categoryId && $request->filled('category_manual')) {
            $category = Category::firstOrCreate(['name' => $request->category_manual]);
            $categoryId = $category->id;
        }

        // Handle manual department
        $departmentName = $request->department;
        if (!$departmentName && $request->filled('department_manual')) {
            $department = Department::firstOrCreate(['name' => $request->department_manual]);
            $departmentName = $department->name;
        }

        $data = [
            'title'          => $request->title,
            'description'    => $request->description,
            'priority'       => $request->priority ?? 'Normal',
            'category_id'    => $categoryId,
            'department'     => $departmentName,
            'assigned_to'    => $request->assigned_to,
            'contact_number' => $request->contact_number,
            'remarks'        => $request->remarks,
        ];

        // Handle status and date_finished
        if ($request->filled('status')) {
            $data['status'] = $request->status;
            $data['date_finished'] = $request->status === 'Closed' ? Carbon::now('Asia/Manila') : null;
        }

        $ticket->update($data);

        // Notify IT personnel if reassigned
        if (!empty($request->assigned_to)) {
            $itPersonnel = User::find($request->assigned_to);
            if ($itPersonnel) {
                $itPersonnel->notify(new TicketAssignedNotification($ticket));
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully ✅');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully ❌');
    }

    public function mine()
    {
        $tickets = Ticket::with(['category'])
            ->where('created_by', Auth::id())
            ->latest()
            ->paginate(10);

        return view('tickets.mine', compact('tickets'));
    }

    public function byDepartment()
    {
        $tickets = Ticket::with(['category'])
            ->orderBy('department')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('department');

        return view('tickets.departments', compact('tickets'));
    }
}
