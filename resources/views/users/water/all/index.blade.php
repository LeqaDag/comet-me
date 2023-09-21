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

<div class="container mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Export Filter</h5>
                </div>
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('water-user.export') }}">
                    @csrf
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="water_system_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                        <option disabled selected>Search System Type</option>
                                        @foreach($waterSystemTypes as $waterSystemType)
                                        <option value="{{$waterSystemType->id}}">
                                            {{$waterSystemType->type}}
                                        </option>
                                        @endforeach
                                    </select> 
                                </fieldset> 
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="community" class="selectpicker form-control" 
                                            data-live-search="true">
                                        <option disabled selected>Search Community</option>
                                        @foreach($communities as $community)
                                        <option value="{{$community->english_name}}">
                                            {{$community->english_name}}
                                        </option>
                                        @endforeach
                                    </select> 
                                </fieldset> 
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <input type="date" name="h2o_installation_date_from" 
                                    class="form-control" title="H2O Installation Data from"> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <input type="date" name="h2o_installation_date" 
                                    class="form-control" title="H2O Installation Data to"> 
                                </fieldset> 
                            </div>
                            <br><br><br>
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
 

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Water System Holders
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
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 5 ||
                Auth::guard('user')->user()->user_type_id == 11)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createWaterUser">
                        Create New Water System Holder	
                    </button>

                    @include('users.water.create')
                </div>
            @endif
            <table id="waterAllUsersTable" 
                class="table table-striped data-table-water-all-users my-2">
                <thead>
                    <tr>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Public</th>
                        <th class="text-center">Main Holder</th>
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
        var table = $('.data-table-water-all-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('all-water.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'public', name: 'public'},
                {data: 'icon'},
                {data: 'community_name', name: 'community_name'},
                {data: 'action'}
            ],
        });
 
        // Update record
        $('#waterAllUsersTable').on('click', '.updateWaterUser',function() {
            var id = $(this).data('id');
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
        });

        // View record details
        $('#waterAllUsersTable').on('click','.viewWaterUser',function() {
            var id = $(this).data('id');
            
            // AJAX request
            $.ajax({
                url: 'water-user/' + id,
                type: 'get', 
                dataType: 'json', 
                success: function(response) {

                    $('#WaterUserModalTitle').html(" ");
                    $('#communityUser').html(" ");
                    $('#communityUser').html(response['community'].english_name);

                    if(response['household'] != null) {

                        $('#WaterUserModalTitle').html(response['household'].english_name);
                        $('#englishNameUser').html(" ");
                        $('#englishNameUser').html(response['household'].english_name);

                    } else if(response['public'] != null) {

                        $('#WaterUserModalTitle').html(response['public'].english_name);
                        $('#englishNameUser').html(" ");
                        $('#englishNameUser').html(response['public'].english_name);
                    }

                    $('#mainHolder').append(" ");
                    $('#mainHolder').html(response['allWaterHolder'].is_main);

                    $('#dataEnergyService').append(" ");
                    $('#dataEnergyService').html(response['household'].energy_system_status);

                    $('#dataEnergyDate').append(" ");
                    $('#dataEnergyMeter').append(" ");
                    $('#dataMeterNumber').append(" ");
                    if(response['energyUser'] != []) {
                        $('#dataEnergyDate').html(response['energyUser'][0].installation_date);
                        if(response['energyUser'][0].meter_number) {

                            $('#dataEnergyMeter').html("Yes");
                            $('#dataEnergyMeter').css('color', 'green');
                        } else {

                            $('#dataEnergyMeter').html("No");
                            $('#dataEnergyMeter').css('color', 'red');
                        }
                        
                        $('#dataMeterNumber').html(response['energyUser'][0].meter_number);
                    }

                    $('#holderPeople').append(" ");
                    $('#holderPeople').html(response['household'].number_of_people);
                    $('#holderMale').append(" ");
                    $('#holderMale').html(response['household'].number_of_male);
                    $('#holderFemale').append(" ");
                    $('#holderFemale').html(response['household'].number_of_female);
                    $('#holderAdult').append(" ");
                    $('#holderAdult').html(response['household'].number_of_adults);
                    $('#holderChildren').append(" ");
                    $('#holderChildren').html(response['household'].number_of_children);

                    if(response['h2oUser'] == null) {

                        $("#h2oDetails").css("visibility", "hidden");
                        $("#h2oDetails").css('display', 'none');
                        $("#gridDetails").css("visibility", "hidden");
                        $("#gridDetails").css('display', 'none');
                        
                    } else if(response['h2oUser']) {

                        $("#h2oDetails").css("visibility", "visible");
                        $("#h2oDetails").css('display', 'block');
                        
                        $('#numberH2oUser').append(" ");
                        $('#dateH2oUser').append(" ");
                        $('#yearH2oUser').append(" ");
                        $('#statusH2oUser').append(" ");
                        $('#numberBsfUser').append(" ");
                        $('#statusBsfUser').append(" "); 

                        $('#numberH2oUser').html(response['h2oUser'].number_of_h20);
                        $('#dateH2oUser').html(response['h2oUser'].h2o_request_date);
                        $('#yearH2oUser').html(response['h2oUser'].installation_year);
                        $('#statusH2oUser').html(response['h2oStatus'].status);
                        $('#numberBsfUser').html(response['h2oUser'].number_of_bsf);
                        if(response['bsfStatus']) $('#statusBsfUser').html(response['bsfStatus'].name); 
                    }
                    
                    if(response['gridUser'] != null) {

                        $("#gridDetails").css("visibility", "visible");
                        $("#gridDetails").css('display', 'block');

                        $('#dateGridUser').append(" ");
                        $('#dateGridUser').html(response['gridUser'].request_date);
                        $('#gridLargeNumber').append(" ");
                        $('#gridLargeNumber').html(response['gridUser'].grid_integration_large);
                        $('#gridLargeDateNumber').append(" ");
                        $('#gridLargeDateNumber').html(response['gridUser'].large_date);
                        $('#gridSmallNumber').append(" ");
                        $('#gridSmallNumber').html(response['gridUser'].grid_integration_small);
                        $('#gridSmallDateNumber').append(" ");
                        $('#gridSmallDateNumber').html(response['gridUser'].small_date);
                        $('#gridDelivery').append(" ");
                        $('#gridDelivery').html(response['gridUser'].is_delivery);
                        $('#gridPaid').append(" ");
                        $('#gridPaid').html(response['gridUser'].is_paid);
                        $('#gridComplete').append(" ");
                        $('#gridComplete').html(response['gridUser'].is_complete);
                    } 

                    if(response['networkUser'] != null) {

                        $("#h2oDetails").css("visibility", "hidden");
                        $("#h2oDetails").css('display', 'none');
                        $("#gridDetails").css("visibility", "hidden");
                        $("#gridDetails").css('display', 'none');
                    }

                    $('#donorsDetailsWaterHolder').html(" ");
                    if(response['allWaterHolderDonors'] != []) {
                        for (var i = 0; i < response['allWaterHolderDonors'].length; i++) {
                            if(response['allWaterHolderDonors'][i].donor_name == "0")  {
                                response['allWaterHolderDonors'][i].donor_name = "Not yet attributed";
                            }
                            $("#donorsDetailsWaterHolder").append(
                            '<ul><li>'+ response['allWaterHolderDonors'][i].donor_name +'</li></ul>');
                               
                        }
                    }

                    $('#incidentUser').html(" ");
                    $('#incidentDate').html(" ");
                    if(response['waterIncident'] != []) {
                        $('#incidentUser').html(response['waterIncident'][0].incident);
                        $('#incidentDate').html(response['waterIncident'][0].incident_date);
                    }
                }
            });
        });


        // Delete record
        $('#waterAllUsersTable').on('click', '.deleteWaterUser',function() {
            var id = $(this).data('id');

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
                                    $('#waterAllUsersTable').DataTable().draw();
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
    });
</script>
@endsection