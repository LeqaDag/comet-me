@extends('layouts/layoutMaster')

@section('title', 'edit energy maintenance log')

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
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Electricity Action</label>
                            <select name="maintenance_electricity_action_id" class="form-control"
                                id="maintenanceElectricityAction" >
                            @if($energyMaintenance->maintenance_electricity_action_id)
                                <option value="{{$energyMaintenance->maintenance_electricity_action_id}}">
                                    {{$energyMaintenance->MaintenanceElectricityAction->maintenance_action_electricity}}
                                </option>
                            @endif 
                            @foreach($actions as $action)
                                <option value="{{$action->id}}">
                                    {{$action->maintenance_action_electricity}}
                                </option>
                            @endforeach
                            </select>
                            @if ($errors->has('maintenance_electricity_action_id'))
                                <span class="error">{{ $errors->first('maintenance_electricity_action_id') }}</span>
                            @endif
                        </fieldset>
                    </div>
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

</script>

@endsection