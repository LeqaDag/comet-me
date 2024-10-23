@extends('layouts/layoutMaster')

@section('title', 'households')

@include('layouts.all')

<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }

    /* Override Smoothness theme styles */
    .ui-autocomplete {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100% - 70px);
        padding-left: 10px;
        background-color: #fff;
        border: 1px solid #ccc;
        border-top: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        max-height: 200px;
        overflow-y: auto;
    }

    /* Center-align the text and add padding */
    .ui-autocomplete .ui-menu-item {
        padding: 30px;
        cursor: pointer;
        text-align: center; /* Center-align the text */
    }

    /* Apply hover effect to the dropdown items */ 
    .ui-autocomplete .ui-menu-item:hover {
        background-color: #f0f0f0;
    }

</style>

@section('content') 
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Household
</h4> 

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" id="householdForm" action="{{url('household')}}" 
                enctype="multipart/form-data" novalidate>
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
                                <option value="other" id="selectedOtherProfession" style="color:red">Other</option>
                            </select>
                        </fieldset>
                        <div id="profession_id_error" style="color: red;"></div>
                        @include('employee.household.profession')
                    </div>
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
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Number of Residents</label>
                            <input type="number" name="number_of_people" id="totalResidents"
                            class="form-control" required>
                        </fieldset>
                    </div>
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
                </div>
                   
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many adults?</label>
                            <input type="number" name="number_of_adults" value="{{old('number_of_adults')}}"
                            class="form-control" id="numberOfAdult"required>
                        </fieldset>
                    </div>
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

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System Type</label>
                            <select name="energy_system_type_id" id="energySystemType"
                                class="selectpicker form-control" data-live-search="true" >
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
                </div>
                
                <div class="row"> 
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cycle Year</label>
                            <select name="energy_system_cycle_id" data-live-search="true"
                            class="selectpicker form-control" id="energyCycleYear">
                            <option disabled selected>Choose one...</option>
                                @foreach($energyCycles as $energyCycle)
                                <option value="{{$energyCycle->id}}">
                                    {{$energyCycle->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="energy_system_cycle_id_error" style="color: red;"></div>
                    </div> 
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                   style="resize:none" cols="20" rows="3"></textarea>
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
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many structures?</label>
                            <input type="number" name="number_of_structures" 
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
                            <label class='col-md-12 control-label'>How many animal shelters?</label>
                            <input type="number" name="number_of_animal_shelters" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many caves?</label>
                            <input type="number" name="number_of_cave" 
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
                            <label class='col-md-12 control-label'>Cisterns volume? (Liters)</label>
                            <input type="number" name="volume_of_cisterns" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cisterns depth? (Meters)</label>
                            <input type="number" name="depth_of_cisterns" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Distance from house (Meters)</label>
                            <input type="number" name="distance_from_house" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Shared cistern?</label>
                            <select name="shared_cisterns"
                                class="form-control" >
                                <option selected disabled>Choose one...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Is there house in town?</label>
                            <select name="is_there_house_in_town"
                                class="form-control" >
                                <option selected disabled>Choose one...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Is there izbih?</label>
                            <select name="is_there_izbih"
                                class="form-control" >
                                <option selected disabled>Choose one...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How long? (Months)</label>
                            <input type="number" name="how_long" 
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Length of stay (Months)</label>
                            <input type="number" name="length_of_stay" 
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
                            <label class='col-md-12 control-label'>Electricity Shared?</label>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>

    $('#householdForm').on('submit', function (event) {

        var englishValue = $('#householdEnglishName').val();
        var arabicValue = $('#householdArabicName').val();
        var phoneValue = $('#phoneNumber').val();
        var communityValue = $('#selectedCommunity').val();
        var professionValue = $('#selectedProfession').val();
        var energySystemType = $('#energySystemType').val();
        var energyCycleValue = $('#energyCycleYear').val();

        if (englishValue == null) {

            $('#english_name_error').html('Please type an English name!');
            return false;
        } else if (englishValue != null){

            $('#english_name_error').empty();
        }

        if (arabicValue == null) {

            $('#arabic_name_error').html('Please type an Arabic name!');
            return false;
        } else if (arabicValue != null){

            $('#arabic_name_error').empty();
        }

        if (phoneValue == null) {

            $('#phone_error').html('Please type the phone number!');
            return false;
        } else if (phoneValue != null){

            $('#phone_error').empty();
        }

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

        if (energyCycleValue == null) {

            $('#energy_system_cycle_id_error').html('Please select the cycle year!'); 
            return false;
        } else if (energyCycleValue != null) {

            $('#energy_system_cycle_id_error').empty();
        }

        if (energySystemType == null) {

            $('#energy_system_type_id_error').html('Please select a type!'); 
            return false;
        } else if (energySystemType != null) {

            $('#energy_system_type_id_error').empty();
        }

        $(this).addClass('was-validated');  
        $('#english_name_error').empty();
        $('#arabic_name_error').empty();
        $('#phone_error').empty();
        $('#community_id_error').empty();
        $('#profession_id_error').empty();
        $('#energy_system_cycle_id_error').empty();
        $('#energy_system_type_id_error').empty();

        this.submit();
    });
    // $(document).ready(function () {

    //     $("#householdEnglishName").autocomplete({

    //         source: function(request, response) {
    //             $.ajax({
    //                 url: "household/autocomplete/" + request.term,
    //                 dataType: "json",
    //                 success: function(data) {

    //                     response(data);
    //                 }
    //             });
    //         },
    //         minLength: 2 // Minimum characters before triggering autocomplete
    //     });
    // });

    $(document).ready(function () {

        var oldSelectProfession;
        var oldSelectCommunity;

        $('#selectedProfession').on('focus', function () {

            oldSelectProfession = $(this).val();
        });

        $('#householdEnglishName').keyup(function () {
            validateEnglishName();
        });
        $('#householdArabicName').keyup(function () {
            validateArabicName();
        });
        $('#selectedCommunity').keyup(function () {
            validateSelectCommunity();
        });
        $('#phoneNumber').keyup(function () {
            validatePhoneNumber();
        });

        $('#householdForm').submit(function (e) {
            if (!validateEnglishName() && !validateArabicName() && !validateSelectCommunity()) {
                e.preventDefault();
                // Prevent form submission if any validation fails
            }
        });

        function validateEnglishName() {

            var english_name = $('#householdEnglishName').val();
            if (english_name.length < 10) {
                $('#english_name_error').html('English Name must be at least 10 characters.');
                return false;
            } else {
                $('#english_name_error').html('');
                return true;
            }
        }

        function validateArabicName() {

            var arabic_name = $('#householdArabicName').val();
            var arabicRegex = /[\u0600-\u06FF]/; // Unicode range for Arabic script
            if (!arabicRegex.test(arabic_name)) {
                $('#arabic_name_error').html('Please enter text with Arabic characters.');
                return false;
            } else {
                $('#arabic_name_error').html('');
                return true;
            }
        }

        function validateSelectCommunity() {
            var selectedValue = $('#selectedCommunity').val();
            if (selectedValue === '') {
                $('#community_error').html('Please select community');
                return false;
            } else {
                $('#community_error').html('');
                return true;
            }
        }

        function validatePhoneNumber() {
            var phoneNumber = $('#phoneNumber').val();
            var phoneRegex = /^\d{10}$/; // 10 digits
            if (!phoneRegex.test(phoneNumber)) {
                $('#phone_error').html('Please enter a valid 10-digit phone number.');
                return false;
            } else {
                $('#phone_error').html('');
                return true;
            }
        }

        // Other field validation functions...
    });

    $(document).on('change', '#selectedProfession', function () {

        selectedValue = $(this).val();
        oldSelectProfession = selectedValue;

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
            $("#createCommunity").modal('show');

            $(document).on('click', '#professionNameButton', function () {
                name = $("#professionName").val();
                $.ajax({
                    url: "household/" + name,
                    method: 'GET',
                    success: function(data) {
                        $("#createCommunity").modal('hide');
                        $("#selectedCommunity").append('<option value='+data.id+' selected="selected">'+data.name+'</option>');
                    }
                });
            });
        }
    });

    $(document).on('change', '#energySourceHousehold', function () {

        $('#electricitySourceShared').prop('disabled', false);
    });
</script>
@endsection