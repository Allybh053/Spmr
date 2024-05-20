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

class MonthlyReportingController extends Controller
{
    public function index() {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
            ->pluck('server_id');

        $defaultServerId = 1;

        // Filter servers based on the fetched IDs
        $serverconfig = Server::whereIn('id', $serverIds)
            ->where('status', 1)->orderBy('id', 'asc')
            ->pluck('hostname', 'id');

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
        ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
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

}
