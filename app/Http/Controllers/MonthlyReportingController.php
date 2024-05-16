<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ActivityLogController extends Controller
{
    public function showActivityLogs()
    {
        return view('monthlyReport.index');
    }

}
