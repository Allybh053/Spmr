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
use App\Models\Server;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $servers = Server::where('status', 1)->first();

        return view('dashboard.index', compact('user', 'role','servers'));
    }
}
