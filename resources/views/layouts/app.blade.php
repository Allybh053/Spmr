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
        <link rel="icon" type="image/x-icon" href="{{asset('assets/img/favicon.png')}}" />
        <link href="{{asset('css/select2.css')}}" rel="stylesheet" />
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
        <link href="{{asset('css/custom.css')}}" rel="stylesheet" />
        <script src="{{ asset('js/xterm.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('css/xterm.css') }}">
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>


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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{asset('js/scripts.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/sweetalert2.min.js')}}"></script>
    <script src="{{asset('js/feather.min.js')}}" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    <script src="{{asset('js/datatables/datatables-simple-demo.js')}}"></script>
    <script src="{{asset('js/custom.js')}}" type="text/javascript"></script>
    <script src="{{asset('js/ajaxSubmit.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();

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
    });
</script>


    @stack('script')
</body>
</html>
