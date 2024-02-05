<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainRelayController extends Controller
{
    public function index()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        return view('mailrelay.index', compact('user'));
    }

    public function show()
    {
        // Register mail relay that is pending
        $user = Auth::user();

        return view('mailrelay.register', compact('user'));
    }
    public function view()
    {
        // Register mail relay that is pending
        $user = Auth::user();

        return view('mailrelay.edit', compact('user'));
    }
}
