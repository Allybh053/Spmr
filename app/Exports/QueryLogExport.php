<?php

namespace App\Exports;

use App\Models\LogSearchResult;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class QueryLogExport implements FromCollection, WithHeadings
{
    protected $filters;
    protected $filename;

    public function __construct(array $filters, string $filename)
    {
        $this->filters = $filters;
        $this->filename = $filename;
    }

    public function collection(): Collection
    {
        $logFilePath = storage_path('cls/' . $this->filename);

        if (!file_exists($logFilePath)) {
            return collect([]);
        }

        $queryLogs = [];
        $counter = 1;

        $file = new \SplFileObject($logFilePath);
        while (!$file->eof()) {
            $line = $file->fgets();
            if (!$line) continue;

            $match = true;
            foreach ($this->filters as $key => $value) {
                if ($value && !str_contains(strtolower($line), strtolower($value))) {
                    $match = false;
                    break;
                }
            }

            if ($match) {
                preg_match('/^(?P<timestamp>\w{3} \d{1,2} \d{2}:\d{2}:\d{2})/', $line, $timestampMatch);
                preg_match('/from=<(?P<from>[^>]+)>/', $line, $fromMatch);
                preg_match('/to=<(?P<to>[^>]+)>/', $line, $toMatch);
                preg_match('/subject=(?P<subject>.*?)\s+(?=\S+=|$)/', $line, $subjectMatch);
                preg_match('/relay=([^\s\[]+)/', $line, $receiverIpMatch);
                preg_match('/\[([0-9\.]+)\]/', $line, $senderIpMatch);
                preg_match('/status=(?P<status>\w+)/', $line, $statusMatch);
                preg_match('/id=(?P<inbound_id>\S+)/', $line, $inboundIdMatch);
                preg_match('/dsn=\S+.*? (\d{3}.*)/', $line, $outboundIdMatch);
                preg_match('/attachments=(?P<attachments>\S+)/', $line, $attachmentMatch);

                // Build row for Excel
                $queryLogs[] = [
                    'Hostname' => gethostname(),
                    'Log Filename' => $this->filename,
                    'No.' => $counter++,
                    'Timestamp' => $timestampMatch['timestamp'] ?? '-',
                    'MTA Sender IP' => $senderIpMatch[1] ?? '-',
                    'Inbound ID' => $inboundIdMatch['inbound_id'] ?? '-',
                    'Status' => $statusMatch['status'] ?? '-',
                    'MTA Receiver IP' => $receiverIpMatch[1] ?? '-',
                    'Outbound ID' => $outboundIdMatch[1] ?? '-',
                    'FROM Address' => $fromMatch['from'] ?? '-',
                    'TO Address' => $toMatch['to'] ?? '-',
                    'Subject' => $subjectMatch['subject'] ?? '-',
                    'Attachments' => $attachmentMatch['attachments'] ?? '-',
                    'Remarks' => '',
                ];

                // ✅ Save to database
                LogSearchResult::create([
                    'hostname'   => gethostname(),
                    'filename'   => $this->filename,
                    'log_date'   => $timestampMatch['timestamp'] ?? null,
                    'ip'         => $senderIpMatch[1] ?? null,
                    'email'      => $fromMatch['from'] ?? null,
                    'subject'    => $subjectMatch['subject'] ?? null,
                    'full_line'  => $line,
                ]);
            }
        }

        return collect($queryLogs);
    }

    public function headings(): array
    {
        return [
            'Hostname',
            'Log Filename',
            'No.',
            'Timestamp',
            'MTA Sender IP',
            'Inbound ID',
            'Status',
            'MTA Receiver IP',
            'Outbound ID',
            'FROM Address',
            'TO Address',
            'Subject',
            'Attachments',
            'Remarks',
        ];
    }
}
