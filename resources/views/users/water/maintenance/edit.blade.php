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
                            <label class='col-md-12 control-label'>Completed Date</label>
                            <input type="date" name="date_completed" class="form-control"
                                value="{{$waterMaintenance->date_completed}}" >
                        </fieldset>
                    </div>
                </div>

                <div class="row">
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
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Water Action</label>
                            <select name="maintenance_h2o_action_id" class="form-control">
                            @if($waterMaintenance->maintenance_h2o_action_id)
                                <option value="{{$waterMaintenance->maintenance_h2o_action_id}}">
                                    {{$waterMaintenance->MaintenanceH2oAction->maintenance_action_h2o}}
                                </option>
                            @endif 
                            @foreach($maintenanceWaterActions as $action)
                                <option value="{{$action->id}}">
                                    {{$action->maintenance_action_h2o}}
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
                </div>
  
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

@endsection