@extends('layouts.app')
@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"></div>
                                SMTP Relay MyGovUC3.0 Request Form
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Main page content-->
        <div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Application Information</div>
                        <div class="card-body">
                            <form>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (tiket number)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputTickNom">Tiket Number:</label>
                                        <input class="form-control" id="inputTickNom" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (types application)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputTypesApp">Types of Application:</label>
                                        <!-- <input class="form-control" id="inputTypesApp" type="text" placeholder="" value="" /> -->
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected disabled>Select types of application:</option>
                                            <option value="0">New Request</option>
                                            <option value="1">Service Termination</option>
                                            <option value="2">Agency Onboarding</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Form Group (project name)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputProjName">Project Name:</label>
                                    <input class="form-control" id="inputProjName" type="text" placeholder="" value="" />
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (server name)-->
                                    <div class="col-md-3">
                                        <label class="small mb-1" for="inputServerName">Server Name:</label>
                                        <input class="form-control" id="inputServerName" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (ip address server)-->
                                    <div class="col-md-3">
                                        <label class="small mb-1" for="inputIpAddrServer">IP Address Server:</label>
                                        <input class="form-control" id="inputIpAddrServer" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (fqdn system)-->
                                    <div class="col-md-3">
                                        <label class="small mb-1" for="inputFqdnServer">FQDN System:</label>
                                        <input class="form-control" id="inputFqdnServer" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Add button-->
                                    <div class="col-md-3" style="padding: 25px">
                                        <button class="btn btn-primary small mb-1"><i class="me-1" data-feather="plus"></i>Add</button>
                                    </div>
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (mailfrom email address)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputMailfromEmailAddr">MAILFROM Email Address:</label>
                                        <input class="form-control" id="inputMailfromEmailAddr" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (mailbox existence)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputMailboxExistence">Mailbox Existence:</label>
                                        <!-- <input class="form-control" id="inputMailboxExistence" type="text" placeholder="" value="" /> -->
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected disabled>Select types of mailbox existence:</option>
                                            <option value="0">Mailbox Available</option>
                                            <option value="1">No Existing Mailbox</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Form Group (system owner name)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputSystOwnerName">System Owner Name:</label>
                                    <input class="form-control" id="inputSystOwnerName" type="text" placeholder="" value="" />
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (email owner system)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEmailAddrOwnerSyst">Email Address Owner System:</label>
                                        <input class="form-control" id="inputEmailAddrOwnerSyst" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (phone number owner system)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputPhoneOwnerSyst">Phone / Mobile Number Owner System:</label>
                                        <input class="form-control" id="inputPhoneOwnerSyst" type="text" placeholder="" value="" />
                                    </div>
                                </div>
                                <!-- Form Group (agencies)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputAgencies">Agencies:</label>
                                    <input class="form-control" id="inputAgencies" type="text" placeholder="" value="" />
                                </div>
                                <!-- Form Group (dept div unit)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputDeptDivUnit">Department / Division / Unit:</label>
                                    <input class="form-control" id="inputDeptDivUnit" type="text" placeholder="" value="" />
                                </div>
                                <!-- Form Group (agency address)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputAgencyAddr">Agency Address:</label>
                                    <textarea class="form-control" id="inputAgencyAddr" rows="3"></textarea>
                                </div>
                                <!-- Form Group (justification for application)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputJustApp">Justification for Application:</label>
                                    <textarea class="form-control" id="inputJustApp" rows="3"></textarea>
                                </div>
                                <!-- Form Group (smtp relay authentication method)-->
                                <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputSmtpRelayAuthMethod">SMTP Relay Authentication Method:</label>
                                        <!-- <input class="form-control" id="inputSmtpRelayAuthMethod" type="text" placeholder="" value="" /> -->
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected disabled>Select SMTP Relay Authentication Method:</option>
                                            <option value="0">SMTP IP Based Authentication Relay</option>
                                            <option value="1">SMTP Username/Password Authentication Relay</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (smtp relay server)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputSmtpRelayServer">SMTP Relay Server:</label>
                                        <!-- <input class="form-control" id="inputSmtpRelayServer" type="text" placeholder="" value="" /> -->
                                        <select class="form-select" aria-label="Default select example">
                                        <option selected disabled>Select SMTP Relay Server:</option>
                                        <option value="0">postmaster.mygovuc.gov.my</option>
                                        <option value="1">postmaster2.mygovuc.gov.my</option>
                                        <option value="2">postmaster3.mygovuc.gov.my</option>
                                    </select>
                                    </div>
                                    <!-- Form Group (smtp Port)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputSmtpPort">SMTP Port</label>
                                        <!-- <input class="form-control" id="inputSmtpPort" type="text" placeholder="" value="" /> -->
                                        <select class="form-select" aria-label="Default select example">
                                        <option selected disabled>Select SMTP Port:</option>
                                        <option value="0">25/tcp</option>
                                        <option value="1">465/tcp</option>
                                        <option value="2">587/tcp</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (smtp username)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputSmtpUsername">SMTP Username:</label>
                                        <input class="form-control" id="inputSmtpUsername" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (smtp password)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputSmtpPwd">SMTP Password:</label>
                                        <input class="form-control" id="inputSmtpPwd" type="text" placeholder="" value="" />
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (estimated number of system users)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEstSystUsers">Estimated number of system users (per day):</label>
                                        <input class="form-control" id="inputEstSystUsers" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (estimated number of emails sent from the system)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEstNumbEmailSent">Estimated number of emails sent from the system (per day):</label>
                                        <input class="form-control" id="inputEstNumbEmailSent" type="text" placeholder="" value="" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6">
                            <!-- Application details card-->
                            <div class="card mb-4">
                                <div class="card-header">Application Verification</div>
                                <div class="card-body">
                                    <label class="small mb-1">
                                        I hereby confirm that all information and statements provided in this application form are <b>TRUE</b> and <b>VALID</b>:
                                    </label>

                                    <!-- Form Group (applicant's name)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputApplicantVerification">Applicant's Name:</label>
                                        <input class="form-control" id="inputApplicantVerification" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (applicant's position)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputApplicantPosition">Applicant's Position:</label>
                                        <input class="form-control" id="inputApplicantPosition" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (applicant's department/division)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputApplicantDeptDiv">Applicant's Department / Division:</label>
                                        <input class="form-control" id="inputApplicantDeptDiv" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (application date)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputAppDate">Application Date:</label>
                                        <input class="form-control" id="inputAppDate" type="text" placeholder="" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <!-- Verification 1 details card-->
                            <div class="card mb-4">
                                <div class="card-header">Verifier I</div>
                                <div class="card-body">
                                    <!-- Form Group (verifier name 1)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerifiedName1">Verifier Name I:</label>
                                        <input class="form-control" id="inputVerifiedName1" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (verifier position 1)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerifiedPosition1">Verifier Position I:</label>
                                        <input class="form-control" id="inputVerifiedPosition1" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (verifier department/division 1)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerifiedDeptDiv1">Verifier Department / Division I:</label>
                                        <input class="form-control" id="inputVerifiedDeptDiv1" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (verification date 1)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerificationDate1">Verification Date I:</label>
                                        <input class="form-control" id="inputVerificationDate1" type="text" placeholder="" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <!-- Verification 2 details card-->
                            <div class="card mb-4">
                                <div class="card-header">Verifier II</div>
                                <div class="card-body">
                                    <!-- Form Group (verifier name 2)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerifiedName2">Verifier Name II:</label>
                                        <input class="form-control" id="inputVerifiedName2" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (verifier position 2)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerifiedPosition2">Verifier Position II:</label>
                                        <input class="form-control" id="inputVerifiedPosition2" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (verifier department/division 2)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerifiedDeptDiv2">Verifier Department / Division II:</label>
                                        <input class="form-control" id="inputVerifiedDeptDiv2" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (verification date 2)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerificationDate2">Verification Date II:</label>
                                        <input class="form-control" id="inputVerificationDate2" type="text" placeholder="" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <!-- Verification 3 details card-->
                            <div class="card mb-4">
                                <div class="card-header">Verifier III</div>
                                <div class="card-body">
                                    <!-- Form Group (verifier name 3)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerifiedName3">Verifier Name III:</label>
                                        <input class="form-control" id="inputVerifiedName3" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (verifier position 3)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerifiedPosition3">Verifier Position III:</label>
                                        <input class="form-control" id="inputVerifiedPosition3" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (verifier department/division 3)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerifiedDeptDiv3">Verifier Department / Division III:</label>
                                        <input class="form-control" id="inputVerifiedDeptDiv3" type="text" placeholder="" value="" />
                                    </div>
                                    <!-- Form Group (verification date 3)-->
                                    <div class="mb-3">
                                        <label class="small mb-1" for="inputVerificationDate3">Verification Date III:</label>
                                        <input class="form-control" id="inputVerificationDate3" type="text" placeholder="" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <!-- Copy attachment card-->
                            <div class="card mb-4">
                                <div class="card-header">Upload Copy Attachment:</div>
                                <div class="card-body text-center">
                                    <!-- Copy attachment image-->
                                    <img class="img-account-profile rounded-circle mb-2" src="assets/img/illustrations/profiles/profile-1.png" alt="" />
                                    <!-- Copy attachment help block-->
                                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                    <!-- Copy attachment upload button-->
                                    <button class="btn btn-primary" type="button">Upload Attachment</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Save and Back button-->
                        <div class="col-md-12" style="padding: 25px">
                            <button class="btn btn-success" type="button">Save</button>
                            <a class="btn btn-secondary" type="button" href="{{ route('smtp_relay.smtp_relay_req') }}">Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
