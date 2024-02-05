<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use \Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
         // Retrieve all users
         $users = User::all();

        return view('users.usermanagements.index', compact('user','users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        return view('users.usermanagements.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if the form is submitted (POST request)
        if ($request->isMethod('post')) {
            // The form has been submitted

            // Validate the form data
            $validatedData = $request->validate([
                'username' => 'required|string',
                'email' => 'required|email',
                'acct_types' => 'required', // Adjust validation rules based on your needs
                'role_id' => 'required',
                'ldap_authentication' => 'required',
                'password' => 'required',
                'enabled' => 'required',
                'fname' => 'required',
                'lname' => 'required',
                'phone' => 'nullable',
            ]);
            $username = $validatedData['username'];
            $email = $validatedData['email'];
            $acctTypes = $validatedData['acct_types'];
            $role = $validatedData['role_id'];
            $ldapAuthentication = $validatedData['ldap_authentication'];
            $password = $validatedData['password'];
            $enable = $validatedData['enabled'];
            $fname = $validatedData['fname'];
            $lname = $validatedData['lname'];
            $phone = $validatedData['phone'];

            $hashedPassword = Hash::make($password);


            // Create the user with the validated data
            $user = User::create([
                'username' => $username,
                'email' => $email,
                'acct_types' => $acctTypes,
                'role_id' => $role,
                'ldap_authentication' => $ldapAuthentication,
                'password' => $hashedPassword,
                'enabled' => $enable,
                'fname' => $fname,
                'lname' => $lname,
                'phone' => $phone,
            ]);

            // Redirect back or to another page
            return redirect()->route('usermanagements.index')->with('success', 'User added successfully');
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

        return view('users.usermanagements.edit', compact('user'));
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
