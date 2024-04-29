@extends('layouts.app')
@push('css')
<style>
    .tableFixHead {
        overflow-y: auto; /* make the table scrollable if height is more than 200 px */
        height: 291px; /* gives an initial height of 200px to the table */
        position: relative; /* ensure the tableFixHead acts as a containing block */
    }
    .tableFixHead thead th,
    .tableFixHead tfoot th {
        position: sticky; /* make the table heads and foots sticky */
        top: 0px; /* table head and foot will be placed from the top of the table and stick to it */
        background: #eee; /* background color for header and footer cells */
    }
    .tableFixHead thead th {
        z-index: 2; /* ensure the header is above the body when scrolling */
    }
    .tableFixHead tfoot th {
        z-index: 2; /* ensure the footer is above the body when scrolling */
        top: auto; /* reset the top position */
        bottom: 0; /* align the footer to the bottom of the tableFixHead */
    }
    .tableFixHead table {
        border-collapse: collapse; /* make the table borders collapse to each other */
        width: 100%;
    }
    .tableFixHead th,
    .tableFixHead td {
        padding: 8px 16px;
        border: 1px solid #ccc;
    }
</style>



@endpush
@section('content')
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"></div>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                       <li class="breadcrumb-item">Report</li>
                                    </ol>
                                </nav>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-xl px-4 mt-4" id="searchingBox">
            <div class="row">
                <div class="col-xl-12">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Report Detail Sender</div>


                        <div class="card-body mt-1 mb-4">
                        <form id="report-form">
                        <div class="row gx-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="serverName">Server Name:</label>
                                        {!! Form::select('serverName', $serverName, null, ['id'=>'serverName', 'name'=>'serverName', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                                    <div class="col-md-4">
                                        <!-- Save and Back button-->
                                        <div style="padding: 25px; text-align: left;">
                                            <button class="btn btn-success" id="searching">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        @include('reports.detailSender.report')

 @endsection
 @push('script')
 <script>
    $('#back').on('click', function (e) {
        $('#reportBox').fadeOut();
       $('#searchingBox').fadeIn();
    });


    $('form').on('submit', function (e) {
				e.preventDefault();
				$(".form-group").removeClass("has-error");
				$(".form-control").removeClass("was-validated invalid is-invalid custom-select.is-invalid valid is-valid custom-select.is-valid");
				$(".form-group").children("span.help-block").remove();

				toggleLoader();

                var senderEmailDatatable = $('#senderEmailDatatable').DataTable({
            // DataTable options
                        "paging": true,
                        "ordering": true,
                        "info": true,
                        // ... other options
                        });

                // Assuming similar initialization for receiverEmailDatatable
                var receiverEmailDatatable = $('#receiverEmailDatatable').DataTable({
                    // DataTable options
                    "paging": true,
                    "ordering": true,
                    "info": true,
                    // ... other options
                });



				$.ajax({
					type: 'POST',
					url: "{!! route('report.detail.validation') !!}",
					data: $('form').serialize(),
					success: function ($response) {
                        console.log($response);
                        $('#reportBox').fadeIn();
                        $('#searchingBox').fadeOut();
                          // Clear existing rows from DataTables
                    senderEmailDatatable.clear().draw();
                    receiverEmailDatatable.clear().draw();

                    $.each($response.data, function (index, row) {
                    // Assuming the data has sender and receiver arrays
                    senderEmailDatatable.row.add([
                        row.sender.localemail,
                        row.sender.senderIcon,
                        row.sender.externalemail,
                        row.sender.mail,
                        row.sender.size,
                        row.sender.lastDate
                    ]).draw();

                    receiverEmailDatatable.row.add([
                        row.receiver.localemail,
                        row.receiver.senderIcon,
                        row.receiver.externalemail,
                        row.receiver.mail,
                        row.receiver.size,
                        row.receiver.lastDate
                    ]).draw();
                });

                    toggleLoader();

                },
		            error: function (xhr, status) {
		                toggleLoader();
		                var response = xhr.responseJSON;

		                if ( $.isEmptyObject(response.errors) ){
		                    var message = response.message;

		                    if (! message.length && response.exception){
		                        message = response.exception;
		                    }

		                    swal("Warning", message, "warning");
		                } else {
		                    swal('Amaran', 'Invalid Information.', 'warning');

		                    $.each(response.errors, function (key, message) {
		                        if (key.indexOf('.') !== -1) {
		                            var splits = key.split('.');
		                            key = '';

		                            $.each(splits, function(i, val) {
		                                if (i === 0) {
		                                    key = val;
		                                } else {
		                                    key += '[' + val + ']';
		                                }
		                            });
		                        }

		                        $('[name="' + key + '"]').closest('.form-group').addClass("has-error");
		                        $('[name="' + key + '"]').addClass("was-validated is-invalid invalid custom-select.is-invalid");
		                        $('[name="' + key + '"]').closest('.form-group').append("<span class=\"help-block\" style='color:red; font-family:Nunito, sans-serif;'>" + message[0] + "</span>");
		                    });
		                    $('html, body').animate({
		                        scrollTop: ($(".has-error").first().offset().top) - 200
		                    }, 500);
		                }
		            }
				});
			});


 </script>
 @endpush
