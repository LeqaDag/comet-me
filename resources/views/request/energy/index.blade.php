@extends('layouts/layoutMaster')

@section('title', 'all request energy systems')

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

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseEnergyRequestExport" aria-expanded="false" 
        aria-controls="collapseEnergyRequestExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button> 
</p>

<div class="collapse multi-collapse container mb-4" id="collapseEnergyRequestExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>
                            Export Requested Systems Report 
                            <i class='fa-solid fa-file-excel text-info'></i>
                        </h5>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('energy-request.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Community</label>
                                        <select name="community"
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
                                        <label class='col-md-12 control-label'>Status of request</label>
                                        <select name="request_status"
                                            class="selectpicker form-control" data-live-search="true">
                                            <option disabled selected>Search Status of request</option>
                                            @foreach($requestStatuses as $requestStatus)
                                            <option value="{{$requestStatus->id}}">
                                                {{$requestStatus->name}}
                                            </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
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

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Requested Systems
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
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4 ||
                Auth::guard('user')->user()->user_type_id == 5 ||
                Auth::guard('user')->user()->user_type_id == 6)
                <div style="margin-top:18px">
                    <a type="button" class="btn btn-success" 
                        href="{{url('energy-request', 'create')}}" >
                        Create New Request System
                    </a>
                </div>
            @endif
        </div>
        <div class="card-body">
            <table id="energyRequestTable" class="table table-striped data-table-energy-request my-2">
                <thead>
                    <tr>
                        <th class="text-center">Requested Household</th>
                        <th class="text-center">Requested Community</th>
                        <th class="text-center">Request Date</th>
                        <th class="text-center">Energy</th>
                        <th class="text-center">Water</th>
                        <th class="text-center">Internet</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script type="text/javascript">
    $(function () {

        var table = $('.data-table-energy-request').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-request.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'household', name: 'household'},
                {data: 'community_name', name: 'community_name'},
                {data: 'date', name: 'date'},
                {data: 'energy_service', name: 'energy_service'},
                {data: 'water_service', name: 'water_service'},
                {data: 'internet_service', name: 'internet_service'},
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