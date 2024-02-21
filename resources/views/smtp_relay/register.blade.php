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
                            Pending Mail Relay List
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
            <div class="card-header">Form Registration Mail Relay</div>
            <div class="card-body">
                <form id="formserver" action="{{ route('smtp_relay.saving')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row gx-3 mb-3">
                    <!-- Form Group (ticket)-->
                    <div class="col-md-6">
                        <label for="ticketno">Ticket Number</label>
                        <input class="form-control" id="ticketno" name="ticketno" type="text" value="" required/>
                    </div>
                    <!-- Form Group (request)-->
                    <div class="col-md-6">
                        <label for="type_req">Type Of Request</label>
                        <select class="form-select" name="type_req" id="type_req" aria-label="Default select">
                            <option selected disabled>Select Type:</option>
                            <option value="1">New Application</option>
                            <option value="2">Service Termination</option>
                            <option value="3">Onboarding Agency</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="projectname">Project Name</label>
                    <input class="form-control" id="projectname" name="projectname" type="text" placeholder="" value="" required>
                </div>
                <div class="mb-3">
                    <label for="fqdn">System Fully Qualified Domain Name (FQDN)</label>
                    <input class="form-control" id="fqdn" name="fqdn" type="text" placeholder="" value="" required>
                </div>
                <div class="mb-3">
                    <label for="ownername">System Owner Name</label>
                    <input class="form-control" id="ownername" name="ownername" type="text" placeholder="" value="" required>
                </div>
                <div class="row gx-3 mb-3">
                    <!-- Form Group (email)-->
                    <div class="col-md-6">
                        <label for="emailadd">Email Address</label>
                        <input class="form-control" id="emailadd" name="emailadd" type="email" value="" required/>
                    </div>
                    <!-- Form Group (phone)-->
                    <div class="col-md-6">
                        <label for="phone">Phone Number</label>
                        <input class="form-control" id="phone" name="phone" type="phone" value="" required/>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <!-- Form Group (agency)-->
                    <div class="col-md-6">
                        <label for="agency">Agency</label>
                        <select class="form-control" name="agency" id="agency" aria-label="Default select">
                            <option selected disabled>Select Agency:</option>
                        </select>
                    </div>
                    <!-- Form Group (depart)-->
                    <div class="col-md-6">
                        <label for="ddu">Department/Division/Unit</label>
                        <select class="form-select" name="ddu" id="ddu" aria-label="Default select">
                            <option selected disabled>Select Department/Division/Unit:</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="agencyadd">Agency Address</label>
                    <textarea class="form-control" id="agencyadd" name="agencyadd" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="justification">Justification</label>
                    <textarea class="form-control" id="justification" name="justification" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="authmethod">Mail Relay Authentication Method</label>
                    <select class="form-select" name="authmethod" id="authmethod" aria-label="Default select example">
                        <option selected disabled>Select Authentication:</option>
                        <option value="1">IP Based Authentication Relay</option>
                        <option value="2">Username/Password Authentication Relay</option>
                    </select>
                </div>
                <div class="mb-3">
                    <table class="table table-striped" id="tableA" style="display: none;">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">System Name</th>
                                <th scope="col">IP Address</th>
                                <th scope="col">Port Number</th>
                                <th scope="col">FQDN Sistem</th>
                                <th scope="col">Server Mail Relay</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-inputs-container">
                            <tr class="dynamic-input-row">
                              <td><input type="text" name="server_name[]" value="" class="form-control" required></td>
                              <td><input type="text" name="ipaddress[]" value="" class="form-control" required></td>
                              <td>
                                <select class="form-select" name="ports[]" aria-label="Default select" required>
                                    <option selected disabled>Select:</option>
                                    <option value="25">25/tcp</option>
                                    <option value="465">465/tcp</option>
                                    <option value="587">587/tcp</option>
                                </select>
                              </td>
                              <td>
                                <select class="form-select" name="fqdns[]" aria-label="Default select" required>
                                    <option selected disabled>Select:</option>
                                    <option value="25">25/tcp</option>
                                    <option value="465">465/tcp</option>
                                    <option value="587">587/tcp</option>
                                </select>
                              </td>
                              <td>
                                <select class="form-select select2" name="mailrelay[]" aria-label="Default select" required>
                                    <option selected disabled></option>
                                    @foreach ($serversArray as $server)
                                    <option value="{{ $server['hostname'] }}">{{ $server['hostname'] }}</option>
                                    @endforeach
                                </select>
                              </td>
                              <td><button class="btn btn-danger  remove-row"><i class="me-1" data-feather="minus"></i>Remove</button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" style="text-align: right;"><button id="add-row" type="button" class="btn btn-primary "><i class="me-1" data-feather="plus"></i>Add</button></th>
                            </tr>
                        </tfoot>
                    </table>
                    <table class="table table-striped" id="tableB" style="display: none;">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">System Name</th>
                                <th scope="col">IP Address</th>
                                <th scope="col">Port Number</th>
                                <th scope="col">FQDN Sistem</th>
                                <th scope="col">Server Mail Relay</th>
                                <th scope="col">Username</th>
                                <th scope="col">password</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="dynamic-inputs-container">
                            <tr class="dynamic-input-row">
                              <td><input type="text" name="server_name[]" value="" class="form-control"></td>
                              <td><input type="text" name="ipaddress[]" value="" class="form-control"></td>
                              <td>
                                <select class="form-select" name="ports[]" aria-label="Default select example" required>
                                    <option selected disabled>Select:</option>
                                    <option value="25">25/tcp</option>
                                    <option value="465">465/tcp</option>
                                    <option value="587">587/tcp</option>
                                </select>
                              </td>
                              <td>
                                <select class="form-select" name="fqdns[]" aria-label="Default select example" required>
                                    <option selected disabled>Select:</option>
                                    <option value="25">25/tcp</option>
                                    <option value="465">465/tcp</option>
                                    <option value="587">587/tcp</option>
                                </select>
                              </td>
                              <td>
                                <select class="form-select select2" name="mailrelay[]" aria-label="" required>
                                    <option selected disabled></option>
                                    @foreach ($serversArray as $server)
                                    <option value="{{ $server['hostname'] }}">{{ $server['hostname'] }}</option>
                                    @endforeach
                                </select>
                              </td>
                              <td><input type="text" name="authusername[]" value="" class="form-control" required></td>
                              <td><input type="text" name="authpassword[]" value="" class="form-control" required></td>
                              <td><button class="btn btn-danger  remove-row"><i class="me-1" data-feather="minus"></i>Remove</button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="8" style="text-align: right;"><button id="add-row2" type="button" class="btn btn-primary "><i class="me-1" data-feather="plus"></i>Add</button></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="row gx-3 mb-3">
                    <!-- Form Group (mail from)-->
                    <div class="col-md-6">
                        <label for="emailaddfrom">Email Address System (MAILFROM)</label>
                        <input class="form-control" id="emailaddfrom" name="emailaddfrom" type="email" value="" required/>
                    </div>
                    <!-- Form Group (mailbox)-->
                    <div class="col-md-6">
                        <label for="mailbox">MailBox</label>
                        <select class="form-select" name="mailbox" id="mailbox" aria-label="Default select">
                            <option selected disabled>Select Mailbox:</option>
                            <option value="1">No</option>
                            <option value="2">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="attachement">Upload Attachement</label>
                    <input class="form-control" type="file" name="attachement" id="attachement" accept=".pdf" required/>
                    <span class="text-xs text-danger">Upload File format .pdf</span>
                </div>
                <button class="btn btn-primary me-2 my-1" type="submit">Submit</button>
                <a class="btn btn-danger me-2 my-1" type="button" href="{{ route('smtp_relay.smtp_relay_req')}}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function(){
        $('.select2').select2({
            placeholder: 'Search Mail Relay',
            allowClear: true,
        });
            // Handle change event on the dropdown
            $('#authmethod').change(function () {
                var selectedOption = $(this).val();

                // Toggle visibility of tables based on selected option
                $('#tableA, #tableB').hide();
                if (selectedOption == 1) {
                    $('#tableA').show();
                } else if (selectedOption == 2) {
                    $('#tableB').show();
                }
            }).change();

            // Add row button click handler
            $('#add-row').click(function () {
                addRow('#tableA');
            });

            $('#add-row2').click(function () {
                addRow('#tableB');
            });

            // Reusable function to add a row to the specified table
            function addRow(tableId) {
                var selectedTable = tableId + ' tbody';
                var newRow = $(selectedTable + ' .dynamic-input-row').first().clone();
                newRow.find('input').val('');

                // Reset the Select2 dropdown
                var clonedSelect = newRow.find('select');
                clonedSelect.val(null).trigger('change');

                $(selectedTable).append(newRow);

                // Initialize Select2 for the cloned dropdown
                clonedSelect.select2({ 
                    allowClear: true
                });
            }


            // Remove row
            $(document).on('click', '.remove-row', function() {
                var numRows = $(this).closest('tbody').find('.dynamic-input-row').length;
                if (numRows <= 1){
                    alert('Not Applicable.');
                }else{
                    $(this).closest('.dynamic-input-row').remove();
                }
            });
        });
    </script>
</main>


@endsection
