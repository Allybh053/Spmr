<?php
//
//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Mail;
//use Illuminate\Support\Str;
//use Symfony\Component\Process\Process;
//use App\Models\ActivityLog;
//use App\Models\RoleServer;
//use App\Models\Server;
//use Maatwebsite\Excel\Facades\Excel;
//use Illuminate\Support\Collection;
//use Maatwebsite\Excel\Concerns\FromCollection;
//use Maatwebsite\Excel\Concerns\WithHeadings;
//use App\Exports\QueryLogExport;
//
//class LogQueryController extends Controller
//{
//    public function index(Request $request, $server_id = null, $server_type = null)
//    {
//        $user = Auth::user();
//        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;
//
//        $serverIds = RoleServer::whereIn('role_servers.role_id', $user->roles->pluck('id')->all())
//            ->leftJoin('servers', 'servers.id', 'role_servers.server_id')
//            ->where('servers.status', 1)
//            ->pluck('server_id');
//
//        $defaultServerId = $serverIds->min();
//
//        $serverconfig = Server::whereIn('master_id', $serverIds)
//            ->whereIn('status_cluster', [1, 0])
//            ->orderBy('status_cluster', 'asc')
//            ->select('id', 'subhostname', 'ipaddress')
//            ->get();
//
//        $dataservers = Server::whereIn('status', [1, 0])
//            ->with(['subServers' => function ($query) {
//                $query->select('id', 'hostname', 'ipaddress', 'status_cluster', 'master_id', 'server_type', 'subhostname');
//            }])
//            ->orderBy('id')
//            ->get();
//
//        ActivityLog::create([
//            'user_id' => Auth::id(),
//            'activity_type' => 'generate_spa_report',
//            'action' => $request->path(),
//            'url' => url()->full(),
//            'method' => $request->method(),
//            'ip_address' => $request->ip(),
//            'input' => json_encode([
//                'agency report' => 'Retrieve Default Data For Filter in Agency Reporting',
//                'request' => $request->except(['password']),
//            ]),
//            'user_agent' => $request->userAgent(),
//        ]);
//
//        return view('GenerateQueryLog.index', compact('role', 'defaultServerId', 'serverconfig', 'dataservers', 'serverIds'));
//    }
//
//    public function search(Request $request)
//    {
//        \Log::info('Search function triggered');
////        $logFile = storage_path('cls/mail.log');
//        $logFile = storage_path('cls/sample-jpa-1day.log');
//
//        if (!file_exists($logFile)) {
//            return back()->withErrors(['msg' => 'Log file not found.']);
//        }
//
//        $filters = array_filter([
//            'date' => $request->input('date'),
//            'ip' => $request->input('ip'),
//            'email' => $request->input('email'),
//            'subject' => $request->input('subject'),
//        ]);
//
//        $lines = array_slice(file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES), -1000);
//        $queryLogs = [];
//
//        foreach ($lines as $line) {
//            $match = true;
//
//            foreach ($filters as $key => $value) {
//                if (!Str::contains(strtolower($line), strtolower($value))) {
//                    $match = false;
//                    break;
//                }
//            }
//
//            if ($match) {
//                preg_match('/(?P<date>\w{3} \d{1,2} \d{2}:\d{2}:\d{2})/', $line, $dateMatch);
//                preg_match('/from=<(?P<email>[^>]+)>/', $line, $emailMatch);
//                preg_match('/subject=(?P<subject>.*?)\s/', $line, $subjectMatch);
//                preg_match('/\[([0-9\.]+)\]/', $line, $ipMatch);
//
//                $queryLogs[] = [
//                    'line' => $line,
//                    'date' => $dateMatch['date'] ?? '-',
//                    'server' => '-',
//                    'ip' => $ipMatch[1] ?? '-',
//                    'email' => $emailMatch['email'] ?? '-',
//                    'subject' => $subjectMatch['subject'] ?? '-',
//                    'file' => '',
//                ];
//            }
//        }
//
//        $generatedAt = now()->format('Y-m-d H:i:s');
//
//        return view('GenerateQueryLog.index', compact('queryLogs', 'generatedAt', 'filters'));
//    }
////    public function exportExcel(Request $request)
////    {
////        $filters = array_filter([
////            'date' => $request->input('date'),
////            'ip' => $request->input('ip'),
////            'email' => $request->input('email'),
////            'subject' => $request->input('subject'),
////        ]);
////
////        $logFile = storage_path('cls/mail.log');
//////        $logFile = env('MAIL_LOG_PATH');
////        //$logFile = storage_path('/your-staging-app/cls/mail.log');
////
////        if (!file_exists($logFile)) {
////            return back()->with('message', 'Log file not found.');
////        }
////
////        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
////        $queryLogs = [];
////        $counter = 1;
////        if (!file_exists($logFile)) {
////            return back()->with('message', 'Log file not found.');
////        }
////
////        $handle = fopen($logFile, "r");
////        if ($handle) {
////            while (($line = fgets($handle)) !== false) {
////                // Your matching logic here:
////                $match = true; // Example, replace with your actual logic
////
////                if ($match) {
////                    $queryLogs[] = [
////                        'no' => $counter++,
////                        'line' => $line,
////                        // other fields you want to extract
////                    ];
////                }
////            }
////            fclose($handle);
////        } else {
////            return back()->with('message', 'Unable to open log file.');
////        }
////
////        foreach ($lines as $line) {
////            $match = true;
////
////            foreach ($filters as $key => $value) {
////                if (!Str::contains(strtolower($line), strtolower($value))) {
////                    $match = false;
////                    break;
////                }
////            }
////
////            if ($match) {
////                preg_match('/^(?P<timestamp>\w{3} \d{1,2} \d{2}:\d{2}:\d{2})/', $line, $timestampMatch);
////                preg_match('/from=<(?P<from>[^>]+)>/', $line, $fromMatch);
////                preg_match('/to=<(?P<to>[^>]+)>/', $line, $toMatch);
////                preg_match('/subject=(?P<subject>.*?)\s+(?=\S+=|$)/', $line, $subjectMatch);
////                preg_match('/relay=([^\s\[]+)/', $line, $receiverIpMatch);
////                preg_match('/\[([0-9\.]+)\]/', $line, $senderIpMatch);
////                preg_match('/status=(?P<status>\w+)/', $line, $statusMatch);
////                preg_match('/id=(?P<inbound_id>\S+)/', $line, $inboundIdMatch);
////                preg_match('/dsn=\S+.*? (\d{3}.*)/', $line, $outboundIdMatch); // custom
////                preg_match('/attachments=(?P<attachments>\S+)/', $line, $attachmentMatch);
////
////                $queryLogs[] = [
////                    'Hostname' => gethostname(),
////                    'No.' => $counter++,
////                    'Timestamp' => $timestampMatch['timestamp'] ?? '-',
////                    'MTA Sender IP' => $senderIpMatch[1] ?? '-',
////                    'Inbound ID' => $inboundIdMatch['inbound_id'] ?? '-',
////                    'Status' => $statusMatch['status'] ?? '-',
////                    'MTA Receiver IP' => $receiverIpMatch[1] ?? '-',
////                    'Outbound ID' => $outboundIdMatch[1] ?? '-',
////                    'FROM Address' => $fromMatch['from'] ?? '-',
////                    'TO Address' => $toMatch['to'] ?? '-',
////                    'Subject' => $subjectMatch['subject'] ?? '-',
////                    'Attachments' => $attachmentMatch['attachments'] ?? '-',
////                    'Remarks' => '',
////                ];
////            }
////        }
////
////        if (empty($queryLogs)) {
////            return back()->with('message', 'No results found.');
////        }
////
////        return Excel::download(new class($queryLogs) implements FromCollection, WithHeadings {
////            protected $logs;
////
////            public function __construct(array $logs)
////            {
////                $this->logs = $logs;
////            }
////
////            public function collection(): Collection
////            {
////                return collect($this->logs);
////            }
////
////            public function headings(): array
////            {
////                return [
////                    'Hostname',
////                    'No.',
////                    'Timestamp',
////                    'MTA Sender IP',
////                    'Inbound ID',
////                    'Status',
////                    'MTA Receiver IP',
////                    'Outbound ID',
////                    'FROM Address',
////                    'TO Address',
////                    'Subject',
////                    'Attachments',
////                    'Remarks',
////                ];
////            }
////        }, 'query_log_report.xlsx');
////    }
////    public function exportExcel(Request $request)
////    {
////        $filters = array_filter([
////            'date' => $request->input('date'),
////            'ip' => $request->input('ip'),
////            'email' => $request->input('email'),
////            'subject' => $request->input('subject'),
////        ]);
////
//////        $logFile = storage_path('cls/mail.log');
////        $logFile = storage_path('cls/sample-jpa-1day.log');
////
////        if (!file_exists($logFile)) {
////            return back()->with('message', 'Log file not found.');
////        }
////
////        $queryLogs = [];
////        $counter = 1;
////
////        $handle = fopen($logFile, "r");
////        if ($handle) {
////            while (($line = fgets($handle)) !== false) {
////                $match = true;
////
////                foreach ($filters as $key => $value) {
////                    if (!Str::contains(strtolower($line), strtolower($value))) {
////                        $match = false;
////                        break;
////                    }
////                }
////
////                if ($match) {
////                    preg_match('/^(?P<timestamp>\w{3} \d{1,2} \d{2}:\d{2}:\d{2})/', $line, $timestampMatch);
////                    preg_match('/from=<(?P<from>[^>]+)>/', $line, $fromMatch);
////                    preg_match('/to=<(?P<to>[^>]+)>/', $line, $toMatch);
////                    preg_match('/subject=(?P<subject>.*?)\s+(?=\S+=|$)/', $line, $subjectMatch);
////                    preg_match('/relay=([^\s\[]+)/', $line, $receiverIpMatch);
////                    preg_match('/\[([0-9\.]+)\]/', $line, $senderIpMatch);
////                    preg_match('/status=(?P<status>\w+)/', $line, $statusMatch);
////                    preg_match('/id=(?P<inbound_id>\S+)/', $line, $inboundIdMatch);
////                    preg_match('/dsn=\S+.*? (\d{3}.*)/', $line, $outboundIdMatch);
////                    preg_match('/attachments=(?P<attachments>\S+)/', $line, $attachmentMatch);
////
////                    $queryLogs[] = [
////                        'Hostname' => gethostname(),
////                        'No.' => $counter++,
////                        'Timestamp' => $timestampMatch['timestamp'] ?? '-',
////                        'MTA Sender IP' => $senderIpMatch[1] ?? '-',
////                        'Inbound ID' => $inboundIdMatch['inbound_id'] ?? '-',
////                        'Status' => $statusMatch['status'] ?? '-',
////                        'MTA Receiver IP' => $receiverIpMatch[1] ?? '-',
////                        'Outbound ID' => $outboundIdMatch[1] ?? '-',
////                        'FROM Address' => $fromMatch['from'] ?? '-',
////                        'TO Address' => $toMatch['to'] ?? '-',
////                        'Subject' => $subjectMatch['subject'] ?? '-',
////                        'Attachments' => $attachmentMatch['attachments'] ?? '-',
////                        'Remarks' => '',
////                    ];
////                }
////            }
////            fclose($handle);
////        } else {
////            return back()->with('message', 'Unable to open log file.');
////        }
////
////        if (empty($queryLogs)) {
////            return back()->with('message', 'No results found.');
////        }
////
////        return Excel::download(new class($queryLogs) implements FromCollection, WithHeadings {
////            protected $logs;
////
////            public function __construct(array $logs)
////            {
////                $this->logs = $logs;
////            }
////
////            public function collection(): Collection
////            {
////                return collect($this->logs);
////            }
////
////            public function headings(): array
////            {
////                return [
////                    'Hostname',
////                    'No.',
////                    'Timestamp',
////                    'MTA Sender IP',
////                    'Inbound ID',
////                    'Status',
////                    'MTA Receiver IP',
////                    'Outbound ID',
////                    'FROM Address',
////                    'TO Address',
////                    'Subject',
////                    'Attachments',
////                    'Remarks',
////                ];
////            }
////        }, 'query_log_report.xlsx');
////    }
//
//    public function exportExcel(Request $request)
//    {
//        ini_set('memory_limit', '1G');
//        $filters = array_filter([
//            'date' => $request->input('date'),
//            'ip' => $request->input('ip'),
//            'email' => $request->input('email'),
//            'subject' => $request->input('subject'),
//        ]);
//
//        $logFile = storage_path('cls/sample-jpa-1day.log');
//        $logFilename = basename($logFile);
//
//        if (!file_exists($logFile)) {
//            return back()->with('message', 'Log file not found.');
//        }
//
//        return Excel::download(new QueryLogExport($filters, $logFilename), 'query_log_report.xlsx');
//    }
//
//
////    public function exportExcel(Request $request)
////    {
////        $filters = array_filter([
////            'date' => $request->input('date'),
////            'ip' => $request->input('ip'),
////            'email' => $request->input('email'),
////            'subject' => $request->input('subject'),
////        ]);
////
////        $logFile = storage_path('cls/sample-jpa-1day.log');
////
////        if (!file_exists($logFile)) {
////            return back()->with('message', 'Log file not found.');
////        }
////
////        $queryLogs = [];
////        $counter = 1;
////
////        $handle = fopen($logFile, "r");
////        if ($handle) {
////            while (($line = fgets($handle)) !== false) {
////                if (!str_contains($line, 'postfix/smtp')) {
////                    continue;
////                }
////
////                $match = true;
////                foreach ($filters as $key => $value) {
////                    if (!Str::contains(strtolower($line), strtolower($value))) {
////                        $match = false;
////                        break;
////                    }
////                }
////
////                if ($match) {
////                    preg_match('/^(?P<timestamp>\w{3} \d{1,2} \d{2}:\d{2}:\d{2})/', $line, $timestampMatch);
////                    preg_match('/from=<(?P<from>[^>]+)>/', $line, $fromMatch);
////                    preg_match('/to=<(?P<to>[^>]+)>/', $line, $toMatch);
////                    preg_match('/subject=(?P<subject>.+?)($|\s+\w+=)/', $line, $subjectMatch);
////                    preg_match('/relay=[^\s\[]+\[(?P<receiver_ip>[^\]]+)\]/', $line, $receiverIpMatch);
////                    preg_match('/\[(?P<sender_ip>\d+\.\d+\.\d+\.\d+)\]/', $line, $senderIpMatch);
////                    preg_match('/status=(?P<status>\w+)/', $line, $statusMatch);
////                    preg_match('/postfix\/smtp\[\d+\]: (?P<inbound_id>\S+):/', $line, $inboundIdMatch);
////                    preg_match('/status=\w+ \((?P<outbound>.*?)\)/', $line, $outboundIdMatch);
////                    preg_match('/^\w{3} \d{1,2} \d{2}:\d{2}:\d{2} (?P<hostname>\S+)/', $line, $hostnameMatch);
////
////                    $queryLogs[] = [
////                        'Hostname' => $hostnameMatch['hostname'] ?? gethostname(),
////                        'No.' => $counter++,
////                        'Timestamp' => $timestampMatch['timestamp'] ?? '-',
////                        'MTA Sender IP' => $senderIpMatch['sender_ip'] ?? '-',
////                        'Inbound ID' => $inboundIdMatch['inbound_id'] ?? '-',
////                        'Status' => $statusMatch['status'] ?? '-',
////                        'MTA Receiver IP' => $receiverIpMatch['receiver_ip'] ?? '-',
////                        'Outbound ID' => $outboundIdMatch['outbound'] ?? '-',
////                        'FROM Address' => $fromMatch['from'] ?? '-',
////                        'TO Address' => $toMatch['to'] ?? '-',
////                        'Subject' => $subjectMatch['subject'] ?? '-',
////                        'Attachments' => '-', // Adjust if attachment info is logged
////                        'Remarks' => '',
////                    ];
////                }
////            }
////            fclose($handle);
////        } else {
////            return back()->with('message', 'Unable to open log file.');
////        }
////
////        if (empty($queryLogs)) {
////            return back()->with('message', 'No results found.');
////        }
////
////        return Excel::download(new class($queryLogs) implements FromCollection, WithHeadings {
////            protected $logs;
////
////            public function __construct(array $logs)
////            {
////                $this->logs = $logs;
////            }
////
////            public function collection(): Collection
////            {
////                return collect($this->logs);
////            }
////
////            public function headings(): array
////            {
////                return [
////                    'Hostname',
////                    'No.',
////                    'Timestamp',
////                    'MTA Sender IP',
////                    'Inbound ID',
////                    'Status',
////                    'MTA Receiver IP',
////                    'Outbound ID',
////                    'FROM Address',
////                    'TO Address',
////                    'Subject',
////                    'Attachments',
////                    'Remarks',
////                ];
////            }
////        }, 'query_log_report.xlsx');
////    }
//}


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\ActivityLog;
use App\Models\RoleServer;
use App\Models\Server;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Exports\QueryLogExport;

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

    public function search(Request $request)
    {
        Log::info('Search function triggered');
        $logFile = storage_path('cls/sample-jpa-1day.log');

        if (!file_exists($logFile)) {
            return back()->withErrors(['msg' => 'Log file not found.']);
        }

        $filters = array_filter([
            'date' => $request->input('date'),
            'ip' => $request->input('ip'),
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
        ]);

        $lines = array_slice(file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES), -1000);
        $queryLogs = [];

        foreach ($lines as $line) {
            $match = true;

            foreach ($filters as $key => $value) {
                if (!Str::contains(strtolower($line), strtolower($value))) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                preg_match('/(?P<date>\w{3} \d{1,2} \d{2}:\d{2}:\d{2})/', $line, $dateMatch);
                preg_match('/from=<(?P<email>[^>]+)>/', $line, $emailMatch);
                preg_match('/subject=(?P<subject>.*?)\s/', $line, $subjectMatch);
                preg_match('/\[([0-9\.]+)\]/', $line, $ipMatch);

                $queryLogs[] = [
                    'line' => $line,
                    'date' => $dateMatch['date'] ?? '-',
                    'server' => '-',
                    'ip' => $ipMatch[1] ?? '-',
                    'email' => $emailMatch['email'] ?? '-',
                    'subject' => $subjectMatch['subject'] ?? '-',
                    'file' => '',
                ];
            }
        }

        $generatedAt = now()->format('Y-m-d H:i:s');

        return view('GenerateQueryLog.index', compact('queryLogs', 'generatedAt', 'filters'));
    }

    public function exportExcel(Request $request)
    {
        ini_set('memory_limit', '1G');

        $filters = array_filter([
            'date' => $request->input('date'),
            'ip' => $request->input('ip'),
            'email' => $request->input('email'),
            'subject' => $request->input('subject'),
        ]);

        $logFile = storage_path('cls/sample-jpa-1day.log');
        $logFilename = basename($logFile);

        if (!file_exists($logFile)) {
            return back()->with('message', 'Log file not found.');
        }

        return Excel::download(new QueryLogExport($filters, $logFilename), 'query_log_report.xlsx');
    }
}
