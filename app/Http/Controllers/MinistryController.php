<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ministry;

class MinistryController extends Controller
{
    public function index()
    {
        $ministrys = Ministry::get();
        return view('ministry.index', compact('ministrys'));
    }

    public function addministry()
    {

        return view('ministry.addministry');
    }

    public function updateministry($id)
    {
        $ministry = Ministry::findOrFail($id);

        return view('ministry.updateministry', compact('ministry'));
    }

    public function saveupdateministry(Request $request, $id)
    {
        $request->validate([
            'code_name' => 'nullable|string',
            'name' => 'required|string',
            'remark' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);
        $position = Ministry::findOrFail($id);
        $position->update([
            'code_name' => $request->input('code_name'),
            'name' => $request->input('name'),
            'remark' => $request->input('remark'),
            'status' => $request->input('status'),
        ]);
        return redirect()->route('ministry.index')->with('success', 'Ministry Updated successfully.');
    }

    public function savingministry(Request $request)
    {
        $position = Ministry::create([
            'code_name' => $request->input('code_name'),
            'name' => $request->input('name'),
            'remark' => $request->input('remark'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('ministry.index')->with('success', 'Ministry created successfully.');

    }

    public function ministrydelete($id)
    {
        $ministry = Ministry::findOrFail($id);
        $ministry->delete();
        return redirect()->route('ministry.index')->with('success', 'Ministry deleted successfully.');
    }
}
