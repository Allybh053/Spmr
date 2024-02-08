@extends('layouts.app')
@section('content')
     <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-fluid px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"></div>
                                List SMTP Relay Request MyGov UC3.0
                            </h1>
                        </div>
                        <div class="col-12 col-xl-auto mb-3">
                            <a class="btn btn-sm btn-light text-primary" href="{{ route('smtp_relay.new') }}">
                                <i class="me-1" data-feather="plus"></i>
                                Add New Request Form
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

         {{--alert successfull and failed created/update/delete form request --}}
         @if(session()->has('success'))
             <div class="alert alert-primary alert-dismissible fade show" role="alert">
                 {{ session()->get('success') }}
                 <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>
         @endif
         @if ($errors->any())
             @foreach ($errors->all() as $error)
                 <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     {{ $error }}
                     <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                 </div>
             @endforeach
         @endif

        <!-- Main page content-->
        <div class="container-fluid px-4">
            <div class="card">
                <div class="card-body">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Ticket No.</th>
                                <th>Types Application</th>
                                <th>Project Name</th>
                                <th>Server Name</th>
                                <th>IP Address Server</th>
                                <th>FQDN System</th>
                                <th>Date Created</th>
                                <th>Date Updated</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                            <tbody>
                                <tr>
                                    <td>SR-123456</td>
                                    <td>New Application</td>
                                    <td>MyLAND PROD</td>
                                    <td>JKPTG_ApacheWeb1_P</td>
                                    <td>10.10.10.101</td>
                                    <td>myland.gov.my</td>
                                    <td>20 Jun 2021</td>
                                    <td>20 Jan 2024</td>
                                    <td>
                                        <span class="badge bg-green-soft text-green">Done Created</span>
                                    </td>
                                    <td>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2" href="user-management-edit-user.html"><i data-feather="edit"></i></a>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark" href="#!"><i data-feather="trash-2"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

 @endsection


@push('script')
    <script type="text/javascript" src="{{ URL::asset ('js/datatables/datatables-simple-demo.js') }}"></script>
@endpush
