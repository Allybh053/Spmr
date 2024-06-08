<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DomainMinistryAgencies;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class DomainMinistryAgenciesListController extends Controller
{
    public function index(Request $request)
    {
        $fqdndata = DomainMinistryAgencies::all();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Domain',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'view domain' => $fqdndata->take(5)->toArray(),
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);
        return view('domain.index', compact('fqdndata'));
    }

    public function create()
    {
        return view('domain.register');
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $domain = new DomainMinistryAgencies();
            $domain -> domain_name = $request->fqdn;
            $domain -> ministry_name = $request->ministry;
            $domain -> agencies_name = $request->agency;
            $domain -> remarks = $request->remarks;
            $domain -> enabled = $request->enabled;
            $domain->save();


            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity_type' => 'New Domain',
                'action' => $request->path(),
                'url' => url()->full(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'input' => json_encode([
                    'new domain' => $domain,
                    'request' => $request->except(['password']),
                ]),
                'user_agent' => $request->userAgent(),
            ]);
            DB::commit();
            return response()->json([
                'success' => '1',
                'redirect' => route('domain.index'),
                'message' => 'FQDN added Successfully Saved'
            ]);
        }catch (\Throwable $e) {
            DB::rollback();

            return response()->json([
                'error' => '1',
                'message' => 'FQDN Unsuccessfully Saved'.$e->getMessage()
            ], 400);
        }
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit(Request $request,string $id)
    {
        $fqdndata = DomainMinistryAgencies::findOrFail($id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Domain Info by Id',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'domain' => $fqdndata,
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return view('domain.edit', compact('fqdndata'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $domain = DomainMinistryAgencies::find($id);
            $domain -> domain_name = $request->fqdn;
            $domain -> ministry_name = $request->ministry;
            $domain -> agencies_name = $request->agency;
            $domain -> remarks = $request->remarks;
            $domain -> enabled = $request->enabled;
            $domain -> updated_at = now();
            $domain->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity_type' => 'Update Domain',
                'action' => $request->path(),
                'url' => url()->full(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'input' => json_encode([
                    'update domain' => $domain,
                    'request' => $request->except(['password']),
                ]),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();
            return response()->json([
                'success' => '1',
                'redirect' => route('domain.index'),
                'message' => 'FQDN Successfully Updated'
            ]);
        }catch (\Throwable $e) {
            DB::rollback();

            return response()->json([
                'error' => '1',
                'message' => 'FQDN Unsuccessfully Updated'.$e->getMessage()
            ], 400);
        }

    }

    public function delete(Request $request,$id)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Domain',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'delete domain' => DomainMinistryAgencies::findOrFail($id),
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        $domain = DomainMinistryAgencies::find($id);

        // Check if the server exists
        if (!$domain) {
            return redirect()->route('domain.index')->with('error', 'Domain not found.');
        }

        // Perform the soft delete
        $domain->delete();

        // Redirect with success message
        return redirect()->route('domain.index')->with('error', 'FQDN successfully deleted');

    }
}
