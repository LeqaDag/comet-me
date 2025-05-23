@extends('layouts/layoutMaster')

@section('title', 'requested households')

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
  <span class="text-muted fw-light">Add </span> New Requested Household
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{url('requested-household')}}" id="requstedHouseholdForm"
                enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Father/Husband Name</label>
                            <input type="text" name="english_name" 
                            placeholder="Write in English" value="{{old('english_name')}}"
                            class="form-control" required>
                            @if ($errors->has('english_name'))
                                <span class="error">{{ $errors->first('english_name') }}</span>
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Father/Husband Name</label>
                            <input type="text" name="arabic_name" placeholder="Write in Arabic"
                            class="form-control" value="{{old('arabic_name')}}" required>
                            @if ($errors->has('arabic_name'))
                                <span class="error">{{ $errors->first('arabic_name') }}</span>
                            @endif
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Wife/Mother Name</label>
                            <input type="text" value="{{old('women_name_arabic')}}" name="women_name_arabic" 
                            class="form-control">
                        </fieldset>
                    </div> 
                    
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Profession</label>
                            <select name="profession_id" id="selectedProfession" 
                                class="form-control" required>
                                <option disabled selected>Choose one...</option>
                                @foreach($professions as $profession)
                                <option value="{{$profession->id}}">
                                    {{$profession->profession_name}}
                                </option>
                                @endforeach
                                <!-- <option value="other" id="selectedOtherProfession" style="color:red">Other</option> -->
                            </select>
                            @if ($errors->has('profession_id'))
                                <span class="error">{{ $errors->first('profession_id') }}</span>
                            @endif
                        </fieldset>
                        <div id="profession_id_error" style="color: red;"></div>
                        @include('employee.household.profession')
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Recommended Energy System</label>
                            <select name="recommendede_energy_system_id" 
                                class="selectpicker form-control" id="recommendedValue"
                                data-live-search="true" required>
                                <option disabled selected>Choose one...</option>
                                @foreach($energySystems as $energySystem)
                                <option value="{{$energySystem->id}}">
                                    {{$energySystem->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="recommendede_energy_system_id_error" style="color: red;"></div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group"> 
                            <label class='col-md-12 control-label'>Community</label>
                            <select name="community_id" id="selectedCommunity" 
                                class="selectpicker form-control"
                                    data-live-search="true" required>
                                <option disabled selected>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">
                                    {{$community->english_name}}
                                </option>
                                @endforeach
                                <option value="other" id="selectedOtherCommunity" style="color:red">Other</option>
                            </select>
                            @include('employee.household.requested.create_community')
                            @if ($errors->has('community_id'))
                                <span class="error">{{ $errors->first('community_id') }}</span>
                            @endif
                        </fieldset>
                        <div id="community_id_error" style="color: red;"></div>
                    </div>
                </div> 

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Phone Number</label>
                            <input type="text" name="phone_number" value="{{old('phone_number')}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many male?</label>
                            <input type="number" name="number_of_male" value="{{old('number_of_male')}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many female?</label>
                            <input type="number" name="number_of_female" value="{{old('number_of_female')}}"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>
                   

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many adults?</label>
                            <input type="number" name="number_of_adults" value="{{old('number_of_adults')}}"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many children under 16?</label>
                            <input type="number" name="number_of_children" value="{{old('number_of_children')}}"
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many children in school?</label>
                            <input type="number" name="school_students" value="{{old('school_students')}}"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many household members in university?</label>
                            <input type="number" name="university_students" 
                            value="{{old('university_students')}}" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Demolition order in house?</label>
                            <select name="demolition_order" class="form-control">
                                <option selected disabled>Choose One...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea type="text" name="notes" style="resize:none"
                            class="form-control" rows="3"></textarea>
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
                            <label class='col-md-12 control-label'>How many structures?</label>
                            <input type="number" name="number_of_structures" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Size of herd</label>
                            <input type="number" name="size_of_herd" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many kitchens?</label>
                            <input type="number" name="number_of_kitchens" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many cisterns?</label>
                            <input type="number" name="number_of_cisterns" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Electricity source</label>
                            <select name="electricity_source" id="energySourceHousehold" 
                                class="form-control">
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Shared?</label>
                            <select name="electricity_source_shared" id="electricitySourceShared"
                                class="form-control">
                                <option selected disabled>Choose One...</option>
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

<script>
    $(document).on('change', '#selectedProfession', function () {
        
        selectedValue = $(this).val();

        if(selectedValue == "other") {
            $("#createProfession").modal('show');

            $(document).on('click', '#professionNameButton', function () {
                name = $("#professionName").val();
                $.ajax({
                    url: "household/" + name,
                    method: 'GET',
                    success: function(data) {
                        $("#createProfession").modal('hide');
                        $("#selectedProfession").append('<option value='+data.id+' selected="selected">'+data.name+'</option>');
                    }
                });
            });
        }
    });

    $(document).on('change', '#selectedCommunity', function () {
        
        selectedValue = $(this).val();

        $.ajax({
            url: "community/energy-source/" + selectedValue,
            method: 'GET',
            success: function(data) {

                $("#energySourceHousehold").html(data.html);
            }
        });

        if(selectedValue == "other") {
            $("#createOtherCommunity").modal('show');

            $(document).on('click', '#professionNameButton', function () {
                name = $("#professionName").val();
                $.ajax({
                    url: "household/" + name,
                    method: 'GET',
                    success: function(data) {
                        $("#createOtherCommunity").modal('hide');
                        $("#selectedCommunity").append('<option value='+data.id+' selected="selected">'+data.name+'</option>');
                    }
                });
            });
        }
    });

    $(document).on('change', '#energySourceHousehold', function () {

        $('#electricitySourceShared').prop('disabled', false);
    });

    $(document).ready(function () {

        $('#requstedHouseholdForm').on('submit', function (event) {

            var communityValue = $('#selectedCommunity').val();
            var professionValue = $('#selectedProfession').val();
            var recommendedValue = $('#recommendedValue').val();

            if (professionValue == null) {

                $('#profession_id_error').html('Please select a profession!'); 
                return false;
            } else if (professionValue != null){

                $('#profession_id_error').empty();
            }

            if (recommendedValue == null) {

                $('#recommendede_energy_system_id_error').html('Please select a value!'); 
                return false;
            } else if (recommendedValue != null){

                $('#recommendede_energy_system_id_error').empty();
            }

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            $(this).addClass('was-validated'); 
            $('#profession_id_error').empty(); 
            $('#community_id_error').empty();
            $('#recommendede_energy_system_id_error').empty();

            this.submit();
        });
    });
</script>
@endsection
