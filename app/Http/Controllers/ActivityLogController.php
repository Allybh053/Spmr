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
        $auditLogs = AuditLog::whereNotNull('old_values')
        ->whereNotNull('new_values')->orderBy('created_at', 'desc')->get();

        // Fetch activity logs
        //$activityLogs = ActivityLog::orderBy('created_at', 'desc')->get();

        // Fetch activity logs, limit to 50
        $activityLogs = ActivityLog::whereNotNull('input')
        ->where('input', '!=', '')
        ->where('input', '!=', '[]')
        ->orderBy('created_at', 'desc')
        ->get();

        function limitWords($string, $wordLimit) {
            $words = explode(' ', $string);
            if (count($words) > $wordLimit) {
                return implode(' ', array_slice($words, 0, $wordLimit)) . '...';
            }
            return $string;
        }

        // Applying the word limit to each log's input
        foreach ($activityLogs as $log) {
            $log->input = limitWords($log->input, 50); // Adjust the number 10 to your desired word limit
        }

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
