<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\AuditLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ActivityLogController extends Controller
{
    public function showActivityLogs()
    {

        // Fetch audit logs
        $auditLogs = AuditLog::orderBy('created_at', 'desc')->get();

        // Fetch activity logs
        $activityLogs = ActivityLog::orderBy('created_at', 'desc')->get();

        // Merge audit logs and activity logs into a single collection
        $logs = $auditLogs->union($activityLogs);

        // Sort the merged logs by created_at timestamp
        $logsPaginated = $logs->sortByDesc('created_at')->values();
     

        // Assuming you want to pass the logs to a view named 'activityLog.index'
        return view('activityLog.index', compact('logsPaginated'));
    }



}
