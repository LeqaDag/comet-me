@extends('layouts/layoutMaster')

@section('title', 'water maintenance')

@include('layouts.all')

@section('content')


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
        <div class="card-body">
            <div class="card-header">
                <div>
                    <a class="btn btn-info" href="{{ route('water-maintenance.export') }}">
                        <i class='fa-solid fa-file-excel'></i>
                        Export Excel
                    </a>

                    <button type="button" class="btn btn-success" 
                        data-bs-toggle="modal" data-bs-target="#createMaintenanceLogWater">
                        Create New Maintenancne Call	
                    </button>
                    @include('users.water.maintenance.create')
                </div>
            </div>
            <table id="maintenanceWaterTable" class="table table-striped data-table-water-maintenance my-2">
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

        var table = $('.data-table-water-maintenance').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('water-maintenance.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'public_name', name: 'public_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'user_name', name: 'user_name'},
                {data: 'maintenance_action_h2o', name: 'maintenance_action_h2o'},
                {data: 'name', name: 'name'},
                {data: 'action'},
            ]
        });
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

                    $('#WaterModalTitle').html(response['household'].english_name);
                    $('#englishNameUser').html(response['household'].english_name);

                } else if(response['public']) {

                    $('#WaterModalTitle').html(response['public'].english_name);
                    $('#englishNameUser').html(response['public'].english_name);
                }

                $('#communityUser').html('');
                $('#communityUser').html(response['community'].english_name);

                $('#callDate').html('');
                $('#callDate').html(response['h2oMaintenance'].date_of_call);
                $('#completedDate').html('');
                $('#completedDate').html(response['h2oMaintenance'].date_completed);

                $('#userReceipent').html('');
                $('#userReceipent').html(response['user'].name);
                $('#maintenanceType').html('');
                $('#maintenanceType').html(response['type'].type);
                $('#maintenanceAction').html('');
                $('#maintenanceAction').html(response['h2oAction'].maintenance_action_h2o);

                $('#maintenanceStatus').html('');
                $('#maintenanceStatus').html(response['status'].name);
            }
        });
    });
</script>
@endsection