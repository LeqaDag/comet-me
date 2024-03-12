@extends('layouts/layoutMaster')

@section('title', 'shared energy users')

@include('layouts.all')

@section('content')

<p> 
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseSharedEnergyUserExport" aria-expanded="false" 
        aria-controls="collapseSharedEnergyUserExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button> 
</p>

<div class="collapse multi-collapse container mb-4" id="collapseSharedEnergyUserExport">
    <div class="container mb-4"> 
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Shared Holders Report 
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearEnergySharedFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div> 
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('household-meter.export') }}">
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
                                        <select name="misc" id="selectedWaterSystemType" 
                                            class="selectpicker form-control">
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
                                        <input type="date" class="form-control" name="date_from"
                                            id="installationSharedDateFrom">
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Installation date to</label>
                                        <input type="date" class="form-control" name="date_to"
                                            id="installationSharedDateTo">
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
  <span class="text-muted fw-light">All </span> Shared Holders
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
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 3 ||
                Auth::guard('user')->user()->user_type_id == 4 ||
                Auth::guard('user')->user()->user_type_id == 12)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createHouseholdMeter">
                        Create New Shared Holder
                    </button>
                    @include('users.energy.shared.create')
                </div>
            @endif
            <table id="allHouseholdMeterTable" class="table table-striped data-table-energy-shared my-2">
                <thead>
                    <tr>
                        <th class="text-center">Shared Holder (Household/Public)</th>
                        <th class="text-center">Meter Holder</th>
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
@include('users.energy.shared.details')

<script type="text/javascript">

    var table;

    function DataTableContent() {

        table = $('.data-table-energy-shared').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('household-meter.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.community_filter = $('#filterByCommunity').val();
                    d.type_filter = $('#filterByType').val();
                    d.date_filter = $('#filterByDateFrom').val();
                }
            },
            columns: [
                {data: 'household_name', name: 'household_name'},
                {data: 'user_name', name: 'user_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'action'},
            ]
        });
    }

    $(function () {
        
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

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByDateFrom').val(' ');
            if ($.fn.DataTable.isDataTable('.data-table-energy-shared')) {
                $('.data-table-energy-shared').DataTable().destroy();
            }
            DataTableContent();
        });

        // Clear Filters for Export
        $('#clearEnergySharedFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#installationSharedDateFrom').val(' ');
            $('#installationSharedDateTo').val(' ');
        });

        // View record details
        $('#allHouseholdMeterTable').on('click', '.viewHouseholdMeterUser',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'household-meter/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) { 

                    $('#energySharedUserModalTitle').html(" ");
                    $('#englishNameSharedUser').html(" ");

                    if(response['sharedUser'] != null) {

                        $('#energySharedUserModalTitle').html(response['sharedUser'].english_name);
                        $('#englishNameSharedUser').html(response['sharedUser'].english_name);
                    } 

                    if(response['sharedPublic'] != null) {

                        $('#energySharedUserModalTitle').html(response['sharedPublic'].english_name);
                        $('#englishNameSharedUser').html(response['sharedPublic'].english_name);
                    }
                    
                    $('#englishNameMainUser').html(" ");
                    $('#englishNameMainUser').html(response['user'].english_name);

                    $('#communityName').html(" ");
                    $('#communityName').html(response['community'].english_name);

                    $('#meterCaseSharedUser').html(" ");
                    $('#meterCaseSharedUser').html(response['meter'].meter_case_name_english);
                    $('#systemNameSharedUser').html(" ");
                    $('#systemNameSharedUser').html(response['system'].name);
                    $('#systemTypeSharedUser').html(" ");
                    $('#systemTypeSharedUser').html(response['type'].name);
                    $('#systemLimitSharedUser').html(" ");
                    $('#systemLimitSharedUser').html(response['mainUser'].daily_limit);
                    $('#systemDateSharedUser').html(" ");
                    $('#systemDateSharedUser').html(response['mainUser'].installation_date);
                    $('#systemNotesSharedUser').html(" ");
                    if(response['mainUser']) $('#systemNotesSharedUser').html(response['mainUser'].notes);
                    $('#vendorDateUser').html(" ");
                    if(response['vendor']) $('#vendorDateSharedUser').html(response['vendor'].name);
                    
                    $('#systemGroundSharedUser').html(" ");
                    $('#systemGroundSharedUser').html(response['mainUser'].ground_connected);
                    if(response['mainUser'].ground_connected == "Yes") {

                        $('#systemGroundSharedUser').css('color', 'green');
                    } else if(response['mainUser'].ground_connected == "No") {

                        $('#systemGroundSharedUser').css('color', 'red');
                    }

                    $('#installationTypeSharedUser').html(" ");
                    if(response['installationType']) $('#installationTypeSharedUser').html(response['installationType'].type);

                }
            });
        }); 

        // Delete record
        $('#allHouseholdMeterTable').on('click', '.deleteAllHouseholdMeterUser',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this household meter?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteHouseholdMeter') }}",
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
                                    $('#allHouseholdMeterTable').DataTable().draw();
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