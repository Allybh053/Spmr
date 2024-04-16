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
                <i data-feather="target"></i>
              </div> Manage Services
            </h1>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Main page content-->
  <div class="container-fluid px-4">
    <div class="card">
        <div class="card-header text-center p-5">
            <h5 style="text-align:left">Server Name</h5>
          </div>
      <div class="card-body">
        <div class="row  mt-5">
            {{-- Start Loop Server Here --}}
          <div class="col-xxl-12 ">

              @php $status = 'Online'; @endphp
              <div class="card-body mainRealtime" style="min-height: auto; max-height: 800px;overflow-y: auto;">
                <div class="row">
                  <div class="col-xl-6 mb-4 selected-item postfix serverId-1">
                    <a class="card lift h-100" href="#!">
                      <div class="card-body cardService d-flex justify-content-center flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="me-3">
                            <h5>Postfix</h5>
                          </div>
                          <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">

                        </div>
                        <span>Active</span>
                      </div>
                      {{-- @can('manage_service_update') --}}
                      <div class="col-md-6 d-flex justify-content-center" style="padding-left: 150px;padding-bottom: 15px;">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                          <button type="button" class="btn btn-primary">
                            <i class="fas fa-play"></i>
                          </button>
                          <button type="button" class="btn btn-danger">
                            <i class="fas fa-stop"></i>
                          </button>
                          <button type="button" class="btn btn-warning">
                            <i class="fas fa-redo"></i>
                          </button>
                          <button type="button" class="btn btn-info">
                            <i class="fas fa-sync-alt"></i>
                          </button>
                        </div>
                      </div>
                       {{-- @endcan  --}}
                    </a>
                  </div>
                  <div class="col-xl-6 mb-4 selected-item apache serverId-1">
                    <a class="card lift h-100" href="#!">
                      <div class="card-body cardService d-flex justify-content-center flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                          <div class="me-3">
                            <h5>Apache</h5>
                          </div>
                          <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                        </div>
                        <span>Active</span>
                      </div>
                    {{-- @can('manage_service_update') --}}
                      <div class="col-md-6 d-flex justify-content-center" style="padding-left: 150px;padding-bottom: 15px;">
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                          <button type="button" class="btn btn-primary">
                            <i class="fas fa-play"></i>
                          </button>
                          <button type="button" class="btn btn-danger">
                            <i class="fas fa-stop"></i>
                          </button>
                          <button type="button" class="btn btn-warning">
                            <i class="fas fa-redo"></i>
                          </button>
                          <button type="button" class="btn btn-info">
                            <i class="fas fa-sync-alt"></i>
                          </button>
                        </div>
                      </div>
                      {{-- @endcan --}}
                    </a>
                  </div>
                    <div class="col-xl-6 mb-4 selected-item webmin serverId-1">
                        <a class="card lift h-100" href="#!">
                            <div class="card-body cardService d-flex justify-content-center flex-column">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-3">
                                        <h5>Webmin</h5>
                                    </div>
                                    <img src="{{ isset($status) && $status === 'Online' ? asset('assets/img/demo/online.png') : asset('assets/img/demo/offline.png') }}" alt="{{ $status === 'Online' ? 'Online' : 'Offline' }}" style="width: 5rem">
                                </div>
                                <span>Active</span>
                            </div>
                            {{-- @can('manage_service_update') --}}
                            <div class="col-md-6 d-flex justify-content-center" style="padding-left: 150px;padding-bottom: 15px;">
                                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                    <button type="button" class="btn btn-primary">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger">
                                        <i class="fas fa-stop"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                    <button type="button" class="btn btn-info">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </div>
                            {{-- @endcan --}}
                        </a>
                    </div>
                </div>
              </div>
          </div>

          {{-- End Loop Server Here --}}
        </div>
      </div>
    </div>
  </div>
</main>
@endsection
@push('script')
<script>
    $(document).ready(function() {
        $('.selected-item').hide().fadeIn(500);

        // Blink animation for the selected item
        function blink(element) {
            $(element).fadeOut(300).fadeIn(300);
        }

        // Extract server type and server ID from the URL
        var url = window.location.href;
        var serverType = url.split('/').pop(); // Extract the last part of the URL
        var serverId = url.split('/')[url.split('/').length - 2]; // Extract the second last part of the URL


         // Call blink animation only on the selected item for 10 seconds
         var duration = 2000; // 10 seconds
        var interval = setInterval(function() {
            blink('.' + serverType + '.serverId-' + serverId);
        }, 600); // Blink every 600 milliseconds


console.log(serverType);
console.log(serverId);

        // Stop blinking after 10 seconds
        setTimeout(function() {
            clearInterval(interval);
        }, duration);
    });
</script>
@endpush
