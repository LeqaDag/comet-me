@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'water-users')

@include('layouts.all')

@section('content')
 
<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseWaterHolderVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseWaterHolderVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseWaterHolderExport" aria-expanded="false" 
        aria-controls="collapseWaterHolderExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export  
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseWaterHolderVisualData collapseWaterHolderExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseWaterHolderVisualData">
    <div class="container mb-4">
        <div class="col-lg-12 col-12">
            <div class="row">
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="bx bx-water fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">H2O Users</span>
                            <h2 class="mb-0">{{$h2oUsers}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="bx bx-water fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">Shared H2O</span>
                            <h2 class="mb-0">{{$h2oSharedUsers}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4"> 
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-info">
                                <i class="bx bx-droplet fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">Integration Users</span>
                            <h2 class="mb-0">{{$gridUsers}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-info">
                                <i class="bx bx-cloud-rain fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">Grid Users</span>
                            <h2 class="mb-0">{{$networkUsers}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-info">
                                <i class="bx bx-group fs-4"></i></span>
                            </div>
                            <span class="d-block text-nowrap">Water beneficiaries</span>
                            <h2 class="mb-0">{{$totalWaterHouseholds->number_of_people}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-success">
                                    <i class="bx bx-male fs-4"></i>
                                </span>
                            </div>
                            <span class="d-block text-nowrap">Male</span>
                            <h2 class="mb-0">{{$totalWaterMale ?? 0}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-danger">
                                    <i class="bx bx-female fs-4"></i>
                                </span>
                            </div>
                            <span class="d-block text-nowrap">Female</span>
                            <h2 class="mb-0">{{$totalWaterFemale ?? 0}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-secondary">
                                    <i class="bx bx-male fs-4"></i>
                                    <i class="bx bx-female fs-4"></i>
                                </span>
                            </div>
                            <span class="d-block text-nowrap">Adults</span>
                            <h2 class="mb-0">{{$totalWaterAdults ?? 0}}</h2>
                        </div>
                    </div>
                </div>   
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="avatar mx-auto mb-2">
                                <span class="avatar-initial rounded-circle bg-label-dark">
                                    <i class="bx bx-face fs-4"></i>
                                </span>
                            </div>
                            <span class="d-block text-nowrap">Children</span>
                            <h2 class="mb-0">{{$totalWaterChildren ?? 0}}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card my-2">
            <div class="card-header">
                <h5>Water System Type Chart</h5>
                <div class="container mb-4">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water System Type</label>
                                <select name="water_type" id="selectedWaterSystemType" 
                                    class="form-control" required>
                                    <option disabled selected>Choose one...</option>
                                    <option value="h2o">Classic H2O System</option>
                                    <option value="grid">Grid Integration</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Status</label>
                                <select name="status" id="selectedWaterStatus" 
                                class="form-control" disabled required>
                                    <option disabled selected>Choose one...</option>
                                    <option value="0">Complete</option>
                                    <option value="1">Not Complete</option>
                                    <option value="2">Delivery</option>
                                    <option value="3">Not Delivery</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div class="container mb-4" id="chartWaterSystem" style="visiblity:hidden; display:none">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 id="chartWaterSystemTitle"></h5>
                            <div id="waterUserChart"></div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<br>

<div class="collapse multi-collapse mb-4" id="collapseWaterHolderExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Water Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearAllWaterFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' id="exportFormWaterHolder"
                        action="{{ route('water-user.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>File Type</label>
                                        <select name="file_type" required id="fileType"
                                            class="selectpicker form-control" data-live-search="true" >
                                            <option disabled selected>Select File Type</option>
                                            <option value="requested">Requested Water Holders</option>
                                            <option value="all">All Water Holders</option>
                                        </select> 
                                        <div id="file_type_error" style="color: red;"></div>
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Region</label>
                                        <select name="region_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Select Region</option>
                                            @foreach($regions as $region)
                                                <option value="{{$region->id}}">{{$region->english_name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Community</label>
                                        <select name="community_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Select Community</option>
                                            @foreach($communities as $community)
                                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <button class="btn btn-info" type="submit">
                                        <i class='fa-solid fa-file-excel'></i>
                                        Export Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>  
            </div>
        </div> 
    </div> 
</div>


<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Water System Requested / Holders
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif



<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Region</label>
                        <select name="community_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByRegion">
                            <option disabled selected>Search Region</option>
                            @foreach($regions as $region)
                                <option value="{{$region->id}}">{{$region->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Community</label>
                        <select name="community_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByCommunity">
                            <option disabled selected>Search Community</option>
                            @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Clear All Filters</label>
                        <button class="btn btn-dark" id="clearFiltersButton">
                            <i class='fa-solid fa-eraser'></i>
                            Clear Filters
                        </button>
                    </fieldset>
                </div>
            </div>
               
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs" role="tablist" id="inProgressTabs" style="padding-top:25px">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#requested-water-holders" role="tab">
                        <i class='fas fa-clock me-2'></i>
                        Requested
                        <span id="requestedWaterCount" class="badge ms-2" style="background-color: #d6f7fa; color: #00cfdd;">
                     
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#confirmed-water-holders" role="tab">
                        <i class='fas fa-check me-2'></i> 
                        Confirmed
                        <span id="confirmedWaterCount" class="badge ms-2" style="background-color: #e7ebef; color: #69809a;">
                     
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#h2o-water-holders" role="tab">
                        <i class='fas fa-droplet me-2'></i> 
                        H2O
                        <span id="h2oWaterCount" class="badge ms-2" style="background-color: #e7ebef; color: #69809a;">
    
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#grid-water-holders" role="tab">
                        <i class='fas fa-water me-2'></i> 
                        Grid
                        <span id="gridWaterCount" class="badge ms-2" style="background-color: #e7ebef; color: #69809a;">
      
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#network-water-holders" role="tab">
                        <i class='fas fa-warehouse me-2'></i> 
                        Network
                        <span id="networkWaterCount" class="badge ms-2" style="background-color: #e7ebef; color: #69809a;">
             
                        </span>
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content mt-3" id="waterUserTabContent">
                <!-- All Requested Holders Tab -->
                <div class="tab-pane fade show active" id="requested-water-holders" role="tabpanel" 
                    aria-labelledby="requested-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex gap-2">
                            <div style="margin-top:18px">
                                <a type="button" class="btn btn-success" 
                                    href="{{url('water-request', 'create')}}" >
                                    Create New Request System
                                </a>
                            </div>
                        </div>
                    </div>
                    <table id="requestedHoldersTable" class="table table-striped my-2 data-table-water-request">
                        <thead>
                            <tr>
                                <th class="text-center">Requested Household/Public Structure</th>
                                <th class="text-center">Community</th>
                                <th class="text-center">Requested Date</th>
                                <th class="text-center">Requested System Type</th>
                                <th class="text-center">Main Energy User?</th>
                                <th class="text-center">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>

                <!-- All confirmed Tab -->
                <div class="tab-pane fade" id="confirmed-water-holders" role="tabpanel" aria-labelledby="confirmed-tab">
                    <table id="condirmedHoldersTable" class="table table-striped my-2 data-table-confirmed-users">
                        <thead>
                            <tr>
                                <th class="text-center">Confirmed Household/Public Structure</th>
                                <th class="text-center">Community</th>
                                <th class="text-center">Requested Date</th>
                                <th class="text-center">Requested System Type</th>
                                <th class="text-center">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>


                <!-- All h2o Tab -->
                <div class="tab-pane fade" id="h2o-water-holders" role="tabpanel" aria-labelledby="h2o-tab">
                    <table id="h2oHoldersTable" class="table table-striped my-2 data-table-h2o-users">
                        <thead>
                            <tr>
                                <th class="text-center">Water Holder</th>
                                <th class="text-center">Community</th>
                                <th class="text-center">Installation Year</th>
                                <th class="text-center"># of H2O System</th>
                                <th class="text-center">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>

                <!-- All In grid Tab -->
                <div class="tab-pane fade" id="grid-water-holders" role="tabpanel" aria-labelledby="grid-tab">
                    <table id="gridHoldersTable" class="table table-striped my-2 data-table-grid-users">
                        <thead>
                            <tr>
                                <th class="text-center">Water Holder</th>
                                <th class="text-center">Community</th>
                                <th class="text-center"># of Grid Large</th>
                                <th class="text-center">Grid Large Date</th>
                                <th class="text-center"># of Grid Small</th>
                                <th class="text-center">Grid Small Date</th>
                                <th class="text-center">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>

                <!-- All network Tab -->
                <div class="tab-pane fade" id="network-water-holders" role="tabpanel" aria-labelledby="network-tab">
                    <table id="networkHoldersTable" class="table table-striped my-2 data-table-network-users">
                        <thead>
                            <tr>
                                <th class="text-center">Water Holder</th>
                                <th class="text-center">Delivered</th>
                                <th class="text-center">Completed</th>
                                <th class="text-center">Paid</th>
                                <th class="text-center">Community</th>
                                <th class="text-center">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('request.water.show')
<script>
 
    $('#exportFormWaterHolder').on('submit', function (event) {

        event.preventDefault(); 

        let valid = true;

        var fileTypeValue = $('#fileType').val();

        if (fileTypeValue == null) {

            $('#file_type_error').html('Please select a type!'); 
            return false;
        } else  if (fileTypeValue != null) {

            $('#file_type_error').empty();
        }

        $('#file_type_error').empty();

        if (valid) {

            $(this).addClass('was-validated');
            this.submit(); 
        }
    });

    // Update the countable values for water 
    function updateCountValue() {

        $.ajax({
            url: "{{ route('water.counts') }}",
            type: "GET",
            success: function(response) {

                $("#requestedWaterCount").text(response.requested);
                $("#h2oWaterCount").text(response.h2o);
                $("#gridWaterCount").text(response.grid);
                $("#networkWaterCount").text(response.network);
                $("#confirmedWaterCount").text(response.confirmed);
            }
        });
    }


    // Visulize data
    $(document).on('change', '#selectedWaterSystemType', function () {
        water_type = $(this).val();

        if(water_type == "h2o") {
            $("#chartWaterSystem").css("visibility", "visible");
            $("#chartWaterSystem").css('display', 'block');
            $("#chartWaterSystemTitle").html("Classic H2O System");

            var analytics = <?php echo $h2oChartStatus; ?>;

            google.charts.load('current', {'packages':['bar']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable(analytics);
                
                var chart = new google.charts.Bar(document.getElementById('waterUserChart'));
                chart.draw(
                    data
                );

                google.visualization.events.addListener(chart,'select',function() {
                    var row = chart.getSelection()[0].row;
                    var selected_data=data.getValue(row,0);
                   
                    $.ajax({
                    url: "{{ route('waterChartDetails') }}",
                    type: 'get',
                    data: {
                        selected_data: selected_data
                    },
                    success: function(response) {
                        $('#h2oDetailsModal').modal('toggle');
                        $('#h2oDetailsTitle').html(selected_data);
                        $('#contentH2oTable').find('tbody').html('');
                        response.forEach(refill_table);
                        function refill_table(item, index){
                            $('#contentH2oTable').find('tbody').append('<tr><td>'+item.english_name+'</td><td>'+item.community_name+'</td><td>'+item.number_of_h20+'</td><td>'+ item.number_of_bsf +'</td></tr>');
                        }
                    }
                    });
                });
            }
        }
        if(water_type == "grid") {
            $('#selectedWaterStatus').prop('disabled', false);
            
            $(document).on('change', '#selectedWaterStatus', function () {
                water_status = $(this).val();

                $.ajax({
                    url: "{{ route('chartWater') }}",
                    type: 'get',
                    data: {
                        water_type: water_type,
                        water_status:water_status
                    },
                    success: function(data) {
           
                        $("#chartWaterSystem").css("visibility", "visible");
                        $("#chartWaterSystem").css('display', 'block');
                        $("#chartWaterSystemTitle").html("Grid Integration System");
                        var analyticsGrid = data;

                        google.charts.load('current', {'packages':['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var dataGrid = google.visualization.arrayToDataTable(analyticsGrid);

                            var chartGrid = new google.charts.Bar(
                                document.getElementById('waterUserChart'));
                            chartGrid.draw(
                                dataGrid
                            );
                        }
                    }
                });
            });
        }
    });


    // Clear Filters for Export
    $('#clearAllWaterFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
    });

    $(function () {

        // keep track of initialized tables
        var tables = {};

        function initRequestedTable() {

            if (tables.requested) return;
            tables.requested = $('.data-table-water-request').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('water-request.index') }}",
                    data: function (d) {
                        d.search = (d.search && d.search.value) ? d.search.value : '';
                        d.community_filter = $('#filterByCommunity').val();
                        d.region_filter = $('#filterByRegion').val();
                        d.date_filter = $('#waterRequestedDateFilter').val();
                        d.status_filter = $('#filterByHolderStatus').val();
                    }
                }, 
                columns: [
                    {data: 'holder', name: 'holder'},
                    {data: 'community_name', name: 'community_name'},
                    {data: 'date', name: 'date'},
                    {data: 'type', name: 'type'},
                    {data: 'is_main', name: 'is_main'},
                    {data: 'action'}
                ]
            });
        }

        function initConfirmedTable() {

            if (tables.confirmed) return;
            tables.confirmed = $('.data-table-confirmed-users').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('water-confirmed-user.index') }}",
                    data: function (d) {
                        d.search = (d.search && d.search.value) ? d.search.value : '';
                        d.community_filter = $('#filterByCommunity').val();
                        d.region_filter = $('#filterByRegion').val();
                    }
                },
                columns: [
                    {data: 'holder', name: 'holder'},
                    {data: 'community_name', name: 'community_name'},
                    {data: 'date', name: 'date'},
                    {data: 'type', name: 'type'},
                    {data: 'action'}
                ]
            });
        }

        function initH2oTable() {

            if (tables.h2o) return;
            tables.h2o = $('.data-table-h2o-users').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('h2o-user.index') }}",
                    data: function (d) {
                        d.search = (d.search && d.search.value) ? d.search.value : '';
                        d.community_filter = $('#filterByCommunity').val();
                        d.region_filter = $('#filterByRegion').val();
                        d.year_filter = $('#FilterByInstallationYear').val();
                    }
                },
                columns: [
                    {data: 'holder'}, 
                    {data: 'community_name', name: 'community_name'},
                    {data: 'installation_year', name: 'installation_year'},
                    {data: 'number_of_h20', name: 'installation_year'},
                    {data: 'action'}
                ]
            });
        }

        function initGridTable() {
            
            if (tables.grid) return;

            tables.grid = $('.data-table-grid-users').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('grid-user.index') }}",
                    data: function (d) {
                        d.search = d.search?.value || '';
                        d.community_filter = $('#filterByCommunity').val();
                        d.region_filter = $('#filterByRegion').val();
                        d.year_filter = $('#FilterByInstallationYear').val();
                    }
                },
                columns: [
                    {data: 'holder'},
                    {data: 'community_name'},
                    {data: 'grid_integration_large'},
                    {data: 'large_date'},
                    {data: 'grid_integration_small'},
                    {data: 'small_date'},
                    {data: 'action'}
                ]
            });
        }

        function initNetworkTable() {

            if (tables.network) return;
            tables.network = $('.data-table-network-users').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('network-user.index') }}",
                    data: function (d) {
                        d.search = (d.search && d.search.value) ? d.search.value : '';
                        d.community_filter = $('#filterByCommunity').val();
                        d.region_filter = $('#filterByRegion').val();
                    }
                },
                columns: [
                    {data: 'holder'},
                    {data: 'delivered', name: 'delivered'},
                    {data: 'completed', name: 'completed'},
                    {data: 'paid', name: 'paid'},
                    {data: 'community_name', name: 'community_name'},
                    {data: 'action'}
                ]
            });
        }

        initRequestedTable();
        updateCountValue();

        // On tab shown, lazy-init the target table
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {

            var target = $(e.target).attr('href');
            if (target == '#grid-water-holders') initGridTable();
            if (target == '#confirmed-water-holders') initConfirmedTable();
            if (target == '#h2o-water-holders') initH2oTable();
            if (target == '#requested-water-holders') initRequestedTable();
            if (target == '#network-water-holders') initNetworkTable();

            // Show the requested filters only when Requested tab is active
            if (target == '#requested-water-holders') {

                $('#waterRequestedDate').show();
                $('#filterByHolderStatus').prop('disabled', false);
                $('#FilterByInstallationYear').prop('disabled', true);
            } else {

                $('#waterRequestedDate').hide();
                $('#filterByHolderStatus').prop('disabled', true);
                $('#FilterByInstallationYear').prop('disabled', false);
            }

            if ($('.selectpicker').length && typeof $('.selectpicker').selectpicker === 'function') {

                $('.selectpicker').selectpicker('refresh');
            }
        });


        // Reload initialized tables when any filter changes
        function reloadInitializedTables() {

            if (tables.init) tables.init.ajax.reload();
            if (tables.ac) tables.ac.ajax.reload();
            if (tables.requested) tables.requested.ajax.reload();
            if (tables.confirmed) tables.confirmed.ajax.reload();
            if (tables.accompleted) tables.accompleted.ajax.reload();
            if (tables.dc) tables.dc.ajax.reload();
            if (tables.served) tables.served.ajax.reload();
        }

        $('#filterByCommunity, #filterByRegion').on('change', function () {

            if (tables.h2o) tables.h2o.ajax.reload();
            if (tables.requested) tables.requested.ajax.reload();
            if (tables.grid) tables.grid.ajax.reload();
            if (tables.network) tables.network.ajax.reload();
            updateCountValue();
        });

        // Clear filters
        $(document).on('click', '#clearFiltersButton', function () {
            $('#filterByCommunity').prop('selectedIndex', 0);
            $('#filterByRegion').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if (tables.h2o) tables.h2o.ajax.reload();
            if (tables.requested) tables.requested.ajax.reload();
            if (tables.grid) tables.grid.ajax.reload();
            if (tables.network) tables.network.ajax.reload();
            updateCountValue();
        });


        // View record details for the requested water holder
        $('#requestedHoldersTable').on('click', '.viewWaterRequest',function() {

            var id = $(this).data('id');
            viewConfirmedRequestedHolder(id);
        }); 

        // Delete record for the requested water holder 
        $('#requestedHoldersTable').on('click', '.deleteWaterRequest',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this water requested household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteWaterRequest') }}",
                        type: 'get',
                        data: {id: id},
                        success: function(response) {
                            if(response.success == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    $('#requestedHoldersTable').DataTable().draw();
                                    updateCountValue();
                                });
                            } else {

                                alert("Invalid ID.");
                            }
                        }
                    });
                } else if (result.isDenied) {

                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });

        // Move requested water holder 
        $('#requestedHoldersTable').on('click', '.moveWaterRequest',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to work/confirm for this requested holder?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('moveWaterRequest') }}",
                        type: 'get',
                        data: {id: id},
                        success: function(response) {
                            if(response.success == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    $('#requestedHoldersTable').DataTable().draw();
                                    if ($.fn.DataTable.isDataTable('#condirmedHoldersTable')) {
                                        $('#condirmedHoldersTable').DataTable().ajax.reload(null, false);
                                    }
                                    updateCountValue();
                                });
                            } else {

                                alert("Invalid ID.");
                            }
                        }
                    });
                } else if (result.isDenied) {

                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });

        // View update form
        $('#requestedHoldersTable').on('click', '.updateWaterRequest',function() {

            var id = $(this).data('id');
            var url = "{{ url('water-request') }}/" + id + "/edit";
            window.location.href = url;
        });

        // Delete confirmed (back to requested list)
        $('#condirmedHoldersTable').on('click', '.deleteConfirmedWaterUser',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to back this confirmed to requested list?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteConfirmedWaterUser') }}",
                        type: 'get',
                        data: {id: id},
                        success: function(response) {
                            if(response.success == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    $('#condirmedHoldersTable').DataTable().draw();
                                    $('#requestedHoldersTable').DataTable().draw();
                                    updateCountValue();
                                });
                            } else {

                                alert("Invalid ID.");
                            }
                        }
                    });
                } else if (result.isDenied) {

                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });

        // Delete H2O water user record
        $('#h2oHoldersTable').on('click', '.deleteWaterUser',function() {

            var id = $(this).data('id');
            var tableName = '#h2oHoldersTable'; 
            deleteWaterHolder(id, tableName);
        });

        // Delete Grid water user record
        $('#gridHoldersTable').on('click', '.deleteWaterUser',function() {

            var id = $(this).data('id');
            var tableName = '#gridHoldersTable'; 
            deleteWaterHolder(id, tableName);
        }); 

        // Delete Network water user record
        $('#networkHoldersTable').on('click', '.deleteWaterUser',function() {

            var id = $(this).data('id');
            var tableName = '#networkHoldersTable'; 
            deleteWaterHolder(id, tableName);
        });

        // View water H2O details
        $('#h2oHoldersTable').on('click', '.viewWaterUser',function() {

            var id = $(this).data('id');
            viewWaterHolder(id);
        });

        // View water Grid details
        $('#gridHoldersTable').on('click', '.viewWaterUser',function() {

            var id = $(this).data('id');
            viewWaterHolder(id);
        });

        // View water Network details
        $('#networkHoldersTable').on('click', '.viewWaterUser',function() {

            var id = $(this).data('id');
            viewWaterHolder(id);
        });

        // Edit water H2O 
        $('#h2oHoldersTable').on('click', '.updateWaterUser',function() {

            var id = $(this).data('id');
            updateFormWaterHolder(id);
        });

        // Edit water Grid 
        $('#gridHoldersTable').on('click', '.updateWaterUser',function() {

            var id = $(this).data('id');
            updateFormWaterHolder(id);
        });

        // Edit water Network 
        $('#networkHoldersTable').on('click', '.updateWaterUser',function() {

            var id = $(this).data('id');
            updateFormWaterHolder(id);
        });

        // Edit water Confirmed 
        $('#condirmedHoldersTable').on('click', '.updateWaterUser',function() {

            var id = $(this).data('id');
            updateFormWaterHolder(id);
        });

        // View record details for the requested water holder
        $('#condirmedHoldersTable').on('click', '.viewWaterUser',function() {

            var id = $(this).data('id');
            viewConfirmedRequestedHolder(id);
        }); 


        function deleteWaterHolder(id, tableName) {

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this user?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteWaterUser') }}",
                        type: 'get',
                        data: {id: id},
                        success: function(response) {
                            if(response.success == 1) {

                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    $(tableName).DataTable().draw();
                                    updateCountValue();
                                });
                            } else {

                                alert("Invalid ID.");
                            }
                        }
                    });
                } else if (result.isDenied) {

                    Swal.fire('Changes are not saved', '', 'info')
                }
                
            });
        }

        function viewWaterHolder(id) {
            
            var url = window.location.href; 
            
            url = url +'/'+ id ;
            window.open(url); 
        }

        function updateFormWaterHolder(id) {
            
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'all-water/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self");
                }
            });
        }

        function viewConfirmedRequestedHolder(id) {

            // AJAX request
            $.ajax({
                url: 'water-request/' + id,
                type: 'get',
                dataType: 'json', 
                success: function(response) { 

                    $('#requestWaterModalTitle').html(" ");
                    $('#englishNameUser').html(" ");

                    if(response['household']) {

                        $('#requestWaterModalTitle').html(response['household'].english_name);
                        $('#englishNameUser').html(response['household'].english_name);
                    } else if(response['public']) {

                        $('#requestWaterModalTitle').html(response['public'].english_name);
                        $('#englishNameUser').html(response['public'].english_name);
                    }
                    
                    $('#communityUser').html(" ");
                    $('#communityUser').html(response['community'].english_name);
                    $('#meterNumberUser').html(" ");
                    $('#meterCaseUser').html(" ");
                    $('#systemTypeUser').html(" ");
                    $('#systemLimitUser').html(" ");
                    $('#installationDate').html(" ");

                    if(response['energy']) {

                        $('#meterNumberUser').html(response['energy'].meter_number);
                        if(response['meter']) $('#meterCaseUser').html(response['meter'].meter_case_name_english);
                        if(response['type'])$('#systemTypeUser').html(response['type'].name);
                        $('#systemLimitUser').html(response['energy'].daily_limit);
                        $('#installationDate').html(response['energy'].installation_date);
                    } 

                    $('#waterRequestedDate').html(" ");
                    if(response['waterRequestSystem'])$('#waterRequestedDate').html(response['waterRequestSystem'].date);
                    $('#waterRequestedType').html(" ");
                    if(response['waterRequestSystemType']) {

                        if(response['gridIntegrationType']) $('#waterRequestedType').html(response['gridIntegrationType'].name);
                        else $('#waterRequestedType').html(response['waterRequestSystemType'].type);
                    }
                    $('#waterRequestStatusCase').html(" ");
                    if(response['waterRequestStatus'])$('#waterRequestStatusCase').html(response['waterRequestStatus'].name);
                    $('#waterNewReplacement').html(" ");
                    if(response['newReplacnment'])$('#waterNewReplacement').html(response['newReplacnment'].status);
                    $('#waterRequestedCycleYear').html(" ");
                    if(response['cycleYear'])$('#waterRequestedCycleYear').html(response['cycleYear'].name);
                    $('#holderStatus').html(" ");
                    if(response['holderStatus'])$('#holderStatus').html(response['holderStatus'].status);
                    $('#referredBy').html(" ");
                    if(response['waterRequestSystem'])$('#referredBy').html(response['waterRequestSystem'].referred_by);
                    $('#systemNotesUser').html(" ");
                    if(response['waterRequestSystem'])$('#systemNotesUser').html(response['waterRequestSystem'].notes);
                }
            });
        }
    });
</script>

@endsection