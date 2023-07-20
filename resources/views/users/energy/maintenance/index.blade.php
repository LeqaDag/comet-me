@extends('layouts/layoutMaster')

@section('title', 'electricity maintenance')

@include('layouts.all')

@section('content')

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
        <div class="card-body"> 
            <div class="card-header">
                <form method="POST" enctype='multipart/form-data' 
                    action="{{ route('energy-maintenance.export') }}">
                    @csrf
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
                                <input type="date" name="date" 
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
                </form>


                @if(Auth::guard('user')->user()->user_type_id == 1 ||
                    Auth::guard('user')->user()->user_type_id == 2 ||
                    Auth::guard('user')->user()->user_type_id == 4 ||
                    Auth::guard('user')->user()->user_type_id == 7 )
                    <div style="margin-top:18px">
                        <button type="button" class="btn btn-success" 
                            data-bs-toggle="modal" data-bs-target="#createMaintenanceLogElectricity">
                            Create New Maintenance Call	
                        </button>
                        @include('users.energy.maintenance.create')
                    </div>
                @endif
            </div>
            <table id="maintenanceEnergyTable" class="table table-striped data-table-energy-maintenance my-2">
                <thead>
                    <tr>
                        <th class="text-center">MG System</th>
                        <th class="text-center">Household</th>
                        <th class="text-center">Public Structure</th>
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

<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function () {

        var table = $('.data-table-energy-maintenance').DataTable({
            dom: "Blfrtip",
            buttons: [
                {
                    text: 'csv',
                    extend: 'csvHtml5',
                },
                {
                    text: 'excel',
                    extend: 'excelHtml5',
                },
                {
                    text: 'pdf',
                    extend: 'pdfHtml5',
                },
                {
                    text: 'print',
                    extend: 'print',
                },  
            ],
            columnDefs: [{
                orderable: true,
                targets: -1
            }],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('energy-maintenance.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'energy_name', name: 'energy_name'},
                {data: 'english_name', name: 'english_name'},
                {data: 'public_name', name: 'public_name'},
                {data: 'community_name', name: 'community_name'},
                {data: 'user_name', name: 'user_name'},
               // {data: 'maintenance_action_electricity', name: 'maintenance_action_electricity'},
                {data: 'name', name: 'name'},
                {data: 'action'},
            ]
        });
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

                 } else if(response['public']) {

                    $('#energyModalTitle').html(response['public'].english_name);
                    $('#englishNameUser').html(response['public'].english_name);
                } else if(response['energySystem']) {

                    $('#energyModalTitle').html(response['energySystem'].name);
                    $('#englishNameUser').html(response['energySystem'].name);
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
                $('#maintenanceAction').html(response['energyAction'].maintenance_action_electricity);

                $('#maintenanceStatus').html('');
                $('#maintenanceStatus').html(response['status'].name);

                $('#maintenanceNotes').html('');
                $('#maintenanceNotes').html(response['energyMaintenance'].notes);
            }
        });
    });
</script>
@endsection