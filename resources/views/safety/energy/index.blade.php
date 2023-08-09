@extends('layouts/layoutMaster')

@section('title', 'energy safety checks')

@include('layouts.all')

@section('content')


<div class="container mb-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Export Filter</h5>
                </div>
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('energy-safety.export') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Community</label>
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
                                    <label class='col-md-12 control-label'>System Type</label>
                                    <select name="system_type" class="selectpicker form-control" 
                                        data-live-search="true">
                                        <option disabled selected>Search System Type</option>
                                        @foreach($energySystemTypes as $energySystemType)
                                            <option value="{{$energySystemType->name}}">
                                                {{$energySystemType->name}}
                                            </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Visit date from</label>
                                    <input type="date" class="form-control" name="date_from">
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Visit date to</label>
                                    <input type="date" class="form-control" name="date_to">
                                </fieldset>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3">
                                <label class='col-md-12 control-label'></label>
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
  <span class="text-muted fw-light">All </span> Meters Safety Check
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
                Auth::guard('user')->user()->user_type_id == 4 )
                <div class="card-header">
                    <div style="margin-top:18px">
                        <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#createSafetyEnergyCheck">
                            Create New Energy Safety Check
                        </button>
                        @include('safety.energy.create')
                    </div>
                </div> 
            @endif
            <table id="energySafetyTable" class="table table-striped data-table-energy-safety my-2">
                <thead>
                    <tr>
                        <th class="text-center">User Name</th>
                        <th class="text-center">Public</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Meter Number</th>
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

@include('safety.energy.details')

<script type="text/javascript">
    $(function () {

        var table = $('.data-table-energy-safety').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-safety.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'household_name', name: 'household_name'},
                {data: 'public', name: 'public'},
                {data: 'community_name', name: 'community_name'},
                {data: 'meter_number', name: 'meter_number'},
                {data: 'energy_type_name', name: 'energy_type_name'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#energySafetyTable').on('click', '.updateEnergySafety',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            // AJAX request
            $.ajax({
                url: 'energy-safety/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // View record details
        $('#energySafetyTable').on('click', '.viewEnergySafety',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'energy-safety/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) { 
                    
                    $('#energyUserModalTitle').html(" ");
                    $('#englishNameUser').html(" ");

                    if(response['household']) {

                        $('#energyUserModalTitle').html(response['household'].english_name);
                        $('#englishNameUser').html(response['household'].english_name);

                    } else if(response['public']) {

                        $('#energyUserModalTitle').html(response['public'].english_name);
                        $('#englishNameUser').html(response['public'].english_name);
                    }

                    $('#communityUser').html(" ");
                    $('#communityUser').html(response['community'].english_name);
                    $('#meterCaseUser').html(" ");
                    $('#meterCaseUser').html(response['meter'].meter_case_name_english);

                    $('#systemTypeUser').html(" ");
                    $('#systemTypeUser').html(response['systemType'].name);

                    $('#systemVisitDate').html(" ");
                    $('#systemVisitDate').html(response['energySafety'].visit_date);
                    $('#meterXphase0').html(" ");
                    $('#meterXphase0').html(response['energySafety'].rcd_x_phase0);
                    $('#meterXphase1').html(" ");
                    $('#meterXphase1').html(response['energySafety'].rcd_x_phase1);
                    $('#meterX1phase0').html(" ");
                    $('#meterX1phase0').html(response['energySafety'].rcd_x1_phase0);
                    $('#meterX1phase1').html(" ");
                    $('#meterX1phase1').html(response['energySafety'].rcd_x1_phase1);
                    $('#meterX5phase0').html(" ");
                    $('#meterX5phase0').html(response['energySafety'].rcd_x5_phase0);
                    $('#meterX5phase1').html(" ");
                    $('#meterX5phase1').html(response['energySafety'].rcd_x5_phase1);
                    $('#meterPhLoop').html(" ");
                    $('#meterPhLoop').html(response['energySafety'].ph_loop);
                    $('#meterNLoop').html(" ");
                    $('#meterNLoop').html(response['energySafety'].n_loop);
                    $('#groundConnected').html(" ");
                    $('#groundConnected').html(response['allEnergyMeter'].ground_connected);
                    $('#systemNotesUser').html(" ");
                    $('#systemNotesUser').html(response['energySafety'].notes);
                }
            });
        }); 

        // delete energy safety
        $('#energySafetyTable').on('click', '.deleteEnergySafety',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this check?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteEnergySafety') }}",
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
                                    $('#energySafetyTable').DataTable().draw();
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