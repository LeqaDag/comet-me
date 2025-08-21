@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'tickets')


@include('layouts.all')

@section('content')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseAllTicketsExport" aria-expanded="false" 
        aria-controls="collapseAllTicketsExport"> 
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button> 
</p>  

<div class="collapse multi-collapse container mb-4" id="collapseAllTicketsExport">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                    Export All Maintenance Report 
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearAllMaintenanceFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('all-maintenance.export') }}">
                        @csrf 
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Community</label>
                                        <select name="community_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($communities as $community)
                                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Service</label>
                                        <select name="service_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($serviceTypes as $serviceType)
                                                <option value="{{$serviceType->id}}">{{$serviceType->service_name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Maintenance Status</label>
                                        <select name="maintenance_status_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($maintenanceStatuses as $maintenanceStatus)
                                                <option value="{{$maintenanceStatus->id}}">{{$maintenanceStatus->name}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Maintenance Type</label>
                                        <select name="maintenance_type_id" class="selectpicker form-control" 
                                            data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($maintenanceTypes as $maintenanceType)
                                                <option value="{{$maintenanceType->id}}">{{$maintenanceType->type}}</option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                            </div> <br>
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Completed Date From</label>
                                        <input type="date" name="completed_date_from" class="form-control"
                                            id="filterByCompletedDateFromExport">
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Completed Date To</label>
                                        <input type="date" name="completed_date_to" class="form-control"
                                            id="filterByCompletedDateToExport">
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
  <span class="text-muted fw-light">All </span>Maintenance
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
                        <label class='col-md-12 control-label'>Filter By Agent</label>
                        <select class="selectpicker form-control" 
                            data-live-search="true" id="filterByAgent">
                            <option disabled selected>Choose one...</option>
                            <option value="household">Household</option>
                            <option value="public">Public Structures</option>
                            <option value="energy_system">Energy System</option>
                            <option value="water_system">Water System</option>
                            <option value="turbine">Turbine</option>
                            <option value="generator">Generator</option>
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
                        <label class='col-md-12 control-label'>Filter By Service</label>
                        <select name="maintenance_status_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByService">
                            <option disabled selected>Choose one...</option>
                            @foreach($serviceTypes as $serviceType)
                                <option value="{{$serviceType->id}}">{{$serviceType->service_name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Maintenance Status</label>
                        <select name="maintenance_status_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByMaintenanceStatus">
                            <option disabled selected>Choose one...</option>
                            @foreach($maintenanceStatuses as $maintenanceStatus)
                                <option value="{{$maintenanceStatus->id}}">{{$maintenanceStatus->name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
            </div>
            <div class="row" style="margin-top:15px">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Maintenance Type</label>
                        <select name="maintenance_type_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByMaintenanceType">
                            <option disabled selected>Choose one...</option>
                            @foreach($maintenanceTypes as $maintenanceType)
                                <option value="{{$maintenanceType->id}}">{{$maintenanceType->type}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3" >
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
            <div style="margin-top:30px">
                <button type="button" class="btn btn-success" 
                    id="getLatestTickets">
                    Get Latest Tickets
                </button>
            </div>

            <table id="allMaintenanceTable" 
                class="table table-striped data-table-all-maintenance my-2">
                <thead>
                    <tr>
                        <th class="text-center">Agent</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('ticket.show')

<script type="text/javascript">
    
    var table;
    function DataTableContent() {

        table = $('.data-table-all-maintenance').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('all-maintenance.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.agent_filter = $('#filterByAgent').val();
                    d.community_filter = $('#filterByCommunity').val();
                    d.service_filter = $('#filterByService').val();
                    d.status_filter = $('#filterByMaintenanceStatus').val();
                    d.type_filter = $('#filterByMaintenanceType').val();
                }
            },
            columns: [
                {data: 'agent_name', name: 'agent_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'service_name', name: 'service_name'},
                {data: 'name', name: 'name'},
                {data: 'action' }
            ]
        });
    }

    // Clear Filters for Export
    $('#clearAllMaintenanceFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
        $('#filterByCompletedDateFromExport').val(' ');
        $('#filterByCompletedDateToExport').val(' ');
    });

    $(function () {

        var urlParams = new URLSearchParams(window.location.search);
        var filterByCommunity = urlParams.get('filterByCommunity');

        if (filterByCommunity) {

            $('#filterByCommunity').val(filterByCommunity);
        }

        DataTableContent();
        $('#filterByAgent').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByService').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByMaintenanceStatus').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByMaintenanceType').on('change', function() {
            table.ajax.reload(); 
        }); 

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-all-maintenance')) {
                $('.data-table-all-maintenance').DataTable().destroy();
            }
            DataTableContent();
        });

        // View record details
        $('#allMaintenanceTable').on('click', '.viewAllMaintenance',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'all-maintenance/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    $('#allMaintenanceModalTitle').html(" ");
                    $('#englishNameAgent').html(" ");
                    $('#arabicNameAgent').html(" ");
                    $('#communityAgent').html(" ");
                    $('#communityAgent').html(response['community'].english_name);

                    if(response['household']) {

                        $('#allMaintenanceModalTitle').html(response['household'].english_name);
                        $('#englishNameAgent').html(response['household'].english_name);
                        $('#phoneNumberAgent').html(response['household'].phone_number);

                        $("#agentIcon").removeClass('bx bx-building bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-cuboid bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-analyse bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#agentIcon").addClass('bx bx-user bx-sm me-3');
                    } else if(response['public']) {

                        $('#allMaintenanceModalTitle').html(response['public'].english_name);
                        $('#englishNameAgent').html(response['public'].english_name);
                        $('#phoneNumberAgent').html(response['public'].phone_number);

                        $("#agentIcon").removeClass('bx bx-user bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-cuboid bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-analyse bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#agentIcon").addClass('bx bx-building bx-sm me-3');
                    }  else if(response['energySystem']) {

                        $('#allMaintenanceModalTitle').html(response['energySystem'].name);
                        $('#englishNameAgent').html(response['energySystem'].name);

                        $("#agentIcon").removeClass('bx bx-user bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-building bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-cuboid bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-analyse bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#agentIcon").addClass('bx bx-bulb bx-sm me-3');
                    }  else if(response['waterSystem']) {

                        $('#allMaintenanceModalTitle').html(response['waterSystem'].name);
                        $('#englishNameAgent').html(response['waterSystem'].name);

                        $("#agentIcon").removeClass('bx bx-user bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-building bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-cuboid bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-analyse bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#agentIcon").addClass('bx bx-droplet bx-sm me-3');
                    }  else if(response['turbine']) {

                        $('#allMaintenanceModalTitle').html(response['turbine'].name);
                        $('#englishNameAgent').html(response['turbine'].name);

                        $("#agentIcon").removeClass('bx bx-user bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-building bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-analyse bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#agentIcon").addClass('bx bx-cuboid bx-sm me-3');
                    }  else if(response['generator']) {

                        $('#allMaintenanceModalTitle').html(response['generator'].name);
                        $('#englishNameAgent').html(response['generator'].name);

                        $("#agentIcon").removeClass('bx bx-user bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-building bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-cuboid bx-sm me-3');
                        $("#agentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#agentIcon").addClass('bx bx-analyse bx-sm me-3');
                    } 

                    $('#departamentMaintenance').html('');
                    $("#maintenanceAction").html(" ");
                    if(response['serviceType'].id == 1) {

                        if(response['energyActions']) {

                            for (var i = 0; i < response['energyActions'].length; i++) {
                            $("#maintenanceAction").append(

                                '<ul><li>'+ response['energyActions'][i].category_english_name + ' - ' + response['energyActions'][i].action_english_name + ' - ' + response['energyActions'][i].issue_english_name +'</li> </ul>');
                            }
                        } 
                        $("#departamentIcon").removeClass('bx bx-droplet bx-sm me-3');
                        $("#departamentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#departamentIcon").addClass('bx bx-bulb bx-sm me-3');
                    } else if(response['serviceType'].id == 2) {

                        if(response['waterActions']) {

                            for (var i = 0; i < response['waterActions'].length; i++) {
                            $("#maintenanceAction").append(

                                '<ul><li>'+ response['waterActions'][i].category_english_name + ' - ' + response['waterActions'][i].action_english_name + ' - ' + response['waterActions'][i].issue_english_name +'</li> </ul>');
                            }
                        } 
                        $("#departamentIcon").removeClass('bx bx-wifi bx-sm me-3');
                        $("#departamentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#departamentIcon").addClass('bx bx-droplet bx-sm me-3');
                    }  else if(response['serviceType'].id == 3) {

                        if(response['internetActions']) {

                            for (var i = 0; i < response['internetActions'].length; i++) {
                            $("#maintenanceAction").append(

                                '<ul><li>'+ response['internetActions'][i].category_english_name + ' - ' + response['internetActions'][i].action_english_name + ' - ' + response['internetActions'][i].issue_english_name +'</li> </ul>');
                            }
                        } 
                        $("#departamentIcon").removeClass('bx bx-droplet bx-sm me-3');
                        $("#departamentIcon").removeClass('bx bx-bulb bx-sm me-3');
                        $("#departamentIcon").addClass('bx bx-wifi bx-sm me-3');
                    } 

                    $('#departamentMaintenance').html(response['serviceType'].service_name);

                    $('#meterNumberAgent').html('');
                    $('#meterNumberAgent').html(response['allMaintenance'].meter_number);
                    $('#startDateMaintenance').html('');
                    $('#startDateMaintenance').html(response['allMaintenance'].start_date);
                    $('#completedDate').html('');
                    $('#completedDate').html(response['allMaintenance'].completed_date);

                    $('#lastHourGenerator').html('');
                    if(response['allMaintenance'].last_hour) $('#lastHourGenerator').html(response['allMaintenance'].last_hour);
                    $('#runHourGenerator').html('');
                    if(response['allMaintenance'].run_hour)$('#runHourGenerator').html(response['allMaintenance'].run_hour);
                    $('#runPerformedHourGenerator').html('');
                    if(response['allMaintenance'].run_performed_hour)$('#runPerformedHourGenerator').html(response['allMaintenance'].run_performed_hour);

                    $('#maintenanceType').html('');
                    $('#maintenanceType').html(response['type'].type);

                    $('#maintenanceStatus').html('');
                    $('#maintenanceStatus').html(response['status'].name);

                    $('#maintenanceNotes').html('');
                    $('#maintenanceNotes').html(response['allMaintenance'].notes);

                    $('#userReceipent').html('');
                    if(response['user']) $('#userReceipent').html(response['user'].name);
                }
            });
        });

        // Get all Contract Holders
        $('#getLatestTickets').on('click', function() {

            // AJAX request
            $.ajax({
                url: 'api/ticket',
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'Tickets Gotten Successfully!',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: 'Okay!' 
                    }).then((result) => {

                        $('#allMaintenanceTable').DataTable().draw();
                    });
                }
            });
        });

    });
</script>
@endsection