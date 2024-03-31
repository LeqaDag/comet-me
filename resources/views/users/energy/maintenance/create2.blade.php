@extends('layouts/layoutMaster')

@section('title', 'create new energy maintenance log')

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
    <span class="text-muted fw-light">Create </span>  
    New Maintenance Log 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" enctype='multipart/form-data' 
                action="{{url('energy-maintenance')}}">
                @csrf

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" 
                                data-live-search="true" id="selectedUserCommunity"
                                name="community_id[]" required>
                                <option disabled selected>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">
                                    {{$community->english_name}}
                                </option> 
                                @endforeach
                            </select>
                            @if ($errors->has('community_id'))
                                <span class="error">{{ $errors->first('community_id') }}</span>
                            @endif
                        </fieldset>
                    </div> 

                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>MG system/ User?</label>
                            <select name="system_user" class="form-control"
                                id="mgSystemOrFbsUser" disabled>
                                <option selected>Choose one...</option>
                                <option value="system">MG System</option>
                                <option value="fbs_user">FBS User</option>
                                <option value="mg_user">MG User</option>
                                <option value="turbine">Turbine</option>
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy User</label>
                            <select name="household_id" class="form-control" 
                                id="selectedUserHousehold" disabled>
                                <option disabled selected>Choose one...</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System</label>
                            <select name="energy_system_id" class="form-control" 
                                id="selectedEnergySystem" disabled>
                                <option disabled selected>Choose One...</option>
                                @foreach($mgSystems as $mgSystem)
                                    <option value="{{$mgSystem->id}}">
                                        {{$mgSystem->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Public Structure</label>
                            <select class="form-control" id="selectedPublic"
                                name="public_structure_id" disabled>
                                <option disabled selected>Choose one...</option>
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6" id="maintenanceElectricityAction">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Electricity Action</label>
                            <select 
                                class="selectpicker form-control" data-live-search="true">
                                <option disabled selected>Choose one...</option>
                                
                            </select>

                            @if ($errors->has('energy_maintenance_action_id'))
                                <span class="error">{{ $errors->first('energy_maintenance_action_id') }}</span>
                            @endif
                        </fieldset>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6" id="maintenanceElectricityActionSystem">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Electricity Action</label>
                            <select name="energy_maintenance_action_id[]" 
                                class="selectpicker form-control" data-live-search="true"
                                id="actionSystemSelect" multiple>
                                <option disabled selected>Choose one...</option>
                                @foreach($systemActions as $systemAction) 
                                    <option value="{{$systemAction->id}}">
                                        {{$systemAction->arabic_name}}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('energy_maintenance_action_id'))
                                <span class="error">{{ $errors->first('energy_maintenance_action_id') }}</span>
                            @endif
                        </fieldset>
                    </div>
                    
                    <div class="col-xl-6 col-lg-6 col-md-6" id="maintenanceElectricityActionUser"
                        style="display:none">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Electricity Action</label>
                            <select name="energy_maintenance_action_id[]" multiple
                                class="selectpicker form-control" data-live-search="true">
                                <option disabled selected>Choose one...</option>
                                @foreach($userActions as $userAction) 
                                    <option value="{{$userAction->id}}">
                                        {{$userAction->arabic_name}}
                                    </option>
                                @endforeach
                            </select>

                            @if ($errors->has('energy_maintenance_action_id'))
                                <span class="error">{{ $errors->first('energy_maintenance_action_id') }}</span>
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Date Of Call</label>
                            <input type="date" name="date_of_call" class="form-control" required>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Visit Date</label>
                            <input type="date" name="visit_date" class="form-control" required>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Completed Date</label>
                            <input type="date" name="date_completed" class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Type</label>
                            <select name="maintenance_type_id" class="form-control" required="true">
                                <option disabled selected>Choose one...</option>
                                @foreach($maintenanceTypes as $maintenanceType)
                                <option value="{{$maintenanceType->id}}">
                                    {{$maintenanceType->type}}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('maintenance_type_id'))
                                <span class="error">{{ $errors->first('maintenance_type_id') }}</span>
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Maintenance Status</label>
                            <select name="maintenance_status_id" class="form-control" required="true" >
                                <option disabled selected>Choose one...</option>
                                @foreach($maintenanceStatuses as $maintenanceStatus)
                                <option value="{{$maintenanceStatus->id}}">
                                    {{$maintenanceStatus->name}}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('maintenance_status_id'))
                                <span class="error">{{ $errors->first('maintenance_status_id') }}</span>
                            @endif
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Recipient</label>
                            <select name="user_id" class="form-control" required="true">
                                <option disabled selected>Choose one...</option>
                                @foreach($users as $user)
                                <option value="{{$user->id}}">
                                    {{$user->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Performed By</label>
                            <select name="performed_by[]" class="selectpicker form-control" 
                                data-live-search="true" multiple>
                                <option disabled selected>Choose one...</option>
                                @foreach($users as $user)
                                <option value="{{$user->id}}">
                                    {{$user->name}}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('user_id'))
                                <span class="error">{{ $errors->first('user_id') }}</span>
                            @endif
                        </fieldset>
                    </div>
                </div> 
                
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3"></textarea>
                        </fieldset>
                    </div>
                </div> 
            </form>
        </div>
    </div>
</div>


<script>

    $(document).on('change', '#selectedUserCommunity', function () {
        community_id = $(this).val();

        $('#mgSystemOrFbsUser').prop('disabled', false);
 
        systemUser = $('#mgSystemOrFbsUser').val();

        if(systemUser == "system") {
           
            $('#selectedEnergySystem').prop('disabled', false);
            $('#selectedUserHousehold').prop('disabled', true);
            $('#selectedPublic').prop('disabled', true);

            getAction(1, 0, community_id);
        } else if(systemUser == "fbs_user") {

            $('#selectedEnergySystem').prop('disabled', true);
            $('#selectedUserHousehold').prop('disabled', false);
            $('#selectedPublic').prop('disabled', false);

            getAction(2, 1, community_id); 
        } else if(systemUser == "mg_user") {
            
            $('#selectedEnergySystem').prop('disabled', true);
            $('#selectedUserHousehold').prop('disabled', false);
            $('#selectedPublic').prop('disabled', false);

            getAction(2, 2, community_id); 
        }

        $(document).on('change', '#mgSystemOrFbsUser', function () {

            systemUser = $('#mgSystemOrFbsUser').val();

            if(systemUser == "system") {

                $('#selectedEnergySystem').prop('disabled', false);
                $('#selectedUserHousehold').prop('disabled', true);
                $('#selectedPublic').prop('disabled', true);

                getAction(1, 0, community_id);

            } else if(systemUser == "fbs_user") {

                $('#selectedEnergySystem').prop('disabled', true);
                $('#selectedUserHousehold').prop('disabled', false);
                $('#selectedPublic').prop('disabled', false);

                getAction(2, 1, community_id); 
    
            } else if(systemUser == "mg_user") {

                $('#selectedEnergySystem').prop('disabled', true);
                $('#selectedUserHousehold').prop('disabled', false);
                $('#selectedPublic').prop('disabled', false);

                getAction(2, 2, community_id);  
            }
        });
    });

    function getAction(system, mg, community_id) {

        $.ajax({
            url: "energy-maintenance/get_system/" + system + "/" + mg + "/" + community_id,
            method: 'GET',
            success: function(data) {

                $('#maintenanceElectricityAction').prop('disabled', false);
                $('#maintenanceElectricityAction').html(data.htmlActions);

                if(system != 1) {

                    $('#selectedUserHousehold').prop('disabled', false);
                    $('#selectedUserHousehold').html(data.htmlUsers);

                    $('#selectedPublic').prop('disabled', false);
                    $('#selectedPublic').html(data.htmlPublics);

                    $("#maintenanceElectricityAction").hide();
                    $('#maintenanceElectricityActionUser').show();
                    $('#maintenanceElectricityActionSystem').hide();

                } else if(system == 1) {

                    $('#selectedEnergySystem').prop('disabled', false);
                    $('#selectedEnergySystem').html(data.htmlEnergyType);

                    $("#maintenanceElectricityAction").hide();
                    $('#maintenanceElectricityActionUser').hide();
                    $('#maintenanceElectricityActionSystem').show();
                }
            }
        });
    } 
</script>

@endsection