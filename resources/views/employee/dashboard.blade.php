
@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@include('layouts.all')

@section('content')

<h1>
  Welcome {{Auth::guard('user')->user()->name}}  
</h1> 

<div class="col-12">
  <div class="card mb-4">
    <h5 class="card-header">All Communities</h5>
    <div class="card-body">
      <form method="POST" enctype='multipart/form-data' id="communityFilterMapForm">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="regions[]" class="selectpicker form-control" 
                data-live-search="true" multiple>
                  <option disabled selected>Search Regions</option>
                  @foreach($regions as $region)
                  <option value="{{$region->id}}">
                    {{$region->english_name}}
                  </option>
                  @endforeach
                </select> 
              </fieldset>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="sub_regions[]" class="selectpicker form-control" 
                data-live-search="true" multiple>
                  <option disabled selected>Search Sub Regions</option>
                  @foreach($subregions as $subregion)
                  <option value="{{$subregion->id}}">
                    {{$subregion->english_name}}
                  </option>
                  @endforeach
                </select> 
              </fieldset>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="services[]"
                  class="selectpicker form-control" 
                  data-live-search="true" multiple>
                  <option disabled selected>Search Services</option>
                  @foreach($services as $service)
                    <option value="{{$service->id}}">
                      {{$service->service_name}}
                    </option>
                  @endforeach
                </select> 
              </fieldset>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="years[]" class="selectpicker form-control" 
                  data-live-search="true" multiple>
                  <option disabled selected>Search Service Year</option>
                  @php
                    $startYear = 2010; // C
                    $currentYear = date("Y");
                  @endphp
                  @for ($year = $currentYear; $year >= $startYear; $year--)
                    <option value="{{ $year }}">{{ $year }}</option>
                  @endfor
                </select> 
              </fieldset>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="statuses[]" class="selectpicker form-control" 
                data-live-search="true" multiple>
                  <option disabled selected>Search Community Statuses</option>
                  @foreach($statuses as $status)
                  <option value="{{$status->id}}">
                    {{$status->name}}
                  </option>
                  @endforeach
                </select> 
              </fieldset>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="system_types[]"
                  class="selectpicker form-control" 
                  data-live-search="true" multiple>
                  <option disabled selected>Search System Types</option>
                  @foreach($energySystemTypes as $energySystemType)
                    <option value="{{$energySystemType->id}}">
                      {{$energySystemType->name}}
                    </option>
                  @endforeach
                </select> 
              </fieldset>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="donors[]" class="selectpicker form-control" 
                  data-live-search="true" multiple>
                  <option disabled selected>Search Donors</option>
                  @foreach($donors as $donor)
                    <option value="{{$donor->id}}">
                      {{$donor->donor_name}}
                    </option>
                  @endforeach
                </select> 
              </fieldset>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <button class="btn btn-info" id="communityFilterMapButton" type="button">
                  <i class='fa-solid fa-map'></i>
                  View Filtered Map
                </button>
              </fieldset>
            </div>
          </div>
        </div>
      </form>
      <div class="leaflet-map" id="layerControl1"></div>
      <div class="leaflet-map" id="layerControlFilter"></div>
    </div>
  </div>
</div>

<div class="card mb-4">
  <div class="card-body">
    <h5>Served Communities Energy</h5>
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="row">
        <div class="col-lg-3 col-sm-3 col-md-3 mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$regionNumbers}}</h2>
              <span class="text-muted">Regions</span>
              <div class="primary">
                <a href="{{'sub-region'}}" target="_blank" type="button">
                  <i class="bx bx-map me-1 bx-lg text-warning"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$communityNumbers}}</h2>
              <span class="text-muted">Communitites</span>
              <div class="">
                <a href="{{'community'}}" target="_blank" type="button">
                  <i class="bx bx-home me-1 bx-lg text-success"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$householdNumbers}}</h2>
              <span class="text-muted">Households</span>
              <div class="primary">
                <a href="{{'household'}}" target="_blank" type="button">
                  <i class="bx bx-user me-1 bx-lg bx-primary"></i>
                </a>
              </div>
            </div>
          </div> 
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$numberOfPeople->number_of_people}}</h2>
              <span class="text-muted">People</span>
              <div class="primary">
                <a href="#" type="button">
                  <i class="bx bx-group me-1 bx-lg text-dark"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-12 col-md-12">
      <div class="row">
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$numberOfMale->number_of_male}}</h2>
              <span class="text-muted">Male</span>
              <div class="">
                <a href="#" type="button">
                  <i class="bx bx-male me-1 bx-lg text-secondary"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$numberOfFemale->number_of_female}}</h2>
              <span class="text-muted">Female</span>
              <div class="primary">
                <a href="#" type="button">
                  <i class="bx bx-female me-1 bx-lg text-light"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$numberOfAdults->number_of_adults}}</h2>
              <span class="text-muted">Adults</span>
              <div class="primary">
                <a href="#" type="button">
                  <i class="bx bx-male bx-lg text-danger"></i>
                  <i class="bx bx-female me-1 bx-lg text-danger"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$numberOfChildren->number_of_children}}</h2>
              <span class="text-muted">Children</span>
              <div class="">
                <a href="#" type="button">
                  <i class="bx bx-face me-1 bx-lg text-info"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$totalMgSystem->count()}}</h2>
              <span class="text-muted">MG Systems</span>
              <div class="">
                <a href="{{'energy-system'}}" target="_blank" type="button">
                  <i class="bx bx-grid me-1 bx-lg text-success"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$totalFbsSystem->count()}}</h2>
              <span class="text-muted">FBS Systems</span>
              <div class="">
                <a href="{{'energy-system'}}" target="_blank" type="button"> 
                  <i class="bx bx-sun me-1 bx-lg text-warning"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-sm-3 col-md-3mb-4">
          <div class="col">
            <div class="card-body text-center">
              <h2 class="mb-1">{{$totalRatedPower}}</h2>
              <span class="text-muted">Total Rated Power (KW)</span>
              <div class="">
                <a href="{{'energy-system'}}" target="_blank" type="button"> 
                  <i class="bx bx-wind me-1 bx-lg" style="color:yellow"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

  <!-- H2O Users -->
  <div class="row mb-4">
    <div class="col-lg-12 col-xl-12 col-md-12 mb-4">
      <div class="card"> 
        <div class="card-header">
          <h5 class="card-title mb-0">Water Users</h5>
        </div>
        <div class="card-body pb-2">
          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-3 mb-4">
            <ul class="p-0 m-0">
              <li class="d-flex mb-4 pb-2">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    <a href="{{'all-water'}}" target="_blank" type="button"> 
                      <i class='bx bx-water'></i>
                    </a>
                  </span>
                </div>
                <div class="d-flex flex-column w-100">
                  <div class="d-flex justify-content-between mb-1">
                    <span>H2O Users</span>
                    <span class="text-muted">
                      {{$h2oUsersNumbers}}
                    </span>
                  </div>
                  <?php
                    $diff = ($h2oUsersNumbers/ $householdNumbers) * 100;
                  ?>
                  <div class="progress" style="height:6px;">
                    <div class="progress-bar bg-primary" style="width: {{$diff}}%" 
                    role="progressbar" aria-valuenow="{{$diff}}" 
                    aria-valuemin="0" 
                    aria-valuemax="{{$householdNumbers}}"></div>
                  </div>
                </div>
              </li>
            
            </ul>
          </div>

          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-3 mb-4">
            <div class="d-flex justify-content-between align-items-center gap-3 w-100">
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    <i class='bx bx-droplet'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$h2oNumber->sum}}</h5>
                  <small class="text-muted">H2O System</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    <i class='bx bx-droplet bx-large'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$gridLarge->sum}}</h5>
                  <small class="text-muted">Grid Integration Large</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    <i class='bx bx-droplet'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$gridSmall->sum}}</h5>
                  <small class="text-muted">Grid Integration Small</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    <i class='bx bx-droplet'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$waterNetworkUsers}}</h5>
                  <small class="text-muted">Water Network</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
    <!-- Internet Users -->
  <div class="row mb-4">
    <div class="col-lg-12 col-xl-12 col-md-12 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Internet Users</h5>
        </div>
        <div class="card-body pb-2">
          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-3 mb-4">
            <ul class="p-0 m-0">
              <li class="d-flex mb-4 pb-2">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <a href="{{'internet-user'}}" target="_blank" type="button"> 
                      <i class='bx bx-wifi'></i>
                    </a>
                  </span>
                </div>
                <div class="d-flex flex-column w-100">
                  <div class="d-flex justify-content-between mb-1">
                    <span>Internet Users</span>
                    <span class="text-muted">
                      {{$internetPercentage}} %
                    </span> 
                  </div>
                  <div class="progress" style="height:6px;">
                    <div class="progress-bar bg-success" style="width: {{$internetPercentage}}%" 
                    role="progressbar" aria-valuenow="{{$internetPercentage}}" 
                    aria-valuemin="0" 
                    aria-valuemax="{{$allInternetPeople}}"></div>
                  </div>
                </div>
              </li>
            
            </ul>
          </div>

          <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-3 mb-4">
            <div class="d-flex justify-content-between align-items-center gap-3 w-100">
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <a type="button" data-bs-toggle="modal" 
                      data-bs-target="#communityInternet">
                      <i class='bx bx-home'></i>
                    </a>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$activeInternetCommuntiiesCount}}</h5>
                  <small class="text-muted">Active Communities</small>
                </div>
              </div>
              @include('employee.community.service.internet')
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <i class='bx bx-book-content bx-large'></i>
                  </span>
                </div>
                <div class="chart-info"> 
                  <h5 class="mb-0">{{$allContractHolders}}</h5>
                  <small class="text-muted">Contract Holders</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <i class='bx bx-user'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$allInternetUsersCounts}}</h5>
                  <small class="text-muted">Users</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <i class='bx bx-happy bx-large'></i>
                  </span>
                </div>
                <div class="chart-info"> 
                  <h5 class="mb-0">{{$youngInternetHolders}}</h5>
                  <small class="text-muted">Young Holders</small>
                </div>
              </div>
              <div class="d-flex align-content-center">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-success">
                    <i class='bx bx-buildings'></i>
                  </span>
                </div>
                <div class="chart-info">
                  <h5 class="mb-0">{{$InternetPublicCount}}</h5>
                  <small class="text-muted">Public Structures</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- Cumulative Sum Energy -->
<div class="row mb-4">
  <div class="col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h5><i class="menu-icon tf-icons bx bx-lg bx-bulb text-warning"></i>
              Total Number of Communities by Year (energy)</h5>
        </div>
        <div class="card-body">
            <div id="energyCumulativeSum"></div>
        </div>
    </div>
  </div>
</div>
<!-- <div class="row mb-4">
  <div class="col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h5>Number of communities vs. Initial Service year (energy)</h5>
        </div>
        <div class="card-body">
            <div id="initialCommunityChart"></div>
        </div>
    </div>
  </div>
</div> -->
<div class="row mb-4">
  <div class="col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header">
        
          <h5> <i class="menu-icon tf-icons bx bx-lg bx-droplet text-info"></i>
            Total Number of Communities by Year (water)</h5>
        </div>
        <div class="card-body">
            <div id="initialYearCommunityChartWater"></div>
        </div>
    </div>
  </div>
</div>
<div class="row mb-4">
  <div class="col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h5><i class="menu-icon tf-icons bx bx-lg bx-wifi text-success"></i>
              Total Number of Communities by Year (internet)</h5>
        </div>
        <div class="card-body">
            <div id="initialYearCommunityChartInternet"></div>
        </div>
    </div>
  </div>
</div>

  <!-- Masafer Yatta-->
<div class="card mb-4">
  <div class="card-header">
    <h3 class="mb-2 pt-4 pb-1">Masafer Yatta</h3>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="row align-items-end">
            <div class="col-6">
                <h4 class=" text-primary mb-2 pt-4 pb-1">{{$communitiesMasafersCount}}</h4>
                <span class="d-block mb-4 text-nowrap">Communities</span>
            </div>
            <div class="col-6">
                <i class="bx bx-home me-1 bx-lg text-primary"></i>
            </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="row align-items-end">
          <div class="col-6">
            <h4 class=" text-primary mb-2 pt-4 pb-1">{{$countHouseholds}}</h4>
            <span class="d-block mb-4 text-nowrap">Households</span>
          </div>
          <div class="col-6">
            <i class="bx bx-user me-1 bx-lg text-warning"></i>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="row align-items-end">
          <div class="col-6">
            <h4 class=" text-primary mb-2 pt-4 pb-1">{{$countEnergyUsers}}</h4>
            <span class="d-block mb-4 text-nowrap">Energy Users</span>
          </div>
          <div class="col-6">
            <i class="bx bx-user-check me-1 bx-lg text-danger"></i>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="row align-items-end">
          <div class="col-6">
            <h4 class=" text-primary mb-2 pt-4 pb-1">{{$countMgSystem->count()}}</h4>
            <span class="d-block mb-4 text-nowrap">MG Systems</span>
          </div>
          <div class="col-6">
            <i class="bx bx-grid me-1 bx-lg text-success"></i>
          </div>
        </div>
      </div>

    </div>

    <div class="row">
      <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="row align-items-end">
          <div class="col-6">
            <h4 class=" text-primary mb-2 pt-4 pb-1">{{$countFbsSystem->count()}}</h4>
            <span class="d-block mb-4 text-nowrap">FBS Systems</span>
          </div>
          <div class="col-6">
            <i class="bx bx-sun me-1 bx-lg text-dark"></i>
          </div>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="row align-items-end">
          <div class="col-6">
            <h4 class=" text-primary mb-2 pt-4 pb-1">{{$countH2oUsers}}</h4>
            <span class="d-block mb-4 text-nowrap">H2O Users</span>
          </div>
          <div class="col-6">
            <i class="bx bx-droplet me-1 bx-lg text-info"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="row align-items-end">
            <div class="col-6">
                <h4 class=" text-primary mb-2 pt-4 pb-1">{{$countInternetUsers}}</h4>
                <span class="d-block mb-4 text-nowrap">Internet Holders</span>
            </div>
            <div class="col-6">
                <i class="bx bx-wifi me-1 bx-lg text-light"></i>
            </div>
        </div>
      </div>
    </div>

  </div>
</div>

@include('employee.incident_details')
  <div class="row mb-4">
    <div class="col-md-12 col-lg-12">
      <div class="col-xl-12 col-lg-12 col-md-12">
        <div class="panel panel-primary">
          <div class="panel-body" >
            <div id="incidentsMgChart" style="height:400px;">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="module">
 
  const street = L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
    maxZoom: 18
  }),
  watercolor = L.tileLayer('http://tile.stamen.com/watercolor/{z}/{x}/{y}.jpg', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
    maxZoom: 18
  });

  var firstMap = $("#layerControl1");
  var filteredMap = $("#layerControlFilter");

  // view default map
  if (firstMap) {

    filteredMap.css("visibility", "hidden");
    filteredMap.css('display','none');

    const communities = {!! json_encode($communities) !!};
    const cities = L.layerGroup();
    communities.forEach(community => {
      const { latitude, longitude, english_name } = community;
      const marker = L.marker([latitude, longitude]).bindPopup(english_name);
      cities.addLayer(marker);
    });

    const layerControl1 = L.map('layerControl1', {
      center: [32.2428238, 35.494258],
      zoom: 10,
      layers: [street, cities]
    });
    const baseMaps = {
      Street: street,
      Watercolor: watercolor
    };
    const overlayMaps = {
      Cities: cities
    };

    MapCommunity(layerControl1, baseMaps, overlayMaps);
  }

  $('#communityFilterMapButton').on('click', function() {
    event.preventDefault();

    var formData = $("#communityFilterMapForm").serialize();

    console.log(formData);

    $.ajax({
      url: '/filter_map', 
      type: 'GET',
      data: formData,
      processData: false,
      contentType: false,
      success: function (data) {

        if(filteredMap) {

          firstMap.css("visibility", "hidden");
          firstMap.css('display','none');

          filteredMap.css("visibility", "visible");
          filteredMap.css('display','block');

          var cities = L.layerGroup();
          data.communities.forEach(community => {
            var { latitude, longitude, english_name } = community;
            var markerFiltered = L.marker([latitude, longitude]).bindPopup(english_name);
            cities.addLayer(markerFiltered);
          });

          const layerControlFiltered = L.map('layerControlFilter', {
            center: [32.2428238, 35.494258],
            zoom: 10,
            layers: [street, cities]
          });
          const baseMapsFiltered = {
            Street: street,
            Watercolor: watercolor
          };
          const overlayMapsFiltered = {
            Cities: cities
          };

          MapCommunityFiltered(layerControlFiltered, baseMapsFiltered, overlayMapsFiltered) 
        }
      },
      error: function (xhr, status, error) {
          // Handle error
          console.error(error);
      }
    });
  });

  function MapCommunity(layerControl1, baseMaps, overlayMaps) {

    L.control.layers(baseMaps, overlayMaps).addTo(layerControl1);
    L.tileLayer('https://c.tile.osm.org/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
      maxZoom: 18
    }).addTo(layerControl1);
  }

  function MapCommunityFiltered(layerControlFiltered, baseMapsFiltered, overlayMapsFiltered) {

    L.control.layers(baseMapsFiltered, overlayMapsFiltered).addTo(layerControlFiltered);
    L.tileLayer('https://c.tile.osm.org/{z}/{x}/{y}.png', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>',
      maxZoom: 18
    }).addTo(layerControlFiltered);
  }

</script>

<script type="text/javascript">

  $(function () {
   
    var water = <?php echo $cumulativeSumWaterData; ?>;
    var internet = <?php echo $cumulativeSumInternetData; ?>;
    var cumulativeSum = <?php echo $cumulativeSum; ?>;

    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);
    
    function drawChart() {
  
      var waterData = google.visualization.arrayToDataTable(water);
      var internetData = google.visualization.arrayToDataTable(internet);
      var cumulativeSumEnergyData = google.visualization.arrayToDataTable(cumulativeSum);

      var chartWater = new google.charts.Bar
        (document.getElementById('initialYearCommunityChartWater'));
      chartWater.draw(
        waterData
      );

      var chartInternet = new google.charts.Bar
        (document.getElementById('initialYearCommunityChartInternet'));
      chartInternet.draw(
        internetData
      );
    }
  });
</script>

<script type="text/javascript">
  $(function () {
    var cumulativeSum = <?php echo $cumulativeSum; ?>;

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
      
    var options = {
        curveType: 'function',
        legend: { position: 'bottom' },
        vAxis: {format: '0000'},
        hAxis: {format: '0000'}
      };

    function drawChart() {
        var cumulativeSumEnergyData = google.visualization.arrayToDataTable(cumulativeSum);

        var chartCumulativeSumEnergy = new google.visualization.LineChart
          (document.getElementById('energyCumulativeSum'));
          chartCumulativeSumEnergy.draw(
          cumulativeSumEnergyData, options
        );
    }
  });
</script>

<script type="text/javascript">

  $(function () {

    var analytics = <?php echo $incidentsData; ?>;
    var numberMg = <?php echo $mgIncidentsNumber;?>;

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = google.visualization.arrayToDataTable(analytics);
      var options  ={
        title:'Status of Micro-Grids Under Threat of Demolition (total '+ numberMg +')',
        is3D:true,
      };

      var chart = new google.visualization.PieChart(
        document.getElementById('incidentsMgChart'));
      chart.draw(
        data, options
      );

      google.visualization.events.addListener(chart,'select',function() {
        var row = chart.getSelection()[0].row;
        var selected_data=data.getValue(row,0);
        
        $.ajax({
          url: "{{ route('incidentDetails') }}",
          type: 'get',
          data: {
            selected_data: selected_data
          },
          success: function(response) {
            $('#incidentsDetailsModal').modal('toggle');
            $('#incidentsDetailsTitle').html(selected_data);
            $('#contentIncidentsTable').find('tbody').html('');
              response.forEach(refill_table);
              function refill_table(item, index){
                $('#contentIncidentsTable').find('tbody').append('<tr><td>'+item.community+'</td><td>'+item.energy+'</td><td>'+item.incident+'</td><td>'+item.date+'</td></tr>');
              }
          }
        });
      });
    }
    
  });
</script>
@endsection