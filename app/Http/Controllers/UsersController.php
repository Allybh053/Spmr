<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use \Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users =  User::orderBy('username','ASC')->get();
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
        return view('users.usermanagements.create', compact('user','roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the form data
            $validatedData = $request->validate([
                'username' => 'required',
                'fname' => 'required',
                'lname' => 'required',
                'role_id' => 'required',
                'email' => 'required',
                'ldap_authentication' => 'required',
                'phone' => 'required',
                'password' => 'required',
                'enabled' => 'required',
            ]);

            // Hash the password
            $hashedPassword = Hash::make($validatedData['password']);

            // Create the user with the validated data
            $user = User::create([
                'username' => $validatedData['username'],
                'password' => $hashedPassword,
                'email' => $validatedData['email'],
                'role_id' => $validatedData['role_id'],
                'ldap_authentication' => $validatedData['ldap_authentication'],
                'enabled' => $validatedData['enabled'],
                'fname' => $validatedData['fname'],
                'lname' => $validatedData['lname'],
                'phone' => $validatedData['phone'],
            ]);

            // Check if user was created successfully
            if ($user) {
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
        $roles = Role::whereNotIn('name', ['Developer', 'Superadmin'])->get()->pluck('name', 'id');

        return view('users.usermanagements.edit', compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation rules can be adjusted based on your requirements
        $validatedData = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email',
            'acct_types' => 'required', // Adjust validation rules based on your needs
            'role_id' => 'required',
            'ldap_authentication' => 'required',
            'enabled' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            'phone' => 'nullable',
            'password'=> 'nullable',
            // Add more fields as needed
        ]);

        $username = $validatedData['username'];
        $email = $validatedData['email'];
        $acctTypes = $validatedData['acct_types'];
        $role = $validatedData['role_id'];
        $ldapAuthentication = $validatedData['ldap_authentication'];
        $enable = $validatedData['enabled'];
        $fname = $validatedData['fname'];
        $lname = $validatedData['lname'];
        $phone = $validatedData['phone'];

        // Check if the password fields are provided
        if ($request->filled('password')) {
            // Validate password and password_confirmation fields
            $request->validate([
                'password' => 'string|min:8',
            ]);

            // Update the user's password
            $user = User::find($id);
            $user->password = Hash::make($request->password);
            $user->save();
        }
        dd($request);
        User::where('id', $id)->update($validatedData);

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
}
