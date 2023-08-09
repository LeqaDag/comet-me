@extends('layouts/layoutMaster')

@section('title', 'edit energy maintenance log')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }

    .dropdown-toggle {
        height: 40px;
    }

    label {
        margin-top: 20px;
    } 
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> 
    @if($energyMaintenance->household_id)

        {{$energyMaintenance->Household->english_name}}
    @else @if($energyMaintenance->energy_system_id)

        {{$energyMaintenance->EnergySystem->name}}
    @else @if($energyMaintenance->public_structure_id)

        {{$energyMaintenance->PublicStructure->english_name}}
    @endif
    @endif
    @endif
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('energy-maintenance.update', $energyMaintenance->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="community_id" disabled>
                                @if($energyMaintenance->community_id)
                                    <option value="{{$energyMaintenance->community_id}}">
                                        {{$energyMaintenance->Community->english_name}}
                                    </option>
                                @endif                                
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                        @if($energyMaintenance->household_id)

                            <label class='col-md-12 control-label'>Energy User</label>
                            <input type="text" value="{{$energyMaintenance->Household->english_name}}" 
                                class="form-control" disabled>
                            
                        @else @if($energyMaintenance->energy_system_id)

                            <label class='col-md-12 control-label'>Energy System</label>
                            <input type="text" value="{{$energyMaintenance->EnergySystem->name}}" 
                                class="form-control" disabled>
                        @else @if($energyMaintenance->public_structure_id)

                            <label class='col-md-12 control-label'>Energy Public</label>
                            <input type="text" value="{{$energyMaintenance->PublicStructure->english_name}}" 
                                class="form-control" disabled>
                        @endif
                        @endif
                        @endif
                        </fieldset>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Date Of Call</label>
                            <input type="date" name="date_of_call" class="form-control" 
                                value="{{$energyMaintenance->date_of_call}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Completed Date</label>
                            <input type="date" name="date_completed" class="form-control"
                                value="{{$energyMaintenance->date_completed}}" >
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Type</label>
                            <select name="maintenance_type_id" class="form-control" required>
                                @if($energyMaintenance->maintenance_type_id)
                                    <option value="{{$energyMaintenance->maintenance_type_id}}">
                                        {{$energyMaintenance->MaintenanceType->type}}
                                    </option>
                                @endif 
                                @foreach($maintenanceTypes as $maintenanceType)
                                <option value="{{$maintenanceType->id}}">
                                    {{$maintenanceType->type}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Status</label>
                            <select name="maintenance_status_id" class="form-control" >
                                @if($energyMaintenance->maintenance_status_id)
                                    <option value="{{$energyMaintenance->maintenance_status_id}}">
                                        {{$energyMaintenance->MaintenanceStatus->name}}
                                    </option>
                                @endif 
                                
                                @foreach($maintenanceStatuses as $maintenanceStatus)
                                <option value="{{$maintenanceStatus->id}}">
                                    {{$maintenanceStatus->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Recipient</label>
                            <select name="user_id" class="form-control">
                                @if($energyMaintenance->user_id)
                                    <option value="{{$energyMaintenance->user_id}}">
                                        {{$energyMaintenance->User->name}}
                                    </option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">
                                            {{$user->name}}
                                        </option>
                                    @endforeach
                                @else
                                <option disabled selected>Choose one...</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">
                                        {{$user->name}}
                                    </option>
                                 @endforeach
                                @endif 
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$energyMaintenance->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>
                <hr>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 control-label'>Maintenance Electricity Action</label>
                    </div>
                </div>
                @if(count($energyActions) > 0)
                    <table id="energyActionsTable" 
                        class="table table-striped data-h2o-actions-donors my-2">  
                        <tbody>
                            @foreach($energyActions as $energyAction)
                            <tr id="energyActionRow">
                                <td class="text-center">
                                    {{$energyAction->maintenance_action_electricity}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteEnergyAction" 
                                        id="deleteEnergyAction" data-id="{{$energyAction->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add more actions</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="actions[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($maintenanceElectricityActions as $maintenanceElectricityAction)
                                        <option value="{{$maintenanceElectricityAction->id}}">
                                            {{$maintenanceElectricityAction->maintenance_action_electricity}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @else 
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add Actions</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_actions[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($maintenanceElectricityActions as $maintenanceElectricityAction)
                                        <option value="{{$maintenanceElectricityAction->id}}">
                                            {{$maintenanceElectricityAction->maintenance_action_electricity}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @endif

                <hr>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 control-label'>Performed By:</label>
                    </div>
                </div>
                @if(count($performedUsers) > 0)
                    <table id="performedEnergyUsersTable" 
                        class="table table-striped data-h2o-actions-donors my-2">  
                        <tbody>
                            @foreach($performedUsers as $performedUser)
                            <tr id="performedUserRow">
                                <td class="text-center">
                                    {{$performedUser->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deletePerformedEnergyUsers" 
                                        id="deletePerformedEnergyUsers" data-id="{{$performedUser->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add more performed users</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="users[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">
                                            {{$user->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @else 
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add performed users</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" name="new_users[]">
                                    <option selected disabled>Choose one...</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">
                                            {{$user->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @endif

                <div class="row" style="margin-top:20px">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <button type="submit" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>

    $(document).on('change', '#fbsSelectedCommuntiy', function () {

        community_id = $(this).val();
        $.ajax({
            url: "energy_user/get_by_community/" +  community_id,
            method: 'GET',
            success: function(data) {
                $('#energyUserSelectedFbs').prop('disabled', false);
                $('#energyUserSelectedFbs').html(data.html);
            }
        });
    });

    // delete action
    $('#energyActionsTable').on('click', '.deleteEnergyAction',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this action?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteEnergyAction') }}",
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
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

    // delete performed user
    $('#performedEnergyUsersTable').on('click', '.deletePerformedEnergyUsers',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this user?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deletePerformedEnergyUsers') }}",
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
                                $ele.fadeOut(1000, function () {
                                    $ele.remove();
                                });
                            });
                        } 
                    }
                });
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        });
    });

</script>

@endsection