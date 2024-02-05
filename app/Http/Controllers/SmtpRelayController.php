<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmtpRelayController extends Controller
{
    public function smtp_relay_req()
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
    
        return view('smtp_relay.smtp_relay_req', compact('user', 'role'));
    }

    public function add() 
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;

        return view('smtp_relay.form_application_smtp_relay', compact('user', 'role'));
    }
}
