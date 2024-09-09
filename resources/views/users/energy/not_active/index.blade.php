@extends('layouts/layoutMaster')

@section('title', 'all energy users')

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
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseEnergyUserVisualData" 
        role="button" aria-expanded="false" aria-controls="collapseEnergyUserVisualData">
        <i class="menu-icon tf-icons bx bx-show-alt"></i>
        Visualize Data
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseEnergyUserExport" aria-expanded="false" 
        aria-controls="collapseEnergyUserExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
    <a class="btn btn-primary" data-toggle="collapse" href="#collapseEnergyUserPurchaseReport" 
        role="button" aria-expanded="false" aria-controls="collapseEnergyUserPurchaseReport">
        <i class="menu-icon tf-icons bx bx-purchase-tag"></i>
        Purchase Report
    </a>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target=".multi-collapse" aria-expanded="false" 
        aria-controls="collapseEnergyUserVisualData collapseEnergyUserExport">
        <i class="menu-icon tf-icons bx bx-expand-alt"></i>
        Toggle All
    </button> 
</p> 
 
<div class="collapse multi-collapse mb-4" id="collapseEnergyUserVisualData">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="panel panel-primary">
                    <div class="panel-header">
                        <h5>Electricity Meter Issues</h5>
                    </div>
                    <div class="panel-body">
                        <div id="energyUserChart">
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="collapse multi-collapse container mb-4" id="collapseEnergyUserExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Electricity Meter Users Report 
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearEnergyHolderFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div> 
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('energy-meter.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Region</label>
                                        <select name="region_id"
                                            class="selectpicker form-control" data-live-search="true">
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
                                        <label class='col-md-12 control-label'>Community</label>
                                        <select name="community_id"
                                            class="selectpicker form-control" data-live-search="true">
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
                                        <label class='col-md-12 control-label'>New/MISC/Grid extension</label>
                                        <select name="type" 
                                            class="selectpicker form-control" >
                                            <option disabled selected>Search Installation Type</option>
                                            @foreach($installationTypes as $installationType)
                                                <option value="{{$installationType->id}}">
                                                    {{$installationType->type}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>System Type</label>
                                        <select name="energy_system_type_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Search System Type</option>
                                            @foreach($energySystemTypes as $energySystemType)
                                                <option value="{{$energySystemType->id}}">{{$energySystemType->name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Meter Status</label>
                                        <select name="meter_case_id" class="selectpicker form-control" 
                                            data-live-search="true" >
                                            <option disabled selected>Search Meter Status</option>
                                            @foreach($meterCases as $meterCase)
                                                <option value="{{$meterCase->id}}">{{$meterCase->meter_case_name_english}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Service Year</label>
                                        <select name="service_year" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Search</option>
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
                                        <label class='col-md-12 control-label'>Installation date from</label>
                                        <input type="date" class="form-control" name="date_from"
                                        id="installationEnergyDateFrom">
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Installation date to</label>
                                        <input type="date" class="form-control" name="date_to"
                                        id="installationEnergyDateTo">
                                    </fieldset>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <label class='col-md-12 control-label'>Download Excel</label>
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

<div class="collapse multi-collapse mb-4" id="collapseEnergyUserPurchaseReport">
<div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Import Your File from Vending Software 
                                    <i class='menu-icon tf-icons bx bx-export text-info'></i>
                                </h5>
                            </div>
                        </div> 
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('energy-meter.import') }}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4">
                                    <fieldset class="form-group">
                                        <label for="">Upload the file</label>
                                        <input name="first_file" type="file"
                                            class="form-control" required>
                                    </fieldset>
                                </div>
                                <!-- <div class="col-xl-4 col-lg-4 col-md-4">
                                    <fieldset class="form-group">
                                        <label for="">Upload the second file</label>
                                        <input name="second_file" type="file"
                                            class="form-control">
                                    </fieldset>
                                </div> -->
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <label for="">Click here!</label>
                                    <button class="btn btn-info" type="submit">
                                        <i class='fa-solid fa-file-excel'></i>
                                        Proccess
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
  <span class="text-muted fw-light">All </span> Electricity Meter Users
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
                        <label class='col-md-12 control-label'>New/MISC/Grid extension</label>
                        <select name="type" id="filterByType" 
                            class="selectpicker form-control" >
                            <option disabled selected>Choose one...</option>
                            @foreach($installationTypes as $installationType)
                                <option value="{{$installationType->id}}">
                                    {{$installationType->type}}
                                </option>
                            @endforeach
                        </select>
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By System Type</label>
                        <select name="energy_system_type_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByEnergySystemType">
                            <option disabled selected>Choose one...</option>
                            @foreach($energySystemTypes as $energySystemType)
                                <option value="{{$energySystemType->id}}">{{$energySystemType->name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Meter Status</label>
                        <select name="meter_case_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByMeterStatus">
                            <option disabled selected>Choose one...</option>
                            @foreach($meterCases as $meterCase)
                                <option value="{{$meterCase->id}}">{{$meterCase->meter_case_name_english}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Service Year</label>
                        <select name="service_year" class="selectpicker form-control" 
                            data-live-search="true" id="filterByYear">
                            <option disabled selected>Choose one...</option>
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
                        <label class='col-md-12 control-label'>Installation date from</label>
                        <input type="date" class="form-control" name="date_from"
                        id="filterByDateFrom">
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
            <table id="energyAllUsersTable" class="table table-striped data-table-energy-users my-2">
                <thead>
                    <tr>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Main User</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Meter Number</th>
                        <th class="text-center">Meter Active</th>
                        <th class="text-center">Energy System</th>
                        <th class="text-center">Energy System Type</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('users.energy.details')

<script type="text/javascript">

    var table;

    function DataTableContent() {
        table = $('.data-table-energy-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('all-meter.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.community_filter = $('#filterByCommunity').val();
                    d.region_filter = $('#filterByRegion').val();
                    d.type_filter = $('#filterByType').val();
                    d.date_filter = $('#filterByDateFrom').val();
                    d.system_type_filter = $('#filterByEnergySystemType').val();
                    d.meter_filter = $('#filterByMeterStatus').val();
                    d.year_filter = $('#filterByYear').val();
                }
            },
            columns: [
                {data: 'household_name', name: 'household_name'},
                {data: 'icon'},
                {data: 'community_name', name: 'community_name'},
                {data: 'meter_number', name: 'meter_number'},
                {data: 'meter_case_name_english', name: 'meter_case_name_english'},
                {data: 'energy_name', name: 'energy_name'},
                {data: 'energy_type_name', name: 'energy_type_name'},
                {data: 'action'}
            ]
        });
    }

    $(function () {

        var analytics = <?php echo $energy_users; ?>;

        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable(analytics);

            var options = {
                title: "",
            };

            var chart = new google.charts.Bar(document.getElementById('energyUserChart'));
            chart.draw(
                data, 
                options,
            );
        }

        DataTableContent();

        $('#filterByType').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByDateFrom').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByRegion').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByEnergySystemType').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByMeterStatus').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByYear').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByDateFrom').val(' ');
            if ($.fn.DataTable.isDataTable('.data-table-energy-users')) {
                $('.data-table-energy-users').DataTable().destroy();
            }
            DataTableContent();
        });

        // Clear Filters for Export
        $('#clearEnergyHolderFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#installationEnergyDateFrom').val(' ');
            $('#installationEnergyDateTo').val(' ');
        });

        // View record details
        $('#energyAllUsersTable').on('click', '.updateAllEnergyUser',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'allMeter/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // View record details
        $('#energyAllUsersTable').on('click', '.viewEnergyUser',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'energy-user/' + id,
                type: 'get',
                dataType: 'json', 
                success: function(response) { 
                    $('#energyUserModalTitle').html(" ");
                    $('#energyUserModalTitle').html(response['household'].english_name);
                    $('#englishNameUser').html(" ");
                    $('#englishNameUser').html(response['household'].english_name);
                    $('#communityUser').html(" ");
                    $('#communityUser').html(response['community'].english_name);
                    $('#meterActiveUser').html(" ");
                    $('#meterActiveUser').html(response['energy'].meter_active);
                    $('#meterCaseUser').html(" ");
                    $('#meterCaseUser').html(response['meter'].meter_case_name_english);
                    $('#systemNameUser').html(" ");
                    $('#systemNameUser').html(response['system'].name);
                    $('#systemTypeUser').html(" ");
                    $('#systemTypeUser').html(response['type'].name);
                    $('#systemLimitUser').html(" ");
                    $('#systemLimitUser').html(response['energy'].daily_limit);
                    $('#systemDateUser').html(" ");
                    $('#systemDateUser').html(response['energy'].installation_date);
                    $('#systemNotesUser').html(" ");
                    if(response['energy']) $('#systemNotesUser').html(response['energy'].notes);
                    $('#vendorDateUser').html(" ");
                    if(response['vendor']) $('#vendorDateUser').html(response['vendor'].name);
                    
                    $('#systemGroundUser').html(" ");
                    $('#systemGroundUser').html(response['energy'].ground_connected);
                    if(response['energy'].ground_connected == "Yes") {

                        $('#systemGroundUser').css('color', 'green');
                    } else if(response['energy'].ground_connected == "No") {

                        $('#systemGroundUser').css('color', 'red');
                    }

                    if(response['energyCycleYear'] != []) {

                        $('#energyCycleYear').html(" ");
                        $('#energyCycleYear').html(response['energyCycleYear'].name);
                    }

                    $('#installationTypeUser').html(" ");
                    if(response['installationType']) $('#installationTypeUser').html(response['installationType'].type);

                    $('#donorsDetails').html(" ");
                    if(response['energyMeterDonors'] != []) {
                        for (var i = 0; i < response['energyMeterDonors'].length; i++) {
                            if(response['energyMeterDonors'][i].donor_name == "0")  {
                                response['energyMeterDonors'][i].donor_name = "Not yet attributed";
                            }
                            $("#donorsDetails").append(
                            '<ul><li>'+ response['energyMeterDonors'][i].donor_name +'</li></ul>');
                               
                        }
                    }

                    $('#sharedHousehold').html(" ");
                    if(response['householdMeters'] != []) {
                        for (var i = 0; i < response['householdMeters'].length; i++) {
                            $("#sharedHousehold").append(
                            '<ul><li>'+ response['householdMeters'][i].english_name +'</li></ul>');  
                        }
                    }
 
                    $('#incidentUser').html(" ");
                    $('#incidentDate').html(" ");
                    if(response['fbsIncident'] != []) {
                        for (var i = 0; i < response['fbsIncident'].length; i++) {
                            $('#incidentUser').html(response['fbsIncident'][i].english_name);
                            $('#incidentDate').html(response['fbsIncident'][i].incident_date);
                        }
                    }
                    if(response['mgIncident'] != []) {
                        for (var i = 0; i < response['mgIncident'].length; i++) {
                            $('#incidentUser').html(response['mgIncident'][i].english_name);
                            $('#incidentDate').html(response['mgIncident'][i].incident_date);
                        }
                    }

                }
            });
        }); 

        // delete energy user
        $('#energyAllUsersTable').on('click', '.deleteAllEnergyUser',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this user?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteEnergyUser') }}",
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
                                    $('#energyAllUsersTable').DataTable().draw();
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

@if(session('success'))
<script type="text/javascript">
    Swal.fire({
        icon: 'success', 
        title: '{{ session('success') }}', 
        showDenyButton: false,
        showCancelButton: false,
        confirmButtonText: 'Success!'
    }).then((result) => {
    });
</script>
@endif

@if(session('error'))
<script type="text/javascript">
    Swal.fire({
        icon: 'error', 
        title: '{{ session('error') }}', 
        showDenyButton: false,
        showCancelButton: false,
        confirmButtonText: 'Error!'
    }).then((result) => {
    });
</script>
@endif
@endsection