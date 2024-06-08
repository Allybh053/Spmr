<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agency;
use App\Models\Ministry;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AgencyController extends Controller
{
    public function index(Request $request)
    {
        $agencies = Agency::select('agencies.*','ministrys.name as ministry_name')
        ->leftJoin('ministrys','ministrys.id',DB::raw('CAST(agencies.ministry AS bigint)'))
        ->get();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Agency',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'agencies' => $agencies->take(5)->toArray(),
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return view('agency.index', compact('agencies'));
    }

    public function addagency(Request $request)
    {
        $ministry = Ministry::get();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Get Ministry Information',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'ministry' => $ministry->take(5)->toArray(),
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);
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

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Saving Agency',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'new agency' => $agency,
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('agency.index')->with('success', 'Agency created successfully.');

    }

    public function updateagency(Request $request,$id)
    {
        $agency = Agency::findOrFail($id);

        $ministry1 = Ministry::where('id',$agency->ministry)->first();

        $ministry = Ministry::get();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Agency Info by Id',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'agency' => $agency->take(5)->toArray(),
                'ministry' => $ministry->take(5)->toArray(),
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

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

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Update Agency',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'agency update' => $agency,
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);


        return redirect()->route('agency.index')->with('success', 'Agency Updated successfully.');
    }

    public function agencydelete(Request $request,$id)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Delete Agency',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'agency delete' => Agency::findOrFail($id),
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        $agency = Agency::findOrFail($id);
        $agency->delete();


        return redirect()->route('agency.index')->with('success', 'Agency deleted successfully.');
    }
}
