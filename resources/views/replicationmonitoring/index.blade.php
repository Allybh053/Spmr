@extends('layouts.app')
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #f8f9fa;
        }
        .container-xl {
            background-color: #1e1e1e;
            padding: 2rem;
            border-radius: 15px;
            margin-top: 2rem;
        }
        .page-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
            color: #f8f9fa;
        }
        .replication-section {
            background-color: #2d2d2d;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .server-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #f8f9fa;
        }
        .card {
            background-color: #ffffff !important;
            border: 1px solid #e0e0e0;
        }
        .card-header {
            background-color: #f8f9fa !important;
            color: #000000 !important;
            border-bottom: 1px solid #e0e0e0;
        }
        .server-box {
            background-color: #ffffff !important;
            color: #000000 !important;
            padding: 1rem;
            border-radius: 8px;
            font-family: monospace;
            margin: 0;
            min-height: 150px;
            text-align: left;
            white-space: pre-wrap;
            word-wrap: break-word;
            overflow-x: hidden;
            border: 2px solid transparent;
        }
        .server-box pre {
            margin: 0;
        }
        .server-box.connecting {
            border-color: #ffc107;
        }
        .server-box.error {
            border-color: #dc3545;
        }
        .server-data-container {
            display: flex;
            flex-direction: column;
        }
        .server-data-row {
            padding: 0.5rem;
            font-weight: bold;
            font-size: 0.7rem;
            color: #000000;
        }
        .icon {
            margin-right: 0.5rem;
        }
        .text-success {
            color: #28a745;
            font-weight: bold;
        }
        .text-warning {
            color: #ffc107;
            font-weight: bold;
        }
        .text-danger {
            color: #dc3545;
            font-weight: bold;
        }
        .text-primary {
            color: #007bff;
            font-weight: bold;
        }
        .text-light {
            color: #f8f9fa;
        }
        .scroll-buttons {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 1000;
        }
        .scroll-button {
            width: 60px;
            height: 60px;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background 0.3s, transform 0.2s;
            font-size: 22px;
            font-weight: bold;
            border: 3px solid white;
            outline: 3px solid rgba(0, 0, 0, 0.2);
        }
        .scroll-button:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }
    </style>
@endpush

@section('content')
    <div class="container-xl px-4 mt-4">
        @php
            $servers = [
                [
                    'main' => 'postmaster.mygovuc.gov.my',
                    'ha_nodes' => ['postmaster1a.mygovuc.gov.my', 'postmaster1b.mygovuc.gov.my'],
                    'dr_nodes' => ['postmaster1a.mygovuc.gov.my', 'postmaster1c.mygovuc.gov.my'],
                ],
                [
                    'main' => 'postmaster2.mygovuc.gov.my',
                    'ha_nodes' => ['postmaster2a.mygovuc.gov.my', 'postmaster2b.mygovuc.gov.my'],
                    'dr_nodes' => ['postmaster2a.mygovuc.gov.my', 'postmaster2c.mygovuc.gov.my'],
                ],
                [
                    'main' => 'postmaster3.mygovuc.gov.my',
                    'ha_nodes' => ['postmaster3a.mygovuc.gov.my', 'postmaster3b.mygovuc.gov.my'],
                    'dr_nodes' => ['postmaster3a.mygovuc.gov.my', 'postmaster3c.mygovuc.gov.my'],
                ],
            ];
        @endphp

        @foreach($servers as $serverGroup)
            <h2 class="page-title">
                <i class="fas fa-server icon" style="margin-right: 5px;"></i> {{ $serverGroup['main'] }}
            </h2>

            <!-- HA Replication Section -->
            <div class="replication-section">
                <div class="server-title" style="font-size: 18px; font-weight: bold;">
                    <i class="fas fa-network-wired" style="font-size: 24px; margin-right: 8px;"></i>Cluster Replication
                </div>

                <div class="row">
                    @foreach($serverGroup['ha_nodes'] as $node)
                        @php
                            $nodeId = strtolower(str_replace(['.', '-'], '', $node));
                            $displayName = ($node === $serverGroup['ha_nodes'][0]) ? 'Main Server' :
                              (($node === $serverGroup['ha_nodes'][1]) ? 'Standby Server' : $node);
                        @endphp
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header" data-actual-hostname="{{ $node }}">
                                    <strong><i class="fas fa-desktop icon" style="margin-right: 5px;"></i> {{ $displayName }}</strong>
                                </div>
                                <div class="card-body server-box" id="ha-box-{{ $nodeId }}">
                                    <p id="status-ha-{{ $nodeId }}">Waiting for HA Data...</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- DR Replication Section -->
            <div class="replication-section">
                <div class="server-title" style="font-size: 18px; font-weight: bold; display: flex; align-items: center;">
                    <i class="fas fa-fire" style="margin-right: 8px;"></i>
                    Disaster Recovery Replication
                </div>
                <div class="row">
                    @foreach($serverGroup['dr_nodes'] as $node)
                        @php
                            $nodeId = strtolower(str_replace(['.', '-'], '', $node));
                            $displayName = ($node === $serverGroup['dr_nodes'][0]) ? 'Production Server' :
                              (($node === $serverGroup['dr_nodes'][1]) ? 'Recovery Server' : $node);
                        @endphp
                        <div class="col-md-6 mb-lg-3">
                            <div class="card">
                                <div class="card-header" data-actual-hostname="{{ $node }}">
                                    <strong><i class="fas fa-desktop icon" style="margin-right: 5px;"></i> {{ $displayName }}</strong>
                                </div>
                                <div class="card-body server-box" id="dr-box-{{ $nodeId }}">
                                    <p id="status-dr-{{ $nodeId }}">Waiting for DR Data...</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            $('.server-box').each(function () {
                let cardHeader = $(this).closest('.card').find('.card-header');
                let hostname = cardHeader.data('actual-hostname');
                let type = $(this).attr('id').includes('ha-box') ? 'ha' :
                    $(this).attr('id').includes('dr-box') ? 'dr' : 'p1c';

                if (hostname) {
                    websocket(hostname, type);
                }
            });
        });

        function websocket(hostname, type) {
            let serverId = hostname.toLowerCase().replace(/[.-]/g, '');
            let statusElement = $('#status-' + type + '-' + serverId);
            let cardElement = $('#' + type + '-box-' + serverId);

            statusElement.text('').addClass('pulse');
            cardElement.addClass('connecting');

            const serverAddress = 'wss://myspmr:Rb1NAPzM57Ycq9z8Uur65g1JaF2xVixKtTGStvPfh65P9HK29Abe0QcxiZC9yV9n@spmr.mygovuc.gov.my/' + hostname;
            const socket = new WebSocket(serverAddress);

            socket.addEventListener('open', () => {
                const userEmail = "{{ auth()->user()->email }}";
                let command;
                if (type === 'ha') {
                    command = `/opt/scripts/websocket/cmd19 ${userEmail} -H`;
                } else {
                    command = `/opt/scripts/websocket/cmd18 ${userEmail} -H`;
                }

                socket.send(command);
            });

            socket.addEventListener('message', (event) => {
                if (event.data.trim()) {
                    updateServerStatus(event.data, hostname, type);
                }
            });
        }

        function updateServerStatus(responseText, hostname, type) {
            if (!responseText.trim()) return;

            let serverId = hostname.toLowerCase().replace(/[.-]/g, '');
            let statusElement = $('#status-' + type + '-' + serverId);
            let cardElement = $('#' + type + '-box-' + serverId);

            let existingContent = statusElement.html();
            let lines = responseText.trim().split("\n");
            let formattedText = '<div class="server-data-container">';

            let p1aData = [], p1cData = [];
            let isP1cSection = false;

            lines.forEach((line, index) => {
                if (isP1cSection) {
                    p1cData.push(`<div class="server-data-row" data-index="${index}">${line}</div>`);
                } else {
                    p1aData.push(`<div class="server-data-row" data-index="${index}">${line}</div>`);
                }
            });

            formattedText += p1aData.join('') + '</div>';
            statusElement.html(existingContent + formattedText);
            cardElement.removeClass('connecting').addClass('connected');

            if (hostname === 'postmaster1a.mygovuc.gov.my') {
                let p1cElement = $('#status-dr-postmaster1cmygovucgovmy');
                let p1cCard = $('#dr-box-postmaster1cmygovucgovmy');

                let existingP1cContent = p1cElement.html();
                if (p1cData.length === 0) {
                    return;
                }

                let p1cFormattedText = '<div class="server-data-container">' + p1cData.join('') + '</div>';
                p1cElement.html(existingP1cContent + p1cFormattedText);
                p1cCard.removeClass('connecting').addClass('connected');
            }

            if (hostname === 'postmaster2a.mygovuc.gov.my') {
                let p1cElement = $('#status-dr-postmaster2cmygovucgovmy');
                let p1cCard = $('#dr-box-postmaster2cmygovucgovmy');

                let existingP1cContent = p1cElement.html();
                if (p1cData.length === 0) {
                    return;
                }

                let p1cFormattedText = '<div class="server-data-container">' + p1cData.join('') + '</div>';
                p1cElement.html(existingP1cContent + p1cFormattedText);
                p1cCard.removeClass('connecting').addClass('connected');
            }
        }
    </script>
@endpush
