<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UcOverviewController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function indexApplication()
    {
        return view('ucOverview.application.index');
    }

    public function datatableApplication()
    {
                // Dummy data array
                $application = [
                    [
                        'id' => 1,
                        'ticket_no' => 'SR-123456',
                        'types_application' => 'New Application',
                        'project_name' => 'MyLAND PROD',
                        'server_name' => 'JKPTG_ApacheWeb1_P',
                        'ip_address' => '10.10.10.101',
                        'fqdn_system' => 'myland.gov.my',
                        'date_created' => '20-06-2024',
                        'date_updated' => '20-06-2024',
                        'status' => 'Done Created',
                    ],
                    // Add more rows as needed
                ];
    
                return datatables()->of($application)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($row) {
                    return $row['ticket_no'];
                })
                ->addColumn('types_application', function ($row) {
                    return $row['types_application'];
                })
                ->addColumn('project_name', function ($row) {
                    return $row['project_name'];
                })
                ->addColumn('server_name', function ($row) {
                    return $row['server_name'];
                })
                ->addColumn('ip_address_server', function ($row) {
                    return $row['ip_address'];
                })
                ->addColumn('fqdn_system', function ($row) {
                    return $row['fqdn_system'];
                })
                ->addColumn('date_created', function ($row) {
                    return $row['date_created'];
                })
                ->addColumn('date_updated', function ($row) {
                    return $row['date_updated'];
                })
                ->addColumn('status', function ($row) {
                    return $row['status'];
                })
                ->make(true);
    }


    public function indexDomain()
    {
        return view('ucOverview.domain.index');
    }

    public function datatableDomain()
    {
                // Dummy data array
                $application = [
                    [
                        'id' => 1,
                        'ticket_no' => 'SR-123456',
                        'types_application' => 'New Application',
                        'project_name' => 'MyLAND PROD',
                        'server_name' => 'JKPTG_ApacheWeb1_P',
                        'ip_address' => '10.10.10.101',
                        'fqdn_system' => 'myland.gov.my',
                        'date_created' => '20-06-2024',
                        'date_updated' => '20-06-2024',
                        'status' => 'Done Created',
                    ],
                    // Add more rows as needed
                ];
    
                return datatables()->of($application)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($row) {
                    return $row['ticket_no'];
                })
                ->addColumn('types_application', function ($row) {
                    return $row['types_application'];
                })
                ->addColumn('project_name', function ($row) {
                    return $row['project_name'];
                })
                ->addColumn('server_name', function ($row) {
                    return $row['server_name'];
                })
                ->addColumn('ip_address_server', function ($row) {
                    return $row['ip_address'];
                })
                ->addColumn('fqdn_system', function ($row) {
                    return $row['fqdn_system'];
                })
                ->addColumn('date_created', function ($row) {
                    return $row['date_created'];
                })
                ->addColumn('date_updated', function ($row) {
                    return $row['date_updated'];
                })
                ->addColumn('status', function ($row) {
                    return $row['status'];
                })
                ->make(true);
    }


    public function indexUsername()
    {
        return view('ucOverview.username.index');
    }

    public function datatableUsername()
    {
                // Dummy data array
                $application = [
                    [
                        'id' => 1,
                        'ticket_no' => 'SR-123456',
                        'types_application' => 'New Application',
                        'project_name' => 'MyLAND PROD',
                        'server_name' => 'JKPTG_ApacheWeb1_P',
                        'ip_address' => '10.10.10.101',
                        'fqdn_system' => 'myland.gov.my',
                        'date_created' => '20-06-2024',
                        'date_updated' => '20-06-2024',
                        'status' => 'Done Created',
                    ],
                    // Add more rows as needed
                ];
    
                return datatables()->of($application)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($row) {
                    return $row['ticket_no'];
                })
                ->addColumn('types_application', function ($row) {
                    return $row['types_application'];
                })
                ->addColumn('project_name', function ($row) {
                    return $row['project_name'];
                })
                ->addColumn('server_name', function ($row) {
                    return $row['server_name'];
                })
                ->addColumn('ip_address_server', function ($row) {
                    return $row['ip_address'];
                })
                ->addColumn('fqdn_system', function ($row) {
                    return $row['fqdn_system'];
                })
                ->addColumn('date_created', function ($row) {
                    return $row['date_created'];
                })
                ->addColumn('date_updated', function ($row) {
                    return $row['date_updated'];
                })
                ->addColumn('status', function ($row) {
                    return $row['status'];
                })
                ->make(true);
    }

    public function indexWildcard()
    {
        return view('ucOverview.wildcard.index');
    }

    public function datatableWildcard()
    {
                // Dummy data array
                $application = [
                    [
                        'id' => 1,
                        'ticket_no' => 'SR-123456',
                        'types_application' => 'New Application',
                        'project_name' => 'MyLAND PROD',
                        'server_name' => 'JKPTG_ApacheWeb1_P',
                        'ip_address' => '10.10.10.101',
                        'fqdn_system' => 'myland.gov.my',
                        'date_created' => '20-06-2024',
                        'date_updated' => '20-06-2024',
                        'status' => 'Done Created',
                    ],
                    // Add more rows as needed
                ];
    
                return datatables()->of($application)
                ->addIndexColumn()
                ->addColumn('ticket_number', function ($row) {
                    return $row['ticket_no'];
                })
                ->addColumn('types_application', function ($row) {
                    return $row['types_application'];
                })
                ->addColumn('project_name', function ($row) {
                    return $row['project_name'];
                })
                ->addColumn('server_name', function ($row) {
                    return $row['server_name'];
                })
                ->addColumn('ip_address_server', function ($row) {
                    return $row['ip_address'];
                })
                ->addColumn('fqdn_system', function ($row) {
                    return $row['fqdn_system'];
                })
                ->addColumn('date_created', function ($row) {
                    return $row['date_created'];
                })
                ->addColumn('date_updated', function ($row) {
                    return $row['date_updated'];
                })
                ->addColumn('status', function ($row) {
                    return $row['status'];
                })
                ->make(true);
    }

}
