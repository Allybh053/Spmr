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
    // Fetch audit logs with old and new values
    $auditLogs = AuditLog::whereNotNull('old_values')
        ->whereNotNull('new_values')
        ->orderBy('created_at', 'desc')
        ->get();

    // Fetch activity logs with input
    $activityLogs = ActivityLog::whereNotNull('input')
        ->where('input', '!=', '')
        ->where('input', '!=', '[]')
        ->orderBy('created_at', 'desc')
        ->get();

    // Merge logs
    $logs = $auditLogs->merge($activityLogs);

    // Sort the merged logs by created_at timestamp
    $logs = $logs->sortByDesc('created_at');

    $totalLogs = $logs->count();
    $perPage = 500; // Adjust per your desired pagination limit
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $currentPageItems = $logs->slice(($currentPage - 1) * $perPage, $perPage)->all();

    // Debug output
    // dd($totalLogs, $perPage, $currentPage, count($currentPageItems));
    $logsPaginated = new LengthAwarePaginator($currentPageItems, $logs->count(), $perPage, $currentPage, [
        'path' => LengthAwarePaginator::resolveCurrentPath(),
    ]);

    // Transform logs to add display date
    $logsPaginated->getCollection()->transform(function ($log) {
        $log->created_at_display = Carbon::parse($log->created_at)->setTimezone('Asia/Kuala_Lumpur')->format('d/m/Y h:i:s A');
        return $log;
    });

    // Assuming you want to pass the logs to a view named 'activityLog.index'
    return view('activityLog.index', compact('logsPaginated'));
}





}
