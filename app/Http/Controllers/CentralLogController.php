<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
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


    // RUNNING - MEMORY UNLIMITED, SET OUTPUT TO NEW LINE
    public function search(Request $request)
    {
        $logFile = '/opt/src/spmr/storage/cls/mail.log'; // cls mail.log path
        $search = $request->input('search', '');
        $searchLine = $request->input('searchLine', '');
        $searchServer = $request->input('searchServer', '');
        $searchTimeStamp = $request->input('searchTimeStamp', '');
        $searchProcessId = $request->input('searchProcessId', '');
        $searchFilter = $request->input('searchFilter', 'latest');

        // Validate input
        if (empty($search)) {
            return response()->json(['error' => 'Search term cannot be empty.'], 400);
        }

        if ($searchTimeStamp === 'Any') {
            $searchTimeStamp = '(.*|any)$'; // Regular expression for any timestamp
        } /*else {
            $searchTimeStamp = preg_quote($searchTimeStamp, '/'); // Escape if not 'Any'
        }*/

        if ($searchServer === 'Any') {
            $searchServer = '(postmaster1a|postmaster2a|postmaster3a)$'; // Regex for any server
        }/* else {
            $searchServer = preg_quote($searchServer, '/'); // Escape if not 'Any'
        }*/

        if ($searchProcessId === 'Any') {
            $searchProcessId = '/postfix\/smtpd|postfix\/cleanup|postfix\/qgmr/'; // Regex for any process
        } /*else {
            $searchProcessId = preg_quote($searchProcessId, '/'); // Escape if not 'Any'
        }*/

        try {
            // Map filters to commands
            $commands = [
                'latest' => "/usr/bin/tail -n $searchLine $logFile | awk '\$1 ~ /^$searchTimeStamp/ && \$2 ~ /^$searchServer/ && \$3 ~ $searchProcessId' | grep -i $search",
            ];

            // Choose the command based on the filter
            $command = $commands[$searchFilter] ?? $commands['latest'];

            \Log::info("Executing command: $command");

            $process = Process::fromShellCommandline($command);
            ini_set('memory_limit', '-1'); // Unlimited memory or 1024M
            $process->setTimeout(3600);    // Set timeout for the process 3600 sec

            $results = [];
            $process->run(function ($type, $buffer) use (&$results, $search) {
                if (Process::OUT === $type) {
                    // Process each line and highlight the search term
                    foreach (explode("\n", $buffer) as $line) {
                        if (!empty($line)) {
                            $escapedLine = htmlspecialchars($line, ENT_QUOTES, 'UTF-8'); // escape special HTML characters
                            $highlightedLine = preg_replace(
                                '/(' . preg_quote($search, '/') . ')/i',
                                '<span style="color:yellow;background:black;">$1</span>',
                                $escapedLine
                            );

                            $results[] = $highlightedLine;
                        }
                    }
                }
            });

            if ($process->isSuccessful()) {
                if (empty($results)) {
                    return response()->json(['output' => 'No matching results found.']);
                }

                // Limit results if necessary (e.g., show only the first 100 lines)
                $maxResults = $searchLine;
                $displayedResults = array_slice($results, 0, $maxResults);

                // Prepare the output with a new line separator
                $output = implode("\n", $displayedResults); // Join results with new lines

                return response()->json([
                    'output' => nl2br($output), // Convert newlines to <br> for browser display
                    'total_results' => count($results),
                    'shown_results' => count($displayedResults),
                ]);
            } else {
                \Log::error("Process Error Output: " . $process->getErrorOutput());
                return response()->json(['output' => 'No matching results found.']);
            }
        } catch (ProcessTimedOutException $e) {
            return response()->json(['error' => 'The search operation timed out.'], 504); // Gateway Timeout
        } catch (\Exception $e) {
            \Log::error("Exception occurred: " . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }


}
