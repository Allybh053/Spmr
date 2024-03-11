@extends('layouts.app')
@push('css')

<style>
    th,td{
    border-bottom-width:0px !important;
    }
    .table > :not(:first-child), .dataTable-table > :not(:first-child) {
     border-top: 0px !important; 
}
thead, tbody, tfoot, tr, td, th {
    text-align: center;
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
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
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
                        <div class="card-header">Replication Status</div>

                        
                        <div class="card-body mt-1 mb-4">
            <div class="row">
                  <div class="table-responsive">
                  <table id="senderEmailDatatable" class="fixed_header display table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th colspan="3" style="text-align:center;"><h2>Node Types</h2></th>
                        </tr>
                        <tr>
                            <th><h4>Master Node</h4></th>
                            <th><h4>Standby Node</h4></th>
                            <th><h4>Slave Node</h4></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $columnCount = 0; // Initialize column count
                        @endphp
                        <tr>
                            @foreach($serverName as $serverId => $name)
                                <td>
                                    <div class="card mb-4">
                                        <div class="card-header">{{ $name }}</div>
                                        <div class="card-body mt-1 mb-4"> 
                                            <img src="{{ $fakeImages[$loop->index] }}" alt="Image {{ $loop->index + 1 }}">
                                        </div>
                                    </div>
                                </td>
                                @php
                                    $columnCount++; // Increment column count
                                    // If the maximum number of columns is reached, start a new row
                                    if ($columnCount >= 3) {
                                        echo '</tr><tr>';
                                        $columnCount = 0; // Reset column count
                                    }
                                @endphp
                            @endforeach
                        </tr>
                    </tbody>

                    </table>

                  </div>
            </div>


          </div>

                    </div>
                </div>
            </div>
        </div>

 @endsection
 @push('script')
 @endpush