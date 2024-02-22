<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DomainMinistryAgencies;

class DomainMinistryAgenciesListController extends Controller
{
    public function index()
    {
        return view('domain.index');
    }

    public function create()
    {
        return view('domain.register');
    }

    public function store(Request $request)
    {
        // Check if the form is submitted (POST request)
        if ($request->isMethod('post')) {
            // The form has been submitted

            // Validate the form data
            $validatedData = $request->validate([
                'fqdn' => 'required|string',
                'ministry' => 'required|string',
                'agency' => 'required|string',
                // 'address' => 'sometimes|string',
                'enabled' => 'required',
            ]);
            $fqdn = $validatedData['fqdn'];
            $ministry = $validatedData['ministry'];
            $agency = $validatedData['agency'];
            // $address = $validatedData['address'];
            $enabled = $validatedData['enabled'];

            // Create the fqdn with the validated data
            $fqdndata = DomainMinistryAgenciesList::create([
                'domain_name' => $fqdn,
                'ministry_name' => $ministry,
                'agencies_name' => $agency,
                // 'address' => $address,
                'enabled' => $enabled,
                'created_at' => now(),
            ]);

            // Redirect back or to another page
            return redirect()->route('domain.index')->with('success', 'FQDN added successfully');
        }
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        // Your edit logic here
    }

    public function update(Request $request, $id)
    {
        // Your update logic here
    }

    public function destroy($id)
    {
        // Your destroy logic here
    }
}
