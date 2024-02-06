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

label, table {
    margin-top: 20px;
}
</style>


@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$household->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('household.update', $household->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>
                                <h4>General Details</h4> 
                            </label>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>English Name</label>
                            <input type="text" name="english_name" 
                            value="{{$household->english_name}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Arabic Name</label>
                            <input type="text" name="arabic_name" 
                            value="{{$household->arabic_name}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Wife/Mother Name</label>
                            <input type="text" name="women_name_arabic" 
                            value="{{$household->women_name_arabic}}"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Profession</label>
                            <select name="profession_id" id="selectedProfession" class="form-control" >
                                @if($household->Profession)
                                    <option value="{{$household->Profession->id}}" disabled selected>
                                        {{$household->Profession->profession_name}}
                                    </option>
                                    @foreach($professions as $profession)
                                    <option value="{{$profession->id}}">
                                        {{$profession->profession_name}}
                                    </option>
                                    @endforeach
                                @else
                                @foreach($professions as $profession)
                                <option value="{{$profession->id}}">
                                    {{$profession->profession_name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                        @include('employee.household.profession')
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Phone Number</label>
                            <input type="text" name="phone_number" 
                            value="{{$household->phone_number}}"
                            class="form-control"> 
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select name="community_id" class="selectpicker form-control"
                                data-live-search="true">
                                @if($household->Community)
                                <option value="{{$household->Community->id}}" disabled selected>
                                    {{$household->Community->english_name}}
                                </option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">
                                    {{$community->english_name}}
                                </option>
                                @endforeach
                                @else
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">
                                    {{$community->english_name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Number of Residents</label>
                            <input type="number" name="number_of_people" 
                            value="{{$household->number_of_people}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many male?</label>
                            <input type="number" name="number_of_male" 
                            value="{{$household->number_of_male}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many female?</label>
                            <input type="number" name="number_of_female" 
                            value="{{$household->number_of_female}}"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>
                   

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many adults?</label>
                            <input type="number" name="number_of_adults" 
                            value="{{$household->number_of_adults}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many children under 16?</label>
                            <input type="number" name="number_of_children" 
                            value="{{$household->number_of_children}}"
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many children in school?</label>
                            <input type="number" name="school_students" 
                            value="{{$household->school_students}}"
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many household members in university?</label>
                            <input type="number" name="university_students" 
                            value="{{$household->university_students}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Demolition order in house?</label>
                            <select name="demolition_order" class="form-control">
                                <option selected disabled
                                    value="{{$household->demolition_order}}">
                                    {{$household->demolition_order}}
                                </option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System Type</label>
                            <select name="energy_system_type_id" 
                                class="selectpicker form-control" data-live-search="true" >
                                
                                @if($household->energy_system_type_id)
                                <option value="{{$household->EnergySystemType->id}}" disabled selected>
                                    {{$household->EnergySystemType->name}}
                                </option>
                                @foreach($energySystemTypes as $energySystemType)
                                <option value="{{$energySystemType->id}}">
                                    {{$energySystemType->name}}
                                </option>
                                @endforeach
                                @else
                                <option disabled selected>Choose one...</option>
                                @foreach($energySystemTypes as $energySystemType)
                                <option value="{{$energySystemType->id}}">
                                    {{$energySystemType->name}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-8">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea type="text" name="notes" cols="2"
                            class="form-control" style="resize:none">
                                {{$household->notes}}
                            </textarea>
                        </fieldset>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>
                                <h4>Services Details</h4> 
                            </label>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy Status</label>
                            <select name="household_status_id" class="form-control" >
                                @if($household->HouseholdStatus)
                                    <option value="{{$household->HouseholdStatus->id}}" disabled selected>
                                        {{$household->HouseholdStatus->status}}
                                    </option>
                                    @foreach($householdStatuses as $householdStatus)
                                    <option value="{{$householdStatus->id}}">
                                        {{$householdStatus->status}}
                                    </option>
                                    @endforeach
                                @else
                                @foreach($householdStatuses as $householdStatus)
                                <option value="{{$householdStatus->id}}">
                                    {{$householdStatus->status}}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Water Service</label>
                            <select name="water_system_status" class="form-control" >
                                @if($household->water_system_status)
                                    <option value="{{$household->water_system_status}}" disabled selected>
                                        {{$household->water_system_status}}
                                    </option>
                                    <option value="Served">Served</option>
                                    <option value="Not Served">Not Served</option>
                                @else
                                <option selected disabled>Choose one...</option>
                                <option value="Served">Served</option>
                                <option value="Not Served">Not Served</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Internet Service</label>
                            <select name="internet_system_status" class="form-control" >
                                @if($household->internet_system_status)
                                    <option value="{{$household->internet_system_status}}" disabled selected>
                                        {{$household->internet_system_status}}
                                    </option>
                                    <option value="Served">Served</option>
                                    <option value="Not Served">Not Served</option>
                                @else
                                <option selected disabled>Choose one...</option>
                                <option value="Served">Served</option>
                                <option value="Not Served">Not Served</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                </div>

                <hr>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>
                                <h4>Door to door Survey Questions</h4> 
                            </label>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Size of herd</label>
                            <input type="number" name="size_of_herd" 
                            value="{{$household->size_of_herd}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many structures?</label>
                            @if(!$structure)
                            <input type="number" name="number_of_structures" 
                            class="form-control">
                            @else
                            <input type="number" name="number_of_structures" 
                            value="{{$structure->number_of_structures}}"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many kitchens?</label>
                            @if(!$structure)
                            <input type="number" name="number_of_kitchens" 
                            class="form-control">
                            @else
                            <input type="number" name="number_of_kitchens" 
                            value="{{$structure->number_of_kitchens}}"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many animal shelters?</label>
                            @if(!$structure)
                            <input type="number" name="number_of_animal_shelters" 
                            class="form-control">
                            @else
                            <input type="number" name="number_of_animal_shelters" 
                            value="{{$structure->number_of_animal_shelters}}"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many cisterns?</label>
                            @if(!$cistern)
                            <input type="number" name="number_of_cisterns" 
                            class="form-control">
                            @else
                            <input type="number" name="number_of_cisterns" 
                            value="{{$cistern->number_of_cisterns}}"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cisterns volume?</label>
                            @if(!$cistern)
                            <input type="number" name="volume_of_cisterns" 
                            class="form-control">
                            @else
                            <input type="number" name="volume_of_cisterns" 
                            value="{{$cistern->volume_of_cisterns}}"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cisterns depth?</label>
                            @if(!$cistern)
                            <input type="number" name="depth_of_cisterns" 
                            class="form-control">
                            @else
                            <input type="number" name="depth_of_cisterns" 
                            value="{{$cistern->depth_of_cisterns}}"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Distance from house</label>
                            @if(!$cistern)
                            <input type="number" name="distance_from_house" 
                            class="form-control">
                            @else
                            <input type="number" name="distance_from_house" 
                            value="{{$cistern->distance_from_house}}"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Shared cistern?</label>
                            <select name="shared_cisterns"
                                class="form-control" >
                                @if($cistern)
                                <option selected disabled value="{{$cistern->shared_cisterns}}">
                                    {{$cistern->shared_cisterns}}
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
                            <label class='col-md-12 control-label'>Is there house in town?</label>
                            <select name="is_there_house_in_town"
                                class="form-control" >
                                @if($communityHousehold)
                                <option selected disabled 
                                    value="{{$communityHousehold->is_there_house_in_town}}">
                                    {{$communityHousehold->is_there_house_in_town}}
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
                            <label class='col-md-12 control-label'>Is there izbih?</label>
                            <select name="is_there_izbih"
                                class="form-control" >
                                @if($communityHousehold)
                                <option selected disabled
                                    value="{{$communityHousehold->is_there_izbih}}">
                                    {{$communityHousehold->is_there_izbih}}
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
                            <label class='col-md-12 control-label'>How long?</label>
                            @if(!$communityHousehold)
                            <input type="number" name="how_long" 
                            class="form-control">
                            @else
                            <input type="number" name="how_long" 
                            value="{{$communityHousehold->how_long}}"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Length of stay</label>
                            @if(!$communityHousehold)
                            <input type="number" name="length_of_stay" 
                            class="form-control">
                            @else
                            <input type="number" name="length_of_stay" 
                            value="{{$communityHousehold->length_of_stay}}"
                            class="form-control">
                            @endif
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Electricity source</label>
                            <select name="electricity_source" id="electricitySource" class="form-control">
                                @if($household->electricity_source)
                                    <option disabled selected value="{{$household->electricity_source}}">
                                        {{$household->electricity_source}}
                                    </option>
                                    <option value="Grid">Grid</option>
                                    <option value="Old Solar System">Old Solar System</option>
                                    <option value="Generator">Generator</option>
                                @else
                                    <option selected disabled>Choose one...</option>
                                    <option value="Grid">Grid</option>
                                    <option value="Old Solar System">Old Solar System</option>
                                    <option value="Generator">Generator</option>
                                @endif
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Shared?</label>
                            <select name="electricity_source_shared" id="electricitySourceShared"
                                class="form-control" >
                                <option selected disabled value="{{$household->electricity_source_shared}}">
                                    {{$household->electricity_source_shared}}
                                </option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
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