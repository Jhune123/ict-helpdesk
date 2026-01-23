<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    /**
     * Display a listing of the departments (ASC order).
     */
    public function index()
    {
        $departments = Department::orderBy('name', 'asc')->get();

        return view('departments.index', compact('departments'));
    }

    /**
     * Store a newly created department (optional use).
     */
=======
    public function index()
    {
    $departments = Department::orderBy('name')->get();
    return view('departments.index', compact('departments'));
}

>>>>>>> Stashed changes
    public function store(Request $request)
{
    {
<<<<<<< Updated upstream
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        Department::create([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department added successfully.');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::findOrFail($id);

        /**
         * 🔒 SAFETY CHECK (IMPORTANT)
         * Prevent deletion if department is already used by tickets
         * Comment this block IF you want force delete
         */
        if (method_exists($department, 'tickets') && $department->tickets()->count() > 0) {
            return redirect()
                ->route('departments.index')
                ->with('error', 'Cannot delete department. It is already used by tickets.');
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully.');
=======
        // validate input
        $request->validate([
            'name' => 'required|string|unique:departments,name'
        ]);

=======
    public function index()
    {
    $departments = Department::orderBy('name')->get();
    return view('departments.index', compact('departments'));
}

    public function store(Request $request)
{
    {
        // validate input
        $request->validate([
            'name' => 'required|string|unique:departments,name'
        ]);

>>>>>>> Stashed changes
        try {
            $department = Department::create([
                'name' => $request->name
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Department added successfully!',
                'department' => $department
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error adding department: ' . $e->getMessage()
            ], 500);
        }
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    }
}

    public function update(Request $request, string $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:departments,name,' . $department->id
        ]);

        $department->update(['name' => $request->input('name')]);

        return redirect()->route('departments.index')
                         ->with('success', 'Department updated successfully!');
    }

   public function destroy($id)
{
    $department = Department::find($id);

    if (!$department) {
        return response()->json([
            'status' => 'error',
            'message' => 'Department not found or already deleted.'
        ], 404);
    }

    if ($department->tickets()->count() > 0) {
        return response()->json([
            'status' => 'error',
            'message' => 'Cannot delete department. It is already used in tickets.'
        ], 400);
    }

    $department->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Department deleted successfully.'
    ]);
}
}
