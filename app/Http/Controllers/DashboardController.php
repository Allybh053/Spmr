<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use Mail;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use Faker\Factory as Faker;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serverconfig = Server::where('status', 1)->pluck('hostname','id');

        return view('dashboard.index', compact('user', 'role','serverconfig'));
    }

    public function api(Request $request)
    {
        $faker = Faker::create();

        $serverId = $request->serverId;
        $ipAddress = $faker->ipv4;
        // Generate fake data for total applications, domains, and SASL
        $totalApplications = $faker->numberBetween(100, 1000);
        $totalDomains = $faker->numberBetween(50, 500);
        $totalSASL = $faker->numberBetween(20, 200);

        // Generate fake data for the table
        $tableData = [];
        for ($i = 0; $i < 5; $i++) {
            $tableData[] = [
                'columnA' => $faker->word,
                'columnB' => $faker->word,
                'columnC' => $faker->word,
                'columnD' => $faker->word,
            ];
        }

        // Generate fake status for each service
        $services = [
            'Postfix' => $faker->randomElement(['Online', 'Offline']),
            'Fail-To-Ban' => $faker->randomElement(['Online', 'Offline']),
            'Apache' => $faker->randomElement(['Online', 'Offline']),
            'Webmin' => $faker->randomElement(['Online', 'Offline']),
            'Replicate Mail Relay' => $faker->randomElement(['Online', 'Offline']),
            'Policy Server' => $faker->randomElement(['Online', 'Offline']),
            'Keep Alive' => $faker->randomElement(['Online', 'Offline']),
        ];

        return new JsonResponse([
            'totalApplications' => $totalApplications,
            'totalDomains' => $totalDomains,
            'totalSASL' => $totalSASL,
            'data' => $tableData,
            'status' => $faker->randomElement(['Online', 'Offline']),
            'services' => $services,
            'ipAddress' => $ipAddress,
        ]);
    }
}
