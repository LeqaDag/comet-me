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