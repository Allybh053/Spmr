<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;

class LdapController extends Controller
{


    public function ldapLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Adldap::auth()->attempt($credentials['email'], $credentials['password'])) {
            // Authentication passed
            return redirect()->intended('main');
        }
    
        // Authentication failed
        return back()->withErrors(['email' => 'Invalid credentials']);
    }
}
