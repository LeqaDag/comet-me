@extends('layouts/layoutMaster')

@section('title', 'water maintenance')

@include('layouts.all')

@section('content')

<p>
    <button class="btn btn-primary" type="button" data-toggle="collapse" 
        data-target="#collapseWatermaintenanceExport" aria-expanded="false" 
        aria-controls="collapseWatermaintenanceExport">
        <i class="menu-icon tf-icons bx bx-export"></i>
        Export Data
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapseWatermaintenanceExport">
    <div class="container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <h5>
                                Export Water Maintenance Report
                                    <i class='fa-solid fa-file-excel text-info'></i>
                                </h5>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2">
                                <fieldset class="form-group">
                                    <button class="" id="clearWaterMaintenanceFiltersButton">
                                    <i class='fa-solid fa-eraser'></i>
                                        Clear Filters
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <form method="POST" enctype='multipart/form-data' 
                        action="{{ route('water-maintenance.export') }}">
                        @csrf
                        <div class="card-body"> 
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3">
                                    <fieldset class="form-group">
                                        <select name="community_id" class="selectpicker form-control" 
                                            data-live-search="true">
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
                                        <input type="date" name="date" id="waterMaintenanceDate"
                                        class="form-control" title="Completed Data from"> 
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
</div> 

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Water Maintenance 
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('users.water.maintenance.show')

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
        <div class="card-body">
            <div class="card-header">
                @if(Auth::guard('user')->user()->user_type_id == 1 ||
                    Auth::guard('user')->user()->user_type_id == 2 ||
                    Auth::guard('user')->user()->user_type_id == 5 ||
                    Auth::guard('user')->user()->user_type_id == 11 )
                    <div style="margin-top:18px">
                        <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#createMaintenanceLogWater">
                            Create New Maintenancne Call	
                        </button>
                        @include('users.water.maintenance.create')
                    </div>
                @endif
            </div>

            <table id="maintenanceWaterTable" class="table table-striped data-table-water-maintenance my-2">
                <thead>
                    <tr>
                        <th class="text-center">Water Holder</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Recipient</th>
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

        table = $('.data-table-water-maintenance').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('water-maintenance.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.community_filter = $('#filterByCommunity').val();
                    d.public_filter = $('#filterByPublic').val();
                    d.date_filter = $('#filterByDateFrom').val();
                }
            },
            columns: [
                {data: 'holder'},
                {data: 'community_name', name: 'community_name'},
                {data: 'user_name', name: 'user_name'},
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

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            $('#filterByDateFrom').val(' ');
            if ($.fn.DataTable.isDataTable('.data-table-water-maintenance')) {
                $('.data-table-water-maintenance').DataTable().destroy();
            }
            DataTableContent();
        });
    });

    // Clear Filters for Export
    $('#clearWaterMaintenanceFiltersButton').on('click', function() {

        $('.selectpicker').prop('selectedIndex', 0);
        $('.selectpicker').selectpicker('refresh');
        $('#waterMaintenanceDate').val(' ');
    });

    // Delete record
    $('#maintenanceWaterTable').on('click', '.deleteWaterMaintenance',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Maintenance?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {

            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteMaintenanceWater') }}",
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
                                $('#maintenanceWaterTable').DataTable().draw();
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

    // View update
    $('#maintenanceWaterTable').on('click', '.updateWaterMaintenance',function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        
        url = url +'/'+ id +'/edit';
        window.open(url, "_self"); 
    });

    // View record details
    $('#maintenanceWaterTable').on('click','.viewWaterMaintenance',function() {
        var id = $(this).data('id');
    
        // AJAX request
        $.ajax({
            url: 'water-maintenance/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                $('#WaterModalTitle').html('');
                $('#englishNameUser').html('');
                if(response['household']) {

                    $("#holderIcon").removeClass('bx bx-building bx-sm me-3');
                    $("#holderIcon").addClass('bx bx-user bx-sm me-3');

                    $('#WaterModalTitle').html(response['household'].english_name);
                    $('#englishNameUser').html(response['household'].english_name);

                } else if(response['public']) {

                    $("#holderIcon").removeClass('bx bx-user bx-sm me-3');
                    $("#holderIcon").addClass('bx bx-building bx-sm me-3');

                    $('#WaterModalTitle').html(response['public'].english_name);
                    $('#englishNameUser').html(response['public'].english_name);
                }

                $('#communityUser').html('');
                $('#communityUser').html(response['community'].english_name);

                $('#callDate').html('');
                $('#callDate').html(response['h2oMaintenance'].date_of_call);
                $('#visitDate').html('');
                $('#visitDate').html(response['h2oMaintenance'].visit_date);
                $('#completedDate').html('');
                $('#completedDate').html(response['h2oMaintenance'].date_completed);

                $('#userReceipent').html('');
                $('#userReceipent').html(response['user'].name);
                $('#maintenanceType').html('');
                $('#maintenanceType').html(response['type'].type);

                $("#maintenanceAction").html(" ");
                for (var i = 0; i < response['h2oAction'].length; i++) {
                    $("#maintenanceAction").append(
                        '<ul><li>'+ response['h2oAction'][i].maintenance_action_h2o +'</li> </ul>');
                } 
                
                $("#maintenancePerformedBy").html(" ");
                for (var i = 0; i < response['performedUsers'].length; i++) {
                    $("#maintenancePerformedBy").append(
                        '<ul><li>'+ response['performedUsers'][i].name +'</li> </ul>');
                } 

                $('#maintenanceStatus').html('');
                $('#maintenanceStatus').html(response['status'].name);

                $('#maintenanceNotes').html('');
                $('#maintenanceNotes').html(response['h2oMaintenance'].notes);
            }
        });
    });
</script>
@endsection