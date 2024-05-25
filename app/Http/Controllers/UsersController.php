<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use \Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeNewUser;
use Illuminate\Support\Facades\Mail;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->orderBy('id', 'DESC')->get();
        $user = Auth::user();

        return view('users.usermanagements.index', compact('user','users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user =  User::whereHas('roles', function($q){
			$q->whereNotIn('name', ['Developer','Superadmin']);
		})->orderBy('username','ASC')->get();
        $roles = Role::whereNotIn('name', ['Developer', 'Superadmin'])->pluck('name', 'id')->toArray();
        $departments = Department::where('status',1)->get();
        $positions = Position::where('status',1)->get();
        return view('users.usermanagements.create', compact('user','roles','departments','positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $existingEmail = User::where('email', $request->input('email'))->where('deleted_at',null)->first();
            if ($existingEmail) {
                return redirect()->route('usermanagements.index')->with('error', 'Email User already registered!');
            }

            $validatedData = $request->validate([
                'username' => 'required',
                'fname' => 'required',
                'lname' => 'required',
                'role_id' => 'required',
                'position' => 'nullable',
                'department' => 'nullable',
                'email' => 'required',
                'phone' => 'nullable',
                'password' => 'required',
                'enabled' => 'required',
            ]);

            $existingPosition = Position::where('name', $validatedData['position'])->first();
            if (!$existingPosition) {
                Position::create([
                    'name' => $validatedData['position'],
                    'status'=> 1,
                ]);
            }
            $existingDepartment = Department::where('name', $validatedData['department'])->first();
            if (!$existingDepartment) {
                Department::create([
                    'name' => $validatedData['department'],
                    'status'=> 1,
                ]);
            }
            $plaintextPassword = $request->input('password');
            // Hash the password
            $hashedPassword = Hash::make($validatedData['password']);

            // Create the user with the validated data
            $user = User::create([
                'username' => $validatedData['username'],
                'password' => $hashedPassword,
                'email' => $validatedData['email'],
                'role_id' => $validatedData['role_id'],
                'enabled' => $validatedData['enabled'],
                'fname' => $validatedData['fname'],
                'lname' => $validatedData['lname'],
                'phone' => $validatedData['phone'],
                'position_id' => $validatedData['position'],
                'department_id' => $validatedData['department'],
            ]);

            $this->syncPermissions($request, $user);

            // Check if user was created successfully
            if ($user) {
                Mail::to($user->email)->send(new WelcomeNewUser($user,$plaintextPassword));
                // Redirect back or to another page
                return redirect()->route('usermanagements.index')->with('success', 'User added successfully');
            } else {
                // Log error or return error response
                Log::error('Failed to create user');
                return redirect()->back()->with('error', 'Failed to create user.');
            }
        } catch (\Exception $e) {
            // Log the exception for debugging
            Log::error('Error creating user: ' . $e->getMessage());

            // Return error response
            return redirect()->back()->with('error', 'An error occurred while creating user.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

       return view('users.usermanagements.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::whereNotIn('name', ['Developer', 'Superadmin'])->get();
        $departments = Department::where('status',1)->get();
        $positions = Position::where('status',1)->get();

        return view('users.usermanagements.edit', compact('user','roles','departments','positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
            // 'acct_types' => 'required',
            'role_id' => 'required',
            'enabled' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'nullable',
            'password' => 'nullable|string|min:8',
            'position_id' => 'nullable', // Add validation rule for position_id
            'department_id' => 'nullable', // Add validation rule for department_id
        ]);
        // Find the user by ID
        $user = User::findOrFail($id);

        // Update the user's password only if new data is provided
        if ($request->filled('password') && $validatedData['password'] !== null) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            // Remove the password from validated data to prevent it from being updated
            unset($validatedData['password']);
        }

        // Update the user with validated data
        $user->update($validatedData);

        // Update position_id and department_id separately
        $user->position_id = $validatedData['position_id'];
        $user->department_id = $validatedData['department_id'];
        $user->save();

        $this->syncPermissions($request, $user);

        // Redirect back to the user management index page with a success message
        return redirect()->route('usermanagements.index')->with('success', 'User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the user by ID
        $user = User::withTrashed()->find($id);

        // Check if the user exists
        if (!$user) {
            return redirect()->route('usermanagements.index')->with('error', 'User not found');
        }

        // Delete the user
        $user->delete();

        return redirect()->route('usermanagements.index')->with('success', 'User deleted successfully');
    }

    private function syncPermissions(Request $request, $user)
    {
        // Get the submitted roles
        $roles = $request->get('role_id', []);
        $permissions = $request->get('permissions', []);

        // Get the roles
        $roles = Role::find($roles);

        // check for current role changes
        if( ! $user->hasAllRoles( $roles ) ) {
            // reset all direct permissions for user
            $user->permissions()->sync([]);
        } else {
            // handle permissions
            $user->syncPermissions($permissions);
        }

        $user->syncRoles($roles);

        return $user;
    }
}
