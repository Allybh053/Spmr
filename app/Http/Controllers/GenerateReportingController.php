<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\DomainMinistryAgencies;
use App\Models\Ministry;
use App\Models\RoleServer;
use App\Models\Server;
use App\Models\smtp_relay_server;
use App\Models\SmtpRelayReqForm;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use function Laravel\Prompts\search;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class GenerateReportingController extends Controller
{
    public function index(Request $request, $server_id = null, $server_type = null)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
             ->leftJoin('servers', 'servers.id', 'role_servers.server_id')
            ->where('servers.status', 1)
            ->pluck('server_id');

        $defaultServerId = $serverIds->min();
        $serverconfig = Server::whereIn('id', $serverIds)
            ->where('status', 1)->orderBy('id', 'asc')
            ->pluck('hostname', 'id');

        $ministries = Ministry::where('status', 1)->pluck('name', 'id');
        $domainsArray = DomainMinistryAgencies::where('enabled', 1)->get()->toArray();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Agencies Reporting',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode(['agency report' => 'Retrieve Default Data For Filter in Agency Reporting', 'request' => $request->except(['password'])]),
            'user_agent' => $request->userAgent(),
        ]);

        return view('generateReporting.index', compact('defaultServerId', 'serverconfig', 'domainsArray', 'ministries'));
    }

    private function fetchSmtpRelayData($calendarType, $dateType, $monthType, $yearType, $dateFromType, $dateToType, $ministry, $agency, $serverName, $requestType)
    {
        return SmtpRelayReqForm::select('smtp_relay_req_form.*', 'smr.*', 'agencies.name as agenciesName')
            ->leftJoin('smtp_relay_server as smr', 'smr.form_id', 'smtp_relay_req_form.form_id')
            ->leftJoin('agencies', 'agencies.id', DB::raw('CAST(smtp_relay_req_form.agencies AS bigint)'))
            ->when(isset($calendarType) && $calendarType == 'date', function ($query) use ($dateType) {
                $formattedDate = date("Y-m-d H:i:s", strtotime($dateType));
                $query->whereDate('smtp_relay_req_form.date_verification_3', $formattedDate);
            })
            ->when(isset($calendarType) && $calendarType == 'month', function ($query) use ($monthType) {
                $startDate = date("Y-m-01 00:00:00", strtotime($monthType));
                $endDate = date("Y-m-t 23:59:59", strtotime($monthType));
                $query->whereBetween('smtp_relay_req_form.date_verification_3', [$startDate, $endDate]);
            })
            ->when(isset($calendarType) && $calendarType == 'year', function ($query) use ($yearType) {
                $startDate = date("Y-01-01 00:00:00", strtotime($yearType));
                $endDate = date("Y-12-31 23:59:59", strtotime($yearType));
                $query->whereBetween('smtp_relay_req_form.date_verification_3', [$startDate, $endDate]);
            })
            ->when($dateFromType != null && $dateToType != null, function ($query) use ($dateFromType, $dateToType) {
                $query->where('smtp_relay_req_form.date_verification_3', '>=', Carbon::parse($dateFromType)->startOfDay())
                    ->where('smtp_relay_req_form.date_verification_3', '<=', Carbon::parse($dateToType)->endOfDay());
            })
            ->when($dateFromType != null && $dateToType == null, function ($query) use ($dateFromType) {
                $query->where('smtp_relay_req_form.date_verification_3', '>=', Carbon::parse($dateFromType)->startOfDay());
            })
            ->when($dateToType != null && $dateFromType == null, function ($query) use ($dateToType) {
                $query->where('smtp_relay_req_form.date_verification_3', '<=', Carbon::parse($dateToType)->endOfDay());
            })
            ->when(isset($ministry) && $ministry != null, function ($query) use ($ministry) {
                $query->where('smtp_relay_req_form.ministry', $ministry);
            })
            ->when(isset($agency) && $agency != null, function ($query) use ($agency) {
                $query->where('smtp_relay_req_form.agencies', $agency);
            })
            ->where('smtp_relay_req_form.status', 1)
            ->where('smtp_relay_req_form.smtp_relay_server', $serverName)
            ->where('smr.types_req', $requestType)
            ->get();
    }

    public function search(Request $request)
    {
        // Retrieve the request parameters
        $serverIds = $request->input('server');
        $ministry = $request->input('ministry');
        $agency = $request->input('agency');
        $calendarType = $request->input('calendarType');
        $dateType = $request->input('input2');
        $monthType = $request->input('input2_month');
        $yearType = $request->input('input2_year');
        $dateFromType = $request->input('input2_from');
        $dateToType = $request->input('input2_to');

        $serverMailRelay = Server::find($serverIds);
        $serverName = $serverMailRelay->hostname;

        // Fetch the data
        $dataSmtpFormIpActive = $this->fetchSmtpRelayData($calendarType, $dateType, $monthType, $yearType, $dateFromType, $dateToType, $ministry, $agency, $serverName, 1);
        $dataSmtpFormIpDeactive = $this->fetchSmtpRelayData($calendarType, $dateType, $monthType, $yearType, $dateFromType, $dateToType, $ministry, $agency, $serverName, 2);
        $dataSmtpFormSasl = $this->fetchSmtpRelayData($calendarType, $dateType, $monthType, $yearType, $dateFromType, $dateToType, $ministry, $agency, $serverName, 2);

        // Log the search activity
        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Searching Agencies Reporting',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'agency report' => 'Retrieve Data Searching',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        // Return JSON response with the data
        return response()->json([
            'dataTbl1' => $dataSmtpFormSasl,
            'dataTbl2' => $dataSmtpFormIpDeactive,
            'data2' => $dataSmtpFormIpActive,
            'totalIP' => $dataSmtpFormIpActive->count(),
            'totalDeactivated' => $dataSmtpFormIpDeactive->count(),
            'totalDomain' => $dataSmtpFormSasl->count(),
        ]);
    }

    public function api(Request $request)
    {
        // Retrieve serverId from the request
        $serverId = $request->serverId;
        return response()->json(['serverId' => $serverId]);
    }

    public function getAgencies(Request $request)
    {
        // Retrieve agencies based on ministry
        $ministry = $request->input('ministry');
        $agencies = Agency::where('ministry', $ministry)
            ->where('status', 1)
            ->where('name', '!=', '-')
            ->get();
        return response()->json($agencies);
    }
}

