@extends('layouts/layoutMaster')

@section('title', 'electricity maintenance')

@include('layouts.all')

@section('content')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseEnergymaintenanceExport" aria-expanded="false" 
        aria-controls="collapseEnergymaintenanceExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseEnergymaintenanceExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Electricity Maintenance Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearEnergyMaintenanceFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('energy-maintenance.export') }}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
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
                                        <select name="public" class="selectpicker form-control" data-live-search="true">
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
                                        <select name="issue" class="selectpicker form-control" data-live-search="true">
                                            <option disabled selected>Search Issue</option>
                                            @foreach($energyIssues as $energyIssue)
                                            <option value="{{$energyIssue->id}}">
                                                {{$energyIssue->english_name}}
                                            </option>
                                            @endforeach
                                        </select> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <input type="date" name="date" id="energyMaintenanceDate"
                                        class="form-control" title="Completed Data from"> 
                                    </fieldset>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3" style="margin-top:12px">
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
</div> 

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Electricity Maintenance
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('users.energy.maintenance.show')

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
                        <label class='col-md-12 control-label'>Filter By Public</label>
                        <select name="public" class="selectpicker form-control" 
                            data-live-search="true" id="filterByPublic">
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
                        <label class='col-md-12 control-label'>Filter By Issue</label>
                        <select id="filterByIssue" class="selectpicker form-control" 
                            data-live-search="true">
                            <option disabled selected>Search Issue</option>
                            @foreach($energyIssues as $energyIssue)
                            <option value="{{$energyIssue->id}}">
                                {{$energyIssue->english_name}}
                            </option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Completed Date from</label>
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
        <div class="card-header">
            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2 ||
                Auth::guard('user')->user()->user_type_id == 4 ||
                Auth::guard('user')->user()->user_type_id == 7 )
                <div style="margin-top:18px">
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createMaintenanceLogElectricity">
                        Create New Maintenancne Call	
                    </button>
                    @include('users.energy.maintenance.create')
                </div>
                <hr> 
                <div>
                    <form action="{{route('energy-maintenance.import')}}" method="POST" 
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-xl-5 col-lg-5 col-md-5">
                                <fieldset class="form-group">
                                    <input name="file" type="file"
                                        class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <button class="btn btn-success" type="submit">Import File</button>
                            </div>
 
                        </div>
                    </form>
                <div>
            @endif
        </div>
        <div class="card-body"> 
            <table id="maintenanceEnergyTable" class="table table-striped data-table-energy-maintenance my-2">
                <thead>
                    <tr>
                        <th class="text-center">Energy Agent</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Recipient</th>
                        <!--<th class="text-center">Action</th>-->
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

<script type="text/javascript">

    var table;
    function DataTableContent() {
        table = $('.data-table-energy-maintenance').DataTable({
            dom: "Blfrtip",
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-maintenance.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.community_filter = $('#filterByCommunity').val();
                    d.public_filter = $('#filterByPublic').val();
                    d.date_filter = $('#filterByDateFrom').val();
                    d.issue_filter = $('#filterByIssue').val();
                }
            },
            columns: [
                {data: 'holder'},
                {data: 'community_name', name: 'community_name'},
                {data: 'user_name', name: 'user_name'},
               // {data: 'maintenance_action_electricity', name: 'maintenance_action_electricity'},
                {data: 'name', name: 'name'},
                {data: 'action'},
            ]
        });
    }

    $(function () {

        DataTableContent();

        $('#filterByPublic').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByDateFrom').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByCommunity').on('change', function() {
            table.ajax.reload(); 
        });

        $('#filterByIssue').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByDateFrom').val(' ');
            if ($.fn.DataTable.isDataTable('.data-table-energy-maintenance')) {
                $('.data-table-energy-maintenance').DataTable().destroy();
            }
            DataTableContent();
        });
    });
        
    // Clear Filters for Export
    $('#clearEnergyMaintenanceFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
        $('#energyMaintenanceDate').val(' ');
    });

    // View update
    $('#maintenanceEnergyTable').on('click', '.updateEnergyMaintenance',function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        
        url = url +'/'+ id +'/edit';
        window.open(url, "_self"); 
    });

    // Delete record
    $('#maintenanceEnergyTable').on('click', '.deleteEnergyMaintenance',function() {
        var id = $(this).data('id');

        Swal.fire({ 
            icon: 'warning',
            title: 'Are you sure you want to delete this Maintenance?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {

            if(result.isConfirmed) {
                $.ajax({ 
                    url: "{{ route('deleteMaintenanceEnergy') }}",
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
                                $('#maintenanceEnergyTable').DataTable().draw();
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

    // View record details
    $('#maintenanceEnergyTable').on('click', '.viewEnergyMaintenance',function() {
        var id = $(this).data('id');
    
        // AJAX request
        $.ajax({
            url: 'energy-maintenance/' + id,
            type: 'get',
            dataType: 'json', 
            success: function(response) {
                $('#energyModalTitle').html('');
                $('#englishNameUser').html('');
                if(response['household']) {

                    $('#energyModalTitle').html(response['household'].english_name);
                    $('#englishNameUser').html(response['household'].english_name);

                    $("#energyHolderIcon").removeClass('bx bx-building bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-bulb bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-cuboid bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-analyse bx-sm me-3');
                    $("#energyHolderIcon").addClass('bx bx-user bx-sm me-3');
                 } else if(response['public']) {

                    $('#energyModalTitle').html(response['public'].english_name);
                    $('#englishNameUser').html(response['public'].english_name);

                    $("#energyHolderIcon").removeClass('bx bx-user bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-bulb bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-cuboid bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-analyse bx-sm me-3');
                    $("#energyHolderIcon").addClass('bx bx-building bx-sm me-3');
                } else if(response['energySystem']) {

                    $('#energyModalTitle').html(response['energySystem'].name);
                    $('#englishNameUser').html(response['energySystem'].name);

                    $("#energyHolderIcon").removeClass('bx bx-user bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-building bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-cuboid bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-analyse bx-sm me-3');
                    $("#energyHolderIcon").addClass('bx bx-bulb bx-sm me-3');
                } else if(response['turbine']) {

                    $('#energyModalTitle').html(response['turbine'].name);
                    $('#englishNameUser').html(response['turbine'].name);

                    $("#energyHolderIcon").removeClass('bx bx-user bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-building bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-bulb bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-analyse bx-sm me-3');
                    $("#energyHolderIcon").addClass('bx bx-cuboid bx-sm me-3');
                } else if(response['generator']) {

                    $('#energyModalTitle').html(response['generator'].name);
                    $('#englishNameUser').html(response['generator'].name);

                    $("#energyHolderIcon").removeClass('bx bx-user bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-building bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-bulb bx-sm me-3');
                    $("#energyHolderIcon").removeClass('bx bx-cuboid bx-sm me-3');
                    $("#energyHolderIcon").addClass('bx bx-analyse bx-sm me-3');
                }

                $('#communityUser').html('');
                $('#communityUser').html(response['community'].english_name);

                $('#callDate').html('');
                $('#callDate').html(response['energyMaintenance'].date_of_call);
                $('#visitDateMaintenance').html('');
                $('#visitDateMaintenance').html(response['energyMaintenance'].visit_date);
                $('#completedDate').html('');
                $('#completedDate').html(response['energyMaintenance'].date_completed);
                
                $('#lastHourGenerator').html('');
                $('#lastHourGenerator').html(response['energyMaintenance'].last_hour);
                $('#runHourGenerator').html('');
                $('#runHourGenerator').html(response['energyMaintenance'].run_hour);
                $('#runPerformedHourGenerator').html('');
                $('#runPerformedHourGenerator').html(response['energyMaintenance'].run_performed_hour);

                $('#userReceipent').html('');
                $('#userReceipent').html(response['user'].name);
                $('#maintenanceType').html('');
                $('#maintenanceType').html(response['type'].type);
 
                $('#maintenanceStatus').html('');
                $('#maintenanceStatus').html(response['status'].name);

                $('#maintenanceNotes').html('');
                $('#maintenanceNotes').html(response['energyMaintenance'].notes);

                $("#maintenanceAction").html(" ");
                for (var i = 0; i < response['energyActions'].length; i++) {
                    $("#maintenanceAction").append(
                        '<ul><li>'+ response['energyActions'][i].arabic_name +'</li> </ul>');
                } 

                $("#maintenancePerformedBy").html(" ");
                for (var i = 0; i < response['performedUsers'].length; i++) {
                    $("#maintenancePerformedBy").append(
                        '<ul><li>'+ response['performedUsers'][i].name +'</li> </ul>');
                }
            }
        });
    });
</script>
@endsection