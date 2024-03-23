<?php

namespace App\Http\Controllers;

use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $dataservers = Server::orderBy('id', 'asc')->get();

        return view('server.index',compact('dataservers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function updateStatus(Request $request)
    {
        $server = Server::find($request->id);
        $server->status = $request->status;
        $server->save();

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function create()
    {
        $user = Auth::user();

        return view('server.create');
    }

    public function uploadServer(Request $request)
    {
        // $request->validate([
        //     'hostname' => 'required|string|max:255',
        //     'ipaddress' => 'required|ip',
        //     'sshusername' => 'required|string|max:255',
        //     'sshkey' => 'required|mimes:pem|max:1024', // Adjust the file validation rules
        // ]);
        // Handle other server details and database storage here

        $file = $request->file('sshkey');
        $fileName = $file->getClientOriginalName();

        // Move the uploaded file to the 'key' directory at the same level as the 'app' folder
        $file->move(storage_path('../Storage/key'), $fileName);

        // Save server details and file path in the database
        $server = Server::create([
            'hostname' => $request->input('hostname'),
            'ipaddress' => $request->input('ipaddress'),
            'sshusername' => $request->input('sshusername'),
            'status' => 0,
            'sshkey' => $fileName, // Save the path relative to the storage directory
        ]);

        $roles = Role::get();

        foreach ($roles as $role) {
            DB::insert('INSERT INTO role_servers (role_id, server_id) VALUES (?, ?)', [$role->id, $server->id]);

        }
        
        return redirect()->route('server.index')->with('success', 'Server details added successfully');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();

        $server = Server::findOrFail($id);

        return view('server.edit',compact('server'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Server $server)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'hostname' => 'required|string|max:255',
            'ipaddress' => 'required|string',
            'sshusername' => 'required|string|max:255',
            'sshkey' => 'sometimes|mimes:pem|max:1024', // Allow file replacement
        ]);

        $server = Server::find($id);

        if (!$server) {
            return redirect()->route('server.index')->with('message', 'Server not found');
        }

        // Handle other server details update here
        $server->hostname = $request->input('hostname');
        $server->ipaddress = $request->input('ipaddress');
        $server->sshusername = $request->input('sshusername');

        // Check if a new file is provided for replacement
        if ($request->hasFile('sshkey')) {
            $newFile = $request->file('sshkey');
            $newFileName = $newFile->getClientOriginalName();

            // Move the new file to the 'key' directory
            $newFile->move(storage_path('../key'), $newFileName);

            // Update the database with the new file path
            $server->sshkey = $newFileName;
        }

        $server->save();

        return redirect()->route('server.index')->with('success', 'Server details updated successfully');

    }

    public function downloadFile($id)
    {
        $server = Server::find($id);

        if (!$server || empty($server->sshkey)) {
            return response()->json(['error' => 'File not found1'], 404);
        }

        $filePath = storage_path('/key/' . basename($server->sshkey));


        // Check if the file exists
        if (file_exists($filePath)) {
            // Return the file as a downloadable response
            return response()->download($filePath, basename($server->sshkey));
        } else {
            // File not found
            return response()->json(['error' => 'File not found2'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Server $server)
    {
        //
    }

    public function deleteServer($id)
    {
        // Retrieve the server by ID
        $server = Server::find($id);

        // Check if the server exists
        if (!$server) {
            return redirect()->route('server.index')->with('error', 'Server not found.');
        }

        // Perform the soft delete
        $server->delete();

        // Redirect with success message
        return redirect()->route('server.index')->with('error', 'Server deleted successfully!.');
    }


}
