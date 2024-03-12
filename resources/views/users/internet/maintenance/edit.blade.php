@extends('layouts/layoutMaster')

@section('title', 'edit internet maintenance log')

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
    @if($internetUser->household_id)

        {{$internetUser->Household->english_name}}
    @else @if($internetUser->public_structure_id)

        {{$internetUser->PublicStructure->english_name}}
    @endif
    @endif
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('internet-maintenance.update', $internetMaintenance->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class=" form-control" name="community_id" disabled>
                                @if($internetMaintenance->community_id)
                                    <option value="{{$internetMaintenance->community_id}}">
                                        {{$internetMaintenance->Community->english_name}}
                                    </option>
                                @endif                                
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                        @if($internetUser->household_id)

                            <label class='col-md-12 control-label'>Energy User</label>
                            <input type="text" value="{{$internetUser->Household->english_name}}" 
                                class="form-control" disabled>
                            
                        @else @if($internetUser->public_structure_id)

                            <label class='col-md-12 control-label'>Energy Public</label>
                            <input type="text" value="{{$internetUser->PublicStructure->english_name}}" 
                                class="form-control" disabled>
                        @endif
                        @endif
                        </fieldset>
                    </div> 
                </div> 

                <div class="row">
                    @if($internetUser->household_id)
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Phone Number</label>
                            <input type="text" name="phone_number" class="form-control"
                                value="{{$internetUser->Household->phone_number}}"> 
                        </fieldset>
                    </div>
                    @endif
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Type</label>
                            <select name="maintenance_type_id" class="form-control" required>
                                @if($internetMaintenance->maintenance_type_id)
                                    <option value="{{$internetMaintenance->maintenance_type_id}}">
                                        {{$internetMaintenance->MaintenanceType->type}}
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
                                @if($internetMaintenance->maintenance_status_id)
                                    <option value="{{$internetMaintenance->maintenance_status_id}}">
                                        {{$internetMaintenance->MaintenanceStatus->name}}
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
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Date Of Call</label>
                            <input type="date" name="date_of_call" class="form-control" 
                                value="{{$internetMaintenance->date_of_call}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Visit Date</label>
                            <input type="date" name="visit_date" class="form-control" 
                                value="{{$internetMaintenance->visit_date}}">
                        </fieldset>
                    </div>
                </div>
 
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Completed Date</label>
                            <input type="date" name="date_completed" class="form-control"
                                value="{{$internetMaintenance->date_completed}}" >
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Recipient</label>
                            <select name="user_id" class="form-control">
                                @if($internetMaintenance->user_id)
                                    <option value="{{$internetMaintenance->user_id}}">
                                        {{$internetMaintenance->User->name}}
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
                                {{$internetMaintenance->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <label class='col-md-12 control-label'>Maintenance Internet Actions</label>
                    </div>
                </div>
                @if(count($internetActions) > 0)
                    <table id="internetActionsTable" 
                        class="table table-striped data-h2o-actions-donors my-2">  
                        <tbody>
                            @foreach($internetActions as $internetAction)
                            <tr id="internetActionRow">
                                <td class="text-center">
                                    {{$internetAction->english_name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deleteInternetAction" 
                                        id="deleteInternetAction" data-id="{{$internetAction->id}}">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add more actions/ Select Issues</label>
                                <select name="internet_issues" data-live-search="true"  
                                    id="internetMaintenanceIssue" 
                                    class="selectpicker form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($internetIssues as $internetIssue)
                                    <option value="{{$internetIssue->id}}">
                                        {{$internetIssue->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Actions</label>
                                <select name="actions[]" class="selectpicker form-control" multiple 
                                    id="selectedInternetActions" data-live-search="true" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                @else 
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Add actions/ Select Issues</label>
                                <select name="internet_issues" data-live-search="true"  
                                    id="internetMaintenanceIssue" 
                                    class="selectpicker form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($internetIssues as $internetIssue)
                                    <option value="{{$internetIssue->id}}">
                                        {{$internetIssue->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Actions</label>
                                <select name="new_actions[]" class="selectpicker form-control" multiple 
                                    id="selectedInternetActions" data-live-search="true" disabled>
                                    <option disabled selected>Choose one...</option>
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
                    <table id="performedUsersInternetTable" 
                        class="table table-striped data-h2o-actions-donors my-2">  
                        <tbody>
                            @foreach($performedUsers as $performedUser)
                            <tr id="performedUserRow">
                                <td class="text-center">
                                    {{$performedUser->name}}
                                </td>
                                <td class="text-center">
                                    <a class="btn deletePerformedInternetUsers" 
                                        id="deletePerformedInternetUsers" data-id="{{$performedUser->id}}">
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

        $(document).on('change', '#internetMaintenanceIssue', function () {
        
            issue_id = $(this).val();
            var selectIssue = $('#selectedInternetActions'); 

            $.ajax({
                url: "/internet-maintenance/get_actions/" + issue_id,
                method: 'GET',
                success: function(data) {
                    
                    selectIssue.prop('disabled', false);
        
                    selectIssue.html(data.html);
                    selectIssue.selectpicker('refresh');
                }
            });
        });

        // delete internet action
        $('#internetActionsTable').on('click', '.deleteInternetAction',function() {
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
                        url: "{{ route('deleteInternetAction') }}",
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
        $('#performedUsersInternetTable').on('click', '.deletePerformedInternetUsers',function() {
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
                        url: "{{ route('deletePerformedInternetUser') }}",
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