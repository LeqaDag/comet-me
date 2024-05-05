
@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@include('layouts.all')

@section('content')

<h1>
  Welcome {{Auth::guard('user')->user()->name}}  
</h1> 

<div class="col-12">
  <div class="card mb-4">
    <h5 class="card-header">
      <div class="row">
        <div class="col-xl-9 col-lg-9 col-md-9">
          Map of Communities
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3">
          <fieldset class="form-group">
            <button class="btn btn-dark" id="clearFiltersButton">
              <i class='fa-solid fa-eraser'></i>
                Clear Filters
            </button>
          </fieldset>
        </div>
        <!--@if(Auth::guard('user')->user()->user_type_id == 1)-->
        <!--<div class="col-xl-3 col-lg-3 col-md-3">-->
        <!--  <fieldset class="form-group">-->
        <!--    <button class="btn btn-dark" id="exportSVGButton">-->
        <!--      <i class='fa-solid fa-eraser'></i>-->
        <!--        Export SVG-->
        <!--    </button>-->
        <!--  </fieldset>-->
        <!--</div>-->
        <!--@endif-->
      </div>
    </h5>
    <div class="card-body">
      <form method="POST" enctype='multipart/form-data' id="communityFilterMapForm">
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="regions[]" class="selectpicker form-control" 
                data-live-search="true" id="filterByRegion" multiple>
                  <option disabled selected>Filter by Regions</option>
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
                  <option disabled selected>Filter by Sub Regions</option>
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
                <select name="bedouin_fallah[]" class="selectpicker form-control" 
                data-live-search="true" multiple>
                  <option disabled selected>Filter by Bedouin/Fallah</option>
                  <option value="bedouin">Bedouin</option>
                  <option value="fallah">Fallah</option>
                </select> 
              </fieldset>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="services[]"
                  class="selectpicker form-control" 
                  data-live-search="true" multiple>
                  <option disabled selected>Filter by Services</option>
                  @foreach($services as $service)
                    <option value="{{$service->id}}">
                      {{$service->service_name}}
                    </option>
                  @endforeach
                </select> 
              </fieldset>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="years[]" class="selectpicker form-control" 
                  data-live-search="true" multiple>
                  <option disabled selected>Filter by Service Year</option>
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
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="statuses[]" class="selectpicker form-control" 
                data-live-search="true" multiple>
                  <option disabled selected>Filter by Community Statuses</option>
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
                  <option disabled selected>Filter by System Types</option>
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
                  <option disabled selected>Filter by Donors</option>
                  @foreach($donors as $donor)
                    <option value="{{$donor->id}}">
                      {{$donor->donor_name}}
                    </option>
                  @endforeach
                </select> 
              </fieldset>
            </div> 
          </div><br> 
          <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3">
              <fieldset class="form-group">
                <select name="incidents[]" class="selectpicker form-control" 
                data-live-search="true" multiple>
                  <option disabled selected>Filter by Incidents</option>
                  <option value="mg">MG</option>
                  <option value="fbs">FBS</option>
                  <option value="water">Water</option>
                  <option value="internet">Internet</option>
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
      <div class="leaflet-map" id="clearMapControl"></div>
      <div class="leaflet-map" id="layerControlFilter"></div>
    </div>
  </div>
</div>

<h4> Active Services Users
  <span style="font-size:15px"><a href="{{'all-active'}}" target="_blank">View details</a></span>
</h4>
@include('shared.summary')

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
  var clearMap = $("#clearMapControl");
  var filteredMap = $("#layerControlFilter");
  DefaultMapView();

  function DefaultMapView() {
   
    // view default map
    if (firstMap) {

      filteredMap.css("visibility", "hidden");
      filteredMap.css('display','none');

      clearMap.css("visibility", "hidden");
      clearMap.css('display','none');

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
  }

function ClearMapView() {
    if (clearMap) {
        // Hide other map elements and show the clearMap
        filteredMap.css("visibility", "hidden");
        filteredMap.css('display', 'none');
        firstMap.css("visibility", "hidden");
        firstMap.css('display', 'none');
        clearMap.css("visibility", "visible");
        clearMap.css('display', 'block');

        // Get the clearMap container element
        const clearMapContainer = document.getElementById('clearMapControl');

        // Remove all child nodes (this effectively clears the existing map)
        while (clearMapContainer.firstChild) {
            clearMapContainer.removeChild(clearMapContainer.firstChild);
        }

        // Create a new map instance in the cleared container
        const communities = {!! json_encode($communities) !!};
        const cities = L.layerGroup();
        communities.forEach(community => {
            const { latitude, longitude, english_name } = community;
            const marker = L.marker([latitude, longitude]).bindPopup(english_name);
            cities.addLayer(marker);
        });

        const clearMapControl = L.map('clearMapControl', {
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

        MapCommunity(clearMapControl, baseMaps, overlayMaps);
    }
}

//   function ClearMapView() {
   
//    // view default map
//    if (clearMap) {

//     filteredMap.css("visibility", "hidden");
//     filteredMap.css('display','none');

//     firstMap.css("visibility", "hidden");
//     firstMap.css('display','none');

//     clearMap.css("visibility", "visible");
//     clearMap.css('display','block');

//      const communities = {!! json_encode($communities) !!};
//      const cities = L.layerGroup();
//      communities.forEach(community => {
//        const { latitude, longitude, english_name } = community;
//        const marker = L.marker([latitude, longitude]).bindPopup(english_name);
//        cities.addLayer(marker);
//      });

//      const clearMapControl = L.map('clearMapControl', {
//        center: [32.2428238, 35.494258],
//        zoom: 10,
//        layers: [street, cities]
//      });
//      const baseMaps = {
//        Street: street,
//        Watercolor: watercolor
//      };
//      const overlayMaps = {
//        Cities: cities
//      };

//      MapCommunity(clearMapControl, baseMaps, overlayMaps);
//    }
//  }

  $('#clearFiltersButton').on('click', function() {

    $('.selectpicker').prop('selectedIndex', 0);
    $('.selectpicker').selectpicker('refresh');
    ClearMapView();
  });

  $('#exportSVGButton').on('click', function() {

    const mapContainer = document.getElementById('layerControl1');

    // Create an SVG element
    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');

    // Get Leaflet map content and render it to the SVG
    const serializer = new XMLSerializer();
    const mapContent = serializer.serializeToString(mapContainer);

    canvg(svg, mapContent, {
        ignoreMouse: true,
        ignoreAnimation: true,
        renderCallback: function () {
            // Convert SVG to Blob and create a download link
            const svgBlob = new Blob([svg.outerHTML], { type: 'image/svg+xml' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(svgBlob);
            link.download = 'map.svg';
            link.click();
        }
    });
  });

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

          clearMap.css("visibility", "hidden");
          clearMap.css('display','none');

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