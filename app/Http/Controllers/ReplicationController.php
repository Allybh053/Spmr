<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Excel\ExportStatisticData;
use Illuminate\Support\Facades\Auth;

class ReplicationController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
        ->pluck('server_id');

        $defaultServerId = 1;

        // Filter servers based on the fetched IDs
        $serverName = Server::whereIn('id', $serverIds)
        ->where('status', 1)
        ->pluck('hostname', 'id');

        // Generate fake server names based on the count of $serverNames
        $fakeImages = [];
        $count = count($serverName);
        for ($i = 0; $i < $count; $i++) {
            $fakeImages[] = 'fake_image_' . ($i + 1) . '.jpg';
        }


        return view('validationVerification.replication.index',compact( 'serverName','fakeImages'));
    }


}
