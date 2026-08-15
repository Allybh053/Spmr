@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">Query Log Report</h1>

{{--        <form method="POST" action="{{ route('report.queryLog.export') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">--}}
{{--            @csrf--}}
        <form action="{{ route('report.queryLog.run') }}" method="POST">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Hostname:</label>
                <input type="text" name="hostname" placeholder="e.g. postmaster1a" class="form-input mt-1 block w-full">
            </div>


{{--            <div>--}}

{{--                <label class="block text-sm font-medium text-gray-700 font-bold">Select Server:</label>--}}
{{--                {!! Form::select('hostname',--}}
{{--                    ['all' => 'All'] + $serverconfig--}}
{{--                        ->pluck('subhostname', 'subhostname')--}}
{{--                        ->map(fn($name) => ucwords(strtolower($name)))--}}
{{--                        ->toArray(),--}}
{{--                    'all',--}}
{{--                    ['id' => 'serverName', 'class' => 'form-control select2']--}}
{{--                ) !!}--}}
{{--                <small class="text-muted">Select at least one server</small>--}}

{{--            </div>--}}





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


{{--            <div class="flex items-end">--}}
{{--                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Generate to Excel</button>--}}
{{--            </div>--}}
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Run</button>
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

        <form method="POST" action="{{ route('report.queryLog.export') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @csrf
            <form action="{{ route('report.queryLog.run') }}" method="POST">
                @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Hostname:</label>
                <input type="text" name="hostname" placeholder="e.g. postmaster1a" class="form-input mt-1 block w-full">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Source Log File:</label>
                <input type="text" name="sourceLog" placeholder="e.g. /data/logs/sample-jpa-1day-1hour.log" class="form-input mt-1 block w-full">
                <small class="text-gray-500">Full file path where logs are located</small>
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
                <small class="text-gray-500">"Yes" shows all related data. "No" uses exact match only.</small>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Output Filename:</label>
                <input type="text" name="outputFile" placeholder="e.g. report.xlsx or report.txt" class="form-input mt-1 block w-full">
                <small class="text-gray-500">Must end with <code>.xlsx</code> or <code>.txt</code></small>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Result Email To:</label>
                <input type="email" name="resultEmailTo" placeholder="e.g. email@gmail.com" class="form-input mt-1 block w-full">
            </div>

            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full">Generate Report</button>
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
        @elseif(request()->anyFilled(['searchDate','ip','email','subject']))
            <div class="text-gray-600 mt-4">No results found. You will receive the report via email if matched.</div>
        @endif
    </div>
@endsection--}}
