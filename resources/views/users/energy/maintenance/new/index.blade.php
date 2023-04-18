@extends('layouts/layoutMaster')

@section('title', 'new electricity maintenance')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> New Electricity Maintenance 
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('users.energy.maintenance.new.show')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div class="card-header">
                <div>
                    <a class="btn btn-info" href="{{ route('new-energy-maintenance.export') }}">
                        <i class='fa-solid fa-file-excel'></i>
                        Export Excel
                    </a>

                    <button type="button" class="btn btn-success"  
                        data-bs-toggle="modal" data-bs-target="#createMaintenanceLogNewElectricity">
                        Create New Maintenance Call	
                    </button>
                @include('users.energy.maintenance.new.create')
                </div>
            </div>
            <table id="maintenanceNewEnergyTable" class="table table-striped data-table-new-energy-maintenance my-2">
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

        var table = $('.data-table-new-energy-maintenance').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('new-energy-maintenance.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'public_name', name: 'public_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'user_name', name: 'user_name'},
                {data: 'maintenance_action_new_electricity', name: 'maintenance_action_new_electricity'},
                {data: 'name', name: 'name'},
                {data: 'action'},
            ]
        });
    });

    // Delete record
    $('#maintenanceNewEnergyTable').on('click', '.deleteNewEnergyMaintenance',function() {
        var id = $(this).data('id');

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Maintenance?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => { 

            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteNewMaintenanceEnergy') }}",
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
                                $('#maintenanceNewEnergyTable').DataTable().draw();
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
    $('#maintenanceNewEnergyTable').on('click', '.viewNewEnergyMaintenance',function() {
        var id = $(this).data('id');
    
        // AJAX request
        $.ajax({
            url: 'new-energy-maintenance/' + id,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                $('#energyModalTitle').html('');
                $('#englishNameUser').html('');
                if(response['household']) {

                    $('#energyModalTitle').html(response['household'].english_name);
                    $('#englishNameUser').html(response['household'].english_name);

                } else if(response['public']) {

                    $('#energyModalTitle').html(response['public'].english_name);
                    $('#englishNameUser').html(response['public'].english_name);
                }

                $('#communityUser').html('');
                $('#communityUser').html(response['community'].english_name);

                $('#callDate').html('');
                $('#callDate').html(response['energyMaintenance'].date_of_call);
                $('#completedDate').html('');
                $('#completedDate').html(response['energyMaintenance'].date_completed);

                $('#userReceipent').html('');
                $('#userReceipent').html(response['user'].name);
                $('#maintenanceType').html('');
                $('#maintenanceType').html(response['type'].type);
                $('#maintenanceAction').html('');
                $('#maintenanceAction').html(response['energyAction'].maintenance_action_new_electricity);

                $('#maintenanceStatus').html('');
                $('#maintenanceStatus').html(response['status'].name);
            }
        });
    });
</script>
@endsection