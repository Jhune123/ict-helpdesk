<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
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
    public function store(Request $request)
    {
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
    }
}
