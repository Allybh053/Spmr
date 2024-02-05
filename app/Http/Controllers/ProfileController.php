<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        return view('users.profile.index', compact('user'));
    }

    public function updatepassword(Request $request, $id)
    {
        // Validate the form data
        $request->validate([
            'password' => 'required|string|min:8',
        ]);
        // Find the user by ID
        $user = User::find($id);

        // Update the user's password
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.index')->with('success', 'Password updated successfully');

    }

    public function updatedetail(Request $request, $id)
    {
        // Validation rules can be adjusted based on your requirements
        $validatedData = $request->validate([
            'username' => 'required|string',
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'enabled' => 'required|in:0,1',
            // Add more fields as needed
        ]);

        // Update the user details using the validated data
        User::where('id', $id)->update($validatedData);

        // Redirect back or to another page
        return redirect()->route('profile.index', $id)->with('success', 'User details updated successfully');
    }

}
