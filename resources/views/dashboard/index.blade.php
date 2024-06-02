@extends('layouts.app')
@section('content')
<header class="py-10 mb-4 bg-gradient-primary-to-secondary">
  <div class="container-xl px-4">
    <div class="text-center">
      <h1 class="text-white">{{ trans('indexMessage.dashboard.welcome') }}</h1>
      <p class="lead mb-0 text-white-50">{{ trans('indexMessage.dashboard.dashboarMailRelay') }}</p>
    </div>
  </div>
</header>
@push('css')
<style>

.chart-single {

width: 100%;
margin: 30px auto 0;
display: block;
max-width: 480px;
color:#4A4A4A;

 /* Small Devices, Tablets */
  @media only screen and (min-width : 768px) {
    max-width: 768px;
  }

  /* Medium Devices, Desktops */
  @media only screen and (min-width : 992px) {
      max-width:992px;
  }

  /* Large Devices, Wide Screens */
  @media only screen and (min-width : 1200px) {
    max-width: 906px;
  }

/* y-axis values */
.numbers {
  margin: 0;
  padding: 0;
  display: inline-block;
  float: left;

  li {
    text-align: right;
    padding-right: 8px;
    list-style: none;
    position: relative;
    left:0px;
    height: 33px;
    top: -9px;
  }
}
.bars {
  max-width: 257px;
  width: 96%;
  display: inline-block;
  padding: 0;
  margin: 0;
  background-image:linear-gradient(top, rgba(0, 0, 0, 0.3) 3%, transparent 2%);
   background-image:-webkit-linear-gradient(top, rgba(0, 0, 0, 0.3) 3%, transparent 2%);
  background-image:-moz-linear-gradient(top, rgba(0, 0, 0, 0.3) 3%, transparent 2%);
  background-size: 100% 33px;
  background-position: left top;
  font-size: 12px;

 /* Extra Small Devices, Phones */
  @media only screen and (min-width : 480px) {
    max-width: 320px;
  }
   /* Small Devices, Tablets */
   @media only screen and (min-width : 768px) {
    max-width: 564px;
  }
  /* Medium Devices, Desktops and Large devices */
  @media only screen and (min-width : 992px) and (max-width: 1200px) {
     max-width: 901px;
  }
   @media only screen and (min-width : 1200px) {
     max-width: 906px;
  }

  /* set height of bar using data-percentage attribute */

    [data-percentage="100"]{
      height: 100%;
     }
     [data-percentage="98"]{
      height:98%;
     }
     [data-percentage="99"]{
      height:99%;
     }
     [data-percentage="93"]{
      height: 93%;
     }
       [data-percentage="95"]{
      height: 95%;
     }
     [data-percentage="96"]{
      height:96%;
     }
   [data-percentage="97"]{
      height:97%;
     }
    [data-numbers^="(n="] {
       text-align: left;
    }

  li {
  display: block;
  width: 8%;
  height: 331px;
  margin: 0;
  text-align: center;
  position: relative;
  margin-right: 10.2%;
  float: left;
    &:last-of-type{
      margin:0;
    }
    &:first-of-type{
      margin-left: 1%;
    }

    .bar {
      display: block;
      width: 100%;
      background-image: linear-gradient(10deg, #E25625 50%, #E25A25 58%, #E26724 67%, #E17C23 75%, #E19A21 84%, #E0BF1E 100%);
        background-image: -webkit-linear-gradient(10deg, #E25625 50%, #E25A25 58%, #E26724 67%, #E17C23 75%, #E19A21 84%, #E0BF1E 100%);
        background-image: -moz-linear-gradient(10deg, #E25625 50%, #E25A25 58%, #E26724 67%, #E17C23 75%, #E19A21 84%, #E0BF1E 100%);
      position: absolute;
      bottom: 0;

        /* numbers inside the bar */
        &:before {
          position: absolute;
          content: attr(data-numbers);
          transform: rotate(-90deg);
          transform-origin: 100% 100%;
          width: 100%;
          bottom: 9%;
          right: 1px;

           @media only screen and  (min-width: 768px){
             bottom: 17%;
            right: 4px;
          }

           @media only screen and  (min-width: 992px){
             bottom: 24%;
            right: 4px;
          }
           @media only screen and  (min-width: 1200px) {
             bottom: 24%;
             right: 4px;
          }
        }
    }

/* x-axis percentage values */
    span {
      width: 100%;
      position: absolute;
      bottom: -2em;
      left: 0;
      text-align: center;
      font-weight: bold;
      font-size: 9px;

       @media only screen and (min-width : 768px) and (max-width: 1200px) {
       font-size: 12px;

      }
      @media only screen and (min-width :1200px){
       font-size: 12px;

      }

    }

    .percentage{
      width: 100%;
      position: absolute;
      top: -26px;
      left: 0;
      text-align: center;
      font-weight: normal;
      font-size: 14px;
      sup{
        font-size: 66%;
      }
    }
  }
}

}


.indication{
padding-bottom: 20px;
 ul{
  li{
    display: inline-block;
      .box{
        height:19px;
        width: 42px;
        margin: 0 5px 0 0;
        vertical-align: top;
        display: inline-block;
      }


    .orange{

      background: linear-gradient(-90deg, #E25625 50%, #E25A25 58%, #E26724 67%, #E17C23 75%, #E19A21 84%, #E0BF1E 100%);
    }
    .gray{

      background: linear-gradient(360deg, #A7A9AC 10%, #D1D3D4 100%);
      margin: 0 5px 0 20px;
     }
  }
 }
}



</style>
@endpush
<!-- Main page content-->
<div class="container-xl px-4 mt-n10">
  <!-- <a class="card card-icon lift lift-sm mb-4" href="#"><div class="row"><div class="col-auto card-icon-aside bg-primary"><i class="text-white-50" data-feather="server"></i></div><div class="col"><div class="card-body py-4">
                                        {{-- {!! Form::select('server', $serverconfig,  $defaultServerId,  ['id'=>'server', 'name'=>'server', 'class'=>'form-control select2', 'placeholder'=>config('params.dropdown_default')]) !!} --}}
                                        <p class="card-text mt-1 mb-1" id="ipAddress"></p><div class="small text-muted"></div></div></div></div></a> -->




<!-- Start Loop By Server Here -->
    {{-- @foreach ($totalCountDashboard as $server) --}}
  <div class="row mb-4">
    <div class="col-xxl-12">
      <div class="card">
        {{-- <div class="card-header text-center p-3">
          <h5 style="text-align:left;">
              {{ $server['hostname']}}
              <p>{{ $server['ipAddress'] }}</p>
          </h5> --}}
        {{-- </div> --}}
        <div class="card-body">

            <div class="table-responsive">
                <div style="width: 100%; overflow-x: auto; height: 355px;">
                <table id="spmrDatatable" class="display table table-striped table-bordered" cellspacing="0" width="60%">
                    @foreach ($totalCountDashboard as $server)
                    <thead>
                        @if($loop->index != 0)
                        <tr><th colspan="2">&nbsp;</th></tr>@endif
                        <tr  style="background-color: rgb(197, 204, 210);">
                            <th colspan="2">
                                <h5 style="text-align:left;">
                                    {{ $server['hostname']}}
                                    <p>{{ $server['ipAddress'] }}</p>
                                </h5>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @can('total_application_registered')
                            <tr>
                                <th width="45%"><h6><b>Total Application Registered</b></h6></th>
                                <td width="15%"><h6>{{$server['totalApplicationRegistered'] ?? '0'}}</h6></td>
                            </tr>
                        @endcan
                        @can('total_domain_registered')
                            <tr>
                                <th width="45%"><h6><b>Total Domain Registered</b></h6>
                                    <div  class="small text-muted">Updated every 30 minutes</div>
                                </th>
                                <td width="15%"><h6>{{$server['totalDomain'] ?? '0'}}</h6></td>
                            </tr>
                        @endcan
                        @can('number_of_registered_sasl')
                            <tr>
                                <th width="45%"><h6><b>Total SASL based (SMTP relay request form registered)</b></h6></th>
                                <td width="15%"><h6>{{$server['totalSasl'] ?? '0'}}</h6></td>
                            </tr>
                        @endcan
                        @can('total_ip_based_registered')
                            <tr>
                                <th width="45%"><h6><b>Total IP based (SMTP relay request form registered)</b></h6></th>
                                <td width="15%"><h6>{{$server['totalIpBased'] ?? '0'}}</h6></td>
                            </tr>
                        @endcan
                    </tbody>
                    @endforeach
                </table>
                </div>
            </div>


          {{-- <div class="row">
            @can('total_application_registered')
            <div class="col-xl-4 mb-6" style="margin-right: -11%;">
                <div class="card h-100" style="width: 64%;">
                  <div class="card-body d-flex justify-content-center flex-column">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="me-3">
                        <i class="feather-xl text-primary mb-3" data-feather="activity"></i>
                        <h5>Total Application Registered</h5>
                        <div class="inline">
                          <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                          <h2 id="totalApplications" style="display: inline-block; margin-left: 5px;">{{$server['totalApplicationRegistered'] ?? '-'}}</h2>
                        </div>
                      </div>
                      <img src="{{asset ('assets/img/illustrations/dash_app.svg')}}" alt="..." style="width: 4rem" />
                    </div>
                  </div>
              </div>
            </div>
            @endcan
            @can('total_domain_registered')
            <div class="col-xl-4 mb-6" style="margin-right: -11%;">
                <div class="card h-100" style="width: 64%;">
                  <div class="card-body d-flex justify-content-center flex-column">
                    <div class="d-flex align-items-center justify-content-between">
                      <div class="me-3">
                        <i class="feather-xl text-secondary mb-3" data-feather="zap"></i>
                        <h5>Total Domain Registered</h5>
                        <div  class="small text-muted">Updated every 30 minutes</div>
                        <div class="inline">
                          <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                          <h2 id="totalDomains" style="display: inline-block; margin-left: 5px;">{{$server['totalDomain'] ?? '-'}}</h2>
                        </div>
                      </div>
                      <img src="{{asset ('assets/img/illustrations/dash_domain.svg')}}" alt="..." style="width: 4rem" />
                    </div>
                  </div>
              </div>
              </div>
            @endcan


            @can('number_of_registered_sasl')
            <div class="col-xl-4 mb-6" style="margin-right: -11%;">
                <div class="card h-100" style="width: 64%;">
                    <div class="card-body d-flex justify-content-center flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="me-3">
                                <i class="feather-xl text-green mb-3" data-feather="shield"></i>
                                <h5>Total SASL based (SMTP relay request form registered)</h5>
                                <div class="inline">
                                    <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                    <h2 id="totalSASL" style="display: inline-block; margin-left: 5px;">{{$server['totalSasl'] ?? '-'}}</h2>
                                </div>
                            </div>
                            <img src="{{asset ('assets/img/illustrations/dash_sasl.svg')}}" alt="..." style="width: 4rem" />
                        </div>
                    </div>
                </div>
            </div>
            @endcan

            @can('total_ip_based_registered')
            <div class="col-xl-4 mb-6" style="margin-right: -11%;">
                <div class="card h-100" style="width: 64%;">
                    <div class="card-body d-flex justify-content-center flex-column">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="me-3">
                                <i class="feather-xl text-green mb-3" data-feather=""></i>
                                <h5>Total IP based (SMTP relay request form registered)</h5>
                                <div class="inline">
                                    <i class="feather-l text-primary" data-feather="corner-down-right"></i>
                                    <h2 id="totalIPBased" style="display: inline-block; margin-left: 5px;">{{$server['totalIpBased'] ?? '-'}}</h2>
                                </div>
                            </div>
                            <img src="{{asset ('assets/img/illustrations/browser-stats.svg')}}" alt="..." style="width: 4rem" />
                        </div>
                    </div>
                </div>
            </div>
            @endcan --}}


          {{-- </div> --}}
        </div>
      </div>
    </div>
  </div>
    {{-- @endforeach --}}
<!-- End Loop By Server Here -->

  <div class="row mb-4">
    @can('top_5_list_ministry')
    <div class="col-xxl-6 mb-4">
        <div class="card" style="height: 455px;">
            <div class="card-header text-center p-3">
                <h5>TOP 5 LIST OF MINISTRY/AGENCY/SUK</h5>
            </div>
            <div class="card-body" style="overflow-y: auto;">
                @foreach($serverListAgencies as $server)
                    <div class="mb-4">
                        <h6>{{ $server['hostname'] }}</h6>
                        <p>{{ $server['ipAddress'] }}</p>

                        <div class="container">
                            <div class="chart-single">

                                <ul class="numbers">
                                  <li>100</li>
                                  <li></li>
                                  <li>80</li>
                                  <li></li>
                                  <li>60</li>
                                  <li></li>
                                  <li>40</li>
                                  <li></li>
                                  <li>20</li>
                                  <li></li>
                                  <li>0</li>

                                </ul>

                                <ul class="bars">
                                    @foreach ($server['agencies'] as $agency)
                                  <li><div data-percentage="{{ $agency['totalAgencies'] }}" data-numbers="" class="bar"></div><span>{{ $agency['agency'] }}</span><span class="percentage">{{ $agency['totalAgencies'] }}<sup>%</sup></span></li>
                                  @endforeach
                                </ul>


                              </div>
                        </div>
                        {{-- <canvas id="chart-{{ $loop->index }}"  width="729" height="217"></canvas> --}}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endcan

    @can('view_current_mail_queue_count')
    <div class="col-xxl-6 mb-4">
        <div class="card">
            <div class="card-header text-center p-3">
                <h5>View Current MailQueue Count</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div style="width: 100%; overflow-x: auto; height: 355px;">
                    <table id="spmrDatatable" class="display table table-striped table-bordered" cellspacing="0" width="60%">
                        <tbody>
                            @foreach ($serverconfig as $index => $server)
                                <tr>
                                    <th width="45%"><h6>{{ $server->hostname }}</h6></th>
                                    <td id="mailQueueCount{{ $index }}"  width="15%"><h6>--</h6></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

<div class="container">
    <div class="chart-single">

        <ul class="numbers">
          <li>100</li>
          <li></li>
          <li>80</li>
          <li></li>
          <li>60</li>
          <li></li>
          <li>40</li>
          <li></li>
          <li>20</li>
          <li></li>
          <li>0</li>

        </ul>

        <ul class="bars">
          <li><div data-percentage="99" data-numbers="" class="bar"></div><span>KKM</span><span class="percentage">99<sup>%</sup></span></li>
          <li><div data-percentage="100" data-numbers="" class="bar"></div><span>KKM</span><span class="percentage">100<sup>%</sup></span></li>
          <li><div data-percentage="98" data-numbers=""  class="bar"></div><span>KKM</span><span class="percentage">98<sup>%</sup></span></li>
          <li><div data-percentage="100" data-numbers="" class="bar"></div><span>KKM</span><span class="percentage">100<sup>%</sup></span></li>
          <li><div data-percentage="100" data-numbers="" class="bar"></div><span>KKM</span><span class="percentage">100<sup>%</sup></span></li>
        </ul>


      </div>
</div>

    {{--@can('top_5_list_ministry')
    <div class="col-xxl-12 mb-4">
        <div class="card" style="height: 500px; overflow-y: auto;">
            <div class="card-header text-center p-5">
                <h5>TOP 5 LIST OF MINISTRY/AGENCY/SUK</h5>
                <h5>{{ $serverListMinistry[0]['hostname'] }}</h5>
                <p>{{ $serverListMinistry[0]['ipAddress'] }}</p>
            </div>
            <div class="card-body">
                @foreach($serverListMinistry as $server)
                    <div class="mb-4">
                        <h6>{{ $server['hostname'] }}</h6>
                        <p>{{ $server['ipAddress'] }}</p>
                        <canvas id="chartMinistry-{{ $loop->index }}"></canvas>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endcan --}}



    {{-- @can('top_5_list_department')
    <div class="col-xxl-6">
        <div class="card">
          <div class="card-header text-center p-5">
            <h5>Top 5 List Department/Division/Unit</h5>
          </div>
          <div class="card-body">
            <!-- <label for="limitSelect">Select Limit:</label><select name="limit" id="limitSelect"><option value="all" {{ $limit === 'all' ? 'selected' : '' }}>All</option><option value="10" {{ $limit == 10 ? 'selected' : '' }}>Top 10</option><option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20</option><option value="50" {{ $limit == 50 ? 'selected' : '' }}>Top 50</option></select> -->
            <canvas id="departmentChart"></canvas>
          </div>
        </div>
      </div>
    @endcan --}}

  </div>
  <!-- <div class="row mb-4"><div class="col-xxl-4"><div class="card"><div class="card-header text-center p-5"><h5>Graf serial apps vs day</h5></div><div class="card-body"><canvas id="appsChart"></canvas></div></div></div><div class="col-xxl-4"><div class="card"><div class="card-header text-center p-5"><h5>Graf serial domain vs day</h5></div><div class="card-body"><canvas id="domainChart"></canvas></div></div></div><div class="col-xxl-4"><div class="card"><div class="card-header text-center p-5"><h5>Graf serial sasl vs day</h5></div><div class="card-body"><canvas id="saslChart"></canvas></div></div></div></div> -->
  <div class="row">
    @can('serial_mail_queue_chart')
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-header text-center p-3">
                <h5>Graph Serial Mail Queue vs Time</h5>
            </div>
            <div class="card-body">
                <canvas id="mailQueueChart"></canvas>
            </div>
        </div>
    </div>
    @endcan


</div>
@can('realtime_service_status')
<div class="row  mt-5">
    <div class="col-xxl-12">
      <div class="card">
        <div class="card-header text-center p-3">
          <h5>RealTime Service Status</h5> @php $status = 'Online'; @endphp
        </div>
        {{--<div class="card-body mainRealtime" style="min-height: auto; max-height: 800px;overflow-y: auto;">--}}
        <div class="card-body mainRealtime"  style="overflow-y: auto;">

            <div class="table-responsive">
                <div style="width: 100%; overflow-x: auto; height: 355px;">
                <table id="spmrDatatable" class="display table table-striped table-bordered" cellspacing="0" width="60%">
                    @foreach ($serverconfig as $server)
                    <thead>
                        @if($loop->index != 0)
                        <tr><th colspan="2">&nbsp;</th></tr>@endif
                        <tr  style="background-color: rgb(197, 204, 210);">
                            <th colspan="2">
                                <h5 style="text-align:left;">
                                    {{ $server['hostname']}}
                                    <p>{{ $server['ipAddress'] }}</p>
                                </h5>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                            <tr>
                                <th width="25%"><a class="mService" href="{{ route('manageService.index', ['server_id' => $server->id, 'server_type' => 'postfix']) }}">
                                    <h6><b>Postfix</b></h6>
                                </a></th>
                                <td width="35%"><h6 id="statusPostfix{{ $loop->index }}">--</h6></td>
                            </tr>
                            <tr>
                                <th width="25%"><a class="mService" href="{{ route('manageService.index', ['server_id' => $server->id , 'server_type' => 'apache']) }}">
                                    <h6><b>Apache</b></h6>
                                    </a>
                                </th>
                                <td width="35%"><h6 id="statusApache2{{ $loop->index }}">--</h6></td>
                            </tr>
                            <tr>
                                <th width="25%"><a class="mService" href="{{ route('manageService.index', ['server_id' => $server->id, 'server_type' => 'webmin']) }}">
                                    <h6><b>Webmin</b></h6>
                                </a></th>
                                <td width="35%"><h6 id="statusWebmin{{ $loop->index }}">--</h6></td>
                            </tr>
                    </tbody>
                    @endforeach
                </table>
                </div>
            </div>


{{--
            @foreach ($serverconfig as $server)
                    <h5>{{ $server->hostname }}</h5>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-4 mb-4">
                                <a class="card h-100 mService" href="{{ route('manageService.index', ['server_id' => $server->id, 'server_type' => 'postfix']) }}">
                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <h5>Postfix</h5>
                                            </div>
                                            <img id="statusPostfixImage{{ $loop->index }}" src="{{ asset('assets/img/demo/offline.png') }}" alt="Postfix is down" style="width: 5rem">
                                        </div>
                                        <p id="statusPostfix{{ $loop->index }}" style="display: inline-block; margin-left: 5px;">--</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <a class="card h-100 mService" href="{{ route('manageService.index', ['server_id' => $server->id , 'server_type' => 'apache']) }}">
                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <h5>Apache</h5>
                                            </div>
                                            <img id="statusApache2Image{{ $loop->index }}" src="{{ asset('assets/img/demo/offline.png') }}" alt="Apache2 is down" style="width: 5rem">
                                        </div>
                                        <p id="statusApache2{{ $loop->index }}" style="display: inline-block; margin-left: 5px;">--</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-xl-4 mb-4">
                                <a class="card h-100 mService" href="{{ route('manageService.index', ['server_id' => $server->id, 'server_type' => 'webmin']) }}">
                                    <div class="card-body cardService d-flex justify-content-center flex-column">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-3">
                                                <h5>Webmin</h5>
                                            </div>
                                            <img id="statusWebminImage{{ $loop->index }}" src="{{ asset('assets/img/demo/offline.png') }}" alt="Webmin is down" style="width: 5rem">
                                        </div>
                                        <p id="statusWebmin{{ $loop->index }}" style="display: inline-block; margin-left: 5px;">--</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

            @endforeach --}}
        </div>
      </div>
    </div>
  </div>
</div>
@endcan

@endsection
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    function getRandomColor() {
        var letters = '0123456789ABCDEF';
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

//     const serverListAgencies = @json($serverListAgencies);

// serverListAgencies.forEach((server, index) => {
//     const chartId = `chart-${index}`;
//     const canvasElement = document.getElementById(chartId);

//     // Ensure the canvas element exists
//     if (!canvasElement) {
//         console.error(`Canvas element with id ${chartId} not found.`);
//         return;
//     }

//     const ctx = canvasElement.getContext('2d');
//     const labels = server.agencies.map(item => item.agency);
//     const datasets = server.agencies.map((item, i) => ({
//         label: item.agency,
//         data: labels.map((label, j) => j === i ? item.totalAgencies : 0),
//         backgroundColor: getRandomColor(),
//         borderColor: getRandomColor(),
//         borderWidth: 1,
//     }));

//     new Chart(ctx, {
//         type: 'bar',
//         data: {
//             labels: labels,
//             datasets: datasets
//         },
//         options: {
//             plugins: {
//                 legend: {
//                     display: true, // Show legend
//                     position: 'bottom' // Position legend at the bottom
//                 },
//                 tooltip: {
//                     callbacks: {
//                         label: function(context) {
//                             return context.dataset.label + ': ' + context.parsed.y;
//                         }
//                     }
//                 }
//             },
//             scales: {
//                 y: {
//                     ticks: {
//                         beginAtZero: true
//                     }
//                 },
//                 x: {
//                     ticks: {
//                     autoSkip: false,
//                     maxRotation: 45,
//                     minRotation: 45
//                 }
//                 }
//             }
//         }
//     });
// });








        // const serverListMinistry = @json($serverListMinistry);

        // serverListMinistry.forEach((server, index) => {
        //     const ctx = document.getElementById(`chartMinistry-${index}`).getContext('2d');
        //     const labels = server.ministry.map(item => item.ministry);
        //     const data = server.ministry.map(item => item.totalMinistry);

        //     const datasets = labels.map((label, i) => ({
        //         label: label,
        //         data: [data[i]],
        //         backgroundColor: getRandomColor(),
        //         borderColor: getRandomColor(),
        //         borderWidth: 1
        //     }));

        //     new Chart(ctx, {
        //         type: 'bar',
        //         data: {
        //             labels: labels,
        //             datasets: datasets
        //         },
        //         options: {
        //             scales: {
        //                 y: {
        //                     beginAtZero: true
        //                 }
        //             }
        //         }
        //     });
        // })
    });
</script>

<script>

var data = [];
var sockets = [];
var serverIpaddresses = [];
var serverIpaddresses1 = [];
var labels = [];
var datasets = {};
$(document).ready(function() {

$('a.mService').click(function(e) {
    e.preventDefault();
    var href = $(this).attr('href');

    $('body').fadeOut(300, function() {
        window.location.href = href;
    });
});

function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

// renderChartKementerian();
// function renderChartKementerian() {
//     var ctx = document.getElementById('kementerianChart').getContext('2d');
//     window.kementerianChart = new Chart(ctx, {
//         type: 'bar',
//         data: {
//             labels: <?php echo json_encode($filteredMinistries); ?>,
//             datasets: <?php
//                       $datasets = [];
//                       foreach ($serverList as $server) {
//                           $serverHostname = $server['hostname'];
//                           $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
//                           $data = collect($departmentCounts)->map(function ($data) use ($serverHostname) {
//                               return $data['postmasters'] === $serverHostname ? $data['count'] : 0;
//                           })->values()->toArray(); // Convert to array
//                           $datasets[] = [
//                               'label' => $serverHostname,
//                               'data' => $data,
//                               'backgroundColor' => $color, // Corrected syntax
//                               'borderColor' => '#4e73df',
//                               'borderWidth' => 1
//                           ];
//                   }
//                       echo json_encode($datasets);
//                       ?>
//         },
//         options: {
//             scales: {
//                 y: {
//                     beginAtZero: true
//                 }
//             },
//             plugins: {
//                 legend: {
//                     display: true,
//                     position: 'bottom',
//                 }
//             }
//         }
//     });
// }

// renderChart();
// function renderChart() {
//     var departmentChart = document.getElementById('departmentChart').getContext('2d');
//     window.chart = new Chart(departmentChart, {
//         type: 'bar',
//         data: {
//             labels: <?php echo json_encode($filteredDept); ?>,
//             datasets: <?php
//                       $datasets = [];
//                       foreach ($serverList as $server) {
//                           $serverHostname = $server['hostname'];
//                           $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
//                           $data = collect($departmentCounts2)->map(function ($data) use ($serverHostname) {
//                               return $data['postmasters'] === $serverHostname ? $data['count'] : 0;
//                           })->values()->toArray(); // Convert to array
//                           $datasets[] = [
//                               'label' => $serverHostname,
//                               'data' => $data,
//                               'backgroundColor' => $color, // Corrected syntax
//                               'borderColor' => '#4e73df',
//                               'borderWidth' => 1
//                           ];
//                       }
//                       echo json_encode($datasets);
//                       ?>
//         },
//         options: {
//             scales: {
//                 y: {
//                     beginAtZero: true
//                 }
//             },
//             plugins: {
//                 legend: {
//                     display: true,
//                     position: 'bottom',
//                 }
//             }
//         }
//     });
// }

function randomColor() {
    return '#' + Math.floor(Math.random() * 16777215).toString(16);
}

// var data = [];

function generateServerData(serverName, cleanResponse) {

    if (cleanResponse) {
        const cleanRes = cleanResponse.replace(/(\\.||\[.*?#\d+ )/g, '').trim();
        const lines = cleanRes.split('\n');
        const filteredLines = lines.filter(line => !line.startsWith('[root@postmaster1a:/opt/cluster/postfix]#'));
        const numericLines = filteredLines.filter(line => /^\d/.test(line));

        numericLines.forEach((line, index) => {
            const [timestamp, value] = line.split(',');
            const formattedTimestamp = timestamp.replace(' ', ' ').replace(/-/g, '/');
            data.push({
                x: formattedTimestamp,
                y: parseInt(value)
            });
        });

    } else {
        //console.error("cleanResponse is undefined");
    }

    var currentDate = new Date();
    currentDate.setHours(0, 0, 0, 0); // Set time to midnight

    return {
        label: serverName,
        borderColor: randomColor(),
        backgroundColor: 'rgba(0, 0, 0, 0)', // Transparent background
        borderWidth: 1,
        data: data
    };

}

// Function to format timestamp as "dd/mm/yyyy HH:mm:ss"
function formatDate(date) {
    var day = ('0' + date.getDate()).slice(-2);
    var month = ('0' + (date.getMonth() + 1)).slice(-2);
    var year = date.getFullYear();
    var hours = ('0' + date.getHours()).slice(-2);
    var minutes = ('0' + date.getMinutes()).slice(-2);
    var seconds = ('0' + date.getSeconds()).slice(-2);

    return day + '/' + month + '/' + year + ' ' + hours + ':' + minutes + ':' + seconds;
}



@foreach ($serverconfig as $server)
var serverMailRelay1{{$loop->iteration}} = "{{ $server->hostname }}";
serverIpaddresses1.push(serverMailRelay1{{$loop->iteration}});
@endforeach

var servers = {};

// Function to handle WebSocket connection and data processing
function connectAndProcess(hostname1, index) {
    const serverAddress1 = 'wss://spmr.mygovuc.gov.my/' + hostname1;
    const socket1 = new WebSocket(serverAddress1);

    socket1.onopen = function() {
        console.log("Connected to " + serverAddress1);

        // Send any initial message if needed
        // socket.send("Initial message or request data");
    };

    socket1.onmessage = function(event1) {
        var message = event1.data.trim();

        // Check if the message matches the desired format
        if (!/^\d{4}-\d{2}-\d{2} \d{2}:\d{2},\d+$/.test(message)) {
            // console.log("Skipping message from " + serverAddress1 + ":", message);
            return; // Skip processing if the message does not match the format
        }
        const [timestamp, value] = message.split(',');

         // Push timestamp to labels array if it's not already present
         if (!labels.includes(timestamp)) {
            labels.push(timestamp);
        }

        // Add value to the dataset corresponding to the hostname
        if (!datasets[hostname1]) {
            datasets[hostname1] = {
                label: hostname1,
                data: [],
                borderColor: getRandomColor(), // Random color for the line
                borderWidth: 2, // Bold line
                fill: false
            };
        }
        datasets[hostname1].data.push({
            x: timestamp,
            y: parseInt(value)
        });

        // Update chart data
        mailQueueChart.data.labels = labels;
        mailQueueChart.data.datasets = Object.values(datasets); // Convert object to array
        mailQueueChart.update();

    };

    socket1.onerror = function(error) {
        console.error("WebSocket Error for " + serverAddress1 + ": ", error);
    };

    socket1.onclose = function() {
        console.log("WebSocket connection closed for " + serverAddress1);
    };

    sockets.push(socket1);
}

// Iterate over the serverIpaddresses array and generate server data
serverIpaddresses1.forEach(function(hostname, index) {
    connectAndProcess(hostname, index);
});

// // start reopen connection
// function reopenConnections() {
//     serverIpaddresses1.forEach(function(hostname, index) {
//         connectAndProcess(hostname, index);
//     });
// }

// // Call the reopenConnections function after 3 minutes
// setTimeout(reopenConnections, 3 * 60 * 1000);
// // end reopen connection

var ctx = document.getElementById('mailQueueChart').getContext('2d');
var mailQueueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: datasets
    },
    options: {
        scales: {
            x: [{
                type: 'time',
                time: {
                    parser: 'YYYY-MM-DD HH:mm', // Adjust parser for the format
                    unit: 'minute', // Display unit as minute
                    displayFormats: {
                        minute: 'HH:mm' // Format for minute display
                    }
                },
                title: {
                    display: true,
                    text: 'Time'
                }
            }],
            y: [{
                title: {
                    display: true,
                    text: 'Value'
                },
                beginAtZero: true
            }]
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom', // Adjust legend position if needed
                onHover: function (event, legendItem) {
                    event.target.style.cursor = 'pointer';
                }
            }
        }
    }
});

@foreach ($serverconfig as $server)
let serverMailRelay{{$loop->iteration}} = "{{ $server->hostname }}";
serverIpaddresses.push(serverMailRelay{{$loop->iteration}});
@endforeach

serverIpaddresses.forEach(hostname => {
    // const serverAddress = 'wss://' + hostname + '/wss/';
    const serverAddress = 'wss://spmr.mygovuc.gov.my/' + hostname;

    const socket = new WebSocket(serverAddress);
    // console.log("serverAddress-> "+ serverAddress);
    sockets.push(socket);
});

var commands = [
    {
        command: 'mailq | grep -c "^[A-F0-9]"',
        id: 'mailQueueCount'
    },
    {
        command: 'status=$(systemctl --no-pager status postfix | grep Active:); if echo "$status" | grep -q "active (exited) since"; then echo "Postfix is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Postfix is down"; else echo "Something is wrong"; fi',
        id: 'statusPostfix'
    },
    {
        command: 'status=$(systemctl --no-pager status fail2ban | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Fail2ban is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Fail2ban is down"; else echo "Something is wrong"; fi',
        id: 'statusFail2ban'
    },
    {
        command: 'status=$(systemctl --no-pager status webmin | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Webmin is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Webmin is down"; else echo "Something is wrong"; fi',
        id: 'statusWebmin'
    },
    {
        command: 'status=$(systemctl --no-pager status apache2 | grep Active:); if echo "$status" | grep -q "active (running) since"; then echo "Apache2 is running"; elif echo "$status" | grep -q "inactive (dead) since"; then echo "Apache2 is down"; else echo "Something is wrong"; fi',
        id: 'statusApache2'
    },
    {
        command: '/usr/bin/tail -50 /data/collections/spmr/count_mailq.txt'
    },
];

sockets.forEach((socket, index) => {

    socket.addEventListener('open', (event) => {
        //console.log("WebSocket connection opened to " + socket.url);
        // Function to send each command with delay
        commands.forEach((commandObj, commandIndex) => {
            setTimeout(() => {
                //console.log("Sending command to : " + commandObj.command);
                socket.send(commandObj.command);
            }, commandIndex * 1000); // Delay each command by 1 second
        });

    });

    socket.addEventListener('close', (event) => {
        //console.log("WebSocket connection closed to " + socket.url);
    });

    socket.addEventListener('error', (event) => {
        //console.error("WebSocket error occurred with " + socket.url + ": " + event);
    });
    var outputs = [];
    socket.addEventListener('message', (event) => {
        //console.log("Message received from " + socket.url + ": " + event.data);
        const response = event.data;
        outputs.push(response);
        // Update the corresponding element based on the response
            $('#mailQueueCount' + index).text(outputs[0]);

            $('#statusPostfix' + index).text(outputs[1]);

            // var postfixStatus = outputs[1];
            // var postfixAlt;
            // if(postfixStatus == "Postfix is running"){
            //     postfixStatus = "Online";
            //     postfixAlt = "Postfix is running";
            // }else{
            //     postfixStatus = "Offline";
            //     postfixAlt = "Postfix is down";
            // }
            // var postfixImageSrc = postfixStatus === 'Online' ? 'assets/img/demo/online.png' : 'assets/img/demo/offline.png';

            // $('#statusPostfixImage'+ index).attr('src', postfixImageSrc).attr('alt', postfixAlt);

            $('#statusFail2ban' + index).text(outputs[2]);

            $('#statusWebmin' + index).text(outputs[3]);
            // var WebminStatus = outputs[3];
            // var WebminAlt;
            // if(WebminStatus == "Webmin is running"){
            //     WebminStatus = "Online";
            //     WebminAlt = "Webmin is running";
            // }else{
            //     WebminStatus = "Offline";
            //     WebminAlt = "Webmin is down";
            // }
            // var WebminImageSrc = WebminStatus === 'Online' ? 'assets/img/demo/online.png' : 'assets/img/demo/offline.png';
            // $('#statusWebminImage'+ index).attr('src', WebminImageSrc).attr('alt', WebminAlt);

            $('#statusApache2' + index).text(outputs[4]);

            // var Apache2Status = outputs[4];
            //     var Apache2Alt;
            //     if(Apache2Status == "Apache2 is running"){
            //         Apache2Status = "Online";
            //         Apache2Alt = "Apache2 is running";
            //     }else{
            //         Apache2Status = "Offline";
            //         Apache2Alt = "Apache2 is down";
            //     }
            //     var Apache2ImageSrc = Apache2Status === 'Online' ? 'assets/img/demo/online.png' : 'assets/img/demo/offline.png';
            //     $('#statusApache2Image'+ index).attr('src', Apache2ImageSrc).attr('alt', Apache2Alt);

                // console.log(outputs[5]);
                extractOutput(response);

    });
});

function extractOutput(response) {

const cleanResponse = response.replace(/\[\d;?\d*m/g, '').trim();
generateServerData('Mailq Server', cleanResponse);

const outputMatch = cleanResponse.match(/\[.*?\]#\S (.*)/);

const outputs = [];

if (outputMatch && outputMatch.length > 1) {

    outputs.push(outputMatch[1]);

    return outputs;
} else {
    return null;
}
}

function updateChart() {
    mailQueueChart.update();
}

});

</script>
@endpush
