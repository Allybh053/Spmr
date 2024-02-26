<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\AuditLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ActivityLogController extends Controller
{
    public function showActivityLogs()
    {
        // Fetch audit logs
        $auditLogs = AuditLog::orderBy('created_at', 'desc')->paginate(10);
    
        // Fetch activity logs
        $activityLogs = ActivityLog::orderBy('created_at', 'desc')->paginate(10);
        // Merge audit logs and activity logs into a single collection
        $logs = new Collection([...$auditLogs, ...$activityLogs]);

        // Sort the merged logs by created_at timestamp
        $sortedLogs = $logs->sortByDesc('created_at');
    
        // Paginate the sorted logs
        $perPage = 10; // Number of items per page
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $sortedLogs->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $logsPaginated = new LengthAwarePaginator($currentItems, count($sortedLogs), $perPage, $currentPage);
    
        // Assuming you want to pass the logs to a view named 'activityLog.index'
        return view('activityLog.index', compact('logsPaginated'));
    }
    
}