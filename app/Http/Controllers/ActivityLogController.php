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

        public function showActivityLogs(Request $request)
        {
            $logs = $this->query($request);


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


        public function filter(Request $request)
        {

            $messages = [
                'end_date.after_or_equal' => 'End Date Must Be Greater Than Start Date',
            ];

            $validation = [
                'start_date' => 'nullable',
                'end_date' => 'nullable|after_or_equal:start_date',
            ];

            try {
                $request->validate($validation, $messages);

                $logs = $this->query($request);

                // Transform logs to format expected by DataTables
                $data = $logs->map(function ($log) {
                    $username = $log->user ? $log->user->username : 'Guest';

                    return [
                        'user_id' => $username,
                        'activity_type' => $log->activity_type ?? $log->event,
                        'action' => $log->action,
                        'ip_address' => $log->ip_address,
                        'user_agent' => $log->user_agent,
                        'url' => $log->url,
                        'old_values' => $log->old_values,
                        'new_values' => $log->new_values,
                        'input' => $log->input,
                        'created_at' => Carbon::parse($log->created_at)
                                            ->setTimezone('Asia/Kuala_Lumpur')
                                            ->format('d/m/Y h:i:s A'), // Assuming this is properly formatted
                    ];
                });

                // Return the formatted data as JSON response
                return response()->json(['data' => $data]);

            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'errors' => $e->errors()
                ], 422);
            }
        }


        private function query(Request $request=null)
        {
            $calendarType = $request->input('calendarType') ?? '';
        $dateType = $request->input('input2') ?? '';
        $monthType = $request->input('input2_month') ?? '';
        $yearType = $request->input('input2_year') ?? '';
        $dateFromType = $request->input('start_date') ?? '';
        $dateToType = $request->input('end_date') ?? '';

            // Fetch audit logs with old and new values
            $auditLogs = AuditLog::whereNotNull('old_values')
            ->whereNotNull('new_values')
            // ->when(isset($calendarType) && $calendarType == 'month', function ($query) use ($monthType) {
            //     $startDate = date("Y-m-01 00:00:00", strtotime($monthType));
            //     $endDate = date("Y-m-t 23:59:59", strtotime($monthType));
            //     $query->whereBetween('created_at', [$startDate, $endDate]);
            // })
            // ->when(isset($calendarType) && $calendarType == 'year', function ($query) use ($yearType) {
            //     $startDate = date("Y-01-01 00:00:00", strtotime($yearType));
            //     $endDate = date("Y-12-31 23:59:59", strtotime($yearType));
            //     $query->whereBetween('created_at', [$startDate, $endDate]);
            // })
            ->when($dateFromType != null && $dateToType != null, function ($query) use ($dateFromType,$dateToType) {
                $query->where('created_at', '>=', Carbon::parse($dateFromType)->startOfDay())
                    ->where('created_at', '<=', Carbon::parse($dateToType)->endOfDay());
            })
            ->when($dateFromType != null && $dateToType == null, function ($query) use ($dateFromType) {
                $query->where('created_at', '>=', Carbon::parse($dateFromType)->startOfDay());
            })
            ->when($dateToType != null && $dateFromType == null, function ($query) use ($dateToType) {
                $query->where('created_at', '<=', Carbon::parse($dateToType)->endOfDay());
            })
            ->orderBy('created_at', 'desc')
            ->get();

            // Fetch activity logs with input
            $activityLogs = ActivityLog::whereNotNull('input')
            ->where('input', '!=', '')
            ->where('input', '!=', '[]')
            // ->when(isset($calendarType) && $calendarType == 'month', function ($query) use ($monthType) {
            //     $startDate = date("Y-m-01 00:00:00", strtotime($monthType));
            //     $endDate = date("Y-m-t 23:59:59", strtotime($monthType));
            //     $query->whereBetween('created_at', [$startDate, $endDate]);
            // })
            // ->when(isset($calendarType) && $calendarType == 'year', function ($query) use ($yearType) {
            //     $startDate = date("Y-01-01 00:00:00", strtotime($yearType));
            //     $endDate = date("Y-12-31 23:59:59", strtotime($yearType));
            //     $query->whereBetween('created_at', [$startDate, $endDate]);
            // })
            ->when($dateFromType != null && $dateToType != null, function ($query) use ($dateFromType,$dateToType) {
                $query->where('created_at', '>=', Carbon::parse($dateFromType)->startOfDay())
                    ->where('created_at', '<=', Carbon::parse($dateToType)->endOfDay());
            })
            ->when($dateFromType != null && $dateToType == null, function ($query) use ($dateFromType) {
                $query->where('created_at', '>=', Carbon::parse($dateFromType)->startOfDay());
            })
            ->when($dateToType != null && $dateFromType == null, function ($query) use ($dateToType) {
                $query->where('created_at', '<=', Carbon::parse($dateToType)->endOfDay());
            })
            ->orderBy('created_at', 'desc')
            ->get();

            // Merge logs
            $logs = $auditLogs->merge($activityLogs);

            // Sort the merged logs by created_at timestamp
            $logs = $logs->sortByDesc('created_at');


            return $logs;
        }





}
