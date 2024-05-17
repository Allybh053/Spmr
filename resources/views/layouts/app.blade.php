<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="" />
        <meta name="author" content="" />
        <title>SPMR</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="{{asset('css/styles.css')}}" rel="stylesheet" />
        <link href="{{asset('assets/styles.css')}}" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.png')}}" />
        <link href="{{asset('css/select2.css')}}" rel="stylesheet" />
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
        <link href="{{asset('css/custom.css')}}" rel="stylesheet" />
        <script src="{{ asset('js/xterm.js') }}"></script>
        {{-- <link rel="stylesheet" href="{{ asset('css/xterm.css') }}"> --}}
        <link rel="stylesheet" href="{{ asset('css/xterm.css') }}">
        {{-- <link href="{{ asset('css/terminal.css') }}" rel="stylesheet"> --}}
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/xterm/4.7.0/xterm.min.css"> --}}

        <script src="{{ asset('assets/ShellInABox.js') }}"></script>

<style>
    .select2-selection__clear {
    display: none;
}

/* Custom styles for DataTables Buttons */
.dt-buttons {
    margin-bottom: 10px;
    text-align: right; /* Align buttons to the right */
}

.dt-buttons button {
    background-color: #007bff !important; /* Set background color */
    color: #fff; /* Set text color */
    border: none; /* Remove border */
    padding: 8px 12px; /* Add padding */
    border-radius: 4px; /* Add border radius */
    margin-right: 5px; /* Add some space between buttons */
    cursor: pointer; /* Change cursor to pointer on hover */
}

.dt-buttons button:hover {
    background-color: #0056b3; /* Darken background color on hover */
}

/* Adjust the spacing of buttons container */
.dataTables_wrapper .col-md-6:eq(0) {
    text-align: right; /* Align buttons to the right */
}

</style>
    @stack('meta')
    @stack('css')

</head>
<body class="nav-fixed">
    @include('layouts._partials.header')
    <div id="layoutSidenav">

         @include('layouts._partials.nav')
         <div id="layoutSidenav_content">
            <main>
                @yield('content')

            </main>
            @include('layouts._partials.footer')
        </div>
    </div>

    <script src="{{ asset('js/jquery-3.6.4.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{asset('js/scripts.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <script src="{{asset('js/feather.min.js')}}" type="text/javascript"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="{{asset('js/datatables/datatables-simple-demo.js')}}"></script>
    <script src="{{asset('js/custom.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/ajaxSubmit.js')}}" type="text/javascript"></script>
    <script src="https://unpkg.com/xterm-addon-search@1.10.0/lib/xterm-addon-search.js"></script>
    <script src="https://unpkg.com/xterm-addon-copy-mode@1.10.0/lib/xterm-addon-copy-mode.js"></script>

    {{-- <script src="{{ asset('js/terminal.js') }}"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xterm/4.7.0/xterm.min.js"></script> --}}
    <script>
$(document).ready(function() {
    // Custom DataTables sorting plugin for date in dd/mm/yyyy format
jQuery.extend(jQuery.fn.dataTableExt.oSort, {
    "date-uk-pre": function (a) {
        var ukDate = a.split('/');
        return Date.parse(ukDate[1] + '/' + ukDate[0] + '/' + ukDate[2]);
    },

    "date-uk-asc": function (a, b) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "date-uk-desc": function (a, b) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
});

    var currentUrl = window.location.href;
    $('.nav-link').each(function() {
        var href = $(this).attr('href');
        if (currentUrl.indexOf(href) !== -1) {
            $(this).addClass('active');
            $(this).parents('.collapse').addClass('show');
            $(this).parents('.nav-link').addClass('active');
        }
    });
});
</script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select an option',
                allowClear: true,
                tags: true,
            });

            var token = '{{ csrf_token() }}';
            var windowLocation;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(function() {
                $(".datepicker").datepicker({
                    format: "dd-mm-yyyy",
                    todayHighlight: true,
                    orientation: "bottom",
                });


            });

                // Handle change event on the dropdown
                $('#authmethod').change(function () {
                    var selectedOption = $(this).val();

                    // Toggle visibility of tables based on selected option
                    $('#tableA').show();
                    if (selectedOption == 1) {
                        $('#authusername, #authpassword').prop('disabled', true).hide();

                    } else if (selectedOption == 2) {
                        $('#authusername, #authpassword').prop('disabled', false).show();
                    }
                }).change();

                // Handle change event on the dropdown
                $('#smtp_relay_auth_method').change(function () {
                    var selectedOption = $(this).val();

                    // Toggle visibility of tables based on selected option
                    $('#tableA').show();
                    if (selectedOption == 1) {
                        $('#smtp_username, #smtp_password').prop('disabled', true).hide();

                    } else if (selectedOption == 2) {
                        $('#smtp_username, #smtp_password').prop('disabled', false).show();
                    }
                }).change();

                // Add row button click handler
                $('#add-row').click(function () {
                    addRow('#tableA');
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
                        allowClear: true,
                        tags: true,
                    });

                    newRow.find('input[required]').attr('required', 'required');
                    newRow.find('select[required]').attr('required', 'required');
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
    @stack('script')
</body>
</html>
