<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\ActivityLog;
use App\Models\RoleServer;
use App\Models\Server;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Exports\QueryLogExport;
use App\Models\LogSearchResult;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


class LogQueryController extends Controller
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

        $serverconfig = Server::whereIn('master_id', $serverIds)
            ->whereIn('status_cluster', [1, 0])
            ->orderBy('status_cluster', 'asc')
            ->select('id', 'subhostname', 'ipaddress')
            ->get();

        $dataservers = Server::whereIn('status', [1, 0])
            ->with(['subServers' => function ($query) {
                $query->select('id', 'hostname', 'ipaddress', 'status_cluster', 'master_id', 'server_type', 'subhostname');
            }])
            ->orderBy('id')
            ->get();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'generate_spa_report',
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

        return view('GenerateQueryLog.index', compact('role', 'defaultServerId', 'serverconfig', 'dataservers', 'serverIds'));
    }
    public function runQuery(Request $request)
    {
        $validated = $request->validate([
            'hostname'       => 'required|string',
            'sourceLog'      => 'required|string',
            'tarikhsearch'   => 'required|string',
            'senderIP'       => 'required|ip',
            'senderFrom'     => 'required|email',
            'emailSubject'   => 'nullable|string',
            'deepSearch'     => 'required|in:Y,N',
            'outFilename'    => 'required|string',
            'resultEmailTo'  => 'required|email',
        ]);

        $bashPath = 'C:\Program Files\Git\bin\bash.exe';
        $scriptPath = base_path('query/query.sh');

        $args = [
            escapeshellarg($validated['hostname']),
            escapeshellarg($validated['sourceLog']),
            escapeshellarg($validated['tarikhsearch']),
            escapeshellarg($validated['senderIP']),
            escapeshellarg($validated['senderFrom']),
            escapeshellarg($validated['emailSubject'] ?? ''),
            escapeshellarg($validated['deepSearch']),
            escapeshellarg($validated['outFilename']),
            escapeshellarg($validated['resultEmailTo']),
        ];

        // Final command
        $fullCommand = array_merge([$bashPath, $scriptPath], $args);

        try {
            $process = new Process($fullCommand);
            $process->setTimeout(300);

            // Set environment path for Python
            $process->setEnv([
                'PATH' => 'C:\laragon\bin\python\python-3.10;' . getenv('PATH'),
            ]);

            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            return back()->with('success', 'Query executed successfully. Check output folder for XLSX.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to execute query: ' . $e->getMessage());
        }
    }
}
