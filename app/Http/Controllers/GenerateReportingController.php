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

class GenerateReportingController extends Controller
{
    public function index(Request $request, $server_id = null, $server_type = null)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
        ->pluck('server_id');

        $defaultServerId = 1;

        // Filter servers based on the fetched IDs
        $serverconfig = Server::whereIn('id', $serverIds)
        ->where('status', 1)->orderBy('id', 'asc')
        ->pluck('hostname', 'id');


        $ministries = Ministry::where('status', 1)->pluck('name')->toArray();
        $agencies = Agency::where('status', 1)->pluck('name')->toArray();
        $allMinistryAgency = array_merge($ministries, $agencies);


        $domainsArray = DomainMinistryAgencies::where('enabled', 1)->get()->toArray();

        return view('generateReporting.index',compact('defaultServerId','serverconfig','domainsArray', 'allMinistryAgency'));
    }

    public function search(Request $request)
    {
        // Retrieve other inputs from the request
        $serverIds = $request->input('server');
        $input1 = $request->input('ministry');
        $input2Type = $request->input('calendarType');
        $dateType = $request->input('input2');
        $monthType = $request->input('input2_month');
        $yearType = $request->input('input2_year');
        $dateFromType = $request->input('input2_from');
        $dateToType = $request->input('input2_to');

        // You can adjust the search logic based on the selected calendar type and agency here
        $serverMailRelay = Server::find($serverIds);
        $serverName = $serverMailRelay->hostname;
        $dataSmtpForm = SmtpRelayReqForm::where('status', 1)->where('smtp_relay_server', $serverName)->get()->toArray();
        $ids = [];
        foreach ($dataSmtpForm as $item) {
            $ids[] = $item['id'];
        }

        $arraySmtpForm = SmtpRelayReqForm::find($ids);
        //$totalApplications = '';
        $totalIpAddr = '';
        $totalDeactivated = '';
        $totalDomain = '';
        $valDataTables = '';
        $valDataTables1 = '';
        $valDataTablesJoin = '';
        $valDataTablesJoin2 ='';

        // Searching and filtering
        /*$queryJoin = SmtpRelayReqForm::select('smtp_relay_req_form.*', 'smtp_relay_server.ip_addr_server')
            ->leftJoin('smtp_relay_server', 'smtp_relay_req_form.form_id', '=', 'smtp_relay_server.form_id');*/

        foreach ($arraySmtpForm as $val) {
            $query = SmtpRelayReqForm::where('smtp_relay_server', $val['smtp_relay_server']);
            $queryJoin = SmtpRelayReqForm::where('smtp_relay_server', $val['smtp_relay_server'])
                ->select('smtp_relay_req_form.*', 'smtp_relay_server.*')
                ->leftJoin('smtp_relay_server', 'smtp_relay_req_form.form_id', '=', 'smtp_relay_server.form_id');
            $queryJoin2 = SmtpRelayReqForm::where('smtp_relay_server', $val['smtp_relay_server'])
                ->select('smtp_relay_req_form.*', 'smtp_relay_server.*')
                ->leftJoin('smtp_relay_server', 'smtp_relay_req_form.form_id', '=', 'smtp_relay_server.form_id');

            // Apply filter based on $input1
            /*if ($input1 !== null) {
                $query->where('agencies', $input1);
                $queryJoin->where('agencies', $input1);
                $queryJoin2->where('agencies', $input1);
            }*/
            if ($input1 !== null) {
                $query->where(function($query) use ($input1) {
                    $query->where('agencies', $input1)
                        ->orWhere('ministry', $input1);
                });

                $queryJoin->where(function($query) use ($input1) {
                    $query->where('agencies', $input1)
                        ->orWhere('ministry', $input1);
                });

                $queryJoin2->where(function($query) use ($input1) {
                    $query->where('agencies', $input1)
                        ->orWhere('ministry', $input1);
                });
            }


            // Apply filter based on $input2Type
            if ($input2Type !== null) {
                if ($input2Type === "date") {
                    $timestamp = strtotime($dateType);
                    $formattedDate = date("Y-m-d H:i:s", $timestamp);
                    $query->whereDate('date_verification_3', $formattedDate);
                    $queryJoin->whereDate('date_verification_3', $formattedDate);
                    $queryJoin2->whereDate('date_verification_3', $formattedDate);
                } elseif ($input2Type === "month") {
                    $startDate = date("Y-m-01 00:00:00", strtotime($monthType));
                    $endDate = date("Y-m-t 23:59:59", strtotime($monthType));
                    $query->whereBetween('date_verification_3', [$startDate, $endDate]);
                    $queryJoin->whereBetween('date_verification_3', [$startDate, $endDate]);
                    $queryJoin2->whereBetween('date_verification_3', [$startDate, $endDate]);
                } elseif ($input2Type === "year") {
                    $startDate = date("Y-01-01 00:00:00", strtotime($yearType));
                    $endDate = date("Y-12-t 23:59:59", strtotime($yearType));
                    $query->whereBetween('date_verification_3', [$startDate, $endDate]);
                    $queryJoin->whereBetween('date_verification_3', [$startDate, $endDate]);
                    $queryJoin2->whereBetween('date_verification_3', [$startDate, $endDate]);
                } elseif ($input2Type === "dateRange") {
                    $startDate = date("Y-m-d 00:00:00", strtotime($dateFromType));
                    $endDate = date("Y-m-t 23:59:59", strtotime($dateToType));
                    $query->whereBetween('date_verification_3', [$startDate, $endDate]);
                    $queryJoin->whereBetween('date_verification_3', [$startDate, $endDate]);
                    $queryJoin2->whereBetween('date_verification_3', [$startDate, $endDate]);
                }
            }

            // Execute the query
            $totalDataForm = $query->where('status', 1)->get();
            $totalDataJoin = $queryJoin->where('deactivated', 0)->get();
            $totalDataJoin2 = $queryJoin2->where('deactivated', 1)->get();

            // Count the number of elements in $totalDataForm
            $totalDataFormCount = count($totalDataForm);

            if ($totalDataFormCount >= 1) {
                // Initialize $totalIP to 0
                //$totalAppAdded = 0;
                $totalIpAdded = 0;
                $totalDeactivatedAdded = 0;
                $totalDomainAdded = 0;

                // Loop through each element of $totalDataForm
                foreach ($totalDataForm as $dataForm) {
                    // Get the count of $totalApp, $totalIp and $totalDeactivated for the current $dataForm element
                    //$getTotalApp = smtp_relay_server::where('form_id', $dataForm['form_id'])->select('server_name')->count();
                    $getTotalIp = smtp_relay_server::where('form_id', $dataForm['form_id'])->where('deactivated', 0)->select('ip_addr_server')->count();
                    $getTotalDeactivated = smtp_relay_server::where('form_id', $dataForm['form_id'])->where('deactivated', 1)->count();
                    $getTotalDomain = smtp_relay_server::where('form_id', $dataForm['form_id'])->select('fqdn_server')->count();


                    // Add the count to $totalApp, $totalIp and $totalDeactivated
                    //$totalAppAdded += $getTotalApp;
                    $totalIpAdded += $getTotalIp;
                    $totalDeactivatedAdded += $getTotalDeactivated;
                    $totalDomainAdded += $getTotalDomain;

                }
                //$totalApplications = $totalAppAdded;
                $totalIpAddr = $totalIpAdded;
                $totalDeactivated = $totalDeactivatedAdded;
                $totalDomain = $totalDomainAdded;


            } else {
                //$totalApplications = "Not Found";
                $totalIpAddr = "Not Found";
                $totalDeactivated = "Not Found";
                $totalDomain = "Not Found";
            }

            $valDataTables = $totalDataForm;
            $valDataTables1 = $query->where('smtp_relay_auth_method', 2)->get();
            $valDataTablesJoin = $totalDataJoin;
            $valDataTablesJoin2 = $totalDataJoin2;
        }

        return response()->json([
            //'data' => $valDataTables,
            'data' => $valDataTables1,
            'data2' => $valDataTablesJoin,
            'data3' => $valDataTablesJoin2,
            //'totalApplications' => $totalApplications,
            'totalIP' => $totalIpAddr,
            'totalDeactivated' => $totalDeactivated,
            'totalDomain' => $totalDomain,
        ]);
    }

    public function api(Request $request)
    {
        // Retrieve serverId from the request
        $serverId = $request->serverId;

        return $serverId;
    }





}
