@extends('layouts/layoutMaster')

@section('title', 'edit compound')

@include('layouts.all')

<style>
    label, input{ 
    display: block;
}
label {
    margin-top: 20px;
}
</style>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$compound->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4> 

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('community-compound.update', $compound->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                    <div class="row">
                        <h5>General Details</h5>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>English Name</label>
                                <input type="text" name="english_name" 
                                class="form-control" value="{{$compound->english_name}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Arabic Name</label>
                                <input type="text" name="arabic_name" class="form-control"
                                value="{{$compound->arabic_name}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" id="selectedRegion" 
                                    class="selectpicker form-control" data-live-search="true"required>
                                    <option disabled selected>{{$compound->Community->english_name}}</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Cellular Reception?</label>
                                <select name="reception" class="form-control">
                                    <option disabled selected>{{$compound->reception}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of Households</label>
                                <input type="text" name="number_of_household" 
                                value="{{$compound->number_of_household}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of People</label>
                                <input type="text" name="number_of_people" 
                                value="{{$compound->number_of_people}}" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Fallah</label>
                                <select name="is_fallah" class="form-control">
                                    <option disabled selected>{{$compound->is_fallah}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset> 
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Bedouin</label>
                                <select name="is_bedouin" class="form-control">
                                    <option disabled selected>{{$compound->is_bedouin}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community Status</label>
                                <select name="community_status_id" data-live-search="true"
                                class="selectpicker form-control" >
                                    @if($compound->community_status_id)
                                    <option disabled selected>
                                        {{$compound->CommunityStatus->name}}
                                    </option>
                                    @endif
                                    @foreach($communityStatuses as $communityStatus)
                                    <option value="{{$communityStatus->id}}">
                                        {{$communityStatus->name}}
                                    </option>
                                    @endforeach

                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Cycle Year</label>
                                <select name="energy_system_cycle_id" data-live-search="true"
                                class="selectpicker form-control" >
                                @if($compound->energy_system_cycle_id)
                                    <option disabled selected>
                                        {{$compound->EnergySystemCycle->name}}
                                    </option>
                                    @foreach($energyCycles as $energyCycle)
                                    <option value="{{$energyCycle->id}}">
                                        {{$energyCycle->name}}
                                    </option>
                                    @endforeach
                                @else
                                <option disabled selected>Choose one...</option>
                                    @foreach($energyCycles as $energyCycle)
                                    <option value="{{$energyCycle->id}}">
                                        {{$energyCycle->name}}
                                    </option>
                                    @endforeach
                                @endif
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Latitude</label>
                                <input type="text" name="latitude" 
                                value="{{$compound->latitude}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Longitude</label>
                                <input type="text" name="longitude" 
                                value="{{$compound->longitude}}" class="form-control">
                            </fieldset>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$compound->notes}}
                                </textarea>
                            </fieldset>
                        </div>
                    </div>


                    <hr style="margin-top:30px">
                    <div class="row">
                        <h5>Compound Products</h5>
                    </div>
                    @if(count($compoundProductTypes) > 0)

                        <table id="compoundProductTypesTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($compoundProductTypes as $compoundProductType)
                                <tr id="compoundProductTypesRow">
                                    <td class="text-center">
                                        {{$compoundProductType->ProductType->name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteCompoundProductTypes" 
                                            id="deleteCompoundProductTypes"
                                            data-id="{{$compoundProductType->id}}">
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
                                    <label class='col-md-12 control-label'>Add More Products</label>
                                    <select class="selectpicker form-control" multiple data-live-search="true" 
                                        name="products[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($products as $product)
                                        <option value="{{$product->id}}">
                                            {{$product->name}}
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
                                    <label class='col-md-12 control-label'>Products</label>
                                    <select class="selectpicker form-control" multiple data-live-search="true" 
                                        name="new_products[]">
                                        @foreach($products as $product)
                                        <option value="{{$product->id}}">
                                            {{$product->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    @endif

                    <hr style="margin-top:30px">
                    <div class="row">
                        <h5>Nearby Towns</h5>
                    </div>
                    @if(count($compoundNearbyTowns) > 0)

                        <table id="compoundNearbyTownsTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($compoundNearbyTowns as $compoundNearbyTown)
                                <tr id="compoundNearbyTownsRow">
                                    <td class="text-center">
                                        {{$compoundNearbyTown->Town->english_name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deletecompoundNearbyTowns" 
                                            id="deletecompoundNearbyTowns"
                                            data-id="{{$compoundNearbyTown->id}}">
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
                                    <label class='col-md-12 control-label'>Add More Nearby Towns</label>
                                    <select class="selectpicker form-control" multiple data-live-search="true" 
                                        name="nearby_towns[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($towns as $town)
                                        <option value="{{$town->id}}">
                                            {{$town->english_name}}
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
                                    <label class='col-md-12 control-label'>Nearby Towns</label>
                                    <select class="selectpicker form-control" multiple data-live-search="true" 
                                        name="new_nearby_towns[]">
                                        @foreach($towns as $town)
                                        <option value="{{$town->id}}">
                                            {{$town->english_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    @endif


                    <hr style="margin-top:30px">
                    <div class="row">
                        <h5>Nearby Settlements</h5>
                    </div>
                    @if(count($compoundNearbySettlements) > 0)

                        <table id="compoundNearbySettlementsTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($compoundNearbySettlements as $compoundNearbySettlement)
                                <tr id="compoundNearbySettlementsRow">
                                    <td class="text-center">
                                        {{$compoundNearbySettlement->Settlement->english_name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deletecompoundNearbySettlements" 
                                            id="deletecompoundNearbySettlements"
                                            data-id="{{$compoundNearbySettlement->id}}">
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
                                    <label class='col-md-12 control-label'>Add More Nearby Settlements</label>
                                    <select class="selectpicker form-control" multiple data-live-search="true" 
                                        name="nearby_settlement[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($settlements as $settlement)
                                        <option value="{{$settlement->id}}">
                                            {{$settlement->english_name}}
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
                                    <label class='col-md-12 control-label'>Nearby Settlements</label>
                                    <select class="selectpicker form-control" multiple data-live-search="true" 
                                        name="new_nearby_settlement[]">
                                        @foreach($settlements as $settlement)
                                        <option value="{{$settlement->id}}">
                                            {{$settlement->english_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    @endif



                    <hr style="margin-top:30px">
                    <div class="row" style="margin-top:12px">
                        <h5>Legal Details</h5>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Demolition orders/demolitions </label>
                                <select name="demolition" class="form-control">
                                    <option disabled selected>{{$compound->demolition}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>How Many Demolitions?</label>
                                <input type="text" name="demolition_number" 
                                value="{{$compound->demolition_number}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Have demolition orders been executed?</label>
                                <select name="demolition_executed" class="form-control">
                                    <option disabled selected>{{$compound->demolition_executed}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>When was the last demolition?</label>
                                <input type="date" name="last_demolition" 
                                value="{{$compound->last_demolition}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Lawyer</label>
                                <input type="text" name="lawyer" class="form-control"
                                    value="{{$compound->lawyer}}">
                            </fieldset>
                        </div>
                    </div>
                   

                    <hr style="margin-top:30px">
                    <div class="row">
                        <h5>Water Sources</h5>
                    </div>
                    @if(count($compoundWaterSources) > 0)

                        <table id="compoundWaterSourcesTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($compoundWaterSources as $compoundWaterSource)
                                <tr id="compoundWaterSourcesRow">
                                    <td class="text-center">
                                        {{$compoundWaterSource->WaterSource->name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deletecompoundWaterSources" 
                                            id="deletecompoundWaterSources"
                                            data-id="{{$compoundWaterSource->id}}">
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
                                    <label class='col-md-12 control-label'>Add More Water Sources</label>
                                    <select class="selectpicker form-control" multiple data-live-search="true" 
                                        name="waters[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($waterSources as $waterSource)
                                        <option value="{{$waterSource->id}}">
                                            {{$waterSource->name}}
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
                                    <label class='col-md-12 control-label'>Water Sources</label>
                                    <select class="selectpicker form-control" multiple data-live-search="true" 
                                        name="new_waters[]">
                                        @foreach($waterSources as $waterSource)
                                        <option value="{{$waterSource->id}}">
                                            {{$waterSource->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    @endif


                <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                </div>

              

                <hr style="margin-top:20px">
                <div class="row" >
                    <h5>Educational "Kindergarten" Details</h5>
                </div>
                <div class="row" id="kindergartenDetails">
                    <div class="col-xl-4 col-lg-4 col-md-4" >
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Is there a kindergarten in the compound?</label>
                            <select name="is_kindergarten" id="isKindergarten" class="selectpicker form-control">
                                <option disabled selected>Choose one...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1" id="kindergartenTown" style="visibility:none; display:none">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Where do students go for kindergarten?</label>
                            <select name="kindergarten_town_id" class="selectpicker form-control"
                                data-live-search="true">
                                @if($compound->kindergarten_town_id)
                                <option disabled selected>{{$compound->KindergartenTown->english_name}}</option>
                                @else
                                <option disabled selected>Choose one...</option>
                                @foreach($towns as $town)
                                <option value="{{$town->id}}">
                                    {{$town->english_name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Students?</label>
                            <input type="number" name="kindergarten_students" class="form-control"
                                value="{{$compound->kindergarten_students}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Male Students?</label>
                            <input type="number" name="kindergarten_male" class="form-control"
                                value="{{$compound->kindergarten_male}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Female Students?</label>
                            <input type="number" name="kindergarten_female" class="form-control"
                                value="{{$compound->kindergarten_female}}">
                        </fieldset>
                    </div>
                </div>

                <hr style="margin-top:20px">
                <div class="row" id="schoolDetails">
                    <h5>Educational "School" Details</h5>
                </div>
                <div class="row">
                    @if($compound->school_town_id)
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1" id="schoolTown">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Where do students go for School?</label>
                            <select name="school_town_id" class="selectpicker form-control"
                                data-live-search="true">
                                @if($compound->school_town_id)
                                <option disabled selected>{{$compound->SchoolTown->english_name}}</option>
                                @else
                                <option disabled selected>Choose one...</option>
                                @foreach($towns as $town)
                                <option value="{{$town->id}}">
                                    {{$town->english_name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    @else
                    
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Is there a school in the compound?</label>
                            <select name="is_school" id="isSchool" class="selectpicker form-control">
                                <option disabled selected>Choose one...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1" id="schoolTown" style="visibility:none; display:none">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Where do students go for School?</label>
                            <select name="school_town_id" class="selectpicker form-control"
                                data-live-search="true">
                                <option disabled selected>Choose one...</option>
                                @foreach($towns as $town)
                                <option value="{{$town->id}}">
                                    {{$town->english_name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    @endif
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Students?</label>
                            <input type="number" name="school_students" class="form-control"
                                value="{{$compound->school_students}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Male Students?</label>
                            <input type="number" name="school_male" class="form-control"
                                value="{{$compound->school_male}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Female Students?</label>
                            <input type="number" name="school_female" class="form-control"
                                value="{{$compound->school_female}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>From Grade?</label>
                            <input type="number" name="grade_from" class="form-control"
                                value="{{$compound->grade_from}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>To Grade?</label>
                            <input type="number" name="grade_to" class="form-control"
                                value="{{$compound->grade_to}}">
                        </fieldset>
                    </div>
                </div>
       
                <hr style="margin-top:20px">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>
                                <h5>Surveyed </h5> 
                            </label>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Is Surveyed</label>
                            <select name="is_surveyed"
                                class="form-control" >
                                @if($compound->is_surveyed)
                                <option selected disabled value="{{$compound->is_surveyed}}">
                                    {{$compound->is_surveyed}}
                                </option>
                                <option value="yes">Yes</option> 
                                <option value="no">No</option>
                                @else
                                <option selected disabled>Choose one...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Last Surveyed Date</label>
                            <input type="date" name="last_surveyed_date" 
                            value="{{$compound->last_surveyed_date}}"
                            class="form-control">
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

    $(document).on('change', '#isKindergarten', function () {

        kindergartenFlag = $(this).val();

        if(kindergartenFlag == "no") {

            $("#kindergartenTown").css("visibility", "visible");
            $("#kindergartenTown").css('display', 'block');
        } else if(kindergartenFlag == "yes"){

            $("#kindergartenTown").css("visibility", "none");
            $("#kindergartenTown").css('display', 'none');
        }
    });

    $(document).on('change', '#isSchool', function () {

        SchoolFlag = $(this).val();

        if(SchoolFlag == "no") {

            $("#schoolTown").css("visibility", "visible");
            $("#schoolTown").css('display', 'block');
        } else if(SchoolFlag == "yes"){

            $("#schoolTown").css("visibility", "none");
            $("#schoolTown").css('display', 'none');
        }
    });

</script>
@endsection