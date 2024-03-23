@extends('layouts.app')
@section('content')
<main>
  <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
      <div class="page-header-content">
        <div class="row align-items-center justify-content-between pt-3">
          <div class="col-auto mb-3">
            <h1 class="page-header-title">
              <div class="page-header-icon">
                <i data-feather="trello"></i>
              </div> Generate Report
            </h1>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Main page content-->
  <div class="container-xl px-4 mt-4" id="searchingBox">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">Filter</div>
                <div class="card-body">
                <div class="serverBox col-md-6">
                    <div class="mb-3">
                        <label class="small mb-1" for="inputAgencies">Choose Server:</label>
                            {!! Form::select('server', $serverconfig,  $defaultServerId,  ['id'=>'server', 'name'=>'server', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                            <p class="card-text mt-1 mb-1" id="ipAddress"></p>
                            <div class="small text-muted"></div>
                    </div>
            </div>

                    <div  id="searchBox">
                    <form id="searchForm">
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small mb-1" for="inputAgencies">Ministry/Agency/SUK:</label>
                                {{--{!! Form::select('agency', $agency, isset($applicantInfo) ? $applicantInfo->agencies : null,  ['id'=>'agency', 'name'=>'agency', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}--}}
                                <select class="form-control select2" name="ministry" id="ministry" aria-label="Default select">
                                    <option selected disabled>Select Ministry/Agency/SUK</option>
                                    @foreach ($domainsArray as $domain)
                                        <option value="{{ $domain['ministry_name'] }}">{{ $domain['ministry_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3" id="calendarSelect">
                                <label class="small mb-1" for="calendarType">Select Calendar:</label>
                                <select class="form-control select2" id="calendarType" name="calendarType">
                                    <option value="date">Date</option>
                                    <option value="month">Month</option>
                                    <option value="year">Year</option>
                                    <option value="dateRange">Date Range</option>
                                </select>
                            </div>
                        </div>

                        </div>
                        <div class="row gx-3 mb-3">
                            <div class="mb-3" id="calendarInputs">
                                <!-- Inputs for date, month, year, and date range will be dynamically appended here -->
                            </div>
                        </div>
                            <div class="text-end"> <!-- Align the button to the right -->
                                <button type="button" class="btn btn-secondary" id="back">Back</button>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                    </form>
                    </div>


            </div>
                </div>
            </div>
        </div>






        <div class="row mb-4 mt-4">
            <div class="col-xxl-12">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-xl-4 mb-4">
                      <a class="card lift h-100" href="#!">
                        <div class="card-body d-flex justify-content-center flex-column">
                          <div class="d-flex align-items-center justify-content-between">
                            <div class="me-3">
                              <i class="feather-xl text-primary mb-3" data-feather="activity"></i>
                              <h5>Total IP Address</h5>
                              <div class="inline">
                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                <h2 id="totalApplications" style="display: inline-block; margin-left: 5px;">123</h2>
                              </div>
                            </div>
                            <img src="{{asset ('assets/img/illustrations/dash_app.svg')}}" alt="..." style="width: 8rem" />
                          </div>
                        </div>
                      </a>
                    </div>
                    <div class="col-xl-4 mb-4">

                      </div>
                    <div class="col-xl-4 mb-4">
                      <a class="card lift h-100" href="#!">
                        <div class="card-body d-flex justify-content-center flex-column">
                          <div class="d-flex align-items-center justify-content-between">
                            <div class="me-3">
                              <i class="feather-xl text-secondary mb-3" data-feather="zap"></i>
                              <h5>Total Domain</h5>
                              <div class="inline">
                                <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                <h2 id="totalDomains" style="display: inline-block; margin-left: 5px;">123</h2>
                              </div>
                            </div>
                            <img src="{{asset ('assets/img/illustrations/dash_domain.svg')}}" alt="..." style="width: 8rem" />
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>


                  {{-- Table List IP Start --}}
                  <div class="row">
                    <div class="table-responsive">
                    <table id="senderEmailDatatable" class="fixed_header display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <th colspan="3" style="background-color: aliceblue">List of Agency Domain vs IP Activated</th>
                        </thead>
                        <thead>
                          <tr>
                              <th style="width: 2px;">No</th>
                              <th style="text-align: center;">Agency Domain</th>
                              <th style="text-align: center;">Ip Activated</th>
                          </tr>
                      </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
              </div>
                  {{-- Table List IP End --}}

                  {{-- Table List SASL Start --}}
                  <div class="row">
                    <div class="table-responsive">
                    <table id="senderEmailDatatable" class="fixed_header display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <th colspan="3" style="background-color: aliceblue">List of SASL Registered</th>
                        </thead>
                        <thead>
                          <tr>
                              <th style="width: 2px;">No</th>
                              <th style="text-align: center;">Column 1</th>
                              <th style="text-align: center;">Column 2</th>
                          </tr>
                      </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
              </div>
                  {{-- Table List SASL End --}}

                  {{-- Table List SASL Start --}}
                  <div class="row">
                    <div class="table-responsive">
                    <table id="senderEmailDatatable" class="fixed_header display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <th colspan="3" style="background-color: aliceblue">List of Monthly Longsumm</th>
                        </thead>
                        <tbody>
                          <tr>
                              <td style="text-align: left;">Longsumm-1.txt</td>
                              <td style="width:25px; text-align: center;"><button class="btn btn-md btn-primary">Download</button></td>
                          </tr>
                          <tr>
                            <td style="text-align: left;">Longsumm-2.txt</td>
                            <td style="width:25px; text-align: center;"><button class="btn btn-md btn-primary">Download</button></td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Longsumm-3.txt</td>
                            <td style="width:25px; text-align: center;"><button class="btn btn-md btn-primary">Download</button></td>
                        </tr>
                      </tbody>
                      </table>
                    </div>
              </div>
                  {{-- Table List SASL End --}}


                </div>
              </div>
            </div>
          </div>


    </div>


</main>
@endsection
@push('script')
<script>

    $(document).ready(function () {
            $('#calendarType').change(function () {
        var selectedType = $(this).val();
        $('#calendarInputs').empty(); // Clear previous inputs

        if (selectedType === 'month') {
            $('#calendarInputs').append('<div class="col-md-6"><label for="input2_month">Month:</label><input type="month" class="form-control" name="input2_month" id="month"></div>');
        } else if (selectedType === 'year') {
            var currentYear = new Date().getFullYear();
            var selectYearHtml = '<div class="col-md-6"><label for="input2_year">Year:</label><select class="form-control" name="input2_year" id="year">';
            for (var year = currentYear; year >= currentYear - 10; year--) {
                selectYearHtml += '<option value="' + year + '">' + year + '</option>';
            }
            selectYearHtml += '</select></div>';
            $('#calendarInputs').append(selectYearHtml);
        }  else if (selectedType === 'dateRange') {
            $('#calendarInputs').append('<div class="row"><div class="col-md-6"><label for="input2_from">Date From:</label><input type="date" class="form-control" name="input2_from" id="input2_from" placeholder="From"></div><div class="col-md-6"><label for="input2_to">Date To:</label><input type="date" class="form-control" name="input2_to" id="input2_to" placeholder="To"></div></div>');
        } else {
            $('#calendarInputs').append('<div class="col-md-6"><label for="input2">Calendar:</label><input type="date" class="form-control" name="input2" placeholder="Calendar"></div>');
        }
    });

        $('#searchForm').submit(function (e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                type: 'GET',
                url: "{{ route('generateReport.index.search') }}",
                data: formData,
                success: function (response) {
                    $('#totalApplications').text(response.totalApplications);
                    $('#totalIP').text(response.totalIP);
                    $('#totalDeactivated').text(response.totalDeactivated);

                    $('#spmrDatatable').DataTable().ajax.reload();
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

        $('#searchBox').fadeOut();
        $('.serverBox').fadeIn();

        $('#back').on('click', function() {
            $('#searchBox').fadeOut();
            $('.serverBox').fadeIn();
        });

         // Event listener for select change
         $('#server').on('change', function() {
            var selectedServerId = $(this).val();

            // AJAX request to fetch data based on the selected server ID
            $.ajax({
                url: "{!! route('dashboard.api') !!}",
                method: 'GET',
                data: {
                    serverId: selectedServerId
                },
                success: function(response) {
                    // Handle success response and update UI
                    console.log(response);
                    $('#searchBox').fadeIn();
                        $('.serverBox').fadeOut();

                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
@endpush
