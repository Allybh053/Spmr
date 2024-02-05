<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Aacotroneo\Saml2\Facades\Saml2Auth;

class Saml2Controller extends Controller
{


    public function samlLogin()
    {
        return Saml2Auth::login();
    }
    
    public function samlAcs()
    {
        Saml2Auth::acs();
        return redirect()->intended('main');
    }
    
}
