@extends('layouts/layoutMaster')

@section('title', 'refrigerator maintenance')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> Refrigerator Maintenance 
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('users.refrigerator.maintenance.show')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div class="card-header">
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('refrigerator-maintenance.export') }}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <fieldset class="form-group">
                                <select name="community_id"
                                    class="form-control">
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
                                <select name="public" class="form-control">
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
                                <input type="date" name="call_date" 
                                class="form-control" title="Call Data from"> 
                            </fieldset>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3">
                            <fieldset class="form-group">
                                <input type="date" name="date" 
                                class="form-control" title="Completed Data from"> 
                            </fieldset>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3" style="margin-top:18px">
                            <fieldset class="form-group">
                                <button class="btn btn-info" type="submit">
                                    <i class='fa-solid fa-file-excel'></i>
                                    Export Excel
                                </button>
                            </fieldset>
                        </div>
                    </div>
                </form>
                <div style="margin-top:18px">
                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createMaintenanceLogRefrigerator">
                        Create New Maintenancne Call	
                    </button>
                    @include('users.refrigerator.maintenance.create')
                </div>
            </div>

            <table id="maintenanceRefrigeratorTable" class="table table-striped data-table-refrigerator-maintenance my-2">
                <thead>
                    <tr>
                        <th class="text-center">Household</th>
                        <th class="text-center">Public Structure</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Recipient</th>
                        <th class="text-center">Action</th>
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
    $(function () {

        var table = $('.data-table-refrigerator-maintenance').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('refrigerator-maintenance.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'public_name', name: 'public_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'user_name', name: 'user_name'},
                {data: 'maintenance_action_refrigerator', name: 'maintenance_action_refrigerator'},
                {data: 'name', name: 'name'},
                {data: 'action'},
            ]
        });
    });

    // Delete record
    $('#maintenanceRefrigeratorTable').on('click', '.deleteRefrigeratorMaintenance',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Maintenance?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {

            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteRefrigerator') }}",
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
                                $('#maintenanceRefrigeratorTable').DataTable().draw();
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
    $('#maintenanceRefrigeratorTable').on('click', '.viewRefrigeratorMaintenance',function() {
        var id = $(this).data('id');
    
        // AJAX request
        $.ajax({
            url: 'refrigerator-maintenance/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                $('#refrigeratorModalTitle').html('');
                $('#englishNameUser').html('');
                if(response['household']) {

                    $('#refrigeratorModalTitle').html(response['household'].english_name);
                    $('#englishNameUser').html(response['household'].english_name);

                } else if(response['public']) {

                    $('#refrigeratorModalTitle').html(response['public'].english_name);
                    $('#englishNameUser').html(response['public'].english_name);
                }

                $('#communityUser').html('');
                $('#communityUser').html(response['community'].english_name);

                $('#callDate').html('');
                $('#callDate').html(response['refrigeratorMaintenance'].date_of_call);
                $('#completedDate').html('');
                $('#completedDate').html(response['refrigeratorMaintenance'].date_completed);

                $('#userReceipent').html('');
                $('#userReceipent').html(response['user'].name);
                $('#maintenanceType').html('');
                $('#maintenanceType').html(response['type'].type);
                $('#maintenanceAction').html('');
                $('#maintenanceAction').html(response['refrigeratorAction'].maintenance_action_refrigerator);

                $('#maintenanceStatus').html('');
                $('#maintenanceStatus').html(response['status'].name);

            }
        });
    });
</script>
@endsection