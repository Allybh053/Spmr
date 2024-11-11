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
use OwenIt\Auditing\Models\Audit;
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


    if ($request->roleId) {
        $role = Role::find($request->roleId);
        $oldValues = $role->getOriginal(); // Capture old values before update
    } else {
        $role = new Role;
        $oldValues = []; // No old values for a new record
    }

    $role->name = $request->name;
    $role->save();

    // Convert old and new values to JSON format
    $oldValuesJson = json_encode($oldValues);
    $newValuesJson = json_encode($role->getAttributes());

    // Manual auditing for created role
    if (!$request->roleId) {
        $audit = new Audit();
        $audit->auditable_type = Role::class;
        $audit->auditable_id = $role->id;
        $audit->old_values = $oldValuesJson; // Empty for creation
        $audit->new_values = $newValuesJson;
        $audit->user_id = Auth::user()->id;
        $audit->event = 'created';
        $audit->url = request()->fullUrl();
        $audit->ip_address = request()->ip();
        $audit->user_agent = request()->userAgent();
        $audit->tags = json_encode(['manual-audit']); // Ensure tags are in JSON format
        $audit->save();
    } else {
        // Manual auditing for updated role
        $audit = new Audit();
        $audit->auditable_type = Role::class;
        $audit->auditable_id = $role->id;
        $audit->user_id = Auth::user()->id;
        $audit->old_values = $oldValuesJson;
        $audit->new_values = $newValuesJson;
        $audit->event = 'updated';
        $audit->url = request()->fullUrl();
        $audit->ip_address = request()->ip();
        $audit->user_agent = request()->userAgent();
        $audit->tags = json_encode(['manual-audit']); // Ensure tags are in JSON format
        $audit->save();
    }

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

        // Capture old permissions and servers for auditing
        $oldPermissions = $role->permissions()->pluck('id')->toArray();
        $oldServers = RoleServer::where('role_id', $roleId)->pluck('server_id')->toArray();

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


        // Capture new permissions and servers for auditing
        $newPermissions = $role->permissions()->pluck('id')->toArray();
        $newServers = RoleServer::where('role_id', $roleId)->pluck('server_id')->toArray();

        // Create audit log
        $audit = new Audit();
        $audit->auditable_type = Role::class;
        $audit->auditable_id = $roleId;
        $audit->user_id = Auth::user()->id;
        $audit->old_values = json_encode(['permissions' => $oldPermissions, 'servers' => $oldServers]);
        $audit->new_values = json_encode(['permissions' => $newPermissions, 'servers' => $newServers]);
        $audit->event = 'updated';
        $audit->url = $request->fullUrl();
        $audit->ip_address = $request->ip();
        $audit->user_agent = $request->userAgent();
        $audit->tags = json_encode(['manual-audit']);
        $audit->save();

        $this->clearCacheAndAutoload();

        return response()->json(['success' => '1', 'redirect' => route('setup.role.index'), 'message' => 'Permissions saved successfully.']);
    }

    public function delete(Request $request)
    {
        // Find the role by ID before deleting it
        $role = Role::find($request->id);

        // Check if the role exists
        if ($role) {
            // Check if there are any users assigned to this role
            $usersWithRole = $role->users()->count();

            if ($usersWithRole > 0) {
                // Users are assigned to this role, prevent deletion
                return response()->json(['error' => '1', 'message' => 'Cannot delete role. Users are assigned to it.'], 404);
            } else {
                // No users are assigned to this role, proceed with deletion
                // Capture the role's attributes for auditing before deleting it
                $oldValues = $role->getAttributes();

                // Delete the role
                $role->delete();

                // Create an audit log for the deletion
                $audit = new Audit();
                $audit->auditable_type = Role::class;
                $audit->auditable_id = $request->id;
                $audit->user_id = Auth::user()->id;
                $audit->old_values = json_encode($oldValues);
                $audit->new_values = null; // Since the role is deleted, there are no new values
                $audit->event = 'deleted';
                $audit->url = $request->fullUrl();
                $audit->ip_address = $request->ip();
                $audit->user_agent = $request->userAgent();
                $audit->tags = json_encode(['manual-audit']);
                $audit->save();

                return response()->json(['success' => '1', 'redirect' => route('setup.role.index'), 'message' => 'Role deleted successfully.']);
            }
        } else {
            // Role does not exist
            return response()->json(['error' => '1', 'message' => 'Role not found.'], 404);
        }


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
