@extends('layouts/layoutMaster')

@section('title', 'households')

@include('layouts.all')

@section('content')

<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style> 

<p>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseHouseholdVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseHouseholdVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseHouseholdExport" aria-expanded="false" 
        aria-controls="collapseHouseholdExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseHouseholdVisualData collapseHouseholdExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button>
</p>   

<div class="collapse multi-collapse mb-4" id="collapseHouseholdVisualData">

    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col">
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
                                </div>
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Initial Households</span>
                                        <span class="text-muted">{{$householdInitial}}</span>
                                    </div>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-primary" style="width: {{$householdInitial}}%" 
                                            role="progressbar" aria-valuenow="{{$householdInitial}}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="{{$householdRecords}}">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-2">
                                <div class="avatar avatar-sm flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded-circle bg-label-warning">
                                        <a type="button" data-bs-toggle="modal" 
                                            data-bs-target="#householdAC">
                                            <i class='bx bx-message-alt-detail'></i>
                                        </a>
                                    </span>
                                </div>
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>AC Survey</span>
                                        <span class="text-muted">
                                            @if($householdAC)
                                                {{$householdAC}}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-warning" style="width: {{$householdAC}}%" 
                                            role="progressbar" aria-valuenow="{{$householdAC}}" aria-valuemin="0" 
                                            aria-valuemax="{{$householdRecords}}">
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
                                    </span>
                                </div>
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Active Households</span>
                                        <span class="text-muted">
                                            {{$householdServed}}
                                        </span>
                                    </div>
                                    <?php
                                        $diff = ($householdServed / $householdRecords ) * 100;
                                    ?>
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar bg-success" 
                                            style="width: {{$diff}}%" 
                                            role="progressbar" 
                                            aria-valuenow="{{$diff}}" 
                                            aria-valuemin="0" 
                                            aria-valuemax="{{$householdRecords}}">
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Water Service</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h4 class="mb-0 me-2">
                                        @if ($householdWater)
                                            {{$householdWater}}
                                        @endif
                                    </h4> <small>Households</small>
                                </div>
                                
                                    @if ($householdWater)
                                    <?php
                                        $min = $householdRecords - $householdWater;
                                    ?>

                                        @if($min < $householdRecords/2)
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
                                        {{$householdInternet}}
                                    </h4>  
                                    <small>Households</small>
                                </div>
                            
                                <?php
                                    $min = $householdRecords - $householdInternet;
                                ?>  
                                    @if($min < $householdRecords/2)
                                        <small class="text-success">{{$min}}
                                    @else 
                                        <small class="text-danger">{{$min}}
                                    @endif
                                
                                </small>
                                <small>Remaining</small>
                            </div>
                            <span class="badge bg-label-success rounded p-2">

                                <a type="button" data-bs-toggle="modal" 
                                    data-bs-target="#communityInternet">
                                    <i class="bx bx-wifi bx-sm"></i>
                                </a>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row g-4 mb-4"> 
            <div class="col">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h5>Households by Region</h5>
                    </div>
                    <div class="panel-body" >
                        <div class="row">
                            <div id="pie_chart_regional_household" class="col-md-12">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container ">
        <div class="row mb-4 my-2">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h5>Households by Sub-Region</h5>
                    </div>
                    <div class="panel-body" >
                        <div id="pie_chart_sub_regional_household" style="height:300px;">
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>

</div>

<div class="collapse multi-collapse container mb-4" id="collapseHouseholdExport">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-xl-10 col-lg-10 col-md-10">
                            <h5>
                                Export Household Report 
                                <i class='fa-solid fa-file-excel text-info'></i>
                            </h5>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-2">
                            <fieldset class="form-group">
                                <button class="" id="clearHouseholdFiltersButton">
                                <i class='fa-solid fa-eraser'></i>
                                    Clear Filters
                                </button>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('household.export') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group"> 
                                    <select name="region[]" class="selectpicker form-control" 
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
                                    <select name="community[]" class="selectpicker form-control" 
                                        data-live-search="true" multiple>
                                        <option disabled selected>Search Community</option>
                                        @foreach($communities as $community)
                                        <option value="{{$community->id}}">
                                            {{$community->english_name}}
                                        </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="status[]" class="selectpicker form-control" 
                                    data-live-search="true" multiple>
                                        <option disabled selected>Search Household Status</option>
                                        @foreach($householdStatuses as $householdStatus)
                                            <option value="{{$householdStatus->id}}">
                                                {{$householdStatus->status}}
                                            </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="system_type[] "
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
                        </div>
                        <div class="row" style="margin-top:18px">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <select name="donor[]" class="selectpicker form-control" 
                                        data-live-search="true" multiple>
                                        <option disabled selected >Search Donors</option>
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
All<span class="text-muted fw-light"> Households</span> 
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif 

@include('employee.household.details')

<div class="container"> 
    <div class="card my-2"> 
        <div class="card-header">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Community</label>
                        <select name="community_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByCommunity">
                            <option disabled selected>Choose one...</option>
                            @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
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
                        <label class='col-md-12 control-label'>Filter By Status</label>
                        <select name="sub_region_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByHouseholdStatus">
                            <option disabled selected>Choose one...</option>
                            @foreach($householdStatuses as $householdStatus)
                                <option value="{{$householdStatus->id}}">{{$householdStatus->status}}</option>
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
            @if(Auth::guard('user')->user()->user_type_id != 7 ||
                Auth::guard('user')->user()->user_type_id != 11  )
                <div>
                    <p class="card-text"> 
                        <div>
                            <a type="button" class="btn btn-success" 
                                href="{{url('household', 'create')}}" >
                                Create New Household	
                            </a>
                        </div>
                    </p>
                </div>
            @endif
            <table id="householdsTable" 
                class="table table-striped data-table-households my-2">
                <thead>
                    <tr>
                        
                        <th class="text-center">English Name</th>
                        <th class="text-center">Arabic Name</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Status</th>
                        @if(Auth::guard('user')->user()->user_type_id == 9)
                        <th class="text-center">Main User</th>
                        <th class="text-center">Refrigerator</th>
                        @endif
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <input type="hidden" name="txtHouseholdId" id="txtHouseholdId" value="0">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

    var table;
    function DataTableContent() {

        table = $('.data-table-households').DataTable({
            processing: true,
            serverSide: true, 
            ajax: {
                url: "{{ route('household.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.filter = $('#filterByCommunity').val();
                    d.second_filter = $('#filterByRegion').val();
                    d.third_filter = $('#filterByHouseholdStatus').val();
                }
            },
            columns: [
               // {data: 'checkStatus'},
                {data: 'english_name', name: 'english_name'},
                {data: 'arabic_name', name: 'arabic_name'},
                {data: 'name', name: 'name'},
                {data: 'statusLabel'},
                @if(Auth::guard('user')->user()->user_type_id == 9)
                    {data: 'is_main', name: 'is_main'},
                    {data: 'icon' },
                @endif
                {data: 'action' }
            ]
        }); 
    }

    $(function () {

        var analytics = <?php echo $regionHouseholdsData; ?>;
        var analyticsSubRegion = <?php echo $subRegionHouseholdsData; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart()
        {
            
            var data = google.visualization.arrayToDataTable(analytics);
            var options = {
                title : 'Households by Region' 
            };

            var dataSubRegion = google.visualization.arrayToDataTable(analyticsSubRegion);
            var optionsSubRegion = {
                title : 'Households by Sub-Region' 
            };

            var chart = new google.charts.Bar(
                document.getElementById('pie_chart_regional_household')
                );
            chart.draw(data, options);

            var chartSubRegion = new google.charts.Bar(
                document.getElementById('pie_chart_sub_regional_household')
                );
            chartSubRegion.draw(dataSubRegion, optionsSubRegion);
        }

        DataTableContent();
        
        $('#filterByRegion').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByHouseholdStatus').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-households')) {
                $('.data-table-households').DataTable().destroy();
            }
            DataTableContent();
        });
        
        // Clear Filters for Export
        $('#clearHouseholdFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
        });

        // change the status
        $('#householdsTable').on('click', '.householdStatus',function() {
            var id = $(this).val();
            alert(id);
        });

        // View record details
        $('#householdsTable').on('click', '.detailsHouseholdButton',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'household/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $('#householdModalTitle').html(" ");
                    $('#englishNameHousehold').html(" ");
                    $('#arabicNameHousehold').html(" ");
                    $('#communityHousehold').html(" ");
                    $('#professionHousehold').html(" ");
                    $('#numberOfMaleHousehold').html(" ");
                    $('#numberOfFemaleHousehold').html(" ");
                    $('#numberOfChildrenHousehold').html(" ");
                    $('#numberOfAdultsHousehold').html(" ");
                    $('#phoneNumberHousehold').html(" ");
                    $('#energyServiceHousehold').html(" ");
                    $('#energyMeterHousehold').html(" ");
                    $('#internetServiceHousehold').html(" ");
                    $('#waterServiceHousehold').html(" ");
                    $('#energyStatusHousehold').html(" ");
                    $('#compoundHousehold').html(" ");
 
                    $('#householdModalTitle').html(response['household'].english_name);
                    $('#englishNameHousehold').html(response['household'].english_name);
                    $('#arabicNameHousehold').html(response['household'].arabic_name);
                    $('#communityHousehold').html(response['community'].english_name);
                    if(response['profession']) $('#professionHousehold').html(response['profession'].profession_name);
                    $('#numberOfMaleHousehold').html(response['household'].number_of_male);
                    $('#numberOfFemaleHousehold').html(response['household'].number_of_female);
                    $('#numberOfChildrenHousehold').html(response['household'].number_of_children);
                    $('#numberOfAdultsHousehold').html(response['household'].number_of_adults);
                    $('#phoneNumberHousehold').html(response['household'].phone_number);
                    $('#energyServiceHousehold').html(response['household'].energy_system_status);
                    $('#energyMeterHousehold').html(response['household'].energy_meter);
                    $('#waterServiceHousehold').html(response['household'].water_system_status);
                    $('#internetServiceHousehold').html(response['household'].internet_system_status);
                    $('#energyStatusHousehold').html(response['status'].status);
                    if(response['compound']) $('#compoundHousehold').html(response['compound'].english_name);

                    $('#numberOfCistern').html(" ");

                    if(response['cistern']) {

                        $('#numberOfCistern').html(response['cistern'].number_of_cisterns);
                        $('#volumeCistern').html(" ");
                        $('#volumeCistern').html(response['cistern'].volume_of_cisterns);
                        $('#depthCistern').html(" ");
                        $('#depthCistern').html(response['cistern'].depth_of_cisterns);
                        $('#sharedCistern').html(" ");
                        $('#sharedCistern').html(response['cistern'].shared_cisterns);
                        $('#distanceCistern').html(" ");
                        $('#distanceCistern').html(response['cistern'].distance_from_house);
                    }
                    $('#herdSize').html(" ");
                    $('#herdSize').html(response['household'].size_of_herd);

                    if(response['structure']) {

                        $('#numberOfStructures').html(" ");
                        $('#numberOfStructures').html(response['structure'].number_of_structures);
                        $('#numberOfkitchens').html(" ");
                        $('#numberOfkitchens').html(response['structure'].number_of_kitchens);
                        $('#numberOfShelters').html(" ");
                        $('#numberOfShelters').html(response['structure'].number_of_animal_shelters);
                    }
                    
                    if(response['communityHousehold']) {

                        $('#izbih').html(" ");
                        $('#izbih').html(response['communityHousehold'].is_there_izbih);
                        $('#houseInTown').html(" ");
                        $('#houseInTown').html(response['communityHousehold'].is_there_house_in_town);
                        $('#howLong').html(" ");
                        $('#howLong').html(response['communityHousehold'].how_long);
                        $('#lengthOfStay').html(" ");
                        $('#lengthOfStay').html(response['communityHousehold'].length_of_stay);
                    }
                   
                    if(response['energyCycleYear'] != []) {

                        $('#energyCycleYear').html(" ");
                        $('#energyCycleYear').html(response['energyCycleYear'].name);
                    }

                    $('#energySourceHousehold').html(" ");
                    $('#energySourceHousehold').html(response['household'].electricity_source);
                    $('#energySourceSharedHousehold').html(" ");
                    $('#energySourceSharedHousehold').html(response['household'].electricity_source_shared);
                    $('#notesHousehold').html(" ");
                    $('#notesHousehold').html(response['household'].notes);
                }
            });
        });
    
        // View record details
        $('#householdsTable').on('click', '.updateHousehold',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'household/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url); 
                }
            });
        });

        // Delete record
        $('#householdsTable').on('click', '.deleteHousehold',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this household?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteHousehold') }}",
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
                                    $('#householdsTable').DataTable().draw();
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