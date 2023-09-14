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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<div class="container mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Electricity Meter Issues</h5>
                </div>
                <div class="card-body">
                    <div id="energyUserChart"></div>
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
                    action="{{ route('energy-meter.export') }}">
                    @csrf
                    <div class="card-body"> 
                        <div class="row">
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
                                    <select name="type" id="selectedWaterSystemType" 
                                        class="form-control" required>
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
                                    <label class='col-md-12 control-label'>Installation date from</label>
                                    <input type="date" class="form-control" name="date_from">
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Installation date to</label>
                                    <input type="date" class="form-control" name="date_to">
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script type="text/javascript">
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

        var table = $('.data-table-energy-users').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('all-meter.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
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

                    $('#incidentUser').html(" ");
                    $('#incidentDate').html(" ");
                    if(response['fbsIncident'] != []) {
                        $('#incidentUser').html(response['fbsIncident'][0].incident);
                        $('#incidentDate').html(response['fbsIncident'][0].incident_date);
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
@endsection