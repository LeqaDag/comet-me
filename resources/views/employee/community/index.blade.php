@extends('layouts/layoutMaster')

@section('title', 'communities')

@include('layouts.all')

@section('content')

<p> 
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseCommunityVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseCommunityVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseCommunityExport" aria-expanded="false" 
        aria-controls="collapseCommunityExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseCommunityVisualData collapseCommunityExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button> 
</p>

<div class="collapse multi-collapse" id="collapseCommunityVisualData">
    <div class="container" >
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

    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-body" >
                        <div id="pie_chart_regional_community" style="height:450px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="collapse multi-collapse container mb-4" id="collapseCommunityExport">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <h5> 
                                Export Community Report 
                                <i class='fa-solid fa-file-excel text-info'></i>
                            </h5>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2">
                            <fieldset class="form-group">
                                <button class="" id="clearCommunityFiltersButton">
                                <i class='fa-solid fa-eraser'></i>
                                    Clear Filters
                                </button>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('community.export') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="region[]" class="selectpicker form-control" 
                                    data-live-search="true" multiple>
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
                                    <select name="public[]" class="selectpicker form-control" 
                                    data-live-search="true" multiple>
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
                                    <select name="system_type[]"
                                        class="selectpicker form-control" 
                                        data-live-search="true" multiple>
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
                                    <select name="donor[]" class="selectpicker form-control" 
                                    data-live-search="true" multiple>
                                        <option disabled selected>Search Donor</option>
                                        @foreach($donors as $donor)
                                            <option value="{{$donor->id}}">
                                                {{$donor->donor_name}}
                                            </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                        </div>
                        <div class="row" style="margin-top:18px">
                            <div class="col-xl-3 col-lg-3 col-md-3">
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
        <div class="card-header">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Region</label>
                        <select name="region_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByRegion">
                            <option disabled selected>Choose one...</option>
                            @foreach($regions as $region)
                                <option value="{{$region->id}}">{{$region->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Sub Region</label>
                        <select name="sub_region_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterBySubRegion">
                            <option disabled selected>Choose one...</option>
                            @foreach($subregions as $subRegion)
                                <option value="{{$subRegion->id}}">{{$subRegion->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Status</label>
                        <select name="status_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByStatus">
                            <option disabled selected>Choose one...</option>
                            @foreach($communityStatuses as $communityStatus)
                                <option value="{{$communityStatus->id}}">{{$communityStatus->name}}</option>
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
        </div>
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
    
    var table;
    function DataTableContent() {

        table = $('.data-table-communities').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('community.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.filter = $('#filterByRegion').val();
                    d.second_filter = $('#filterBySubRegion').val();
                    d.third_filter = $('#filterByStatus').val();
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
    }

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

        DataTableContent();
        
        $('#filterByRegion').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterBySubRegion').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByStatus').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-communities')) {
                $('.data-table-communities').DataTable().destroy();
            }
            DataTableContent();
        });

        // Clear Filters for Export
        $('#clearCommunityFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
        });

        // View record details
        $('#communityTable').on('click', '.detailsCommunityButton', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id ;
            window.open(url); 
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
