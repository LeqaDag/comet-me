@extends('layouts/layoutMaster')

@section('title', 'edit water maintenance log')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }


    label {
        margin-top: 20px;
    } 
</style>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> 
    @if($waterMaintenance->household_id)

        {{$waterMaintenance->Household->english_name}}
    @else @if($waterMaintenance->public_structure_id)

        {{$waterMaintenance->PublicStructure->english_name}}
    @else @if($waterMaintenance->water_system_id)

        {{$waterMaintenance->WaterSystem->name}}
    @endif
    @endif
    @endif
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('water-maintenance.update', $waterMaintenance->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="community_id" disabled>
                                @if($waterMaintenance->community_id)
                                    <option value="{{$waterMaintenance->community_id}}">
                                        {{$waterMaintenance->Community->english_name}}
                                    </option>
                                @endif                                
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group"> 
                        @if($waterMaintenance->household_id)

                            <label class='col-md-12 control-label'>Water User</label>
                            <input type="text" value="{{$waterMaintenance->Household->english_name}}" 
                                class="form-control" disabled>
                            
                        @else @if($waterMaintenance->public_structure_id)

                            <label class='col-md-12 control-label'>Water Public</label>
                            <input type="text" value="{{$waterMaintenance->PublicStructure->english_name}}" 
                                class="form-control" disabled>
                        @else @if($waterMaintenance->water_system_id)

                            <label class='col-md-12 control-label'>Water System</label>
                            <input type="text" value="{{$waterMaintenance->WaterSystem->name}}" 
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
                                value="{{$waterMaintenance->date_of_call}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Visit Date</label>
                            <input type="date" name="visit_date" class="form-control"
                                value="{{$waterMaintenance->visit_date}}" >
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Completed Date</label>
                            <input type="date" name="date_completed" class="form-control"
                                value="{{$waterMaintenance->date_completed}}" >
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Type</label>
                            <select name="maintenance_type_id" class="form-control" required>
                                @if($waterMaintenance->maintenance_type_id)
                                    <option value="{{$waterMaintenance->maintenance_type_id}}">
                                        {{$waterMaintenance->MaintenanceType->type}}
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
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Status</label>
                            <select name="maintenance_status_id" class="form-control" >
                                @if($waterMaintenance->maintenance_status_id)
                                    <option value="{{$waterMaintenance->maintenance_status_id}}">
                                        {{$waterMaintenance->MaintenanceStatus->name}}
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
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Recipient</label>
                            <select name="user_id" class="form-control">
                                @if($waterMaintenance->user_id)
                                    <option value="{{$waterMaintenance->user_id}}">
                                        {{$waterMaintenance->User->name}}
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
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$waterMaintenance->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>
  
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 control-label'>Maintenance Water Actions</label>
                    </div>
                </div>
                @if(count($h2oActions) > 0)
                    <table id="h2oActionsTable" 
                        class="table table-striped data-h2o-actions-donors my-2">  
                        <tbody>
                            @foreach($h2oActions as $h2oAction)
                            <tr id="h2oActionRow">
                                <td class="text-center">
                                    {{$h2oAction->maintenance_action_h2o}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteH2oAction" 
                                        id="deleteH2oAction" data-id="{{$h2oAction->id}}">
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
                                    @foreach($maintenanceWaterActions as $maintenanceWaterAction)
                                        <option value="{{$maintenanceWaterAction->id}}">
                                            {{$maintenanceWaterAction->maintenance_action_h2o}}
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
                                    @foreach($maintenanceWaterActions as $maintenanceWaterAction)
                                        <option value="{{$maintenanceWaterAction->id}}">
                                            {{$maintenanceWaterAction->maintenance_action_h2o}}
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
                    <table id="performedUsersTable" 
                        class="table table-striped data-h2o-actions-donors my-2">  
                        <tbody>
                            @foreach($performedUsers as $performedUser)
                            <tr id="performedUserRow">
                                <td class="text-center">
                                    {{$performedUser->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deletePerformedUsers" 
                                        id="deletePerformedUsers" data-id="{{$performedUser->id}}">
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


<script type="text/javascript">
    $(function () {

        // delete water action
        $('#h2oActionsTable').on('click', '.deleteH2oAction',function() {
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
                        url: "{{ route('deleteH2oAction') }}",
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
        $('#performedUsersTable').on('click', '.deletePerformedUsers',function() {
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
                        url: "{{ route('deletePerformedUsers') }}",
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
    });
</script>

@endsection