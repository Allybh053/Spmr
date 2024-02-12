<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Mail;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;


        $server = [
            "Server Local" => [
                "Postfix" => "Online",
                "Fail-To-Ban" => "Offline",
                "Apache" => "Offline",
                "Webmin" => "Online",
                "Replicate Mail Relay" => "Online",
                "Policy Server" => "Offline",
                "Keep Alive" => "Online"
            ],
            "Server First" => [
                "Postfix" => "Online",
                "Fail-To-Ban" => "Offline",
                "Apache" => "Offline",
                "Webmin" => "Online",
                "Replicate Mail Relay" => "Online",
                "Policy Server" => "Offline",
                "Keep Alive" => "Online"
            ],
            "Server Second" => [
                "Postfix" => "Online",
                "Fail-To-Ban" => "Offline",
                "Apache" => "Offline",
                "Webmin" => "Online",
                "Replicate Mail Relay" => "Online",
                "Policy Server" => "Offline",
                "Keep Alive" => "Online"
            ]
        ];







        return view('dashboard.index', compact('user', 'role','server'));
    }
}
