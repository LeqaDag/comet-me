@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'edit requested household')

@include('layouts.all')

<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
.dropdown-toggle{
        height: 40px;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Edit </span> {{$household->english_name}}
    <span class="text-muted fw-light">Information </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{route('served-household.update', $household->id)}}"
             enctype="multipart/form-data" >
                @csrf
                @method('PATCH')
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

                <label for=""></label>
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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

@endsection