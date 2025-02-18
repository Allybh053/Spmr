<?php

namespace App\Http\Controllers;

use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Models\ActivityLog;

class SearchSaslController extends Controller
{
    /**
     * Display the search SASL index page.
     *
     * @param Request $request
     * @param int|null $server_id
     * @param string|null $server_type
     * @return \Illuminate\View\View
     */
    public function index(Request $request, $server_id = null, $server_type = null)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;

        //1 cara
        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
            ->pluck('server_id');

        $servers = Server::whereIn('id', $serverIds)
            ->where('status_cluster', 1)
            ->orderBy('id', 'asc')
            ->select(['id', 'hostname'])
            ->get();

        $status = $request->input('status');
        $auth_method = $request->input('auth_method');

        $serverconfig = Server::whereIn('master_id', $serverIds)
            ->where('status_cluster', 1)
            ->orderBy('id', 'asc')
            ->select('id', 'hostname', 'ipaddress', 'subhostname')
            ->get();

        $defaultServerId = $serverIds->min();

        $serverQuery = Server::where('status_cluster', 1);
        if ($server_id && $server_id !== 'all') {
            $serverQuery->where('id', $server_id);
        }
        $serverName = $serverQuery
            ->orderBy('id', 'asc')
            ->pluck('hostname', 'id', 'subhostname',);

        $serverData = [
            "serverName" => "--",
            "ipAddress" => "--",
            "serverStatus" => "--",
            "serviceStatus" => "--",
            "sasl" => "--",
            "smtpdLost" => "--",
            "smtpdTimeOut" => "--",
            "bounced" => "--",
        ];

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'Search SASL',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'activity' => 'Search SASL',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);
        return view('searchSasl.index', compact(
            'user',
            'role',
            'serverName',
            'defaultServerId',
            'serverData',
            'serverconfig',
            'status',
            'auth_method',
            'server_id'
        ));
    }
    /**
     * Fetch SASL data for given SMTP usernames.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

//    public function getsasldata(Request $request)
//    {
//        \Log::info('getSaslData function started');
//        $serverName = $request->input('serverName');
//        $searchInput = $request->input('smtp-username');
//        $searchInputs = !empty($searchInput)
//            ? array_filter(array_map('trim', explode("\n", trim($searchInput))))
//            : [];
//
//        if (empty($searchInputs)) {
//            \Log::info('No SMTP usernames provided');
//            return response()->json([
//                'subhostname' => $serverName !== 'all' ? $serverName : null,
//                'saslData' => [],
//            ]);
//        }
//
//        \Log::info('Search Inputs:', $searchInputs);
//        DB::enableQueryLog();
//        $query = DB::table('smtp_relay_req_form')
//            ->select([
//                'smtp_relay_req_form.form_id',
//                'smtp_relay_req_form.types_req',
//                'smtp_relay_req_form.smtp_relay_server',
//                'smtp_relay_req_form.smtp_username'
//            ])
//            ->where('smtp_relay_req_form.smtp_relay_server', $serverName)
//            ->where(function ($q) use ($searchInputs) {
//                foreach ($searchInputs as $input) {
//                    $q->orWhere('smtp_username', 'LIKE', "%$input%");
//                }
//            });
//
//        $saslData = $query->get();
//        \Log::info('Executed Query:', DB::getQueryLog());
//        \Log::info('SASL Data Retrieved:', $saslData->toArray());
//        $totalCounts = [];
//        foreach ($saslData as $val) {
//            $count = DB::table('smtp_relay_server')
//                ->where('form_id', $val->form_id)
//                ->count();
//            $totalCounts[$val->form_id] = $count;
//        }
//        \Log::info('Form ID Counts:', $totalCounts);
//
//        if ($saslData->isEmpty()) {
//            \Log::info('No SASL data found for the provided SMTP username.');
//        } else {
//            \Log::info('SASL Data Retrieved:', $saslData->toArray());
//        }
//
//        return response()->json([
//            'subhostname' => $serverName !== 'all' ? $serverName : null,
//            'saslData' => $saslData ?? [],
//            'formCounts' => $totalCounts,
//        ]);
//    }
    public function searchServerName(Request $request)
    {
        $serverName = $request->serverName;

        if ($serverName === 'all') {
            $servers = Server::all();
        } else {
            $servers = Server::find($serverName);
        }

        return response()->json($servers);
    }
}

