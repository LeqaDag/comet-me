@extends('layouts/layoutMaster')

@section('title', 'Action Items')

@include('layouts.all')

@section('content')

<style>
    .user-info {
        display: flex;
        align-items: center; 
    }

    .user-info img {
        margin-right: 10px; 
    }

    .user-name {
        font-size: 15px;  
    }
</style>

<p>
    <a class="btn btn-success" data-toggle="collapse" href="#collapsePlatformTasks" 
        role="button" aria-expanded="false" aria-controls="collapsePlatformTasks">
        <i class="menu-icon tf-icons bx bx-align-middle"></i>
        Platform Tasks
    </a>
    <button class="btn btn-success" type="button" data-toggle="collapse" 
        data-target="#collapseActionItem" aria-expanded="false" 
        aria-controls="collapseActionItem">
        <i class="menu-icon tf-icons bx bx-list-ul"></i>
        Your Action Items
    </button>
    <button class="btn btn-success" type="button" data-toggle="collapse" 
        data-target="#collapseExportReports" aria-expanded="false" 
        aria-controls="collapseExportReports">
        <i class="menu-icon tf-icons bx bx-bulb"></i>
        <i class="menu-icon tf-icons bx bx-droplet"></i>
        <i class="menu-icon tf-icons bx bx-wifi"></i>
        Service Progress Reports
    </button>
</p> 

<div class="collapse multi-collapse mb-4" id="collapsePlatformTasks">
    <div class="container mb-4">                 
        @if(count($groupedActionItems) > 0)
            @foreach($groupedActionItems as $userId => $userActionItems)
                @php
                    $currentUserTypeId = Auth::guard('user')->user()->user_type_id;
                    $userTypeId = $userActionItems->first()->User->user_type_id;
                @endphp

                @if(($currentUserTypeId == 3 && $userTypeId == 3) || 
                    ($currentUserTypeId == 4 && $userTypeId == 4) || 
                    ($currentUserTypeId == 6 && $userTypeId == 6) )
                    <div class="user-tasks">
                        <div class="pb-0">
                            @php
                                $userTypeId = Auth::guard('user')->user()->user_type_id;
                            @endphp

                            @if($userTypeId == 3) 
                            <div class="container mb-4">
                                @php
                                    $userTypeId = 3;
                                    $collapseId = 'energyWorkPlans';
                                    $includeView = 'actions.admin.installation.ac_dc_process';
                                    $flag = 0;
                                @endphp
                                @include('actions.admin.user_tasks')
                            </div>
                            @else @if($userTypeId == 4) 
                            <div class="container mb-4">
                                @php
                                    $userTypeId = 4;
                                    $collapseId = 'maintenanceEnergyWorkPlans';
                                    $includeView = 'actions.admin.energy.maintenance';
                                    $flag = 0;
                                @endphp
                                @include('actions.admin.user_tasks')
                            </div>
                            @else @if($userTypeId == 6) 
                            <div class="container mb-4">
                                @php
                                    $userTypeId = 6;
                                    $collapseId = 'internetWorkPlans';
                                    $includeView = 'actions.admin.internet.index';
                                    $flag = 0;
                                @endphp
                                @include('actions.admin.user_tasks')
                            </div>
                            @endif
                            @endif
                            @endif
                        </div>
                    </div>
                @endif 
            @endforeach
        @endif
    </div>
</div>

<div class="collapse multi-collapse mb-4" id="collapseActionItem">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">All </span> Action Items
    </h4>
    @if(session()->has('message'))
        <div class="row">
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        </div>
    @endif

    @include('actions.users.action.show')

    <div class="container">
        <div class="card my-2">
            <div class="card-header">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Filter By Status</label>
                            <select name="action_status_id" class="selectpicker form-control" 
                                data-live-search="true" id="filterByStatus">
                                <option disabled selected>Choose one...</option>
                                @foreach($actionStatuses as $actionStatus)
                                    <option value="{{$actionStatus->id}}">{{$actionStatus->status}}</option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Filter By Priority</label>
                            <select name="action_priority_id" class="selectpicker form-control" 
                                data-live-search="true" id="filterByPriority">
                                <option disabled selected>Choose one...</option>
                                @foreach($actionPriorities as $actionPriority)
                                    <option value="{{$actionPriority->id}}">{{$actionPriority->name}}</option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Filter By Start Date</label>
                            <input type="date" name="date" class="form-control" id="filterByStartDate">
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Filter By End Date</label>
                            <input type="date" name="due_date" class="form-control" id="filterByEndDate">
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
                <div>
                    <button type="button" class="btn btn-success btn-sm" 
                        data-bs-toggle="modal" data-bs-target="#createUserActionItem">
                        <i class="bx bx-plus"></i>
                        Add New Action Item
                    </button>
                    @include('actions.users.create_task')
                </div> 
                <table id="actionItemUserTable" class="table table-striped data-table-action-item-users my-2">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Task Creation Date</th>
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="collapse multi-collapse mb-4" id="collapseExportReports">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
        <span class="text-muted fw-light">All </span> Service Progress Reports
    </h4>

    <div class="container" >
        <h6 class="py-3 breadcrumb-wrapper">
            <a data-toggle="collapse" class="text-warning" 
                href="#EnergyProjectFiles" 
                aria-expanded="false" 
                aria-controls="EnergyProjectFiles">
                <i class="bx bx-bulb bx-sm me-3"></i>
                Energy Project
            </a>
        </h6>
        
        <div class="row collapse multi-collapse container" id="EnergyProjectFiles">
            <div class="user-tasks">
                <div class="">
                    <form method="POST" enctype='multipart/form-data' id="EnergyProjectFileForm"
                        action="{{ route('energy-request.export') }}">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Export</label>
                                    <input type="text" class="form-control" disabled
                                    value="Energy Installation Progress Report"> 
                                </fieldset>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Cycle Year</label>
                                    <select name="energy_cycle_id" id="cycleYearSelected"
                                        class="selectpicker form-control" data-live-search="true">
                                        <option disabled selected>Search Cycle Year</option>
                                        @foreach($energyCycles as $energyCycle)
                                            <option value="{{$energyCycle->id}}">
                                                {{$energyCycle->name}}
                                            </option>
                                        @endforeach
                                    </select> 
                                </fieldset>
                                <div id="energy_cycle_id_error" style="color: red;"></div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4" >
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Click Here</label>
                                    <button class="form-control btn-warning" type="submit">
                                        <i class='fa-solid fa-file-excel'></i>
                                        Export Energy Installation Progress Report
                                    </button>
                                </fieldset>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <h6 class="py-3 breadcrumb-wrapper">
            <a data-toggle="collapse" class="text-success" 
                href="#InternetProjectFiles" 
                aria-expanded="false" 
                aria-controls="InternetProjectFiles">
                <i class="bx bx-wifi bx-sm me-3"></i>
                Internet Project
            </a>
        </h6> 

        <div class="row overflow-hidden collapse multi-collapse container" id="InternetProjectFiles">
            <div class=" mb-4">
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('internet-user.export') }}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Export</label>
                                <input type="text" class="form-control" disabled
                                value="Internet Metrics Report File"> 
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Click Here</label>
                                <button class="form-control btn-success" type="submit">
                                    <i class='fa-solid fa-file-excel'></i>
                                    Export Internet Metrics Report File
                                </button>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    $('#EnergyProjectFileForm').on('submit', function (event) {

        var englishValue = $('#cycleYearSelected').val();

        if (englishValue == null) {

            $('#energy_cycle_id_error').html('Please Select a cycle year!');
            return false;
        } else if (englishValue != null){

            $('#energy_cycle_id_error').empty();
        }

        $(this).addClass('was-validated');  
        $('#energy_cycle_id_error').empty();

        this.submit();
    });

    var tableUser;
    function DataTableContent() {

        tableUser = $('.data-table-action-item-users').DataTable({
            
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('action-item-user.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.status_filter = $('#filterByStatus').val();
                    d.priority_filter = $('#filterByPriority').val();
                    d.start_date_filter = $('#filterByStartDate').val();
                    d.end_date_filter = $('#filterByEndDate').val();
                }
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1,2,3,4,5] // Column index which needs to export
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [0,5] // Column index which needs to export
                    }
                },
                {
                    extend: 'excel',
                }
            ],
            columns: [
                {data: 'task', name: 'task'},
                {data: 'owner', name: 'owner'},
                {data: 'statusLabel'},
                {data: 'priorityLabel'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action'}
            ]
            
        });
    }

    $(function () {

        DataTableContent();
        
        $('#filterByStatus').on('change', function() {
            tableUser.ajax.reload(); 
        });
        $('#filterByPriority').on('change', function() {
            tableUser.ajax.reload(); 
        });
        $('#filterByStartDate').on('input', function() {
            tableUser.ajax.reload(); 
        });
        $('#filterByEndDate').on('input', function() {
            tableUser.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-action-item-users')) {
                tableUser.destroy();
            }
            $('#filterByStartDate').val(' ');
            $('#filterByEndDate').val(' ');

            DataTableContent();
        });

        // View record details
        $('#actionItemUserTable').on('click', '.detailsUserActionItemButton',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'work-plan/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $('#actionItemUserModalTitle').html(" ");

                    $('#actionOwner').html(" ");
                    $('#ownerRole').html(" ");
                    $('#actionStatus').html(" ");
                    $('#actionPriority').html(" ");
                    $('#actionDate').html(" ");
                    $('#actionDueDate').html(" ");
                    $('#actionNotes').html(" ");

                    $('#actionItemUserModalTitle').html(response['actionItem'].task);
                    $('#actionOwner').html(response['user'].name);
                    $('#ownerRole').html(response['userType'].name);
                    $('#actionStatus').html(response['status'].status);
                    $('#actionPriority').html(response['priority'].name);
                    $('#actionDate').html(response['actionItem'].date);
                    $('#actionDueDate').html(response['actionItem'].due_date);
                    $('#actionNotes').html(response['actionItem'].notes);
                }
            });
        });

        // Update record
        $('#actionItemUserTable').on('click', '.updateUserActionItem',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // Delete record
        $('#actionItemUserTable').on('click', '.deleteUserActionItem',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Action Item?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteUserActionItem') }}",
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
                                    $('#actionItemUserTable').DataTable().draw();
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