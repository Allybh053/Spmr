<?php

namespace App\Http\Controllers;

use App\Models\Jadual10;
use App\Models\Jadual14;
use App\Models\Jadual2;
use App\Models\Jadual6;
use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Models\ActivityLog;

class MonthlyReportingController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
        ->leftJoin('servers','servers.id','role_servers.server_id')
        ->where('servers.status',1)
        ->pluck('server_id');

        $defaultServerId = $serverIds->min();

        // Filter servers based on the fetched IDs
        $serverconfig = Server::whereIn('id', $serverIds)
            ->where('status', 1)->orderBy('id', 'asc')
            ->pluck('hostname', 'id');

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Usage Reporting',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'usage report' => 'Retrieve data for viewing monthly reporting Page',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return view('monthlyReport.index',compact('defaultServerId','serverconfig'));
    }

    public function api(Request $request)
    {
        // Retrieve serverId from the request
        $serverId = $request->serverId;

        return $serverId;
    }

    public function search(Request $request)
    {
        // Retrieve other inputs from the request
        $serverIds = $request->input('server');
        $input2Type = $request->input('calendarType');
        $monthType = $request->input('input2_month');
        $yearType = $request->input('input2_year');

        // You can adjust the search logic based on the selected calendar type and agency here
        $serverMailRelay = Server::find($serverIds);
        $serverName = $serverMailRelay->hostname;

        $queryJad2 = Jadual2::where('server', $serverName);
        $queryJad6 = Jadual6::where('server', $serverName);
        $queryJad10 = Jadual10::where('server', $serverName);
        $queryJad14 = Jadual14::where('server', $serverName);

        // Apply filter based on $input2Type
        if ($input2Type !== null) {
            if ($input2Type === "month") {
                $queryJad2->where('month', $monthType);
                $queryJad6->where('month', $monthType);
                $queryJad10->where('month', $monthType);
                $queryJad14->where('month', $monthType);
            } elseif ($input2Type === "year") {
                $queryJad2->where('year', $yearType);
                $queryJad6->where('year', $yearType);
                $queryJad10->where('year', $yearType);
                $queryJad14->where('year', $yearType);
            } else {
                // to do
            }
        }

        // Execute the query
        $valDataJad2 = $queryJad2->get();
        $valDataJad6 = $queryJad6->get();
        $valDataJad10 = $queryJad10->get();
        $valDataJad14 = $queryJad14->get();



        // Apply formatting
        $valDataJad2 = $this->formatData($valDataJad2);
        $valDataJad6 =  $this->formatData($valDataJad6);
        $valDataJad10 = $this->formatDataEmail($valDataJad10);
        $valDataJad14 =  $this->formatDataEmail($valDataJad14);

        // this will use for date range

        // ->when($request->date_from != null && $request->date_to != null, function ($query) use ($request) {
        //     $query->where('transaction_headers.transaction_date', '>=', Carbon::parse($request->date_from)->startOfDay())
        //         ->where('transaction_headers.transaction_date', '<=', Carbon::parse($request->date_to)->endOfDay());
        // })
        // ->when($request->date_from != null && $request->date_to == null, function ($query) use ($request) {
        //     $query->where('transaction_headers.transaction_date', '>=', Carbon::parse($request->date_from)->startOfDay());
        // })
        // ->when($request->date_to != null && $request->date_from == null, function ($query) use ($request) {
        //     $query->where('transaction_headers.transaction_date', '<=', Carbon::parse($request->date_to)->endOfDay());
        // })


        $chartJadual2 = Jadual2::when(isset($serverName) && $serverName != null, function ($query) use ($serverName) {
            $query->where('server', $serverName);
        })
        ->when(isset($request->input2_month) && $request->input('input2_month') != null, function ($query) use ($request) {
            $query->where('month', $request->input('input2_month'));
        })
        ->when(isset($request->input2_year) && $request->input('input2_year') != null, function ($query) use ($request) {
            $query->where('year', $request->input('input2_year'));
        })
        // by day
        // ->whereDate('date', Carbon::now())
        // by week
        // ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->get();


        $chartJadual6 = Jadual6::when(isset($serverName) && $serverName != null, function ($query) use ($serverName) {
            $query->where('server', $serverName);
        })
        ->when(isset($request->input2_month) && $request->input('input2_month') != null, function ($query) use ($request) {
            $query->where('month', $request->input('input2_month'));
        })
        ->when(isset($request->input2_year) && $request->input('input2_year') != null, function ($query) use ($request) {
            $query->where('year', $request->input('input2_year'));
        })
        ->get();

        $chartJadual10 = Jadual10::when(isset($serverName) && $serverName != null, function ($query) use ($serverName) {
            $query->where('server', $serverName);
        })
        ->when(isset($request->input2_month) && $request->input('input2_month') != null, function ($query) use ($request) {
            $query->where('month', $request->input('input2_month'));
        })
        ->when(isset($request->input2_year) && $request->input('input2_year') != null, function ($query) use ($request) {
            $query->where('year', $request->input('input2_year'));
        })
        ->orderBy('total_email', 'desc')
        ->limit(20)
        ->get();

        $chartJadual14 = Jadual14::when(isset($serverName) && $serverName != null, function ($query) use ($serverName) {
            $query->where('server', $serverName);
        })
        ->when(isset($request->input2_month) && $request->input('input2_month') != null, function ($query) use ($request) {
            $query->where('month', $request->input('input2_month'));
        })
        ->when(isset($request->input2_year) && $request->input('input2_year') != null, function ($query) use ($request) {
            $query->where('year', $request->input('input2_year'));
        })
        ->orderBy('total_email', 'desc')
        ->limit(20)
        ->get();


        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Searching Usage Reporting',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'usage report' => 'Retrieve data for searching monthly reporting Page',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'dataTbl2' => $valDataJad2,
            'dataTbl6' => $valDataJad6,
            'dataTbl10' => $valDataJad10,
            'dataTbl14' => $valDataJad14,
            'chartJadual2' => $chartJadual2,
            'chartJadual6' => $chartJadual6,
            'chartJadual10' => $chartJadual10,
            'chartJadual14' => $chartJadual14,
            'serverName' =>$serverName,
        ]);
    }


    private function formatData(Collection $data)
    {
        return $data->map(function ($item) {
            $item->received = number_format($item->received);
            $item->delivered = number_format($item->delivered);
            $item->deferred = number_format($item->deferred);
            $item->bounced = number_format($item->bounced);
            $item->rejected = number_format($item->rejected);
            return $item;
        });
    }
    private function formatDataEmail(Collection $data)
    {
        return $data->map(function ($item) {
            $item->total_email = number_format($item->total_email);
            return $item;
        });
    }


}


