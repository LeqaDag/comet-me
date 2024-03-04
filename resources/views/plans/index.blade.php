@extends('layouts/layoutMaster')

@section('title', 'Work Plans')

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
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Work Plans
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('plans.show')
<div class="container">
    <div class="card my-2">
        <div class="card-header">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Filter By Owner</label>
                        <select name="user_id" class="selectpicker form-control" 
                            data-live-search="true" id="filterByUser">
                            <option disabled selected>Choose one...</option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select> 
                    </fieldset>
                </div>
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
            @if(Auth::guard('user')->user()->user_type_id == 1 ||  
                Auth::guard('user')->user()->user_type_id == 2)
                <div>
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createWorkPlan">
                        Add New Action Item
                    </button>
                    @include('plans.create')
                </div> 
            @endif
            <table id="workPlanTable" class="table table-striped data-table-work-plans my-2">
                <thead>
                    <tr>
                        <th class="text-center">Task</th>
                        <th class="text-center">Owner</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Priority</th>
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

        table = $('.data-table-work-plans').DataTable({
            
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('work-plan.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.user_filter = $('#filterByUser').val();
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
                { data: 'image', name: 'image',
                    render: function(data, type, full, meta) {
                        var imageSrc = data ? '/users/profile/' + data : '/default-image-path'; // Replace '/default-image-path' with the default image path if 'data' is null or empty.
                        var userName = full.name;

                        return "<div class='user-info'><img class='rounded-circle' src='" + 
                            imageSrc + "' height='50' alt='User Image'/><span class='user-name'>" + 
                            userName + "</span></div>";
                    }
                },
                {data: 'statusLabel'},
                {data: 'priorityLabel'},
                {data: 'action'}
            ]
            
        });
    }

    $(function () {

        DataTableContent();
        
        $('#filterByUser').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByStatus').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByPriority').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByStartDate').on('input', function() {
            table.ajax.reload(); 
        });
        $('#filterByEndDate').on('input', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-work-plans')) {
                $('.data-table-work-plans').DataTable().destroy();
            }
            $('#filterByStartDate').val(' ');
            $('#filterByEndDate').val(' ');

            DataTableContent();
        });

        // View record details
        $('#workPlanTable').on('click', '.detailsWorkPlanButton',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'work-plan/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $('#workPlanModalTitle').html(" ");

                    $('#actionOwner').html(" ");
                    $('#ownerRole').html(" ");
                    $('#actionStatus').html(" ");
                    $('#actionPriority').html(" ");
                    $('#actionDate').html(" ");
                    $('#actionDueDate').html(" ");
                    $('#actionNotes').html(" ");

                    $('#workPlanModalTitle').html(response['actionItem'].task);
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

        // View record photos
        $('#workPlanTable').on('click', '.updateWorkPlan',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // Delete record
        $('#workPlanTable').on('click', '.deleteWorkPlan',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Action Item?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteWorkPlan') }}",
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
                                    $('#workPlanTable').DataTable().draw();
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