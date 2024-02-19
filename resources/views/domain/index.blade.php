@extends('layouts.app')
@section('content')
    <main>
        <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
            <div class="container-xl px-4">
                <div class="page-header-content">
                    <div class="row align-items-center justify-content-between pt-3">
                        <div class="col-auto mb-3">
                            <h1 class="page-header-title">
                                <div class="page-header-icon"><i data-feather="cloud"></i></div>
                               Registered Domain
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-xl px-4">
            <div class="card mb-4">
                <div class="card-header">Domain</div>
                <div class="card-body">
                    <table id="datatablesSimple" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Domain (FQDN)</th>
                                <th>Ministry</th>
                                <th>Agency</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($smtpMailServers as $smtpMailServer) --}}
                            <tr>
                                <td>1.</td>
                                <td>artgallery.gov.my</td>
                                <td>Kementerian Pelancongan, Seni Dan Budaya
                                {{-- @if($smtpMailServer -> types_req == 0)
                                    <span class="badge bg-success text-white rounded-pill">New Application</span>
                                @elseif($smtpMailServer -> types_req == 1)
                                    <span class="badge bg-danger text-white rounded-pill">Terminate of Service</span>
                                @else
                                    <span class="badge bg-primary text-white rounded-pill">Onboarding Agency</span>
                                @endif --}}
                                </td>
                                <td>Lembaga Pembangunan Seni Visual Negara</td>
                                <td><span class="badge bg-green-soft text-green">Active</span>
                                {{-- @if($user->enabled == 1)
                                    <span class="badge bg-green-soft text-green">Active</span>
                                @else
                                    <span class="badge bg-red-soft text-red">Inactive</span>
                                @endif --}}

                                    <!-- span class="badge bg-green-soft text-green">Sales</span>
                                    <span class="badge bg-blue-soft text-blue">Developers</span>
                                    <span class="badge bg-red-soft text-red">Marketing</span>
                                    <span class="badge bg-purple-soft text-purple">Managers</span>
                                    <span class="badge bg-yellow-soft text-yellow">Customer</span -->
                                </td>
                                <td>01/01/2024</td>
                                <td>
                                    <a class="btn btn-datatable btn-icon me-2 text-blue" title="View Detail" href=""><i data-feather="eye"></i></a>
                                    <form class="btn btn-datatable btn-icon me-2 text-blue" action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this FQDN?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-datatable btn-icon text-danger btn-transparent" title="Delete FQDN"><i data-feather="trash-2"></i></button>
                                    </form>
                                </td>
                            </tr>
                            {{-- @endforeach --}}
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </main>
@endsection
