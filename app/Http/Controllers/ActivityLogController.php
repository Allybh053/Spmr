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
        ini_set('memory_limit', '512M'); // Increase memory limit if necessary

        // Initialize an empty collection for logs
        $allLogs = collect();
        // Fetch audit logs
        //$auditLogs = AuditLog::orderBy('created_at', 'desc')->get();

       // Process audit logs with cursor
            foreach (AuditLog::whereNotNull('old_values')
            ->whereNotNull('new_values')
            ->orderBy('created_at', 'desc')
            ->cursor() as $auditLog) {
            $allLogs->push($auditLog);
            }

            // Process activity logs with cursor
            foreach (ActivityLog::whereNotNull('input')
            ->where('input', '!=', '')
            ->where('input', '!=', '[]')
            ->orderBy('created_at', 'desc')
            ->cursor() as $activityLog) {
                if ($activityLog) { // Ensure $activityLog is valid
                    $activityLog->input = $this->limitWords($activityLog->input, 50); // Apply word limit
                    $allLogs->push($activityLog);
                }
            }
        // Merge audit logs and activity logs into a single collection
        // $logs = $auditLogs->merge($activityLogs);

        // Sort the merged logs by created_at timestamp
        // $logsPaginated = $logs->sortByDesc('created_at')->values();

         // Sort the merged logs by created_at timestamp
       // Sort the merged logs by created_at timestamp
            $logsPaginated = $allLogs->sortByDesc('created_at');

            // Transform the collection to add display date
            $logsPaginated->transform(function ($log) {
                $log->created_at_display = Carbon::parse($log->created_at)->setTimezone('Asia/Kuala_Lumpur')->format('d/m/Y h:i:s A');
                return $log;
            });
        // Assuming you want to pass the logs to a view named 'activityLog.index'
        return view('activityLog.index', compact('logsPaginated'));
    }

    private function limitWords($string, $wordLimit)
    {
        $words = explode(' ', $string);
        if (count($words) > $wordLimit) {
            return implode(' ', array_slice($words, 0, $wordLimit)) . '...';
        }
        return $string;
    }



}
