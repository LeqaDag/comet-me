
@extends('layouts/layoutMaster')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
<link rel="stylesheet" 
href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/charts-apex.js')}}"></script>
<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')

<h1>
  Welcome {{Auth::guard('user')->user()->name}}  
</h1>

<div class="row">
  <!-- Website Analytics-->
  <div class="col-lg-6 col-md-12 mb-4">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Website Analytics</h5>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="analyticsOptions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="analyticsOptions">
            <a class="dropdown-item" href="javascript:void(0);">Select All</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
            <a class="dropdown-item" href="javascript:void(0);">Share</a>
          </div>
        </div>
      </div>
      <div class="card-body pb-2">
        <div class="d-flex justify-content-around align-items-center flex-wrap mb-4">
          <div class="user-analytics text-center me-2">
            <i class="bx bx-user me-1"></i>
            <span>Users</span>
            <div class="d-flex align-items-center mt-2">
              <div class="chart-report" data-color="success" data-series="35"></div>
              <h3 class="mb-0">61K</h3>
            </div>
          </div>
          <div class="sessions-analytics text-center me-2">
            <i class="bx bx-pie-chart-alt me-1"></i>
            <span>Sessions</span>
            <div class="d-flex align-items-center mt-2">
              <div class="chart-report" data-color="warning" data-series="76"></div>
              <h3 class="mb-0">92K</h3>
            </div>
          </div>
          <div class="bounce-rate-analytics text-center">
            <i class="bx bx-trending-up me-1"></i>
            <span>Bounce Rate</span>
            <div class="d-flex align-items-center mt-2">
              <div class="chart-report" data-color="danger" data-series="65"></div>
              <h3 class="mb-0">72.6%</h3>
            </div>
          </div>
        </div>
        <div id="analyticsBarChart"></div>
      </div>
    </div>

  </div>

  <!-- Referral, conversion, impression & income charts -->
  <div class="col-lg-6 col-md-12">
    <div class="row">
      <!-- Referral Chart-->
      <div class="col-sm-6 col-12 mb-4">
        <div class="card">
          <div class="card-body text-center">
            <h2 class="mb-1">$32,690</h2>
            <span class="text-muted">Referral 40%</span>
            <div id="referralLineChart"></div>
          </div>
        </div>
      </div>
      <!-- Conversion Chart-->
      <div class="col-sm-6 col-12 mb-4">
        <div class="card">
          <div class="card-header d-flex justify-content-between pb-3">
            <div class="conversion-title">
              <h5 class="card-title mb-1">Conversion</h5>
              <p class="mb-0 text-muted">60%
                <i class="bx bx-chevron-up text-success"></i>
              </p> 
            </div>
            <h2 class="mb-0">89k</h2>
          </div>
          <div class="card-body">
            <div id="conversionBarchart"></div>
          </div>
        </div>
      </div>
      <!-- Impression Radial Chart-->
      <div class="col-sm-6 col-12 mb-4">
        <div class="card">
          <div class="card-body text-center">
            <div id="impressionDonutChart"></div>
          </div>
        </div>
      </div>
      <!-- Growth Chart-->
      <div class="col-sm-6 col-12">
        <div class="row">
          <div class="col-12 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div class="d-flex align-items-center gap-3">
                    <div class="avatar">
                      <span class="avatar-initial bg-label-primary rounded-circle">
                        <i class="bx bx-user fs-4"></i>
                      </span>
                    </div>
                    <div class="card-info">
                      <h5 class="card-title mb-0 me-2">
                      
                      </h5>
                      <small class="text-muted">Users</small>
                    </div>
                  </div>
                  <div id="conversationChart"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                  <div class="d-flex align-items-center gap-3">
                    <div class="avatar">
                      <span class="avatar-initial bg-label-warning rounded-circle"><i class="bx bx-dollar fs-4"></i></span>
                    </div>
                    <div class="card-info">
                      <h5 class="card-title mb-0 me-2">$53,659</h5>
                      <small class="text-muted">Income</small>
                    </div>
                  </div>
                  <div id="incomeChart"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Referral, conversion, impression & income charts -->

  <!-- Activity -->
  <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Activity</h5>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          <li class="d-flex mb-4 pb-2">
            <div class="avatar avatar-sm flex-shrink-0 me-3">
              <span class="avatar-initial rounded-circle bg-label-primary"><i class='bx bx-cube'></i></span>
            </div>
            <div class="d-flex flex-column w-100">
              <div class="d-flex justify-content-between mb-1">
                <span>Total Sales</span>
                <span class="text-muted">$2,459</span>
              </div>
              <div class="progress" style="height:6px;">
                <div class="progress-bar bg-primary" style="width: 40%" 
                role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </li>
          <li class="d-flex mb-4 pb-2">
            <div class="avatar avatar-sm flex-shrink-0 me-3">
              <span class="avatar-initial rounded-circle bg-label-success">
                <i class='bx bx-dollar'></i></span>
            </div>
            <div class="d-flex flex-column w-100">
              <div class="d-flex justify-content-between mb-1">
                <span>Income</span>
                <span class="text-muted">$8,478</span>
              </div>
              <div class="progress" style="height:6px;">
                <div class="progress-bar bg-success" style="width: 80%" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </li>
          <li class="d-flex mb-4 pb-2">
            <div class="avatar avatar-sm flex-shrink-0 me-3">
              <span class="avatar-initial rounded-circle bg-label-warning"><i class='bx bx-trending-up'></i></span>
            </div>
            <div class="d-flex flex-column w-100">
              <div class="d-flex justify-content-between mb-1">
                <span>Budget</span>
                <span class="text-muted">$12,490</span>
              </div>
              <div class="progress" style="height:6px;">
                <div class="progress-bar bg-warning" style="width: 80%" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </li>
          <li class="d-flex mb-2">
            <div class="avatar avatar-sm flex-shrink-0 me-3">
              <span class="avatar-initial rounded-circle bg-label-danger"><i class='bx bx-check'></i></span>
            </div>
            <div class="d-flex flex-column w-100">
              <div class="d-flex justify-content-between mb-1">
                <span>Tasks</span>
                <span class="text-muted">$184</span>
              </div>
              <div class="progress" style="height:6px;">
                <div class="progress-bar bg-danger" style="width: 25%" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!--/ Activity -->

  <!-- Profit Report & Registration -->
  <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3">
    <div class="row">
      <div class="col-12 col-sm-6 col-md-12 mb-4">
        <div class="card h-100">
          <div class="card-header">
            <h5 class="card-title mb-0">Profit Report</h5>
          </div>
          <div class="card-body d-flex align-items-end justify-content-between">
            <div class="d-flex justify-content-between align-items-center gap-3 w-100">
              <div class="d-flex align-content-center">
                <div class="chart-report" data-color="danger" data-series="25"></div>
                <div class="chart-info">
                  <h5 class="mb-0">$12k</h5>
                  <small class="text-muted">2020</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="chart-report" data-color="info" data-series="50"></div>
                <div class="chart-info">
                  <h5 class="mb-0">$64k</h5>
                  <small class="text-muted">2021</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-12 mb-4">
        <div class="card">
          <div class="card-header pb-2">
            <h5 class="card-title mb-0">Registration</h5>
          </div>
          <div class="card-body pb-2">
            <div class="d-flex justify-content-between align-items-end gap-3">
              <div class="mb-3">
                <div class="d-flex align-content-center">
                  <h5 class="mb-1">58.4k</h5>
                  <i class="bx bx-chevron-up text-success"></i>
                </div>
                <small class="text-success">12.8%</small>
              </div>
              <div id="registrationsBarChart"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Profit Report & Registration -->

  <!-- Sales -->
  <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-4">
    <div class="card">
      <div class="card-header d-flex align-items-start justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Sales</h5>
          <small class="card-subtitle text-muted">Calculated in last 7 days</small>
        </div>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="salesReport" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesReport">
            <a class="dropdown-item" href="javascript:void(0);">Select All</a>
            <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
            <a class="dropdown-item" href="javascript:void(0);">Share</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div id="salesChart"></div>
        <ul class="p-0 m-0">
          <li class="d-flex mb-3">
            <span class="text-primary me-2"><i class='bx bx-up-arrow-alt bx-sm'></i></span>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0 lh-1">Best Selling</h6>
                <small class="text-muted">Saturday</small>
              </div>
              <div class="item-progress">28.6k</div>
            </div>
          </li>
          <li class="d-flex">
            <span class="text-secondary me-2"><i class='bx bx-down-arrow-alt bx-sm'></i></span>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                <h6 class="mb-0 lh-1">Lowest Selling</h6>
                <small class="text-muted">Thursday</small>
              </div>
              <div class="item-progress">7.9k</div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!--/ Sales -->

  <!-- Growth Chart-->
  <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-4">
    <div class="card">
      <div class="card-body text-center">
        <div class="dropdown mb-4">
          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButtonSec" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            2020
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButtonSec">
            <a class="dropdown-item" href="javascript:void(0);">2022</a>
            <a class="dropdown-item" href="javascript:void(0);">2021</a>
            <a class="dropdown-item" href="javascript:void(0);">2020</a>
          </div>
        </div>
        <div id="growthRadialChart"></div>
        <h6 class="mb-0 mt-5"> 62% Growth in 2022</h6>
      </div>
    </div>
  </div>
  <!-- Growth Chart-->


  <!-- Donut Chart -->
   <div class="col-md-6 col-12">
    <div class="card">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div>
          <h5 class="card-title mb-0">Expense Ratio</h5>
          <small class="text-muted">Spending on various categories</small>
        </div>
        <div class="dropdown d-none d-sm-flex">
          <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-calendar"></i></button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Today</a></li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Yesterday</a></li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 7 Days</a></li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 30 Days</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Current Month</a></li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last Month</a></li>
          </ul>
        </div>
      </div>
      <div class="card-body">
        <div id="donutChart"></div>
      </div>
    </div>
  </div>
</div>
@endsection