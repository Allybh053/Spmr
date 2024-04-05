<?php

namespace App\Http\Controllers\Setup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Mail;
use Validator;
use Auth;
use Illuminate\Support\Facades\View;
use App\Models\Server;
use App\Models\RoleServer;

use Illuminate\Support\Facades\Artisan;


class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::get()->groupBy(['group','sub_group']);
        $dataServers = Server::where('status', 1)->orderBy('id', 'asc')->get();
        $roleServer = RoleServer::get();

        return view('role.index', compact('roles', 'permissions','dataServers','roleServer'));
    }

    public function create(Request $request)
    {
        $messages = [
            'name.required' => 'Namespace Required.',
            'name.max'      => 'Namespace must not exceed 255 characters.'
        ];

        $validation = [
            'name' => 'required|max:255|unique:roles,name,' . ($request->roleId ? $request->roleId : 'NULL') . ',id'
        ];

        $request->validate($validation, $messages);

        // Reject if role name equal to developer or superadmin
        if(trim(strtolower($request->name)) == 'developer' || trim(strtolower($request->name)) == 'superadmin') {
            return response()->json(['error' => '1', 'message' => 'Please Select another Role Name.'], 401);
        }

        if($request->roleId) {
            $role = Role::find($request->roleId);
        } else {
            $role = new Role;
        }
        $role->name = $request->name;
        $role->save();

        $permissions = Permission::where('name', 'LIKE', 'lihat_%')->get();
        $role->syncPermissions($permissions);

        return response()->json(['success' => '1', 'redirect' => route('setup.role.index'), 'message' => 'Role saved successfully.']);
    }

    public function storePermission(Request $request)
    {
        $roleId = $request->id;
        $permissionsArray = $request->permissions; // Assuming $request->permissions contains nested arrays
        $serverArray = $request->servers;

        // Find the role by ID
        $role = Role::find($roleId);

            // Detach all existing permissions associated with the role
            DB::table('role_has_permissions')->where('role_id', $roleId)->delete();

            // Iterate through the top-level array
            if (!is_null($permissionsArray)) {
            foreach ($permissionsArray as $subGroups) {

                        DB::table('role_has_permissions')->insert([
                            'role_id' => $roleId,
                            'permission_id' => $subGroups,
                        ]);
            }
        }

        $roleServer = RoleServer::where('role_id',$roleId)->delete();

        if (!is_null($serverArray)) {
            foreach ($serverArray as $server) {
                DB::insert('INSERT INTO role_servers (role_id, server_id) VALUES (?, ?)', [$roleId, $server]);
            }
        }

        $this->clearCacheAndAutoload();



        return response()->json(['success' => '1', 'redirect' => route('setup.role.index'), 'message' => 'Permissions saved successfully.']);
    }

    public function delete(Request $request)
    {
        $role = Role::find($request->id)->delete();

        return response()->json(['success' => '1', 'redirect' => route('setup.role.index'), 'message' => 'Role deleted successfully.']);
    }

    private function clearCacheAndAutoload()
{
    // Clear the application cache
    Artisan::call('cache:clear');

    // Clear and optimize the configuration cache
    Artisan::call('config:cache');

    // Dump the autoloader
    exec('composer dump-autoload');
}
}
