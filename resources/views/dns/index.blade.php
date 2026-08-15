@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>DNS Lookup</h2>

        <form id="dnsForm">
            <div class="form-group">
                <label for="hostname">Hostname:</label>
                <input type="text" id="hostname" name="hostname" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="record_type">Record Type:</label>
                <select id="record_type" name="record_type" class="form-control" required>
                    <option value="A" selected>A</option>
                    <option value="AAAA">AAAA</option>
                    <option value="MX">MX</option>
                    <option value="NS">NS</option>
                    <option value="PTR">PTR</option>
                    <option value="SOA">SOA</option>
                    <option value="SRV">SRV</option>
                    <option value="TXT">TXT</option>
                </select>
            </div>

            <div class="form-group">
                <label for="dns_servers">DNS Servers (optional):</label>
                <textarea
                    id="dns_servers"
                    name="dns_server"
                    class="form-control"
                    placeholder=""
                    rows="3"
                ></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Run Lookup</button>
        </form>

        <hr>

        <div id="resultsContainer" style="display: none;">
            <h4>DNS Lookup Results</h4>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title" id="resultsTitle"></h5>
                    <div id="resultsContent"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('dnsForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const hostname = document.getElementById('hostname').value.trim();
            const record_type = document.getElementById('record_type').value;
            const dnsServersRaw = document.getElementById('dns_servers').value.trim();
            let dnsServers = dnsServersRaw
                ? dnsServersRaw.split(/[\n,]+/).map(s => s.trim()).filter(s => s)
                : [''];

            const resultsContainer = document.getElementById('resultsContainer');
            const resultsTitle = document.getElementById('resultsTitle');
            const resultsContent = document.getElementById('resultsContent');

            // Show loading state
            resultsContainer.style.display = 'block';
            resultsTitle.textContent = `Running DNS lookup for ${hostname} (${record_type})...`;
            resultsContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';

            try {
                const response = await fetch("{{ route('dns.run') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        hostname,
                        record_type,
                        dns_server: dnsServers.join(',')
                    })
                });

                const data = await response.json();

                if (data.status === 'success') {
                    resultsTitle.textContent = `Results for ${hostname} (${record_type})`;

                    let html = '';
                    data.results.forEach(result => {
                        html += `<div class="mb-4">`;
                        html += `<h6>DNS Server: ${result.dns_server}</h6>`;

                        if (result.error) {
                            html += `<div class="alert alert-danger">${result.error}</div>`;
                        } else {
                            // Display all IP resolutions (both internal and external)
                            if (record_type === 'A') {
                                html += `<div class="mb-3">`;
                                html += `<h6>IP Resolutions:</h6>`;
                                html += `<ul class="list-group mb-2">`;

                                // Display all internal IPs
                                if (result.internal_ips && result.internal_ips.length > 0) {
                                    result.internal_ips.forEach(ip => {
                                        html += `<li class="list-group-item list-group-item-info">`;
                                        html += `<strong>Internal IP:</strong> ${hostname} → ${ip}`;
                                        html += `</li>`;
                                    });
                                }

                                // Display all external IPs
                                if (result.external_ips && result.external_ips.length > 0) {
                                    result.external_ips.forEach(ip => {
                                        html += `<li class="list-group-item list-group-item-warning">`;
                                        html += `<strong>External IP:</strong> ${hostname} → ${ip}`;
                                        html += `</li>`;
                                    });
                                }

                                html += `</ul>`;
                                html += `</div>`;
                            }

                            // Display raw records if they exist
                            if (result.records && result.records.length > 0) {
                                html += `<div class="mb-3">`;
                                html += `<h6>Raw Records:</h6>`;
                                html += `<pre class="bg-light p-2 rounded">`;
                                result.records.forEach(record => {
                                    html += `${record}\n`;
                                });
                                html += `</pre>`;
                                html += `</div>`;
                            }

                            // Display formatted records if they exist
                            if (result.resolved_records && result.resolved_records.length > 0) {
                                html += `<div class="mb-3">`;
                                html += `<h6>Formatted Records:</h6>`;
                                html += `<ul class="list-group">`;
                                result.resolved_records.forEach(record => {
                                    html += `<li class="list-group-item">`;
                                    if (record.type === 'MX') {
                                        html += `<strong>MX Record:</strong> ${record.host} → ${record.target} (Priority: ${record.priority})`;
                                    } else {
                                        html += `<strong>${record.type} Record:</strong> ${record.host} → ${record.ip || 'N/A'}`;
                                    }
                                    html += `</li>`;
                                });
                                html += `</ul>`;
                                html += `</div>`;
                            }
                        }
                        html += `</div>`;
                    });

                    resultsContent.innerHTML = html;
                } else {
                    resultsTitle.textContent = 'Error';
                    resultsContent.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            } catch (error) {
                resultsTitle.textContent = 'Error';
                resultsContent.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
            }
        });
    </script>
@endsection
