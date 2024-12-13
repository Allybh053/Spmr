<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Http\JsonResponse;

class CentralLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
        $serversArray = Server::leftJoin('role_servers as rs','servers.master_id','rs.server_id')
            ->where('rs.role_id',$user->roles->pluck('id')->first())
            ->whereIn('servers.status_cluster',[1,0])
            ->orderBy('servers.master_id', 'asc')
            ->get();

        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
            ->leftJoin('servers','servers.master_id','role_servers.server_id')
            ->whereIn('servers.status_cluster',[1,0])
            ->pluck('server_id');

        $defaultServerId = $serverIds->min();


        $serverIp = Server::find($defaultServerId);

        // Filter servers based on the fetched IDs
        $serverconfig = Server::whereIn('master_id', $serverIds)
            ->orderBy('status_cluster', 'desc')
            ->pluck('subhostname', 'id');

        /*ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'View Central Log',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'maillog' => 'Retrieve related data for viewing central log page',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);*/

        return view('centralLog.index',compact( 'user', 'role', 'serversArray', 'serverIds', 'defaultServerId', 'serverconfig','serverIp'));
        //return view('centralLog.index');
    }

    public function search(Request $request)
    {
        $logFile = '/opt/src/spmr/storage/cls/mail.log'; // ajust the path for UNIX
        //$logFile = 'C:\\laragon\\www\\spmr\\storage\\cls\\mail.log'; // adjust the path for Windows Development
        //$logFile = 'storage\\cls\\mail.log'; // adjust the path for Windows Development
        $search = $request->input('search', '');

        // validate the input
        if (empty($search)) {
            return response()->json(['error' => 'Search term cannot be empty.'], 400);
        }

        try {
            // escape the search term to prevent shell injection
            $escapedSearch = escapeshellarg($search);

            // build the command properly
            //$command = "grep -E $escapedSearch $logFile";
            //$command = "grep -i $escapedSearch $logFile";
            //$command = "fgrep -w $escapedSearch $logFile";
            //$command = "fgrep -wm 10 $escapedSearch $logFile"; // Stops after 10 matches
            //$command = "head -n 1000 $logFile | fgrep -w $escapedSearch"; // Search in the first 1000 lines
            $command = "/usr/bin/tail -f -n 1000 $logFile | fgrep -w $escapedSearch"; // Search in the last 1000 lines
            //$command = "fgrep -w $escapedSearch $logFile | slow";
            \Log::info("Executing command: $command");

            /*$process = Process::fromShellCommandline($command);
            $process->setTimeout(300); // Optional if the file is large
            $process->run(function ($type, $buffer) {
                if (Process::OUT === $type) {
                    echo $buffer; // Process each line of output
                } else {
                    echo "ERROR: $buffer";
                }
            });*/

            $process = Process::fromShellCommandline($command);
            //$process->setTimeout(300); // Set timeout to 300 seconds (5 minutes)

            // run the process
            $process->run();
            \Log::info("Process Output: " . $process->getOutput());

            // handle the result
            if ($process->isSuccessful()) {
                $output = $process->getOutput();

                // highlight the matched search term
                $highlightedOutput = preg_replace(
                    '/(' . preg_quote($search, '/') . ')/i',
                    '<span style="color:yellow;background:black;">$1</span>',
                    $output
                );

                //return response()->json(['output' => nl2br(e($highlightedOutput))]);
                return response()->json(['output' => nl2br($highlightedOutput)]);
            } else {
                \Log::error("Process Error Output: " . $process->getErrorOutput());
                return response()->json(['output' => 'No matching results found.']);
            }
        } catch (ProcessFailedException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            \Log::error("Exception occurred: " . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    /*public function search(Request $request)
    {
        $logFile = 'C:\\opt\\src\\spmr\\storage\\cls\\mail.log'; // Adjust the path for Windows
        $search = $request->input('search', '');

        if (empty($search)) {
            return response()->json(['error' => 'Search term cannot be empty.'], 400);
        }

        try {
            if (!file_exists($logFile)) {
                return response()->json(['error' => 'Log file not found.'], 404);
            }

            $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $matches = [];

            foreach ($lines as $line) {
                if (stripos($line, $search) !== false) {
                    $matches[] = $line;
                }
            }

            if (!empty($matches)) {
                $highlightedMatches = array_map(function ($line) use ($search) {
                    return preg_replace(
                        '/(' . preg_quote($search, '/') . ')/i',
                        '<span style="color:yellow;background:black;">$1</span>',
                        $line
                    );
                }, $matches);

                return response()->json(['output' => nl2br(implode("\n", $highlightedMatches))]);
            } else {
                return response()->json(['output' => 'No matching results found.']);
            }
        } catch (\Exception $e) {
            \Log::error("Exception occurred: " . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }*/

}
