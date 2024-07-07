@extends('layouts/layoutMaster')

@section('title', 'Elc')

@include('layouts.all')
<style>
    label, input{
    display: block;
}
label {
    margin-top: 20px;
}
</style>
@section('vendor-style')


@endsection 


@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Elc.
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" enctype='multipart/form-data' id="elecUserForm" 
                action="{{url('progress-household')}}">
                @csrf
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>New/Old Community</label>
                            <select name="misc" id="selectedUserMisc" data-live-search="true"
                                class="selectpicker form-control" data-parsley-required="true" 
                                required>
                                <option disabled selected>Choose one...</option>
                                @foreach($installationTypes as $installationType)
                                    <option value="{{$installationType->id}}">
                                        {{$installationType->type}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="misc_error" style="color: red;"></div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control"
                                data-live-search="true" 
                                name="community_id" id="selectedUserCommunity"
                                data-parsley-required="true">
                            </select>
                        </fieldset>
                        <div id="community_id_error" style="color: red;"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Users</label>
                            <select name="household_id[]" id="selectedUserHousehold" 
                                class="selectpicker form-control" data-live-search="true" 
                                multiple disabled required>
                                <option disabled selected>Choose one...</option>
                            </select>
                        </fieldset>
                        <div id="household_id_error" style="color: red;"></div>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Doesn't exist?</label>
                            <button type="button" class="form-control btn btn-success" 
                                data-bs-toggle="modal" data-bs-target="#createNewHousehold">
                                Create Now
                            </button>
                            
                        </fieldset>
                    </div>

                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System Type</label>
                            <select name="energy_system_type_id" data-parsley-required="true"
                                class="selectpicker form-control" id="selectedEnergySystemType"data-live-search="true">
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
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System</label>
                            <select name="energy_system_id" id="selectedEnergySystem" 
                                class="form-control" data-parsley-required="true" disabled>
                                <option disabled selected>Choose one...</option>
                            </select>
                        </fieldset>
                        <div id="energy_system_id_error" style="color: red;"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cycle Year</label>
                            <select name="energy_system_cycle_id" data-parsley-required="true"
                                class="selectpicker form-control" id="energySystemCycleSelected"
                                data-live-search="true">
                                <option disabled selected>Choose one...</option>
                                @foreach($energySystemCycles as $energySystemCycle)
                                    <option value="{{$energySystemCycle->id}}">
                                        {{$energySystemCycle->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="energy_system_cycle_id_error" style="color: red;"></div>
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

<div id="createNewHousehold" class="modal fade"  role="dialog" tabindex="-1" 
        aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" >
                    Create New Household
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="_token" id="csrf" value="{{Session::token()}}">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select name="community_id" id="selectedCommunity" 
                                class="selectpicker form-control" 
                                data-live-search="true" >
                                <option disabled selected>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">
                                    {{$community->english_name}}
                                </option>
                                @endforeach
                                <option value="other" id="selectedOtherCommunity" style="color:red">Other</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Father/Husband Name</label>
                            <input type="text" name="english_name" id="english_name"
                            placeholder="Write in English"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Father/Husband Name</label>
                            <input type="text" name="arabic_name" placeholder="Write in Arabic"
                            id="arabic_name" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Wife/Mother Name</label>
                            <input type="text" name="women_name_arabic" id="women_name_arabic"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Profession</label>
                            <select name="profession_id" id="selectedProfession" class="form-control" >
                                <option disabled selected>Choose one...</option>
                                @foreach($professions as $profession)
                                <option value="{{$profession->id}}">
                                    {{$profession->profession_name}}
                                </option>
                                @endforeach
                                <option value="other" id="selectedOtherProfession" style="color:red">Other</option>
                            </select>
                        </fieldset>
                        @include('employee.household.profession')
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many male?</label>
                            <input type="number" name="number_of_male" id="number_of_male"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many female?</label>
                            <input type="number" name="number_of_female" id="number_of_female"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>
                   

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many adults?</label>
                            <input type="number" name="number_of_adults" id="number_of_adults"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many children under 16?</label>
                            <input type="number" name="number_of_children" id="number_of_children"
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many children in school?</label>
                            <input type="number" name="school_students" id="school_students"
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many household members in university?</label>
                            <input type="number" name="university_students" id="university_students"
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <button type="button" class="btn btn-secondary" 
                    id="newHouseholdButton">Submit
                </button>
            </div>
        </div>
    </div>
</div>

<script>
   
    $(document).on('change', '#selectedUserMisc', function () {

        installation_type = $(this).val();
        $.ajax({
            url: "household/get_community_type/" +  installation_type,
            method: 'GET',  
            success: function(data) {
                $('#selectedUserCommunity').prop('disabled', false);

                var select = $('#selectedUserCommunity'); 

                select.html(data.html);
                select.selectpicker('refresh');
            }
        }); 

    });

    $(document).on('change', '#selectedUserCommunity', function () {

        community_id = $(this).val();
        $.ajax({
            url: "household/get_un_user_by_community/" +  community_id,
            method: 'GET',  
            success: function(data) {
                $('#selectedUserHousehold').prop('disabled', false);

                var select = $('#selectedUserHousehold'); 

                select.html(data.html);
                select.selectpicker('refresh');
            }
        }); 

        energy_type_id= $("#selectedEnergySystemType").val();

        changeEnergySystemType(energy_type_id, community_id);
    });

    $(document).on('change', '#selectedEnergySystemType', function () {

        energy_type_id = $(this).val();

        if(energy_type_id == 1 || energy_type_id == 3 || energy_type_id == 4) {

            community_id = $("#selectedUserCommunity").val();
        } else {

            community_id = 0;
        }

        changeEnergySystemType(energy_type_id, community_id);
    });

    function changeEnergySystemType(energy_type_id, community_id) {

        $.ajax({
            url: "energy-user/get_by_energy_type/" + energy_type_id + "/" + community_id,
            method: 'GET',
            success: function(data) {
                $('#selectedEnergySystem').prop('disabled', false);
                $('#selectedEnergySystem').html(data.html);
            }
        });
    }

    $(document).ready(function () {

        $('#elecUserForm').on('submit', function (event) {

            var miscValue = $('#selectedUserMisc').val();
            var communityValue = $('#selectedUserCommunity').val();
            var householdValue = $('#selectedUserHousehold').val();
            var energyTypeValue = $('#selectedEnergySystemType').val();
            var energyValue = $('#selectedEnergySystem').val();
            var energyCycle = $('#energySystemCycleSelected').val();

            if (miscValue == null) {

                $('#misc_error').html('Please select an option!'); 
                return false;
            } else if (miscValue != null){

                $('#misc_error').empty();
            }

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            if (!householdValue || householdValue.length === 0) {

                $('#household_id_error').html('Please select at least one household!');
                return false;
            } else {

                $('#household_id_error').empty();
            }

            if (energyTypeValue == null) {

                $('#energy_system_type_id_error').html('Please select a Energy System Type!'); 
                return false;
            } else if (energyTypeValue != null){

                $('#energy_system_type_id_error').empty();
            } 

            if (energyValue == null) {

                $('#energy_system_id_error').html('Please select an Energy System!'); 
                return false;
            } else if (energyValue != null){

                $('#energy_system_id_error').empty();
            }

            if (energyCycle == null) {

                $('#energy_system_cycle_id_error').html('Please select an Energy cycle!'); 
                return false;
            } else if (energyCycle != null){

                $('#energy_system_cycle_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#misc_error').empty(); 
            $('#community_id_error').empty();
            $('#household_id_error').empty();
            $('#energy_system_type_id_error').empty();
            $('#energy_system_id_error').empty();
            $('#energy_system_cycle_id_error').empty();

            this.submit();
        });
    });

    $(function() {

        $('#newHouseholdButton').on('click', function(e) {
            e.preventDefault(e); 
            community_id = $("#selectedCommunity").val();
            english_name = $("#english_name").val();
            profession_id = $("#selectedProfession").val();
            arabic_name = $("#arabic_name").val();
            university_students = $("#university_students").val();
            school_students = $("#school_students").val();
            number_of_children = $("#number_of_children").val();
            number_of_adults = $("#number_of_adults").val();
            number_of_female = $("#number_of_female").val();
            number_of_male = $("#number_of_male").val();
            phone_number = $("#phone_number").val();
            women_name_arabic = $("#women_name_arabic").val();

            $.ajax({
                type: "get",
                url: 'household/new',
                data:{
                    _token: $("#csrf").val(),
                    community_id: community_id,
                    english_name : english_name,
                    arabic_name : arabic_name,
                    profession_id : profession_id,
                    university_students: university_students,
                    school_students: school_students,
                    number_of_children: number_of_children,
                    number_of_adults: number_of_adults,
                    number_of_female: number_of_female,
                    number_of_male: number_of_male,
                    phone_number: phone_number,
                    women_name_arabic: women_name_arabic,
                },
                dataType: 'json',
                success: function(data) {
                    $('body').removeClass('modal-open');
                    $('body').css('padding-right', '');
                    $(".modal-backdrop").remove();
                    $("#createNewHousehold").modal("hide");
                    $('#selectedUserHousehold').html(data.html);
                }
            })
        });
    });

</script>

@endsection