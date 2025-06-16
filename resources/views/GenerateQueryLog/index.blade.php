@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">Query Log Report</h1>

        <form method="POST" action="{{ route('report.queryLog.export') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @csrf


            <div>
                <label class="block text-sm font-medium text-gray-700">Hostname:</label>
                <input type="text" name="hostname" placeholder="e.g. postmaster1a" class="form-input mt-1 block w-full">
            </div>


            <div>

                <label class="block text-sm font-medium text-gray-700 font-bold">Select Server:</label>
{{--                {!! Form::select('hostname',--}}
{{--                    ['all' => 'All'] + $serverconfig--}}
{{--                        ->pluck('subhostname', 'subhostname')--}}
{{--                        ->map(fn($name) => ucwords(strtolower($name)))--}}
{{--                        ->toArray(),--}}
{{--                    'all',--}}
{{--                    ['id' => 'serverName', 'class' => 'form-control select2']--}}
{{--                ) !!}--}}
                <small class="text-muted">Select at least one server</small>

            </div>





            <div>
                <label class="block text-sm font-medium text-gray-700">Source Log File:</label>
                <input type="text" name="sourceLog" placeholder="e.g. /data/logs/sample-jpa-1day-1hour.log" class="form-input mt-1 block w-full">
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700">Search Date:</label>
                <input type="date" name="searchDate" class="form-input mt-1 block w-full">
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700">Sender IP:</label>
                <input type="text" name="ip" placeholder="e.g. 10.29.202.173" class="form-input mt-1 block w-full">
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700">Sender Email (From):</label>
                <input type="email" name="email" placeholder="e.g. noreply@gov.my" class="form-input mt-1 block w-full">
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700">Email Subject:</label>
                <input type="text" name="subject" class="form-input mt-1 block w-full">
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700">Deep Search:</label>
                <select name="deepSearch" class="form-select mt-1 block w-full">
                    <option value="Y">Yes</option>
                    <option value="N">No</option>
                </select>
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700">Output Filename:</label>
                <input type="text" name="outputFile" placeholder="e.g. test" class="form-input mt-1 block w-full">
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-700">Result Email To:</label>
                <input type="email" name="resultEmailTo" placeholder="e.g. email@gmail.com" class="form-input mt-1 block w-full">
            </div>


            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Generate to Excel</button>
            </div>
        </form>


        @if(!empty($reportFile))
            <div class="mb-4">
                <a
                    href="{{ route('report.queryLog.download', ['file' => $reportFile]) }}"
                    class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                >
                    Download Report: {{ $reportFile }}
                </a>
            </div>
        @endif


        @if (!empty($queryLogs))
            <div class="mb-2 text-sm text-gray-500">Generated at: {{ $generatedAt }}</div>
            <table class="min-w-full bg-white border border-gray-300 rounded shadow text-sm">
                <thead class="bg-gray-100">
                <tr>
                    <th class="border px-2 py-1 text-left">Date</th>
                    <th class="border px-2 py-1 text-left">Server</th>
                    <th class="border px-2 py-1 text-left">Sender IP</th>
                    <th class="border px-2 py-1 text-left">Sender Email</th>
                    <th class="border px-2 py-1 text-left">Subject</th>
                    <th class="border px-2 py-1 text-left">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($queryLogs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-2 py-1">{{ $log['date'] ?? '-' }}</td>
                        <td class="border px-2 py-1">{{ $log['server'] ?? '-' }}</td>
                        <td class="border px-2 py-1">{{ $log['ip'] ?? '-' }}</td>
                        <td class="border px-2 py-1">{{ $log['email'] ?? '-' }}</td>
                        <td class="border px-2 py-1">{{ $log['subject'] ?? '-' }}</td>
                        <td class="border px-2 py-1">
                            <a href="{{ route('report.queryLog.download', ['file' => $log['file'] ?? $reportFile]) }}" class="text-blue-600 hover:underline">Download</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @elseif(request()->anyFilled(['date','ip','email','subject']))
            <div class="text-gray-600 mt-4">No results found. You will receive the report via email if matched.</div>
        @endif
    </div>
@endsection
{{--@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">Query Log Report</h1>

        --}}{{-- TABS --}}{{--
        <div class="mb-6">
            <ul class="flex border-b" id="tabs">
                <li class="mr-1">
                    <a href="#tab-form" class="bg-white inline-block py-2 px-4 text-blue-500 font-semibold border-l border-t border-r rounded-t active-tab">Generate</a>
                </li>
                @if (!empty($queryLogs))
                    <li class="mr-1">
                        <a href="#tab-result" class="bg-white inline-block py-2 px-4 text-blue-500 font-semibold border-l border-t border-r rounded-t">Result</a>
                    </li>
                @endif
            </ul>
        </div>

        --}}{{-- FORM TAB CONTENT --}}{{--
        <div id="tab-form" class="tab-content">
            --}}{{-- EXPORT FORM --}}{{--
            <form method="POST" action="{{ route('report.queryLog.export') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                @csrf

                --}}{{-- Hostname --}}{{--
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hostname:</label>
                    <input type="text" name="hostname" placeholder="e.g. postmaster1a" class="form-input mt-1 block w-full">
                </div>

                --}}{{-- Source Log File --}}{{--
                <div>
                    <label class="block text-sm font-medium text-gray-700">Source Log File:</label>
                    <input type="text" name="sourceLog" placeholder="e.g. /data/logs/sample-jpa-1day-1hour.log" class="form-input mt-1 block w-full">
                </div>

                --}}{{-- Search Date --}}{{--
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search Date:</label>
                    <input type="date" name="searchDate" class="form-input mt-1 block w-full">
                </div>

                --}}{{-- Sender IP --}}{{--
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sender IP:</label>
                    <input type="text" name="ip" placeholder="e.g. 10.29.202.173" class="form-input mt-1 block w-full">
                </div>

                --}}{{-- Sender Email --}}{{--
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sender Email (From):</label>
                    <input type="email" name="email" placeholder="e.g. noreply@gov.my" class="form-input mt-1 block w-full">
                </div>

                --}}{{-- Subject --}}{{--
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Subject:</label>
                    <input type="text" name="subject" class="form-input mt-1 block w-full">
                </div>

                --}}{{-- Deep Search --}}{{--
                <div>
                    <label class="block text-sm font-medium text-gray-700">Deep Search:</label>
                    <select name="deepSearch" class="form-select mt-1 block w-full">
                        <option value="Y">Yes</option>
                        <option value="N">No</option>
                    </select>
                </div>

                --}}{{-- Output Filename --}}{{--
                <div>
                    <label class="block text-sm font-medium text-gray-700">Output Filename:</label>
                    <input type="text" name="outputFile" placeholder="e.g. test" class="form-input mt-1 block w-full">
                </div>

                --}}{{-- Result Email To --}}{{--
                <div>
                    <label class="block text-sm font-medium text-gray-700">Result Email To:</label>
                    <input type="email" name="resultEmailTo" placeholder="e.g. email@gmail.com" class="form-input mt-1 block w-full">
                </div>

                --}}{{-- Submit --}}{{--
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Generate to Excel</button>
                </div>
            </form>
        </div>

        --}}{{-- RESULT TAB CONTENT --}}{{--
        @if (!empty($queryLogs))
            <div id="tab-result" class="tab-content hidden">
                <div class="mb-4 text-sm text-gray-500">Generated at: {{ $generatedAt }}</div>

                --}}{{-- Processing Log --}}{{--
                <div class="card bg-dark text-light mb-6">
                    <div class="card-header font-bold text-white bg-gray-800 px-4 py-2">Processing Log</div>
                    <div class="card-body px-4 py-3" style="font-family: monospace; white-space: pre-wrap; overflow-y: auto; max-height: 300px;">
                        Searching {{ $filters['searchDate'] ?? 'Date' }}...
                        Searching {{ $filters['ip'] ?? 'Sender IP' }}...
                        Searching {{ $filters['email'] ?? 'From address' }}...
                        Filtering for {{ $filters['subject'] ?? 'Subject lines' }}...
                        Decoding subjects...
                        Searching subject...
                        Searching additional data...
                        ├ Filtering inbound ID...
                        └ Parsing inbound ID...

                        Total ID: {{ count($queryLogs) }}
                        Progress: 100.00% ({{ count($queryLogs) }} / {{ count($queryLogs) }})

                        Generating XLSX file...
                        Total lines: {{ count($queryLogs) }}
                        100.00% Completed - Check output file:
                        "/data/reporting/query_logs/{{ gethostname() }}_{{ \Carbon\Carbon::parse($generatedAt)->format('Ymd_His') }}.xlsx"

                        Generating metadata...
                        Cleaning up...
                        Done

                        Sending result to {{ $filters['resultEmailTo'] ?? auth()->user()->email }}...
                        Email sent to {{ $filters['resultEmailTo'] ?? auth()->user()->email }}

                        Your request has ended, press Enter to proceed...
                    </div>
                </div>

                --}}{{-- Results Table --}}{{--
                <table class="min-w-full bg-white border border-gray-300 rounded shadow text-sm">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 text-left">Date</th>
                        <th class="border px-2 py-1 text-left">Server</th>
                        <th class="border px-2 py-1 text-left">Sender IP</th>
                        <th class="border px-2 py-1 text-left">Sender Email</th>
                        <th class="border px-2 py-1 text-left">Subject</th>
                        <th class="border px-2 py-1 text-left">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($queryLogs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-2 py-1">{{ $log['date'] ?? '-' }}</td>
                            <td class="border px-2 py-1">{{ $log['server'] ?? '-' }}</td>
                            <td class="border px-2 py-1">{{ $log['ip'] ?? '-' }}</td>
                            <td class="border px-2 py-1">{{ $log['email'] ?? '-' }}</td>
                            <td class="border px-2 py-1">{{ $log['subject'] ?? '-' }}</td>
                            <td class="border px-2 py-1">
                                <a href="{{ route('report.queryLog.download', ['file' => $log['file'] ?? $reportFile]) }}" class="text-blue-600 hover:underline">Download</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        --}}{{-- No results fallback --}}{{--
        @if (request()->anyFilled(['date','ip','email','subject']) && empty($queryLogs))
            <div class="text-gray-600 mt-4">No results found. You will receive the report via email if matched.</div>
        @endif

        --}}{{-- DOWNLOAD REPORT BUTTON --}}{{--
        @if(!empty($reportFile))
            <div class="mb-4">
                <a
                    href="{{ route('report.queryLog.download', ['file' => $reportFile]) }}"
                    class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                >
                    Download Report: {{ $reportFile }}
                </a>
            </div>
        @endif
    </div>

    --}}{{-- JS Tab Logic --}}{{--
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = document.querySelectorAll('#tabs a');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Remove active styles
                    tabs.forEach(t => t.classList.remove('active-tab'));
                    tabContents.forEach(tc => tc.classList.add('hidden'));

                    // Add active to clicked tab and show content
                    this.classList.add('active-tab');
                    const target = document.querySelector(this.getAttribute('href'));
                    target.classList.remove('hidden');
                });
            });

            // If queryLogs exist, auto-switch to result tab
            @if (!empty($queryLogs))
            document.querySelector('a[href="#tab-result"]').click();
            @endif
        });
    </script>

    --}}{{-- Tailwind style for active tab --}}{{--
    <style>
        .active-tab {
            background-color: #1D4ED8;
            color: white !important;
        }
    </style>
@endsection--}}
