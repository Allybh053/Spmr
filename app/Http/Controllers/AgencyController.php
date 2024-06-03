<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agency;
use App\Models\Ministry;

class AgencyController extends Controller
{
    public function index()
    {
        $agencies = Agency::get();
        return view('agency.index', compact('agencies'));
    }

    public function addagency()
    {
        $ministry = Ministry::get();
        return view('agency.addagency', compact('ministry'));
    }

    public function savingagency(Request $request)
    {
        $agency = Agency::create([
            'ministry' => $request->input('ministry'),
            'code_name' => $request->input('code_name'),
            'name' => $request->input('name'),
            'remark' => $request->input('remark'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('agency.index')->with('success', 'Agency created successfully.');

    }

    public function updateagency($id)
    {
        $agency = Agency::findOrFail($id);

        $ministry1 = Ministry::where('id',$agency->ministry)->first();

        $ministry = Ministry::get();

        return view('agency.updateagency', compact('agency','ministry','ministry1'));
    }

    public function saveupdateagency(Request $request, $id)
    {
        $request->validate([
            'ministry' => 'nullable|string',
            'name' => 'required|string',
            'code_name' => 'nullable|string',
            'remark' => 'nullable|string',
            'status' => 'required|in:0,1',
        ]);
        $agency = Agency::findOrFail($id);
        $agency->update([
            'ministry' => $request->input('ministry'),
            'code_name' => $request->input('code_name'),
            'name' => $request->input('name'),
            'remark' => $request->input('remark'),
            'status' => $request->input('status'),
        ]);
        return redirect()->route('agency.index')->with('success', 'Agency Updated successfully.');
    }

    public function agencydelete($id)
    {
        $agency = Agency::findOrFail($id);
        $agency->delete();
        return redirect()->route('agency.index')->with('success', 'Agency deleted successfully.');
    }
}
