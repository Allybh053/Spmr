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

        // Filter servers based on the fetched IDs
        $serverconfig = Server::whereIn('id', $serverIds)
            ->where('status', 1)->orderBy('id', 'asc')
            ->pluck('hostname', 'id');

        $ministries = Ministry::where('status', 1)->pluck('name', 'id');
        $domainsArray = DomainMinistryAgencies::where('enabled', 1)->get()->toArray();

//        // Logging the retrieved data
//        \Log::info('Server IDs: ', $serverIds->toArray());
//        \Log::info('Server Config: ', $serverconfig->toArray());
//        \Log::info('Ministries: ', $ministries->toArray());
//        \Log::info('Domains Array: ', $domainsArray);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Agencies Reporting',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'agency report' => 'Retrieve Default Data For Filter in Agency Reporting',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return view('generateReporting.index', compact('defaultServerId', 'serverconfig', 'domainsArray', 'ministries'));
    }

    public function search(Request $request)
    {
//        \Log::info('Search Request Data: ', $request->all());

        $serverIds = $request->input('server');
        $ministry = $request->input('ministry');
        $agency = $request->input('agency');
        $calendarType = $request->input('calendarType');
        $dateType = $request->input('input2');
        $monthType = $request->input('input2_month');
        $yearType = $request->input('input2_year');
        $dateFromType = $request->input('input2_from');
        $dateToType = $request->input('input2_to');

        // Find based on option server name
        $server = Server::find($serverIds);
        $serverName = $server->hostname;

//        // Log server details
//        \Log::info('Server Name: ', ['serverName' => $serverName]);

        // Do base query with joins and filters
        $baseQuery = SmtpRelayReqForm::select('smtp_relay_req_form.*', 'smr.*', 'agencies.name as agenciesName')
            ->leftJoin('smtp_relay_server as smr', 'smr.form_id', '=', 'smtp_relay_req_form.form_id')
            ->leftJoin('agencies', 'agencies.id', '=', DB::raw('CAST(smtp_relay_req_form.agencies AS bigint)'))
            ->where('smtp_relay_req_form.status', 1)
            ->where('smtp_relay_req_form.smtp_relay_server', $serverName);

        // Apply filters based on calendar type
        $baseQuery->when($calendarType === 'date', function ($query) use ($dateType) {
            $query->whereDate('smtp_relay_req_form.date_verification_3', $dateType);
        })
            ->when($calendarType === 'month', function ($query) use ($monthType) {
                $startDate = Carbon::parse($monthType)->startOfMonth();
                $endDate = Carbon::parse($monthType)->endOfMonth();
                $query->whereBetween('smtp_relay_req_form.date_verification_3', [$startDate, $endDate]);
            })
            ->when($calendarType === 'year', function ($query) use ($yearType) {
                $startDate = Carbon::parse($yearType)->startOfYear();
                $endDate = Carbon::parse($yearType)->endOfYear();
                $query->whereBetween('smtp_relay_req_form.date_verification_3', [$startDate, $endDate]);
            })
            ->when($dateFromType && $dateToType, function ($query) use ($dateFromType, $dateToType) {
                $query->whereBetween('smtp_relay_req_form.date_verification_3', [
                    Carbon::parse($dateFromType)->startOfDay(),
                    Carbon::parse($dateToType)->endOfDay()
                ]);
            })
            ->when($ministry, fn($query) => $query->where('smtp_relay_req_form.ministry', $ministry))
            ->when($agency, fn($query) => $query->where('smtp_relay_req_form.agencies', $agency));

        // Retrieve data grouped by ip_active, ip_deactive
        $data = $baseQuery->get()->groupBy(function ($item) {
            if ($item->types_req == 1) return 'ip_active';
            if ($item->types_req == 2) return 'ip_deactive';
        });

        // Retrieve data grouped by sasl, ip_based
        $data2 = $baseQuery->get()->groupBy(function ($item2) {
            if ($item2->smtp_relay_auth_method == 1) return 'ip_based';
            if ($item2->smtp_relay_auth_method == 2) return 'sasl';
        });

//        // Log filtered data
//        \Log::info('Filtered Data (Grouped by IP Active/Deactive): ', $data->toArray());
//        \Log::info('Filtered Data (Grouped by SASL/IP-based): ', $data2->toArray());

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Searching Agencies Reporting',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode(['request' => $request->except(['password'])]),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'dataTbl1' => $data2->get('sasl', collect()),
            'dataTbl2' => $data2->get('ip_based', collect()),
            'data2' => $data->get('ip_active', collect()),
            'data3' => $data->get('ip_deactive', collect()),
            'totalIP' => $data->get('ip_active', collect())->count(),
            'totalDeactivated' => $data->get('ip_deactive', collect())->count(),
            'totalDomain' => $data->get('ip_based', collect())->pluck('fqdn_server')->count()
        ]);
    }

    public function api(Request $request)
    {
        // Retrieve serverId from the request
        $serverId = $request->serverId;

//        // Log serverId
//        \Log::info('Server ID received: ', ['serverId' => $serverId]);

        return $serverId;
    }

    public function getAgencies(Request $request)
    {
        $ministry = $request->input('ministry');
        $agencies = Agency::where('ministry', $ministry)
            ->where('status', 1)
            ->where('name', '!=', '-')
            ->get();

//        // Log retrieved agencies
//        \Log::info('Ministry ID: ', ['ministry' => $ministry]);
//        \Log::info('Retrieved Agencies: ', $agencies->toArray());

        return response()->json($agencies);
    }
}
