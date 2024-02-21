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


class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::get()->groupBy(['group','sub_group']);

        return view('role.index', compact('roles', 'permissions'));
    }

    public function create(Request $request)
    {
        $messages = [
            'name.required' => 'Namespace Required.',
            'name.max'      => 'Namespace must not exceed 255 characters.'
        ];

        $validation = [
            'name' => 'required|max:255'
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

        return response()->json(['success' => '1', 'redirect' => route('role.index'), 'message' => 'Role saved successfully.']);
    }

    public function storePermission(Request $request)
    {
        $role = Role::find($request->id);
        $permissions = $request->permissions;
        $role->syncPermissions($permissions);

        return response()->json(['success' => '1', 'redirect' => route('role.index'), 'message' => 'Permissions saved successfully.']);
    }

    public function delete(Request $request)
    {
        $role = Role::find($request->id)->delete();

        return response()->json(['success' => '1', 'redirect' => route('role.index'), 'message' => 'Role deleted successfully.']);
    }
}