@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'edit household')

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
    <span class="text-muted fw-light">Edit </span> {{$community->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('community.update', $community->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" name="english_name" 
                                class="form-control" value="{{$community->english_name}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Arabic Name</label>
                                <input type="text" name="arabic_name" class="form-control"
                                value="{{$community->arabic_name}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Region</label>
                                <select name="region_id" id="selectedRegion" 
                                    class="form-control" required>
                                    <option disabled selected>{{$community->Region->english_name}}</option>
                                    @foreach($regions as $region)
                                    <option value="{{$region->id}}">
                                        {{$region->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Sub Region</label>
                                <select name="sub_region_id" id="selectedSubRegions" 
                                class="form-control" required>
                                    <option disabled selected>{{$community->SubRegion->english_name}}</option>
                                    @foreach($subRegions as $region)
                                    <option value="{{$region->id}}">
                                        {{$region->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community Status</label>
                                <select name="community_status_id" 
                                class="form-control" >
                                    <option disabled selected>
                                        {{$community->CommunityStatus->name}}
                                    </option>
                                    @foreach($communityStatuses as $communityStatus)
                                    <option value="{{$communityStatus->id}}">
                                        {{$communityStatus->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Cellular Reception?</label>
                                <select name="reception" class="form-control">
                                    <option disabled selected>{{$community->reception}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of Households</label>
                                <input type="text" name="number_of_household" 
                                value="{{$community->number_of_household}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of People</label>
                                <input type="text" name="number_of_people" 
                                value="{{$community->number_of_people}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Fallah</label>
                                <select name="is_fallah" class="form-control">
                                    <option disabled selected>{{$community->is_fallah}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Bedouin</label>
                                <select name="is_bedouin" class="form-control">
                                    <option disabled selected>{{$community->is_bedouin}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Demolition orders/demolitions </label>
                                <input type="text" name="demolition" 
                                value="{{$community->demolition}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Land Status</label>
                                <input type="text" name="land_status" 
                                value="{{$community->land_status}}" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy Service</label>
                                <select name="energy_service" class="form-control">
                                    <option disabled selected>{{$community->energy_service}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy Service Year</label>
                                <input type="text" name="energy_service_beginning_year" 
                                value="{{$community->energy_service_beginning_year}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water Service</label>
                                <select name="water_service" class="form-control">
                                    <option disabled selected>{{$community->water_service}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water Service Year</label>
                                <input type="text" name="water_service_beginning_year" 
                                value="{{$community->water_service_beginning_year}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Internet Service</label>
                                <select name="internet_service" class="form-control">
                                    <option disabled selected>{{$community->internet_service}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Internet Service Year</label>
                                <input type="text" name="internet_service_beginning_year" 
                                value="{{$community->internet_service_beginning_year}}" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 mb-1" id="percentageQuestion1Div">
                            <fieldset class="form-group">
                                <input type="text" name="description" class="form-control"
                                    id="percentageInputQuestion1" 
                                    style="visiblity:hidden; display:none">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                   style="resize:none" cols="20" rows="3"></textarea>
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