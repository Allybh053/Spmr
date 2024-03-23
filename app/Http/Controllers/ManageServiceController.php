<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ManageServiceController extends Controller
{
    public function index(Request $request, $server_id = null, $server_type = null)
    {

        return view('manageService.index');
    }

}
