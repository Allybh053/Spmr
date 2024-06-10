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
        // $user = Auth::user();
        // $dataservers = Server::whereNotNull('status')->orderBy('id', 'asc')->get();
        $dataservers  = Server::whereIn('status', [1, 0])
        // ->whereHas('subServers', function ($query) {
        //     $query->whereNotNull('master_id');
        // })
        ->with(['subServers' => function ($query) {
            $query->select('id', 'hostname', 'ipaddress', 'status_cluster', 'master_id', 'server_type', 'subhostname');
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
        $getServer = Server::whereIn('status', [1, 0])
        ->whereNull('subhostname')
        ->orderBy('id')
        ->pluck('hostname','id');

        return view('server.create',compact('getServer'));
    }

    public function uploadServer(Request $request)
    {
        $validatedData = $request->validate([
            'hostname' => 'required',
            'ipaddress' => 'nullable|ip',
            'primary1name' => 'nullable|string',
            'primary1ip' => 'nullable|ip',
            'primary2name' => 'nullable|string',
            'primary2ip' => 'nullable|ip',
        ]);

        DB::beginTransaction();

        try {

            $getServer =Server::find($validatedData['hostname']);

            // Check and insert primary1 (sub-server 1)
            if (!empty($validatedData['primary1name']) && !empty($validatedData['primary1ip'])) {
                $existingHostname = Server::where('subhostname', $validatedData['primary1name'])->first();

             if (isset($existingHostname) && $existingHostname != null) {
                return redirect()->route('server.create',['id'=>$getServer->id])->with('error', 'Subhostname already exists for another server. Please select another subhostname.');
             }

             // Check if the IP address already exists for the provided serverId
             $existingIpAddress = Server::where('ipaddress', $validatedData['primary1ip'])->first();

             if ($existingIpAddress != null) {
                return redirect()->route('server.create',['id'=>$getServer->id])->with('error', 'IP address already exists for another server. Please select another IP address.');
             }

                DB::table('servers')->insert([
                    'hostname' => $getServer->hostname,
                    'subhostname' => $validatedData['primary1name'],
                    'ipaddress' => $validatedData['primary1ip'],
                    'status' => null, // Assuming null status indicates a sub-server
                    'server_type' => 1, // Assuming server_type = 1 indicates a sub-server
                    'status_cluster' => 0, // Assuming status_cluster = 1 indicates active cluster
                    'master_id' => $getServer->id,
                ]);

                $insertedId = DB::getPdo()->lastInsertId();

                $roles = Role::get();

                foreach ($roles as $role) {
                    DB::insert('INSERT INTO role_servers (role_id, server_id) VALUES (?, ?)', [$role->id,$insertedId]);

                }
            }

            // Check and insert primary2 (sub-server 2)
            if (!empty($validatedData['primary2name']) && !empty($validatedData['primary2ip'])) {
                $existingHostname = Server::where('subhostname', $validatedData['primary2name'])->first();

                if (isset($existingHostname) && $existingHostname != null) {
                   return redirect()->route('server.create',['id'=>$getServer->id])->with('error', 'Subhostname already exists for another server. Please select another subhostname.');
                }

                // Check if the IP address already exists for the provided serverId
                $existingIpAddress = Server::where('ipaddress', $validatedData['primary2ip'])->first();

                if ($existingIpAddress != null) {
                   return redirect()->route('server.create',['id'=>$getServer->id])->with('error', 'IP address already exists for another server. Please select another IP address.');
                }

                DB::table('servers')->insert([
                    'hostname' => $getServer->hostname,
                    'subhostname' => $validatedData['primary2name'],
                    'ipaddress' => $validatedData['primary2ip'],
                    'status' => null, // Assuming null status indicates a sub-server
                    'server_type' => 1, // Assuming server_type = 1 indicates a sub-server
                    'status_cluster' => 0, // Assuming status_cluster = 1 indicates active cluster
                    'master_id' => $getServer->id,
                ]);

                $insertedId = DB::getPdo()->lastInsertId();

                $roles2 = Role::get();

                foreach ($roles2 as $role) {
                    DB::insert('INSERT INTO role_servers (role_id, server_id) VALUES (?, ?)', [$role->id,$insertedId]);

                }
            }

            // Commit the transaction
            DB::commit();




        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'activity_type' => 'Update Server',
                'action' => $request->path(),
                'url' => url()->full(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'input' => json_encode([
                    'server' => $e,
                    'request' => $request->except(['password']),
                ]),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('server.index')->with('error', 'Failed to save server data. Please try again.');
        }

        // $roles = Role::get();

        // foreach ($roles as $role) {
        //     DB::insert('INSERT INTO role_servers (role_id, server_id) VALUES (?, ?)', [$role->id, $server->id]);

        // }

        // return redirect()->route('server.index')->with('success', 'Server details added successfully');
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'New Server',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'server' => $validatedData,
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

        $subserver = Server::where('master_id',$server->id)->get();


        $getServer = Server::whereIn('status', [1, 0])
        ->whereNull('subhostname')
        ->orderBy('id')
        ->pluck('hostname','id');

        return view('server.edit',compact('server','subserver','getServer'));
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
            'hostname' => 'required',
            'ipaddress' => 'nullable|ip',
            // 'primary1name' => 'nullable|string',
            // 'primary1ip' => 'nullable|ip',
            // 'primary2name' => 'nullable|string',
            // 'primary2ip' => 'nullable|ip',
            'subServers.*.name' => 'nullable|string',
            'subServers.*.ip' => 'nullable|string',

        ]);

        // Find the master server by ID
        $server = Server::find($id);

        if (!$server) {
            return redirect()->route('server.index')->with('error', 'Server not found');
        }

        $getServer =Server::find( $validatedData['hostname']);

        $subServers = $request->input('subServers', []);

        $subServersData=[];
        foreach($subServers as $subs){
            $serverSub = [
                'id'=>$subs['id'] ?? null,
            'hostname' => $getServer->hostname,
            'master_id' => $getServer->id,
            'subhostname' =>$subs['name'],
            'ipaddress' => $subs['ip'],
            ];

            array_push($subServersData,$serverSub);
        }

        // Handle the sub-servers update
        // $subServersData = [
        //     [
        //         'hostname' => $getServer->hostname,
        //         'master_id' => $getServer->id,
        //         'subhostname' => $validatedData['primary1name'],
        //         'ipaddress' => $validatedData['primary1ip'],
        //     ],
        //     [
        //         'hostname' => $getServer->hostname,
        //         'master_id' => $getServer->id,
        //         'subhostname' => $validatedData['primary2name'],
        //         'ipaddress' => $validatedData['primary2ip'],
        //     ]
        // ];


        // return redirect()->route('server.index')->with('success', 'Server details updated successfully');

        foreach ($subServersData as $subData) {


            if ($subData['id'] != null && (!empty($subData['subhostname']) && !empty($subData['ipaddress']))) {
                // Find the sub-server by master ID and subhostname


                $existingHostname = Server::where('subhostname', $subData['subhostname'])
                ->where('id', '!=', $subData['id'])
                ->first();

                if (isset($existingHostname) && $existingHostname != null) {
                    return redirect()->route('server.edit',['id'=>$getServer->id])->with('error', 'Subhostname already exists for another server. Please select another subhostname.');
                }

                // Check if the IP address already exists for the provided serverId
                $existingIpAddress = Server::where('ipaddress', $subData['ipaddress'])->where('id', '!=', $subData['id'])
                ->first();

                if ($existingIpAddress != null) {
                    return redirect()->route('server.edit',['id'=>$getServer->id])->with('error', 'IP address already exists for another server. Please select another IP address.');
                }

                // Check if the provided serverId exists
                $existingServer = Server::find($subData['id']);

                if ($existingServer) {
                    // If the server exists, update the server details
                        $existingServer->subhostname = $subData['subhostname'];
                        $existingServer->ipaddress = $subData['ipaddress'];
                        $existingServer->save();

                }


                } else {

                    if (!Server::where('subhostname', $subData['subhostname'])->exists() && !Server::where('ipaddress',$subData['ipaddress'])->exists()) {
                        // Create a new sub-server if it doesn't exist
                        DB::table('servers')->insert([
                            'hostname' => $subData['hostname'],
                            'subhostname' => $subData['subhostname'],
                            'ipaddress' => $subData['ipaddress'],
                            'status'=>1,
                            'server_type' => 1,  // Assuming server_type = 1 indicates a sub-server
                            //'status_cluster' => 1, // Assuming status_cluster = 1 indicates active cluster
                            'master_id' => $server->id,
                        ]);

                        $insertedId = DB::getPdo()->lastInsertId();

                        $roles2 = Role::get();

                        foreach ($roles2 as $role) {
                            DB::insert('INSERT INTO role_servers (role_id, server_id) VALUES (?, ?)', [$role->id,$insertedId]);

                        }
                }


                }
            }



        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Update Server',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'server' => $validatedData,
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

    public function checkHostnameUnique(Request $request)
    {
        $hostname = $request->input('hostname');

        $exists = Server::where('id', $hostname)
                    ->whereIn('status', [0, 1])
                    ->exists();

        return response()->json(['exists' => $exists]);
    }


    public function virtualServer(Request $request){
        $messages = [
            'hostname.required' => 'Hostname Required.',
            'hostname.max'      => 'Hostname must not exceed 255 characters.'
        ];

        $validation = [
            'hostname' => 'required|max:255'
        ];

        $request->validate($validation, $messages);


        $hostname = $request->hostname;
        $ipaddress = $request->ipaddress;
        $serverId = $request->serverId;

// Check if serverId is not null
        if ($serverId !== null) {

            // Check if the hostname already exists for the provided serverId
            $existingHostname = Server::where('hostname', $hostname)->where('id', '!=', $serverId)->first();

            if (isset($existingHostname) && $existingHostname != null) {
                return response()->json(['error' => '1', 'message' => 'Hostname already exists for another server. Please select another hostname.'], 401);
            }

            // Check if the IP address already exists for the provided serverId
            $existingIpAddress = Server::where('ipaddress', $ipaddress)->where('id', '!=', $serverId)->first();

            if ($existingIpAddress != null) {
                return response()->json(['error' => '1', 'message' => 'IP address already exists for another server. Please select another IP address.'], 401);
            }

            // Check if the provided serverId exists
            $existingServer = Server::find($serverId);

            if ($existingServer) {
                // If the server exists, update the server details
                $existingServer->hostname = $hostname;
                $existingServer->ipaddress = $ipaddress;
                $existingServer->save();


                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'activity_type' => 'Update Virtual Server',
                    'action' => $request->path(),
                    'url' => url()->full(),
                    'method' => $request->method(),
                    'ip_address' => $request->ip(),
                    'input' => json_encode([
                        'server' => 'Updating New Virtual Server',
                        'request' => $request->except(['password']),
                    ]),
                    'user_agent' => $request->userAgent(),
                ]);

                return response()->json(['success' => '1', 'redirect' => route('server.index'), 'message' => 'Virtual Host updated successfully.']);
            }
        } else {

             // Check if the hostname already exists for the provided serverId
             $existingHostname = Server::where('hostname', $hostname)->first();

             if (isset($existingHostname) && $existingHostname != null) {
                 return response()->json(['error' => '1', 'message' => 'Hostname already exists for another server. Please select another hostname.'], 401);
             }

             // Check if the IP address already exists for the provided serverId
             $existingIpAddress = Server::where('ipaddress', $ipaddress)->first();

             if ($existingIpAddress != null) {
                 return response()->json(['error' => '1', 'message' => 'IP address already exists for another server. Please select another IP address.'], 401);
             }


            // Get the maximum ID including soft-deleted records
            $maxId = Server::withTrashed()->max('id');

            // Increment the maximum ID to get the new ID for insertion
            $newId = $maxId + 1;

            // If the serverId is null and the hostname/IP address doesn't exist, create a new record
            if (!Server::where('hostname', $hostname)->exists() && !Server::where('ipaddress', $ipaddress)->exists()) {
                $server = new Server;
                $server->id = $newId;
                $server->status = 1;
                $server->server_type = 0;
                $server->hostname = $hostname;
                $server->ipaddress = $ipaddress;
                $server->save();

                $roles2 = Role::get();

                foreach ($roles2 as $role) {
                    DB::insert('INSERT INTO role_servers (role_id, server_id) VALUES (?, ?)', [$role->id,$server->id]);

                }
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'activity_type' => 'Create Virtual Server',
                    'action' => $request->path(),
                    'url' => url()->full(),
                    'method' => $request->method(),
                    'ip_address' => $request->ip(),
                    'input' => json_encode([
                        'server' => 'Creating New Virtual Server',
                        'request' => $request->except(['password']),
                    ]),
                    'user_agent' => $request->userAgent(),
                ]);

                return response()->json(['success' => '1', 'redirect' => route('server.index'), 'message' => 'Virtual Host saved successfully.']);
            }
        }


 }

    public function getIp(Request $request)
    {
        $hostname = $request->input('hostname');

        // Assuming your Server model has a relationship named 'ipAddresses' to fetch IP addresses
        $server = Server::where('id', $hostname)->first();

        return response()->json(['ip_address' => $server->ipaddress]);
    }

    public function checkServer($serverId)
    {
        // Check if the serverId exists in the master_id column and deleted_at column is null
        $serverExists = Server::where('master_id', $serverId)->whereNull('deleted_at')->exists();

        return response()->json(['exists' => $serverExists]);
    }

}
