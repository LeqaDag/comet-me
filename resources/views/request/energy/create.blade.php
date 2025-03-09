@extends('layouts/layoutMaster')
@include('layouts.all')
@section('title', 'create energy request')
<style>
    label, input{
    display: block;
}
.dropdown-toggle{
        height: 40px;
        
    }
label {
    margin-top: 20px;
}
</style>
@section('vendor-style')


@endsection


@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Request Energy System
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
        <form method="POST" action="{{url('energy-request')}}" enctype="multipart/form-data" 
            id="energyRequestedForm">
            @csrf

            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Father/Husband Name</label>
                        <div class="autocomplete-container">
                            <input type="text" name="english_name" id="householdEnglishName" 
                            placeholder="Write in English" value="{{old('english_name')}}"
                            class="form-control" required>
                        </div>
                    </fieldset>
                    <div id="english_name_error" style="color: red;"></div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Father/Husband Name</label>
                        <input type="text" name="arabic_name" placeholder="Write in Arabic"
                        class="form-control" value="{{old('arabic_name')}}" 
                        id="householdArabicName" required>
                    </fieldset>
                    <div id="arabic_name_error" style="color: red;"></div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Profession</label>
                        <select name="profession_id" id="selectedProfession" 
                            class="selectpicker form-control" required>
                            <option disabled selected>Choose one...</option>
                            @foreach($professions as $profession)
                            <option value="{{$profession->id}}">
                                {{$profession->profession_name}}
                            </option>
                            @endforeach
                        </select>
                    </fieldset>
                    <div id="profession_id_error" style="color: red;"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Phone Number</label>
                        <input type="text" name="phone_number" value="{{old('phone_number')}}"
                            class="form-control" id="phoneNumber" required>
                    </fieldset>
                    <div id="phone_error" style="color: red;"></div>
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
                    </fieldset>
                    <div id="community_id_error" style="color: red;"></div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Number of Residents</label>
                        <input type="number" name="number_of_people" id="totalResidents"
                        class="form-control" required>
                    </fieldset>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>How many male?</label>
                        <input type="number" name="number_of_male" value="{{old('number_of_male')}}"
                        class="form-control" id="numberOfMale" required>
                    </fieldset>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>How many female?</label>
                        <input type="number" name="number_of_female" value="{{old('number_of_female')}}"
                        class="form-control" id="numberOfFemale" required>
                    </fieldset>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>How many adults?</label>
                        <input type="number" name="number_of_adults" value="{{old('number_of_adults')}}"
                        class="form-control" id="numberOfAdult"required>
                    </fieldset>
                </div>
            </div>
                
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>How many children under 16?</label>
                        <input type="number" name="number_of_children" value="{{old('number_of_children')}}"
                        class="form-control" id="numberOfChildren"required>
                    </fieldset>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>How many children in school?</label>
                        <input type="number" name="school_students" value="{{old('school_students')}}"
                        class="form-control">
                    </fieldset>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>How many household members in university?</label>
                        <input type="number" name="university_students" 
                        value="{{old('university_students')}}" class="form-control">
                    </fieldset>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Demolition order in house?</label>
                        <select name="demolition_order" class="form-control" required>
                            <option selected disabled>Choose One...</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </fieldset>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Energy System Type</label>
                        <select name="energy_system_type_id" id="energySystemType"
                            class="selectpicker form-control" data-live-search="true" required>
                            <option disabled selected>Choose one...</option>
                            @foreach($energySystemTypes as $energySystemType)
                            <option value="{{$energySystemType->id}}">
                                {{$energySystemType->name}}
                            </option>
                            @endforeach
                        </select>
                    </fieldset>
                    <div id="energy_system_type_id_error" style="color: red;"></div>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Request Date</label>
                        <input type="date" name="request_date" class="form-control" required>
                    </fieldset>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Referred by</label>
                        <select name="referred_by_id" id="selectedReferredBy" 
                            class="selectpicker form-control"
                                data-live-search="true" required>
                            <option disabled selected>Choose one...</option>
                            @foreach($users as $user)
                            <option value="{{$user->id}}">
                                {{$user->name}}
                            </option>
                            @endforeach
                        </select>
                    </fieldset>
                    <div id="referred_by_error" style="color: red;"></div>
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
   
    $(document).on('change', '#selectedRequestCommunity', function () {

        community_id = $(this).val();
        $.ajax({
            url: "energy-request/get_by_community/" +  community_id,
            method: 'GET',  
            success: function(data) {
                $('#selectedRequestHousehold').prop('disabled', false);

                var select = $('#selectedRequestHousehold'); 

                select.html(data.html);
                select.selectpicker('refresh');
            }
        }); 
    }); 

    // Validate the form
    $('#energyRequestedForm').on('submit', function (event) {

        var professionValue = $('#selectedProfession').val();
        var communityValue = $('#selectedCommunity').val();
        var referredBy = $('#selectedReferredBy').val();
        var systemType = $('#energySystemType').val();

        
        if (professionValue == null) {

            $('#profession_id_error').html('Please select a profession!'); 
            return false;
        } else if (professionValue != null){

            $('#profession_id_error').empty();
        }

        if (communityValue == null) {

            $('#community_id_error').html('Please select a community!'); 
            return false;
        } else if (communityValue != null){

            $('#community_id_error').empty();
        }

        if (systemType == null) {

            $('#energy_system_type_id_error').html('Please select an option!'); 
            return false;
        } else if (systemType != null){

            $('#energy_system_type_id_error').empty();
        }

        if (referredBy == null) {

            $('#referred_by_error').html('Please select a user!'); 
            return false;
        } else if (referredBy != null){

            $('#referred_by_error').empty();
        }

        $(this).addClass('was-validated');  
        $('#referred_by_error').empty();  
        $('#energy_system_type_id_error').empty();
        $('#community_id_error').empty();
        $('#profession_id_error').empty();

        this.submit();
    });
</script>
@endsection