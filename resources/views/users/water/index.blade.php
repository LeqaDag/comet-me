@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'water-users')

@include('layouts.all')

@section('content')

<div class="container mb-4">
    <div class="col-lg-12 col-12">
        <div class="row">
            <div class="col-6 col-md-3 col-lg-3 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <span class="avatar-initial rounded-circle bg-label-info">
                            <i class="bx bx-droplet fs-4"></i></span>
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
                        <h2 class="mb-0">{{$totalWaterMale->number_of_male}}</h2>
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
                        <h2 class="mb-0">{{$totalWaterFemale->number_of_female}}</h2>
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
                        <h2 class="mb-0">{{$totalWaterAdults->number_of_adults}}</h2>
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
                        <h2 class="mb-0">{{$totalWaterChildren->number_of_children}}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<div class="container mb-4" id="chartWaterSystem" style="visiblity:hidden; display:none">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 id="chartWaterSystemTitle"></h5>
                </div>
                <div class="card-body">
                    <div id="waterUserChart"></div>
                </div>
            </div>
        </div>
    </div> 
</div>

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span>Used Water System Holders
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
        <div class="card-header">
            <div>
                <a class="btn btn-info" href="{{ route('water-user.export') }}">
                    <i class='fa-solid fa-file-excel'></i>
                    Export Excel
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="waterUsersTable" 
                class="table table-striped data-table-water-users my-2">
                <thead>
                    <tr>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Number of H2O</th>
                        <th class="text-center">H2O Status</th>
                        <th class="text-center">Number of Grid Large</th>
                        <th class="text-center">Number of Grid Small</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('users.water.details')

<script type="text/javascript">

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

    $(function () {

        // DataTable
        var table = $('.data-table-water-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('water-user.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'number_of_h20', name: 'number_of_h20'},
                {data: 'status', name: 'status'},
                {data: 'grid_integration_large', name: 'grid_integration_large'},
                {data: 'grid_integration_small', name: 'grid_integration_small' },
                {data: 'action'}
            ],
        });

        // View record details
        $('#waterUsersTable').on('click','.viewWaterUser',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'water-user/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $('#WaterUserModalTitle').html(response['household'].english_name);
                    $('#englishNameUser').html(response['household'].english_name);
                    $('#communityUser').html(response['community'].english_name);
                    $('#numberH2oUser').html(response['h2oUser'].number_of_h20);
                    $('#statusH2oUser').html(response['h2oStatus'].status);
                    $('#numberBsfUser').html(response['h2oUser'].number_of_bsf);
                    $('#statusBsfUser').html(response['bsfStatus'].name);

                    $('#gridLargeNumber').html(response['gridUser'].grid_integration_large);
                    $('#gridLargeDateNumber').html(response['gridUser'].large_date);
                    $('#gridSmallNumber').html(response['gridUser'].grid_integration_small);
                    $('#gridSmallDateNumber').html(response['gridUser'].small_date);
                    $('#gridDelivery').html(response['gridUser'].is_delivery);
                    $('#gridPaid').html(response['gridUser'].is_paid);
                    $('#gridComplete').html(response['gridUser'].is_complete);
                }
            });
        });
    });
</script>
@endsection