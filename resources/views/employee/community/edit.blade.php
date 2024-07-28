@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'edit community')

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
                        <h5>General Details</h5>
                    </div>
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
                                    class="selectpicker form-control" data-live-search="true"required>
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
                                class="selectpicker form-control" data-live-search="true" required>
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
                                <select name="community_status_id" data-live-search="true"
                                class="selectpicker form-control" >
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
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Cycle Year</label>
                                <select name="energy_system_cycle_id" data-live-search="true"
                                class="selectpicker form-control" >
                                @if($community->energy_system_cycle_id)
                                    <option disabled selected>
                                        {{$community->EnergySystemCycle->name}}
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
                    </div>

                    <div class="row">
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
                    </div>

                    <div class="row">
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
                                <label class='col-md-12 control-label'>Latitude</label>
                                <input type="text" name="latitude" 
                                value="{{$community->latitude}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Longitude</label>
                                <input type="text" name="longitude" 
                                value="{{$community->longitude}}" class="form-control">
                            </fieldset>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="3">
                                {{$community->notes}}
                                </textarea>
                            </fieldset>
                        </div>
                    </div>

                    <hr>
                    <div class="row" style="margin-top:12px">
                        <h5>Second Name for community</h5>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Second Name in English</label>
                                
                                @if($secondName)

                                    <input name="second_name_english" type="text" 
                                        value="{{$secondName->english_name}}" class="form-control">
                                @else

                                    <input name="second_name_english" type="text" class="form-control">
                                @endif
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Second Name in Arabic</label>
                                @if($secondName)

                                    <input name="second_name_arabic" type="text" 
                                        value="{{$secondName->arabic_name}}" class="form-control">
                                @else

                                    <input name="second_name_arabic" type="text" class="form-control">
                                @endif
                            </fieldset>
                        </div>
                    </div> 

                    <hr style="margin-top:30px">
                    <div class="row">
                        <h5>Compounds</h5>
                    </div>
                    @if(count($compounds) > 0)

                        <table id="communityCompoundTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($compounds as $compound)
                                <tr id="compoundsRow">
                                    <td class="text-center">
                                        {{$compound->english_name}}
                                    </td>
                                    <td class="text-center">
                                        {{$compound->arabic_name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteCommunityCompound" 
                                            id="deleteCommunityCompound"
                                            data-id="{{$compound->id}}">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <span>Add More Compounds</span>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                                <table class="table table-bordered" id="dynamicAddRemoveCompoundName">
                                    <tr>
                                        <th>Compound Name</th>
                                        <th>Options</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="addMoreInputFieldsCompoundName[0][subject]" 
                                            placeholder="Enter English Copmound Name" class="target_point form-control" 
                                            data-id="0"/>
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="addCompoundNameButton" 
                                            class="btn btn-outline-primary">
                                                Add More
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        
                    @else
                        <div class="row">
                            <h6>Add New Compounds</h6>
                        </div>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                                <table class="table table-bordered" id="dynamicAddRemoveCompoundName">
                                    <tr>
                                        <th>Compound Name</th>
                                        <th>Options</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="addMoreInputFieldsCompoundName[0][subject]" 
                                            placeholder="Enter English Copmound Name" class="target_point form-control" 
                                            data-id="0"/>
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="addCompoundNameButton" 
                                            class="btn btn-outline-primary">
                                                Add More
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif

                    <hr style="margin-top:30px">
                    <div class="row">
                        <h5>Community Products</h5>
                    </div>
                    @if(count($communityProductTypes) > 0)

                        <table id="communityProductTypesTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($communityProductTypes as $communityProductType)
                                <tr id="communityProductTypesRow">
                                    <td class="text-center">
                                        {{$communityProductType->ProductType->name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deletecommunityProductTypes" 
                                            id="deletecommunityProductTypes"
                                            data-id="{{$communityProductType->id}}">
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
                    @if(count($communityNearbyTowns) > 0)

                        <table id="communityNearbyTownsTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($communityNearbyTowns as $communityNearbyTown)
                                <tr id="communityNearbyTownsRow">
                                    <td class="text-center">
                                        {{$communityNearbyTown->Town->english_name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deletecommunityNearbyTowns" 
                                            id="deletecommunityNearbyTowns"
                                            data-id="{{$communityNearbyTown->id}}">
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
                    @if(count($communityNearbySettlements) > 0)

                        <table id="communityNearbySettlementsTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($communityNearbySettlements as $communityNearbySettlement)
                                <tr id="communityNearbySettlementsRow">
                                    <td class="text-center">
                                        {{$communityNearbySettlement->Settlement->english_name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteCommunityNearbySettlements" 
                                            id="deleteCommunityNearbySettlements"
                                            data-id="{{$communityNearbySettlement->id}}">
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
                                    <option disabled selected>{{$community->demolition}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>How many Demolitions?</label>
                                <input type="text" name="demolition_number" 
                                value="{{$community->demolition_number}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Demolition Legal Status</label>
                                <input type="text" name="demolition_legal_status" 
                                class="form-control" value="{{$community->demolition_legal_status}}">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Have demolition orders been executed?</label>
                                <select name="demolition_executed" class="form-control">
                                    <option disabled selected>{{$community->demolition_executed}}</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>When was the last demolition?</label>
                                <input type="date" name="last_demolition" 
                                value="{{$community->last_demolition}}" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Lawyer</label>
                                <input type="text" name="lawyer" class="form-control"
                                    value="{{$community->lawyer}}">
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
                   

                    <hr style="margin-top:30px">
                    <div class="row">
                        <h5>Recommended Energy Systems</h5>
                    </div>
                    @if(count($recommendedEnergySystems) > 0)

                        <table id="recommendedEnergySystemsTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($recommendedEnergySystems as $recommendedEnergySystem)
                                <tr id="recommendedEnergySystemsRow">
                                    <td class="text-center">
                                        {{$recommendedEnergySystem->EnergySystemType->name}}
                                    </td>
                                    <td class="text-center">
                                        <input type="text" name="numbers" value="{{$recommendedEnergySystem->numbers}}"
                                            placeholder="How many systems?" class="target_point form-control" 
                                            data-id="{{$recommendedEnergySystem->id}}"
                                            data-name="{{$recommendedEnergySystem->community_id}}"
                                            id="recommended_numbers"/>
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteRecommendedEnergySystems" 
                                            id="deleteRecommendedEnergySystems"
                                            data-id="{{$recommendedEnergySystem->id}}">
                                            <i class="fa fa-trash text-danger"></i>
                                        </a>
                                    </td
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                                <table class="table table-bordered" id="dynamicAddRemoveCompoundName">
                                    <tr>
                                        <th>Energy System Type</th>
                                        <th>Numbers</th>
                                        <th>Options</th>
                                    </tr>
                                    <tr> 
                                        <td>
                                            <select class="form-control"  name="recommended_systems">
                                                <option selected disabled>Choose one...</option>
                                                @foreach($energySystemTypes as $energySystemType)
                                                    <option value="{{$energySystemType->id}}">
                                                        {{$energySystemType->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="addMoreInputFieldsCompoundName[0][subject]" 
                                            placeholder="How many systems?" class="target_point form-control" 
                                            data-id="0"/>
                                        </td>
                                        <td>
                                            <button type="button" name="add" id="addCompoundNameButton" 
                                            class="btn btn-outline-primary">
                                                Add More
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-4 col-lg-4 col-md-4">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Add More Recommended Energy Systems</label>
                                    <select class="selectpicker form-control" 
                                        multiple data-live-search="true" name="recommended_systems[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($energySystemTypes as $energySystemType)
                                            <option value="{{$energySystemType->id}}">
                                                {{$energySystemType->name}}
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
                                    <label class='col-md-12 control-label'>Add Recommended Energy Systems</label>
                                    <select class="selectpicker form-control" 
                                        multiple data-live-search="true" name="new_recommended_systems[]">
                                        <option selected disabled>Choose one...</option>
                                        @foreach($energySystemTypes as $energySystemType)
                                            <option value="{{$energySystemType->id}}">{{$energySystemType->name}}</option>
                                        @endforeach
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                    @endif

                    <hr style="margin-top:30px">
                    <div class="row">
                        <h5>Water Sources</h5>
                    </div>
                    @if(count($communityWaterSources) > 0)

                        <table id="communityWaterSourcesTable" class="table table-striped my-2">
                            <tbody>
                                @foreach($communityWaterSources as $communityWaterSource)
                                <tr id="communityWaterSourcesRow">
                                    <td class="text-center">
                                        {{$communityWaterSource->WaterSource->name}}
                                    </td>
                                    <td class="text-center">
                                        <a class="btn deleteCommunityWaterSources" 
                                            id="deleteCommunityWaterSources"
                                            data-id="{{$communityWaterSource->id}}">
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

                <hr style="margin-top:30px">
                <div class="row">
                    <h5>System Details</h5>
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

                <hr style="margin-top:20px">
                <div class="row" >
                    <h5>Educational "Kindergarten" Details</h5>
                </div>
                <div class="row" id="kindergartenDetails">
                    <div class="col-xl-4 col-lg-4 col-md-4" >
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Is there a kindergarten in the community?</label>
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
                                @if($community->kindergarten_town_id)
                                <option disabled selected>{{$community->KindergartenTown->english_name}}</option>
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
                                value="{{$community->kindergarten_students}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Male Students?</label>
                            <input type="number" name="kindergarten_male" class="form-control"
                                value="{{$community->kindergarten_male}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Female Students?</label>
                            <input type="number" name="kindergarten_female" class="form-control"
                                value="{{$community->kindergarten_female}}">
                        </fieldset>
                    </div>
                </div>

                <hr style="margin-top:20px">
                <div class="row" id="schoolDetails">
                    <h5>Educational "School" Details</h5>
                </div>

                @if($schools)
                <div class="row" style="margin-top:20px">
                    <h6 class="text-info">{{$schoolCommunity->english_name}}</h6>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Students?</label>
                            <input type="number" name="number_of_students" class="form-control"
                                value="{{$schools->number_of_students}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Male Students?</label>
                            <input type="number" name="number_of_boys" class="form-control"
                                value="{{$schools->number_of_boys}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Female Students?</label>
                            <input type="number" name="number_of_girls" class="form-control"
                                value="{{$schools->number_of_girls}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>From Grade?</label>
                            <input type="number" name="grade_from_community" class="form-control"
                                value="{{$schools->grade_from}}">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>To Grade?</label>
                            <input type="number" name="grade_to_community" class="form-control"
                                value="{{$schools->grade_to}}">
                        </fieldset>
                    </div>
                </div>
                @endif
                
                <div class="row" style="margin-top:30px">
                    <h6 class="text-info">Do students attend schools in neighboring communities?</h6>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Neighboring school 1</label>
                            <select name="neighboring_school1" class="selectpicker form-control"
                                data-live-search="true">
                                @if($neighboringCommunitySchool1)
                                <option disabled selected>{{$neighboringCommunitySchool1->PublicStructure->english_name}}</option>
                                @foreach($schoolCommunities as $schoolCommunity)
                                <option value="{{$schoolCommunity->id}}">
                                    {{$schoolCommunity->english_name}}
                                </option>
                                @endforeach
                                @else
                                <option disabled selected>Choose one...</option>
                                @foreach($schoolCommunities as $schoolCommunity)
                                <option value="{{$schoolCommunity->id}}">
                                    {{$schoolCommunity->english_name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
           
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Students?</label>
                            @if($neighboringCommunitySchool1)
                            <input type="number" name="school_students1" class="form-control"
                                value="{{$neighboringCommunitySchool1->number_of_student_school}}">
                            @else 
                            <input type="number" name="school_students1" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Male Students?</label>
                            @if($neighboringCommunitySchool1)
                            <input type="number" name="school_male1" class="form-control"
                                value="{{$neighboringCommunitySchool1->number_of_male}}">
                            @else 
                            <input type="number" name="school_male1" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Female Students?</label>
                            @if($neighboringCommunitySchool1)
                            <input type="number" name="school_female1" class="form-control"
                                value="{{$neighboringCommunitySchool1->number_of_female}}">
                            @else 
                            <input type="number" name="school_female1" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>From Grade?</label>
                            @if($neighboringCommunitySchool1)
                            <input type="number" name="grade_from_school1" class="form-control"
                                value="{{$neighboringCommunitySchool1->grade_from_school}}">
                            @else 
                            <input type="number" name="grade_from_school1" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>To Grade?</label>
                            @if($neighboringCommunitySchool1)
                            <input type="number" name="grade_to_school1" class="form-control"
                                value="{{$neighboringCommunitySchool1->grade_to_school}}">
                            @else 
                            <input type="number" name="grade_to_school1" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Neighboring school 2</label>
                            <select name="neighboring_school2" class="selectpicker form-control"
                                data-live-search="true">
                                @if($neighboringCommunitySchool2)
                                <option disabled selected>{{$neighboringCommunitySchool2->PublicStructure->english_name}}</option>
                                @foreach($schoolCommunities as $schoolCommunity)
                                <option value="{{$schoolCommunity->id}}">
                                    {{$schoolCommunity->english_name}}
                                </option>
                                @endforeach
                                @else
                                <option disabled selected>Choose one...</option>
                                @foreach($schoolCommunities as $schoolCommunity)
                                <option value="{{$schoolCommunity->id}}">
                                    {{$schoolCommunity->english_name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
           
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Students?</label>
                            @if($neighboringCommunitySchool2)
                            <input type="number" name="school_students2" class="form-control"
                                value="{{$neighboringCommunitySchool2->number_of_student_school}}">
                            @else 
                            <input type="number" name="school_students2" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Male Students?</label>
                            @if($neighboringCommunitySchool2)
                            <input type="number" name="school_male2" class="form-control"
                                value="{{$neighboringCommunitySchool2->number_of_male}}">
                            @else 
                            <input type="number" name="school_male2" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Female Students?</label>
                            @if($neighboringCommunitySchool2)
                            <input type="number" name="school_female2" class="form-control"
                                value="{{$neighboringCommunitySchool2->number_of_female}}">
                            @else 
                            <input type="number" name="school_female2" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>From Grade?</label>
                            @if($neighboringCommunitySchool2)
                            <input type="number" name="grade_from_school2" class="form-control"
                                value="{{$neighboringCommunitySchool2->grade_from_school}}">
                            @else 
                            <input type="number" name="grade_from_school2" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>To Grade?</label>
                            @if($neighboringCommunitySchool2)
                            <input type="number" name="grade_to_school2" class="form-control"
                                value="{{$neighboringCommunitySchool2->grade_to_school}}">
                            @else 
                            <input type="number" name="grade_to_school2" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                </div>
            

                <div class="row" style="margin-top:30px">
                    <h6 class="text-info">Do students attend schools in neighboring town?</h6>
                </div>
                <div class="row">
                    
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Neighboring town</label>
                            <select name="school_town_id" class="selectpicker form-control"
                                data-live-search="true">
                                @if($neighboringTownSchool)
                                <option disabled selected>{{$neighboringTownSchool->Town->english_name}}</option>
                                @foreach($towns as $town)
                                <option value="{{$town->id}}">
                                    {{$town->english_name}}
                                </option>
                                @endforeach
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
                            @if($neighboringTownSchool)
                            <input type="number" name="school_students_town" class="form-control"
                                value="{{$neighboringTownSchool->number_of_student_school}}">
                            @else 
                            <input type="number" name="school_students_town" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Male Students?</label>
                            @if($neighboringTownSchool)
                            <input type="number" name="number_of_male" class="form-control"
                                value="{{$neighboringTownSchool->number_of_male}}">
                            @else 
                            <input type="number" name="number_of_male" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Female Students?</label>
                            @if($neighboringTownSchool)
                            <input type="number" name="number_of_female" class="form-control"
                                value="{{$neighboringTownSchool->number_of_female}}">
                            @else 
                            <input type="number" name="number_of_female" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>From Grade?</label>
                            @if($neighboringTownSchool)
                            <input type="number" name="grade_from_school" class="form-control"
                                value="{{$neighboringTownSchool->grade_from_school}}">
                            @else 
                            <input type="number" name="grade_from_school" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>To Grade?</label>
                            @if($neighboringTownSchool)
                            <input type="number" name="grade_to_school" class="form-control"
                                value="{{$neighboringTownSchool->grade_to_school}}">
                            @else 
                            <input type="number" name="grade_to_school" class="form-control">
                            @endif
                        </fieldset>
                    </div>
                </div>


                <hr style="margin-top:30px">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>
                                <h5>Surveyed </h5> 
                            </label>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Is Surveyed</label>
                            <select name="is_surveyed"
                                class="form-control" >
                                @if($community->is_surveyed)
                                <option selected disabled value="{{$community->is_surveyed}}">
                                    {{$community->is_surveyed}}
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
                            value="{{$community->last_surveyed_date}}"
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

    // delete community water source
    $('#communityWaterSourcesTable').on('click', '.deleteCommunityWaterSources',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this Water Source?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteCommunityWaterSources') }}",
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

    // delete community product
    $('#communityProductTypesTable').on('click', '.deletecommunityProductTypes',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this product?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deletecommunityProductTypes') }}",
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

    // delete community nearby town
    $('#communityNearbyTownsTable').on('click', '.deletecommunityNearbyTowns',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this nearby town?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deletecommunityNearbyTowns') }}",
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

    // delete community nearby settlements 
    $('#communityNearbySettlementsTable').on('click', '.deleteCommunityNearbySettlements',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({
            icon: 'warning',
            title: 'Are you sure you want to delete this nearby settlement?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteCommunityNearbySettlements') }}",
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

    $('#recommended_numbers').on('change', function(){

        var number = $(this).val();
        var community_id = $(this).data("name");
        alert(community_id);
        $.ajax({
            url: "/recommended/numbers",
            method: 'POST',
            data: {
                number : number,
                community_id : community_id
            },
            success: function(data) {
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
        });
    });

    // delete community compound
    $('#communityCompoundTable').on('click', '.deleteCommunityCompound',function() {
        var id = $(this).data('id');
        var $ele = $(this).parent().parent();

        Swal.fire({ 
            icon: 'warning',
            title: 'Are you sure you want to delete this Compound?',
            showDenyButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if(result.isConfirmed) {
                $.ajax({
                    url: "{{ route('deleteCommunityCompound') }}",
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

    var j = 0;
    $("#addCompoundNameButton").click(function () {
        ++j;
        $("#dynamicAddRemoveCompoundName").append('<tr><td><input type="text"' +
            'name="addMoreInputFieldsCompoundName[][subject]" placeholder="Enter Another one"' +
            'class="target_point form-control" data-id="'+ j +'" /></td><td><button type="button"' +
            'class="btn btn-outline-danger remove-input-field-target-points">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.remove-input-field-target-points', function () {
        $(this).parents('tr').remove();
    });
</script>
@endsection