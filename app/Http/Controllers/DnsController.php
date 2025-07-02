<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\RoleServer;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Astrotomic\Dns\Facades\Dns;
use Spatie\Dns\Dns as SpatieDns;
use Spatie\Dns\Records\A;

class DnsController extends Controller
{
    private const ALLOWED_RECORD_TYPES = ['A', 'MX', 'PTR', 'NS', 'SRV', 'AAAA', 'TXT'];

    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->roles->isNotEmpty() ? $user->roles->pluck('name')->first() : null;

        $serverIds = RoleServer::whereIn('role_id', $user->roles->pluck('id')->all())
            ->pluck('server_id');

        $servers = Server::all();

        $serverconfig = Server::whereIn('master_id', $serverIds)
            ->where('status_cluster', 1)
            ->orderBy('id', 'asc')
            ->select('id', 'hostname', 'ipaddress', 'subhostname')
            ->get();

        $defaultServerId = null;

        ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => 'DNS Monitoring',
            'action' => $request->path(),
            'url' => url()->full(),
            'method' => $request->method(),
            'ip_address' => $request->ip(),
            'input' => json_encode([
                'activity' => 'DNS Monitoring',
                'request' => $request->except(['password']),
            ]),
            'user_agent' => $request->userAgent(),
        ]);

        return view('dns.index', compact(
            'user',
            'role',
            'defaultServerId',
            'servers',
            'serverconfig',
        ));
    }

    public function run(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'hostname' => 'required|string',
                'record_type' => ['required', 'string', Rule::in(self::ALLOWED_RECORD_TYPES)],
                'dns_server' => ['nullable', 'string', function ($attribute, $value, $fail) {
                    $servers = preg_split('/[\r\n,]+/', $value);
                    foreach ($servers as $server) {
                        $server = trim($server);
                        if ($server === '') continue;
                        if (!filter_var($server, FILTER_VALIDATE_IP) && !preg_match('/^([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+$/', $server)) {
                            $fail("Each DNS server must be a valid IP address or hostname.");
                            break;
                        }
                    }
                }],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $hostname = $request->input('hostname');
            $recordType = strtoupper($request->input('record_type'));
            $dnsServerInput = $request->input('dns_server');

            $dnsServers = [];
            if ($dnsServerInput) {
                $dnsServers = preg_split('/[\r\n,]+/', $dnsServerInput);
                $dnsServers = array_filter(array_map('trim', $dnsServers));
            }

            if (empty($dnsServers)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'At least one DNS server must be provided.',
                ], 422);
            }

            $results = [];
            foreach ($dnsServers as $dnsServerRaw) {
                $dnsServer = filter_var($dnsServerRaw, FILTER_VALIDATE_IP)
                    ? $dnsServerRaw
                    : gethostbyname($dnsServerRaw);

                $lookupResult = $this->performDnsLookup($hostname, $recordType, $dnsServer);

                $recordDetails = array_map(function ($rec) {
                    return [
                        'host' => $rec['host'] ?? '',
                        'type' => $rec['type'] ?? '',
                        'ip' => $rec['ip'] ?? 'N/A',
                        'ttl' => $rec['ttl'] ?? 'N/A',
                        'class' => $rec['class'] ?? 'N/A',
                        'target' => $rec['target'] ?? null,
                        'priority' => $rec['priority'] ?? null,
                    ];
                }, $lookupResult['astrotomic_records'] ?? []);

                $results[] = [
                    'dns_server' => $dnsServer,
                    'records' => $lookupResult['spatie_records'] ?? [],
                    'resolved_records' => $recordDetails,
                    'internal_queries' => $lookupResult['internal_queries'] ?? [],
                    'external_queries' => $lookupResult['external_queries'] ?? [],
                    'error' => $lookupResult['error'] ?? null,
                ];
            }

            return response()->json([
                'status' => 'success',
                'hostname' => $hostname,
                'record_type' => $recordType,
                'results' => $results,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

//    private function performDnsLookup($hostname, $recordType, $dnsServer)
//    {
//        try {
//            $recordType = strtoupper($recordType);
//
//            $spatieDns = (new SpatieDns())
//                ->useNameserver($dnsServer)
//                ->setTimeout(2)
//                ->setRetries(2);
//
//            $spatieRecords = $spatieDns->getRecords($hostname, $recordType);
//
//            $spatieRecordsFormatted = [];
//            $internalQueries = [];
//            $externalQueries = [];
//
//            foreach ($spatieRecords as $record) {
//                $recordString = (string)$record;
//                $spatieRecordsFormatted[] = $recordString;
//
//                // Extract IP address if this is an A record
//                if ($recordType === 'A' && preg_match('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $recordString, $matches)) {
//                    $ip = $matches[0];
//                    if ($this->isInternalIP($ip)) {
//                        $internalQueries[] = $ip;
//                    } else {
//                        $externalQueries[] = $ip;
//                    }
//                }
//            }
//
//            $astrotomicRecords = Dns::records($hostname, constant("DNS_{$recordType}"));
//
//            $astrotomicRecordsFormatted = $astrotomicRecords->map(function ($record) use ($recordType) {
//                $base = [
//                    'host' => $record->host(),
//                    'ttl' => $record->ttl(),
//                    'class' => $record->class(),
//                    'type' => $record->type(),
//                    'ip' => $record instanceof A ? $record->ip() : null,
//                ];
//
//                if ($recordType === 'MX' && method_exists($record, 'target')) {
//                    $base['target'] = $record->target();
//                    $base['priority'] = $record->priority();
//                }
//
//                return $base;
//            })->all();
//
//            return [
//                'dns_server' => $dnsServer,
//                'spatie_records' => $spatieRecordsFormatted,
//                'astrotomic_records' => $astrotomicRecordsFormatted,
//                'internal_queries' => array_unique($internalQueries),
//                'external_queries' => array_unique($externalQueries),
//            ];
//        } catch (\Exception $e) {
//            return [
//                'dns_server' => $dnsServer,
//                'error' => $e->getMessage()
//            ];
//        }
//    }
//
//    private function isInternalIP($ip)
//    {
//        // Check for private IP ranges
//        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
//    }
    private function performDnsLookup($hostname, $recordType, $dnsServer)
    {
        try {
            $recordType = strtoupper($recordType);

            $spatieDns = (new SpatieDns())
                ->useNameserver($dnsServer)
                ->setTimeout(2)
                ->setRetries(2);

            $spatieRecords = $spatieDns->getRecords($hostname, $recordType);

            $spatieRecordsFormatted = [];
            $allIPs = [];

            foreach ($spatieRecords as $record) {
                $recordString = (string)$record;
                $spatieRecordsFormatted[] = $recordString;

                // Extract all IP addresses from records
                if ($recordType === 'A' && preg_match_all('/\b(?:\d{1,3}\.){3}\d{1,3}\b/', $recordString, $matches)) {
                    $allIPs = array_merge($allIPs, $matches[0]);
                }
            }

            // Classify IPs as internal/external
            $internalIPs = [];
            $externalIPs = [];
            foreach ($allIPs as $ip) {
                if ($this->isInternalIP($ip)) {
                    $internalIPs[] = $ip;
                } else {
                    $externalIPs[] = $ip;
                }
            }

            $astrotomicRecords = Dns::records($hostname, constant("DNS_{$recordType}"));

            $astrotomicRecordsFormatted = $astrotomicRecords->map(function ($record) use ($recordType) {
                $base = [
                    'host' => $record->host(),
                    'ttl' => $record->ttl(),
                    'class' => $record->class(),
                    'type' => $record->type(),
                    'ip' => $record instanceof A ? $record->ip() : null,
                ];

                if ($recordType === 'MX' && method_exists($record, 'target')) {
                    $base['target'] = $record->target();
                    $base['priority'] = $record->priority();
                }

                return $base;
            })->all();

            return [
                'dns_server' => $dnsServer,
                'spatie_records' => $spatieRecordsFormatted,
                'astrotomic_records' => $astrotomicRecordsFormatted,
                'internal_ips' => array_unique($internalIPs),
                'external_ips' => array_unique($externalIPs),
            ];
        } catch (\Exception $e) {
            return [
                'dns_server' => $dnsServer,
                'error' => $e->getMessage()
            ];
        }
    }

    private function isInternalIP($ip)
    {
        // Check for private IP ranges (10.x, 172.16-31.x, 192.168.x)
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }
}
