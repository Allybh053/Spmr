@extends('layouts.app')
@push('css')



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
                                        <li class="breadcrumb-item">Validation & Verification</li>
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
                        <div class="card-header">Print List Ip Address</div>


                        <div class="card-body mt-1 mb-4">
                        <form id="report-form">
                        <div class="row col-12 mb-1">
                                    <div class="col-md-4">
                                        <label class="small mb-1" for="serverName">Server Name:</label>
                                        {!! Form::select('serverName', $serverName, null, ['id'=>'serverName', 'name'=>'serverName', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!}
                                    </div>
                        </div>
                        <div class="col-md-12">
                            <!-- Save and Back button-->
                            <div style="padding: 25px; text-align: right;">
                                <button class="btn btn-success" id="searching">Search</button>
                            </div>
                        </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @include('validationVerification.printList.datatable')
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



				$.ajax({
					type: 'POST',
					url: "{!! route('printList.validation') !!}",
					data: $('form').serialize(),
					success: function ($response) {
                            console.log($response);
                            $('#reportBox').fadeIn();
                            $('#searchingBox').fadeOut();

                            senderEmailDatatable.clear().draw();

                        $.each($response.data, function (index, row) {
                            senderEmailDatatable.row.add([
                                row.No,                // Matches <th>No</th>
                                row.IpAddress,         // Matches <th>IpAddress</th>
                                row.Action             // Matches <th>Action</th>
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
