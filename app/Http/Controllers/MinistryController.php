<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ministry;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class MinistryController extends Controller
{
    public function index(Request $request)
    {
        $ministrys = Ministry::get();
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Ministry',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'ministry' => $ministrys->take(5)->toArray(),
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

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

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Update Ministry',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'ministry' => $position,
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
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

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'New Ministry',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'ministry' => $position,
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('ministry.index')->with('success', 'Ministry created successfully.');

    }

    public function ministrydelete(Request $request,$id)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Delete Ministry',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'ministry' => Ministry::findOrFail($id),
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);


        $ministry = Ministry::findOrFail($id);
        $ministry->delete();
        return redirect()->route('ministry.index')->with('success', 'Ministry deleted successfully.');
    }
}
