@extends('layouts/layoutMaster')

@section('title', 'communities')

@include('layouts.all')

@section('content')

<div class="container">
    <div class="row g-4 mb-4">
        <div class="col-md-8 col-lg-8 col-xl-8 col-xxl-3 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Energy Service</h5>
                </div>
                <div class="card-body">
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-2">
                            <div class="avatar avatar-sm flex-shrink-0 me-3">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    <a type="button" data-bs-toggle="modal" 
                                        data-bs-target="#communityInitial">
                                        <i class='bx bx-message'></i>
                                    </a>
                                </span>
                                @include('employee.community.service.initial')
                            </div>
                            <div class="d-flex flex-column w-100">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Initial Communities</span>
                                    <span class="text-muted">{{$communityInitial}}</span>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-primary" style="width: {{$communityInitial}}%" 
                                        role="progressbar" aria-valuenow="{{$communityInitial}}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="{{$communityRecords}}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-2">
                            <div class="avatar avatar-sm flex-shrink-0 me-3">
                                <span class="avatar-initial rounded-circle bg-label-warning">
                                    <a type="button" data-bs-toggle="modal" 
                                        data-bs-target="#communityAC">
                                        <i class='bx bx-message-alt-detail'></i>
                                    </a>
                                </span>
                                @include('employee.community.service.ac_survey')
                            </div>
                            <div class="d-flex flex-column w-100">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>AC Communitites</span>
                                    <span class="text-muted">
                                        @if($communityAC)
                                            {{$communityAC}}
                                        @endif
                                    </span>
                                </div>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-warning" style="width: {{$communityAC}}%" 
                                        role="progressbar" aria-valuenow="{{$communityAC}}" aria-valuemin="0" 
                                        aria-valuemax="{{$communityRecords}}">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-2">
                            <div class="avatar avatar-sm flex-shrink-0 me-3">
                                <span class="avatar-initial rounded-circle bg-label-success">
                                    <a type="button" data-bs-toggle="modal" 
                                        data-bs-target="#communitySurveyed">
                                        <i class='bx bx-bulb'></i>
                                    </a>
                                </span>
                                @include('employee.community.service.surveyed')
                                </span>
                            </div>
                            <div class="d-flex flex-column w-100">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Active Communities</span>
                                    <span class="text-muted">
                                        {{$communitySurvyed}}
                                    </span>
                                </div>
                                <?php
                                    $diff = ($communitySurvyed / $communityRecords ) * 100;
                                ?>
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-success" 
                                        style="width: {{$diff}}%" 
                                        role="progressbar" 
                                        aria-valuenow="{{$diff}}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="{{$communityRecords}}">
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-4 col-xl-4 col-xxl-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Water Service</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    @if ($communityWater)
                                        {{$communityWater}}
                                    @endif
                                </h4> <small>Communities</small>
                            </div>
                            
                                @if ($communityWater)
                                <?php
                                    $min = $communityRecords - $communityWater;
                                ?>

                                    @if($min < $communityRecords/2)
                                        <small class="text-success">{{$min}}
                                    @else 
                                        <small class="text-danger">{{$min}}
                                    @endif
                                    
                                @endif
                            </small>
                            <small>Remaining</small>
                        </div>
                        <span class="badge bg-label-primary rounded p-2">
                            <a type="button" data-bs-toggle="modal" 
                                data-bs-target="#communityWater">
                                <i class="bx bx-water bx-sm"></i>
                            </a>
                            @include('employee.community.service.water')
                        </span>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span>Internet Service</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">
                                    @if ($communityInternet)
                                        {{$communityInternet}}
                                    @endif
                                </h4>  
                                <small>Communities</small>
                            </div>
                            @if ($communityInternet)
                            <?php
                                $min = $communityRecords - $communityInternet;
                            ?>  
                                @if($min < $communityRecords/2)
                                    <small class="text-success">{{$min}}
                                @else 
                                    <small class="text-danger">{{$min}}
                                @endif
                            @endif
                            </small>
                            <small>Remaining</small>
                        </div>
                        <span class="badge bg-label-success rounded p-2">

                            <a type="button" data-bs-toggle="modal" 
                                data-bs-target="#communityInternet">
                                <i class="bx bx-wifi bx-sm"></i>
                            </a>

                            @include('employee.community.service.internet')
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-4">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_regional_community" style="height:450px;">
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-xl-6 col-lg-6 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-body" >
                    <div id="pie_chart_sub_regional_community" style="height:450px;">
                    </div>
                </div>
            </div>
        </div> -->
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
                    action="{{ route('community.export') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="region" class="selectpicker form-control" 
                                    data-live-search="true">
                                        <option disabled selected>Search Region</option>
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
                                    <select name="public" class="selectpicker form-control" 
                                    data-live-search="true">
                                        <option disabled selected>Search Public Structure</option>
                                        @foreach($publicCategories as $publicCategory)
                                        <option value="{{$publicCategory->id}}">
                                            {{$publicCategory->name}}
                                        </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="system_type"
                                        class="selectpicker form-control" 
                                    data-live-search="true">
                                        <option disabled selected>Search System Type</option>
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
                                    <select name="donor" class="selectpicker form-control" 
                                    data-live-search="true">
                                        <option disabled selected>Search Donor</option>
                                        @foreach($donors as $donor)
                                            <option value="{{$donor->id}}">
                                                {{$donor->donor_name}}
                                            </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div style="margin-top:18px" class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <button class="btn btn-info" type="submit">
                                        <i class='fa-solid fa-file-excel'></i>
                                        Export Excel
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </form>
            </div>  
        </div>
    </div> 
</div> 

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> communities
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif
@include('employee.community.details')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2  )
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createCommunity">
                        Create New Community	
                    </button>
                    @include('employee.community.create')

                    <a href="{{url('community', 'create')}}" 
                        class="btn btn-primary">Calculate Number of Households
                    </a>
                </div>
            @endif
            <table id="communityTable" class="table table-striped data-table-communities my-2">
                <thead>
                    <tr>
                        <th>English Name</th>
                        <th>Arabic Name</th>
                        <th># of Households</th>
                        <th># of People</th>
                        <th>Region</th>
                        <th>Sub Region</th>
                        <th>Status</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <input type="hidden" name="txtCommunityId" id="txtCommunityId" value="0">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        var analytics = <?php echo $regionsData; ?>;
        var analyticsSubRegions = <?php echo $subRegionsData; ?>;

        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title : 'Communities by Region' 
            };

            var data1 = google.visualization.arrayToDataTable(analyticsSubRegions);
            var options1 = {
                title : 'Communities by Sub-Region' 
            };


            var chart = new google.visualization.PieChart(document.getElementById('pie_chart_regional_community'));
            chart.draw(data, options);

            var chart1 = new google.visualization.PieChart(document.getElementById('pie_chart_sub_regional_community'));
            chart1.draw(data1, options1);

        }
        
        var table = $('.data-table-communities').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('community.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'number_of_household', name: 'number_of_household'},
                {data: 'number_of_people', name: 'number_of_people'},
                {data: 'name', name: 'name'},
                {data: 'subname', name: 'subname'},
                {data: 'status_name', name: 'status_name'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#communityTable').on('click','.detailsCommunityButton',function() {
            var id = $(this).data('id');
         
            // AJAX request
            $.ajax({
                url: 'community/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    $('#communityModalTitle').html(" ");
                    $('#englishNameCommunity').html(" ");
                    $('#arabicNameCommunity').html(" ");
                    $('#numberOfCompoundsCommunity').html(" ");
                    $('#numberOfPeopleCommunity').html(" ");
                    $('#englishNameRegion').html(" ");
                    $('#numberOfHouseholdCommunity').html(" ");
                    $('#englishNameSubRegion').html(" ");
                    $('#statusCommunity').html(" ");
                    $('#energyServiceCommunity').html(" ");
                    $('#energyServiceYearCommunity').html(" ");
                    $('#waterServiceCommunity').html(" ");
                    $('#waterServiceYearCommunity').html(" ");
                    $('#internetServiceCommunity').html(" ");
                    $('#internetServiceYearCommunity').html(" ");
 
                    $('#communityModalTitle').html(response['community'].english_name);
                    $('#englishNameCommunity').html(response['community'].english_name);
                    $('#arabicNameCommunity').html(response['community'].arabic_name);
                    $('#numberOfCompoundsCommunity').html(response['community'].number_of_compound);
                    $('#numberOfPeopleCommunity').html(response['community'].number_of_people);
                    $('#englishNameRegion').html(response['region'].english_name);
                    $('#numberOfHouseholdCommunity').html(response['community'].number_of_household);
                    $('#englishNameSubRegion').html(response['sub-region'].english_name);
                    $('#statusCommunity').html(response['status'].name);
                    $('#energyServiceCommunity').html(response['community'].energy_service);
                    $('#energyServiceYearCommunity').html(response['community'].energy_service_beginning_year);
                    
                    $('#waterServiceCommunity').html(response['community'].water_service);
                    $('#waterServiceYearCommunity').html(response['community'].water_service_beginning_year);
                    $('#internetServiceCommunity').html(response['community'].internet_service);
                    $('#internetServiceYearCommunity').html(response['community'].internet_service_beginning_year);
                    $('#energySource').html(" ");
                    $('#energySource').html(response['community'].energy_source);
                    $('#meterHoldersCommunity').html(" ");
                    $('#meterHoldersCommunity').html(response['totalMeters']);

                    $('#energyDonorsCommunity').html(" ");
                    if(response['energyDonors'] != []) {
                        for (var i = 0; i < response['energyDonors'].length; i++) {
                            if(response['energyDonors'][i].donor_name == "0")  {
                                response['energyDonors'][i].donor_name = "Not yet attributed";
                            }
                            $("#energyDonorsCommunity").append(
                            '<ul><li>'+ response['energyDonors'][i].donor_name +'</li></ul>');
                               
                        }
                    }

                    $('#waterDonorsCommunity').html(" ");
                    if(response['waterDonors'] != []) {
                        for (var i = 0; i < response['waterDonors'].length; i++) {
                            if(response['waterDonors'][i].donor_name == "0")  {
                                response['waterDonors'][i].donor_name = "Not yet attributed";
                            }
                            $("#waterDonorsCommunity").append(
                            '<ul><li>'+ response['waterDonors'][i].donor_name +'</li></ul>');
                               
                        }
                    }

                    $('#internetDonorsCommunity').html(" ");
                    if(response['internetDonors'] != []) {
                        for (var i = 0; i < response['internetDonors'].length; i++) {
                            if(response['internetDonors'][i].donor_name == "0")  {
                                response['internetDonors'][i].donor_name = "Not yet attributed";
                            }
                            $("#internetDonorsCommunity").append(
                            '<ul><li>'+ response['internetDonors'][i].donor_name +'</li></ul>');
                               
                        }
                    }

                    $("#secondNameDiv").css("visibility", "hidden");
                    $("#secondNameDiv").css('display', 'none');

                    if(response['secondName']) {

                        $("#secondNameDiv").css("visibility", "visible");
                        $("#secondNameDiv").css('display', 'block');

                        $('#secondNameEnglish').html(" ");
                        $('#secondNameArabic').html(" ");
                        $('#secondNameEnglish').html(response['secondName'].english_name);
                        $('#secondNameArabic').html(response['secondName'].arabic_name);
                    }

                    $("#communityRepresentative").html(" "); 
                    $("#representativeRole").html(" ");
                    for (var i = 0; i < response['communityRepresentative'].length; i++) {
                        $("#communityRepresentative").append(
                            '<ul><li>'+ response['communityRepresentative'][i].english_name +'</li></ul>');
                        $("#representativeRole").append(
                            '<ul><li>'+ response['communityRepresentative'][i].role +'</li></ul>');
                    }

                    $("#structuresCommunity").html(" ");
                    for (var i = 0; i < response['public'].length; i++) {
                        $("#structuresCommunity").append(
                            '<ul><li>'+ response['public'][i].english_name +'</li> </ul>');
                    } 

                    $("#compoundsCommunity").html(" ");
                    for (var i = 0; i < response['compounds'].length; i++) {
                        $("#compoundsCommunity").append(
                            '<ul><li>'+ response['compounds'][i].english_name +'</li> </ul>');
                    }

                    $("#townsCommunity").html(" ");
                    for (var i = 0; i < response['nearbyTown'].length; i++) {
                        $("#townsCommunity").append(
                            '<ul><li>'+ response['nearbyTown'][i].english_name +'</li> </ul>');
                    }

                    $("#settlementsCommunity").html(" ");
                    for (var i = 0; i < response['nearbySettlement'].length; i++) {
                        $("#settlementsCommunity").append(
                            '<ul><li>'+ response['nearbySettlement'][i].english_name +'</li> </ul>');
                    }
 
                    $("#waterSourcesCommunity").html(" ");
                    for (var i = 0; i < response['communityWaterSources'].length; i++) {
                        $("#waterSourcesCommunity").append(
                            '<ul><li>'+ response['communityWaterSources'][i].name +'</li> </ul>');
                    }

                    $("#energySourcesCommunity").html(" ");
                    for (var i = 0; i < response['communityRecommendedEnergy'].length; i++) {
                        $("#energySourcesCommunity").append(
                            '<ul><li>'+ response['communityRecommendedEnergy'][i].name +'</li> </ul>');
                    }
                }
            });
        }); 
        
        // View record photos
        $('#communityTable').on('click', '.imageCommunity',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/photo';
            window.open(url); 
        });

        // View record map
        $('#communityTable').on('click', '.mapCommunityButton', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/map';
            window.open(url); 
        });

        // View record update page
        $('#communityTable').on('click', '.updateCommunity', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            
            // AJAX request
            $.ajax({
                url: 'community/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // delete community
        $('#communityTable').on('click', '.deleteCommunity',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this community?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteCommunity') }}",
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
                                    $('#communityTable').DataTable().draw();
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
