<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\AuditLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityLogController extends Controller
{
    public function showActivityLogs()
    {

        // Fetch audit logs
        //$auditLogs = AuditLog::orderBy('created_at', 'desc')->get();

        // Fetch audit logs, limit to 50
        $auditLogs = AuditLog::orderBy('created_at', 'desc')->take(50)->get();

        // Fetch activity logs
        //$activityLogs = ActivityLog::orderBy('created_at', 'desc')->get();

        // Fetch activity logs, limit to 50
        $activityLogs = ActivityLog::orderBy('created_at', 'desc')->take(50)->get();

        // Merge audit logs and activity logs into a single collection
        $logs = $auditLogs->merge($activityLogs);

        // Sort the merged logs by created_at timestamp
        // $logsPaginated = $logs->sortByDesc('created_at')->values();

         // Sort the merged logs by created_at timestamp
        $logsPaginated = $logs->sortByDesc('created_at');

    // Transform the collection to add display date
         $logsPaginated->transform(function ($log) {
            $log->created_at_display = Carbon::parse($log->created_at)->setTimezone('Asia/Kuala_Lumpur')->format('d/m/Y h:i:s A');
            return $log;
         });
        // Assuming you want to pass the logs to a view named 'activityLog.index'
        return view('activityLog.index', compact('logsPaginated'));
    }



}
