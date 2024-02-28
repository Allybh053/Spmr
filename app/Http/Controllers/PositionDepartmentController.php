<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Department;

class PositionDepartmentController extends Controller
{
    public function index()
    {
        $positions = Position::all();
        $departments = Department::all();
        return view('positiondepartment.index', compact('positions','departments'));
    }

    public function updateposition($id)
    {
        $position = Position::findOrFail($id);

        return view('positiondepartment.updateposition', compact('position'));
    }

    public function updatedepartment($id)
    {
        $department = Department::findOrFail($id);

        return view('positiondepartment.updatedepartment', compact('department'));
    }

    public function saveupdateposition(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'remark' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);
        $position = Position::findOrFail($id);
        $position->update([
            'name' => $request->input('name'),
            'remark' => $request->input('remark'),
            'status' => $request->input('status'),
        ]);
        return redirect()->route('positiondepartment.index')->with('success', 'Position Updated successfully.');
    }

    public function saveupdatedepartment(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'remark' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);
        $department = Department::findOrFail($id);
        $department->update([
            'name' => $request->input('name'),
            'remark' => $request->input('remark'),
            'status' => $request->input('status'),
        ]);
        return redirect()->route('positiondepartment.index')->with('success', 'Department Updated successfully.');
    }

    public function addposition(){

        return view('positiondepartment.addposition');
    }

    public function savingposition(Request $request)
    {
        $position = Position::create([
            'name' => $request->input('name'),
            'remark' => $request->input('remark'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('positiondepartment.index')->with('success', 'Position created successfully.');

    }

    public function adddepartment(){
        return view('positiondepartment.adddepartment');
    }

    public function savingdepartment(Request $request)
    {
        $department = Department::create([
            'name' => $request->input('name'),
            'remark' => $request->input('remark'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('positiondepartment.index')->with('success', 'Department created successfully.');

    }

    public function departmentdelete($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return redirect()->route('positiondepartment.index')->with('success', 'Department deleted successfully.');
    }

    public function positiondelete($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();
        return redirect()->route('positiondepartment.index')->with('success', 'Position deleted successfully.');
    }
}
