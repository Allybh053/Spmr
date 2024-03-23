<?php

namespace App\Http\Controllers;

use App\Models\DomainMinistryAgencies;
use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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
        ->where('status', 1)
        ->pluck('hostname', 'id');

        $agency = [];
        $domainsArray = DomainMinistryAgencies::where('enabled', 1)->get()->toArray();

        return view('generateReporting.index',compact('defaultServerId','serverconfig','domainsArray'));
    }

    public function search(Request $request)
    {
        $input1 = $request->input('agency'); // Assuming 'agency' is the name of the select input for Kementerian/Agensi/SUK
        $input2Type = $request->input('calendarType');

        // Generate Faker data based on the selected calendar type and agency
        $faker = Faker::create();
        $totalApplications = $faker->numberBetween(100, 500);
        $totalIP = $faker->numberBetween(200, 1000);
        $totalDeactivated = $faker->numberBetween(5, 50);

        // You can adjust the search logic based on the selected calendar type and agency here

        return response()->json([
            'totalApplications' => $totalApplications,
            'totalIP' => $totalIP,
            'totalDeactivated' => $totalDeactivated
        ]);
    }


}
