@extends('layouts.app')
@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="mail"></i></div>
                                Detail SMTP Relay Request MyGovUC
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mb-3">

                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-fluid px-4">
            <div class="card mb-4">
                <div class="card-header text-center"><h3 class=" text-primary">Detail SMTP Relay Request MyGovUC</h3></div>
                <div class="card-body">
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label>Serial Number</label>
                            <p>{{ $datasmtp->form_id}}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Status</label>
                            <p>
                                @if ($datasmtp->status == 0)
                                    Pending
                                @elseif ($datasmtp->status == 1)
                                    Approved
                                @elseif (($datasmtp->status == 2))
                                    Rejected
                                @endif</p>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label>Ticket Number</label>
                            <p>{{ $datasmtp->ticket_id}}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Type Of Request</label>
                            <p>
                                @if ($datasmtp->types_req == 1)
                                    New Application
                                @elseif ($datasmtp->types_req == 2)
                                    Terminate of Service
                                @else
                                    Onboarding Agency
                                @endif</p>
                        </div>
                    </div>
                    <div class="mb-3" hidden @disabled(true)>
                        <label>System Fully Qualified Domain Name (FQDN)</label>
                        <p></p>
                    </div>
                    <div class="mb-3">
                        <label>Project Name</label>
                        <p>{{ $datasmtp->project_name}}</p>
                    </div>
                    <div class="mb-3">
                        <label>System Owner Name</label>
                        <p>{{ $datasmtp->system_owner_name}}</p>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label>Email Address</label>
                            <p>{{ $datasmtp->owner_email_addr}}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Phone Number</label>
                            <p>{{ $datasmtp->owner_phone_number}}</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Ministry</label>
                        <p>{{ $datasmtp->ministry_name }}</p>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label>Agency</label>
                            <p>{{ $datasmtp->agencies_name}}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Department/Division/Unit</label>
                            <p>{{ $datasmtp->dept_div_unit}}</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Agency Address</label>
                        <p>{{ $datasmtp->agencies_addr}}</p>
                    </div>
                    <div class="mb-3">
                        <label>Justification</label>
                        <p>{{ $datasmtp->req_justifications}}</p>
                    </div>

                    <div class="mb-3">
                        <label>Mail Relay Authentication Method</label>
                        <p>
                            @if ($datasmtp->smtp_relay_auth_method == 1)
                                IP Based Authentication Relay
                            @else
                                Username/Password Authentication Relay
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label>Server Mail Relay</label>
                        <p>{{ $datasmtp->smtp_relay_server ?? 'Not Available' }}</p>
                    </div><hr>
                    <div class="mb-3">
                        <table class="table table-striped" id="datatablesSimple">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">System Name</th>
                                <th scope="col">IP Address</th>
                                <th scope="col">Domain Sistem</th>
                                <th scope="col">MailFrom</th>
                            </tr>
                            </thead>
                            <tbody id="dynamic-inputs-container">
                            @foreach ( $details as $detail)
                                <tr class="dynamic-input-row">
                                    <td><p>{{ $loop->iteration }}.</p></td>
                                    <td><p>{{ $detail -> server_name}}</p></td>
                                    <td><p>{{ $detail -> ip_addr_server}}</p></td>
                                    <td><p>{{ $detail -> fqdn_server}}</p></td>
                                    <td><p>{{ $detail -> mailfrom}}</p></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($datasmtp->smtp_relay_auth_method == 2)
                        <div class="row gx-3 mb-3" id="authusername">
                            <div class="col-md-6">
                                <label>SMTP Username</label>
                                <p>{{ $datasmtp->smtp_username }}</p>
                            </div>
                            @can('smtp_mail_relay_update')
                                <div class="col-md-4">
                                    <label for="smtp_password">SMTP Password</label>
                                    <input type="text" name="smtp_password" id="smtp_password"
                                           value="{{ old('smtp_password', $datasmtp->smtp_password) }}"
                                           class="form-control" form="updateForm">
                                </div>
                                <div class="col-md-2" style="padding-top: 24px">
                                    <button class="btn btn-success" type="button" onclick="generatePassword()">
                                        Generate Password
                                    </button>
                                </div>
                            @endcan
                        </div>
                    @endif

                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label>SMTP Port Number</label>
                            {{--<p>{{ $datasmtp->smtp_port}} /tcp</p>--}}
                            <p id="smtpPortDescription"></p>
                        </div>
                        <div class="col-md-6">
                            <label></label>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6" hidden @disabled(true)>
                            <label>Email Address System (MAILFROM)</label>
                            <p>{{ $datasmtp->mailfrom_email_addr}}</p>
                        </div>
                        <div class="col-md-6">
                            <label>MailBox</label>
                            <p>
                                @if($datasmtp->mailbox_created == 0 )
                                    None
                                @elseif($datasmtp->mailbox_created == 1)
                                    No
                                @else
                                    Yes
                                @endif</p>
                        </div>
                    </div>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label>Estimated number of system users (per day)</label>
                            <p>{{ $datasmtp->est_no_system_users_per_day}} /per day</p>
                        </div>
                        <div class="col-md-6">
                            <label>Estimated number of emails sent from the system (per day)</label>
                            <p>{{ $datasmtp->est_no_email_sent_from_system_users_per_day}} /per day</p>
                        </div>
                    </div>
                    <hr>
                    <div class="card-header text-center"><h4 class=" text-primary">Application Verification</h4></div><br>
                    <span>I hereby confirm that all information and statements provided in this application form are <b>TRUE</b> and <b>VALID</b>:</span><br><br>
                    <div class="mb-3">
                        <label>Applicant Name</label>
                        <p>{{ $datasmtp->applicant_name}}</p>
                    </div>
                    <div class="row col-12">
                        <div class="mb-3 col-md-4">
                            <label class=" mb-1">Applicant's Position:</label>
                            <p>{{ $datasmtp->applicant_position}}</p>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class=" mb-1">Applicant's Department / Division:</label>
                            <p>{{ $datasmtp->applicant_dept_div}}</p>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class=" mb-1">Date:</label>
                            <p>{{ $datasmtp->date_application ? date('d/m/Y', strtotime($datasmtp->date_application)) : 'null' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="card-header text-center"><h4 class=" text-primary">Verifier I</h4></div><br>
                    <div class="mb-3">
                        <label>Verifier Name</label>
                        <p>{{ $datasmtp->verifier_1_name}}</p>
                    </div>
                    <div class="row col-12">
                        <div class="mb-3 col-md-4">
                            <label class=" mb-1">Verifier Position:</label>
                            <p>{{ $datasmtp->verifier_1_position}}</p>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class=" mb-1">Verifier Department / Division:</label>
                            <p>{{ $datasmtp->verifier_1_dept_div}}</p>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class=" mb-1">Date:</label>
                            <p>{{ $datasmtp->date_verification_1 ? date('d/m/Y', strtotime($datasmtp->date_verification_1)) : 'null' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="card-header text-center"><h4 class=" text-primary">Helpdesk Officer</h4></div><br>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label>Officer Name</label>
                            <p>{{ $datasmtp->verifier_2_name}}</p>
                        </div>
                        <div class="col-md-6">
                            <label>Date:</label>
                            <p>{{ $datasmtp->date_verification_2 ? date('d/m/Y', strtotime($datasmtp->date_verification_2)) : 'null' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="card-header text-center"><h4 class=" text-primary">Approval JDN</h4></div><br>
                    <div class="mb-3">
                        <label>Approval Name</label>
                        <p>{{ $datasmtp->verifier_3_name}}</p>
                    </div>
                    <div class="row col-12">
                        <div class="mb-3 col-md-4">
                            <label class=" mb-1">Approval Position</label>
                            <p>{{ $datasmtp->verifier_3_position}}</p>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class=" mb-1">Approval Department / Division:</label>
                            <p>{{ $datasmtp->verifier_3_dept_div}}</p>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class=" mb-1">Date:</label>
                            <p>{{ $datasmtp->date_verification_3 ? date('d/m/Y', strtotime($datasmtp->date_verification_3)) : 'null' }}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label>Attachment</label><br>

                        @if ($datasmtp->upload_copy_attachment)
                            <a href="{{ route('smtp_relay.viewPdf', ['filename' => $datasmtp->upload_copy_attachment]) }}" target="_blank">{{ $datasmtp->upload_copy_attachment }}</a><br>
                        @endif
                        @foreach ($attachment as $attachments)
                            @if ($attachments->attachment_path)
                                <a href="{{ route('smtp_relay.viewPdf', ['filename' => $attachments->attachment_path]) }}" target="_blank">{{ $attachments->attachment_path }}</a><br>
                            @endif
                            @if (!$attachments->upload_copy_attachment && !$attachments->attachment_path)
                                <span class="text-muted">No Attachment</span><br>
                            @endif
                        @endforeach

                    </div>
                    @can('smtp_mail_relay_update')
                        <div class="row">
                            <div class="col-md-12">
                                <form id="updateForm" action="{{ route('smtp_relay.update_comments', ['id' => $datasmtp->id]) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label><strong>Current Remarks:</strong></label>
                                        <p class="text-muted">{{ $datasmtp->comments ?: 'No remarks available' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comments"><strong>New Comments:</strong></label>
                                        <textarea id="comments" name="comments" class="form-control" rows="3"
                                                  placeholder="-- Enter New Comment --">{{ old('comments') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <a class="btn btn-primary me-2 my-1" href="{{ url()->previous() }}">Back</a>
                                        <button type="submit" class="btn btn-success me-2 my-1">Update Form</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // The smtpPort value passed from PHP to JavaScript
                var smtpPort = @json($datasmtp->smtp_port);

                // Define the mapping from port value to description
                var portDescriptions = {
                    0: 'Not Answered',
                    1: '25/tcp',
                    2: '465/tcp',
                    3: '587/tcp',
                    4: '25/tcp & 465/tcp',
                    5: '25/tcp & 587/tcp',
                    6: '465/tcp & 587/tcp',
                    7: 'All'
                };

                // Get the paragraph element
                var descriptionElement = document.getElementById('smtpPortDescription');

                // Set the description based on the smtpPort value
                descriptionElement.textContent = portDescriptions[smtpPort] || 'Unknown';
            });
            function generatePassword() {
                const length = 22;
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-=_+[]{}|;:,.?';
                let password = '';
                for (let i = 0; i < length; i++) {
                    const randomIndex = Math.floor(Math.random() * chars.length);
                    password += chars[randomIndex];
                }

                const passwordField = document.getElementById('smtp_password');
                if (passwordField) {
                    passwordField.value = password;
                } else {
                    console.warn('SMTP Password field not found.');
                }
            }
        </script>
        <style>
            label {
                font-weight: bold;
            }
            th,td {
                font-size: var(--bs-body-font-size);
            }
            p {
                color: #0061f2 !important;
            }
        </style>
    </main>
@endsection
