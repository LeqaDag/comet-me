
@extends('layouts/layoutMaster')

@section('title', 'Dashboard - Analytics')

@include('layouts.all')

@section('content')

<h1>
  Welcome {{Auth::guard('user')->user()->name}}  
</h1>

<div class="col-lg-12 col-md-12">
  <div class="row">
    <div class="col-sm-3 col-3 mb-4">
      <div class="card">
        <div class="card-body text-center">
          <h2 class="mb-1">{{$regionNumbers}}</h2>
          <span class="text-muted">Regions</span>
          <div class="primary">
            <a href="{{'sub-region'}}" type="button">
              <i class="bx bx-map me-1 bx-lg text-warning"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3 col-3 mb-4">
      <div class="card">
        <div class="card-body text-center">
          <h2 class="mb-1">{{$communityNumbers}}</h2>
          <span class="text-muted">Communitites</span>
          <div class="">
            <a href="{{'community'}}" type="button">
              <i class="bx bx-home me-1 bx-lg text-success"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3 col-3 mb-4">
      <div class="card">
        <div class="card-body text-center">
          <h2 class="mb-1">{{$numberOfPeople->number_of_people}}</h2>
          <span class="text-muted">People</span>
          <div class="primary">
            <a href="{{'sub-region'}}" type="button">
              <i class="bx bx-group me-1 bx-lg text-dark"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3 col-3 mb-4">
      <div class="card">
        <div class="card-body text-center">
          <h2 class="mb-1">{{$householdNumbers}}</h2>
          <span class="text-muted">Households</span>
          <div class="primary">
            <a href="{{'household'}}" type="button">
              <i class="bx bx-user me-1 bx-lg bx-primary"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="col-sm-3 col-3 mb-4">
      <div class="card">
        <div class="card-body text-center">
          <h2 class="mb-1">{{$systemHoldersNumber}}</h2>
          <span class="text-muted">System Holders</span>
          <div class="">
            <a href="{{'household'}}" type="button">
              <i class="bx bx-smile me-1 bx-lg text-danger"></i>
            </a>
          </div>
        </div>
      </div>
    </div> -->
  </div>
</div>

<div class="col-lg-12 col-md-12">
  <div class="row">
    
    <div class="col-sm-3 col-3 mb-4">
      <div class="card">
        <div class="card-body text-center">
          <h2 class="mb-1">{{$numberOfMale->number_of_male}}</h2>
          <span class="text-muted">Male</span>
          <div class="">
            <a href="{{'community'}}" type="button">
              <i class="bx bx-male me-1 bx-lg text-secondary"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3 col-3 mb-4">
      <div class="card">
        <div class="card-body text-center">
          <h2 class="mb-1">{{$numberOfFemale->number_of_female}}</h2>
          <span class="text-muted">Female</span>
          <div class="primary">
            <a href="{{'household'}}" type="button">
              <i class="bx bx-female me-1 bx-lg text-light"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3 col-3 mb-4">
      <div class="card">
        <div class="card-body text-center">
          <h2 class="mb-1">{{$numberOfAdults->number_of_adults}}</h2>
          <span class="text-muted">Adults</span>
          <div class="primary">
            <a href="{{'sub-region'}}" type="button">
              <i class="bx bx-male bx-lg text-danger"></i>
              <i class="bx bx-female me-1 bx-lg text-danger"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3 col-3 mb-4">
      <div class="card">
        <div class="card-body text-center">
          <h2 class="mb-1">{{$numberOfChildren->number_of_children}}</h2>
          <span class="text-muted">Children</span>
          <div class="">
            <a href="{{'household'}}" type="button">
              <i class="bx bx-face me-1 bx-lg text-info"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="row mb-4">
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
</div>
<div class="row mb-4">
  <div class="col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header">
            <h5>Number of communities vs. Initial Service year (water)</h5>
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
            <h5>Number of communities vs. Initial Service year (internet)</h5>
        </div>
        <div class="card-body">
            <div id="initialYearCommunityChartInternet"></div>
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
          <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-4">
            <ul class="p-0 m-0">
              <li class="d-flex mb-4 pb-2">
                <div class="avatar avatar-sm flex-shrink-0 me-3">
                  <span class="avatar-initial rounded-circle bg-label-primary">
                    <i class='bx bx-water'></i></span>
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

          <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-4">
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
                  <small class="text-muted">Grid Large System</small>
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
                  <small class="text-muted">Grid Small System</small>
                </div>
              </div>
            </div>
          </div>
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




<script type="text/javascript">

    $(function () {

        var analytics = <?php echo $initialYearEnergyData; ?>;
        var water = <?php echo $initialYearWaterData; ?>;
        var internet = <?php echo $initialYearInternetData; ?>;

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        
        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);
            var waterData = google.visualization.arrayToDataTable(water);
            var internetData = google.visualization.arrayToDataTable(internet);
      
            var chart = new google.visualization.LineChart(document.getElementById('initialCommunityChart'));
            chart.draw(
                data
            );

            var chartWater = new google.visualization.LineChart
              (document.getElementById('initialYearCommunityChartWater'));
            chartWater.draw(
              waterData
            );

            var chartInternet = new google.visualization.LineChart
              (document.getElementById('initialYearCommunityChartInternet'));
            chartInternet.draw(
              internetData
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