<?php

namespace App\Http\Controllers;

use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dataservers = Server::whereNotNull('status')->orderBy('id', 'asc')->get();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Server',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'server' => $dataservers->take(5)->toArray(),
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

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

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Update Server',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'server' => $server,
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function create()
    {
        $user = Auth::user();

        return view('server.create');
    }

    public function uploadServer(Request $request)
    {
        $data = $request->all();
        // Save server details and file path in the database
        $server = Server::create([
            'hostname' => $request->input('hostname'),
            'ipaddress' => $request->input('ipaddress'),
            'sshusername' => "null",
            'status' => 0,
            'sshkey' => "null",
            'server_type' => 0,
            'updated_at' => null,
        ]);

        // // Insert primary1name
        // Server::create([
        //     'hostname' => $data['primary1name'],
        //     'ipaddress' => $data['primary1ip'],
        //     'sshusername' => "null",
        //     'sshkey' => "null",
        //     'server_type' => 1,
        //     'master_id'=>$request->input('hostname'),
        // ]);

        // // Insert primary2name
        // Server::create([
        //     'hostname' => $data['primary2name'],
        //     'ipaddress' => $data['primary2ip'],
        //     'sshusername' => "null",
        //     'sshkey' => "null",
        //     'server_type' => 1,
        //     'master_id'=>$request->input('hostname'),
        // ]);

        $roles = Role::get();

        foreach ($roles as $role) {
            DB::insert('INSERT INTO role_servers (role_id, server_id) VALUES (?, ?)', [$role->id, $server->id]);

        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'New Server',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'server' => $server,
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

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

        $subserver = Server::where('master_id',$server->hostname)->get();

        return view('server.edit',compact('server','subserver'));
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
            // 'sshusername' => 'required|string|max:255',
            // 'sshkey' => 'sometimes|mimes:pem|max:1024', // Allow file replacement
        ]);

        $server = Server::find($id);

        if (!$server) {
            return redirect()->route('server.index')->with('message', 'Server not found');
        }

        // Handle other server details update here
        $server->hostname = $request->input('hostname');
        $server->ipaddress = $request->input('ipaddress');
        // $server->sshusername = $request->input('sshusername');

        // // Check if a new file is provided for replacement
        // if ($request->hasFile('sshkey')) {
        //     $newFile = $request->file('sshkey');
        //     $newFileName = $newFile->getClientOriginalName();

        //     // Move the new file to the 'key' directory
        //     $newFile->move(storage_path('../key'), $newFileName);

        //     // Update the database with the new file path
        //     $server->sshkey = $newFileName;
        // }

        $server->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Update Server',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'server' => $server,
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('server.index')->with('success', 'Server details updated successfully');

    }

    public function downloadFile(Request $request,$id)
    {
        $server = Server::find($id);

        if (!$server || empty($server->sshkey)) {
            return response()->json(['error' => 'File not found1'], 404);
        }

        $filePath = storage_path('/key/' . basename($server->sshkey));

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Download File',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'server' => $server,
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

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

    public function deleteServer(Request $request,$id)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Delete Server',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'server' => Server::find($id),
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        // Retrieve the server by ID
        $server = Server::find($id);

        // Check if the server exists
        if (!$server) {
            return response()->json(['error' => 'Server not found.'], 404);
        }

        // Perform the soft delete
        $server->delete();

        // Redirect with success message
        return response()->json(['message' => 'Server deleted successfully.']);
    }


}
