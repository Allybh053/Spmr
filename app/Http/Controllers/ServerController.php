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
        // $user = Auth::user();
        // $dataservers = Server::whereNotNull('status')->orderBy('id', 'asc')->get();
        $dataservers  = Server::whereIn('status', [1, 0])
            ->with(['subServers' => function($query) {
                $query->select('id', 'hostname', 'ipaddress', 'status_cluster', 'master_id','server_type','subhostname');
            }])
            ->orderBy('id')
            ->get();

        // Transform the data as needed
        // $result = [];
        // foreach ($servers as $master) {
        //     foreach ($master->subServers as $sub) {
        //         $result[] = [
        //             'master' => $master->hostname,
        //             'sub' => $sub->hostname,
        //             'subip' => $sub->ipaddress,
        //             'subcluster' => $sub->status_cluster
        //         ];
        //     }
        // }

        // return response()->json($result);

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
        $validatedData = $request->validate([
            'hostname' => 'required|string',
            'ipaddress' => 'nullable|ip',
            'primary1name' => 'nullable|string',
            'primary1ip' => 'nullable|ip',
            'primary2name' => 'nullable|string',
            'primary2ip' => 'nullable|ip',
        ]);

        DB::beginTransaction();

        try {
            // Insert the master server
            $master = new Server();
            $master->hostname = $validatedData['hostname'];
            $master->ipaddress = $validatedData['ipaddress'];
            $master->status = 1;  // Assuming status = 1 indicates a master server
            $master->server_type = 0; // Assuming server_type = 0 indicates a master server
            $master->save();

            // Check and insert primary1 (sub-server 1)
            if (!empty($validatedData['primary1name']) && !empty($validatedData['primary1ip'])) {
                $primary1 = new Server();
                $primary1->subhostname = $validatedData['primary1name'];
                $primary1->ipaddress = $validatedData['primary1ip'];
                $primary1->status = null;  // Assuming null status indicates a sub-server
                $primary1->server_type = 1; // Assuming server_type = 1 indicates a sub-server
                $primary1->status_cluster = 0; // Assuming status_cluster = 1 indicates active cluster
                $primary1->master_id = $master->id;
                $primary1->save();
            }

            // Check and insert primary2 (sub-server 2)
            if (!empty($validatedData['primary2name']) && !empty($validatedData['primary2ip'])) {
                $primary2 = new Server();
                $primary2->subhostname = $validatedData['primary2name'];
                $primary2->ipaddress = $validatedData['primary2ip'];
                $primary2->status = null;  // Assuming null status indicates a sub-server
                $primary2->server_type = 1; // Assuming server_type = 1 indicates a sub-server
                $primary2->status_cluster = 0; // Assuming status_cluster = 1 indicates active cluster
                $primary2->master_id = $master->id;
                $primary2->save();
            }

            // Commit the transaction
            DB::commit();

        $roles = Role::get();

        foreach ($roles as $role) {
            DB::insert('INSERT INTO role_servers (role_id, server_id) VALUES (?, ?)', [$role->id, $master->id]);

        }

            return redirect()->route('server.index')->with('success', 'Server data saved successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            return redirect()->route('server.index')->with('error', 'Failed to save server data. Please try again.');
        }

        // $roles = Role::get();

        // foreach ($roles as $role) {
        //     DB::insert('INSERT INTO role_servers (role_id, server_id) VALUES (?, ?)', [$role->id, $server->id]);

        // }

        // return redirect()->route('server.index')->with('success', 'Server details added successfully');
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

        $subserver = Server::where('master_id',$server->id)->get();

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
        // Validate the incoming request data
        $validatedData = $request->validate([
            'hostname' => 'required|string',
            'ipaddress' => 'nullable|ip',
            'primary1name' => 'nullable|string',
            'primary1ip' => 'nullable|ip',
            'primary2name' => 'nullable|string',
            'primary2ip' => 'nullable|ip',
        ]);

        // Find the master server by ID
        $server = Server::find($id);

        if (!$server) {
            return redirect()->route('server.index')->with('error', 'Server not found');
        }

        // Update the master server details
        $server->hostname = $validatedData['hostname'];
        $server->ipaddress = $validatedData['ipaddress'];
        $server->save();

        // Handle the sub-servers update
        $subServersData = [
            [
                'subhostname' => $validatedData['primary1name'],
                'ipaddress' => $validatedData['primary1ip'],
            ],
            [
                'subhostname' => $validatedData['primary2name'],
                'ipaddress' => $validatedData['primary2ip'],
            ]
        ];

        foreach ($subServersData as $subData) {
            if (!empty($subData['subhostname']) && !empty($subData['ipaddress'])) {
                // Find the sub-server by master ID and subhostname
                $subServer = Server::where('master_id', $server->id)
                                   ->where('subhostname', $subData['subhostname'])
                                   ->first();

                if ($subServer) {
                    // Update the existing sub-server
                    $subServer->ipaddress = $subData['ipaddress'];
                    $subServer->save();
                } else {
                    // Create a new sub-server if it doesn't exist
                    Server::create([
                        'hostname' => $subData['subhostname'], // Assuming 'hostname' field
                        'subhostname' => $subData['subhostname'],
                        'ipaddress' => $subData['ipaddress'],
                        'server_type' => 1,  // Assuming server_type = 1 indicates a sub-server
                        'status_cluster' => 1, // Assuming status_cluster = 1 indicates active cluster
                        'master_id' => $server->id,
                    ]);
                }
            }
        }

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

    public function deleteServer(Request $request,$id)
    {
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

    public function checkHostnameUnique(Request $request)
    {
        $hostname = $request->input('hostname');

        $exists = Server::where('hostname', $hostname)
                    ->whereIn('status', [0, 1])
                    ->exists();

        return response()->json(['exists' => $exists]);
    }


}
