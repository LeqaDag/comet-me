@extends('layouts/layoutMaster')

@section('title', 'edit water request')

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
    @if($waterRequestSystem->household_id)

        {{$waterRequestSystem->Household->english_name}} - 
    @else @if($waterRequestSystem->public_structure_id)

        {{$waterRequestSystem->PublicStructure->english_name}} - 
    @endif
    @endif
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('water-request.update', $waterRequestSystem->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
              
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" 
                                data-live-search="true" id="selectedWaterRequestCommunity"
                                name="community_id" disabled>
                                <option disabled selected>{{$waterRequestSystem->Community->english_name}}</option>
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Household</label>
                            <select name="household_id" class="selectpicker form-control" 
                                id="selectedWaterRequestHousehold" data-live-search="true" disabled>
                                @if($waterRequestSystem->household_id)

                                    <option disabled selected>{{$waterRequestSystem->Household->english_name}}</option>
                                @else @if($waterRequestSystem->public_structure_id)

                                    <option disabled selected>{{$waterRequestSystem->PublicStructure->english_name}}</option>
                                @endif
                                @endif
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Status of request</label>
                            <select name="water_request_status_id" 
                                class="selectpicker form-control" data-live-search="true"
                                id="actionSystemSelect">
                                <option disabled selected>{{$waterRequestSystem->WaterRequestStatus->name}}</option>
                                @foreach($requestStatuses as $requestStatus) 
                                    <option value="{{$requestStatus->id}}">
                                        {{$requestStatus->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Request Date</label>
                            <input type="date" name="date" value="{{$waterRequestSystem->date}}" class="form-control" required>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Recommended Water System Type</label>
                            <select name="water_system_type_id" 
                                class="selectpicker form-control" data-live-search="true" >
                                <option disabled selected>{{$waterRequestSystem->WaterSystemType->type}}</option>
                                @foreach($waterSystemTypes as $waterSystemType)
                                <option value="{{$waterSystemType->id}}">
                                    {{$waterSystemType->type}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Grid Integration Type</label>
                            <select name="grid_integration_type_id" class="selectpicker form-control"
                                data-live-search="true">
                                @if($waterRequestSystem->grid_integration_type_id)
                                    <option disabled selected>{{$waterRequestSystem->GridIntegrationType->name}}</option>
                                    @foreach($gridTypes as $gridType)
                                    <option value="{{$gridType->id}}">
                                        {{$gridType->name}}
                                    </option>
                                    @endforeach
                                @else
                                    <option disabled selected>Choose one...</option>
                                    @foreach($gridTypes as $gridType)
                                    <option value="{{$gridType->id}}">
                                        {{$gridType->name}}
                                    </option>
                                    @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>New or Replacement</label>
                            <select name="water_system_status_id" 
                                class="selectpicker form-control" data-live-search="true">
                                @if($waterRequestSystem->water_system_status_id)
                                <option disabled selected>{{$waterRequestSystem->WaterSystemStatus->status}}</option>
                                @else
                                <option disabled selected>Select one...</option>
                                @endif
                                @foreach($waterSystemStatuses as $waterSystemStatus)
                                <option value="{{$waterSystemStatus->id}}">
                                    {{$waterSystemStatus->status}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cycle Year</label>
                            <select name="water_system_cycle_id" 
                                class="selectpicker form-control" data-live-search="true" >
                                @if($waterRequestSystem->water_system_status_id)
                                <option disabled selected>{{$waterRequestSystem->WaterSystemCycle->name}}</option>
                                @else
                                <option disabled selected>Select one...</option>
                                @endif
                                @foreach($waterCycleYears as $waterCycleYear)
                                <option value="{{$waterCycleYear->id}}">
                                    {{$waterCycleYear->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Holder status</label>
                            <select name="water_holder_status_id" 
                                class="selectpicker form-control" data-live-search="true">
                                @if($waterRequestSystem->household_id)
                                    @if($waterRequestSystem->Household->water_holder_status_id)
                                    <option disabled selected>{{$waterRequestSystem->Household->WaterHolderStatus->status}}</option>
                                    @else
                                    <option disabled selected>Select one...</option>
                                    @endif
                                @else @if($waterRequestSystem->public_structure_id)
                                    @if($waterRequestSystem->PublicStructure->water_holder_status_id)
                                    <option disabled selected>{{$waterRequestSystem->PublicStructure->WaterHolderStatus->status}}</option>
                                    @else
                                    <option disabled selected>Select one...</option>
                                    @endif
                                @endif
                                @endif
                                @foreach($waterHolderStatues as $waterHolderStatus)
                                <option value="{{$waterHolderStatus->id}}">
                                    {{$waterHolderStatus->status}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Referred by</label>
                            <textarea name="referred_by" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$waterRequestSystem->referred_by}}
                            </textarea>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$waterRequestSystem->notes}}
                            </textarea>
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