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

class GenerateReportingController extends Controller
{
    public function index(Request $request, $server_id = null, $server_type = null)
    {
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


        $ministries = Ministry::where('status', 1)->pluck('name')->toArray();
        $agencies = Agency::where('status', 1)->whereNotIn('name',['-'])->pluck('name')->toArray();
        $allMinistryAgency = array_merge($ministries, $agencies);


        $domainsArray = DomainMinistryAgencies::where('enabled', 1)->get()->toArray();

        return view('generateReporting.index',compact('defaultServerId','serverconfig','domainsArray','ministries' ,'agencies','allMinistryAgency'));
    }

    public function search(Request $request)
    {
        // Retrieve other inputs from the request
        $serverIds = $request->input('server');
        $ministry = $request->input('ministry');
        $agency = $request->input('agency');
        $calendarType = $request->input('calendarType');
        $dateType = $request->input('input2');
        $monthType = $request->input('input2_month');
        $yearType = $request->input('input2_year');
        $dateFromType = $request->input('input2_from');
        $dateToType = $request->input('input2_to');

        // You can adjust the search logic based on the selected calendar type and agency here
        $serverMailRelay = Server::find($serverIds);
        $serverName = $serverMailRelay->hostname;

        $dataSmtpFormIpActive = SmtpRelayReqForm::select('smtp_relay_req_form.*', 'smr.*')
                                ->leftJoin('smtp_relay_server as smr','smr.form_id','smtp_relay_req_form.form_id')
                                ->when(isset($calendarType) && $calendarType == 'date', function ($query) use ($dateType) {
                                    $timestamp = strtotime($dateType);
                                    $formattedDate = date("Y-m-d H:i:s", $timestamp);
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
                                ->when($dateFromType != null && $dateToType != null, function ($query) use ($dateFromType,$dateToType) {
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
                                ->where('smtp_relay_req_form.smtp_relay_auth_method', 1)
                                ->get();

        $dataSmtpFormIpActiveCount = $dataSmtpFormIpActive->count();

        $dataSmtpFormIpDeactive = SmtpRelayReqForm::select('smtp_relay_req_form.*', 'smr.*')
                                    ->leftJoin('smtp_relay_server as smr','smr.form_id','smtp_relay_req_form.form_id')
                                    ->when(isset($calendarType) && $calendarType == 'date', function ($query) use ($dateType) {
                                        $timestamp = strtotime($dateType);
                                        $formattedDate = date("Y-m-d H:i:s", $timestamp);
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
                                    ->when($dateFromType != null && $dateToType != null, function ($query) use ($dateFromType,$dateToType) {
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
                                    ->where('smtp_relay_req_form.smtp_relay_auth_method', 2)
                                    ->get();

        $dataSmtpFormIpDeactiveCount = $dataSmtpFormIpDeactive->count();


        $dataSmtpFormSasl = SmtpRelayReqForm::select('smtp_relay_req_form.*', 'smr.*')
                            ->leftJoin('smtp_relay_server as smr','smr.form_id','smtp_relay_req_form.form_id')
                            ->when(isset($calendarType) && $calendarType == 'date', function ($query) use ($dateType) {
                                $timestamp = strtotime($dateType);
                                $formattedDate = date("Y-m-d H:i:s", $timestamp);
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
                            ->when($dateFromType != null && $dateToType != null, function ($query) use ($dateFromType,$dateToType) {
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
                            ->where('smtp_relay_req_form.smtp_relay_auth_method', 2)
                            ->get();

        $dataSmtpFormIpBased = SmtpRelayReqForm::select('smtp_relay_req_form.*', 'smr.*')
                            ->leftJoin('smtp_relay_server as smr','smr.form_id','smtp_relay_req_form.form_id')
                            ->when(isset($calendarType) && $calendarType == 'date', function ($query) use ($dateType) {
                                $timestamp = strtotime($dateType);
                                $formattedDate = date("Y-m-d H:i:s", $timestamp);
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
                            ->when($dateFromType != null && $dateToType != null, function ($query) use ($dateFromType,$dateToType) {
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
                            ->where('smtp_relay_req_form.smtp_relay_auth_method', 1)
                            ->get();



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

        // Searching and filtering
        foreach ($arraySmtpForm as $val) {
            $query = SmtpRelayReqForm::where('smtp_relay_server', $val['smtp_relay_server']);



            // Execute the query
            $totalDataForm = $query->where('status', 1)->get();

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
                    $getTotalIp = smtp_relay_server::where('form_id', $dataForm['form_id'])->where('types_req', 1)->select('ip_addr_server')->count();
                    $getTotalDeactivated = smtp_relay_server::where('form_id', $dataForm['form_id'])->where('types_req', 2)->count();
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
                $totalIpAddr = "0";
                $totalDeactivated = "0";
                $totalDomain = "0";
            }
        }

        return response()->json([
            //'data' => $valDataTables,
            'dataTbl1' => $dataSmtpFormSasl,
            'dataTbl2' => $dataSmtpFormIpBased,
            'data2' => $dataSmtpFormIpActive,
            'data3' => $dataSmtpFormIpDeactive,
            //'totalApplications' => $totalApplications,
            'totalIP' => $dataSmtpFormIpActiveCount,
            'totalDeactivated' => $dataSmtpFormIpDeactiveCount,
            'totalDomain' => $totalDomain,
        ]);
    }

    public function api(Request $request)
    {
        // Retrieve serverId from the request
        $serverId = $request->serverId;

        return $serverId;
    }


    // public function getAgencies(Request $request) {
    //     $ministry = $request->input('ministry');
    //     $agencies = Agency::where('ministry', $ministry)->where('status', 1)->where('name', '!=', '-')->get();
    //     return response()->json($agencies);
    // }



}
