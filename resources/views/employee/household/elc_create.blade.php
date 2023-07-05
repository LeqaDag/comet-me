@extends('layouts/layoutMaster')

@section('title', 'Elc')
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

@endsection


@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Elc.
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" enctype='multipart/form-data' action="{{url('progress-household')}}">
                @csrf
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>New/Old Community</label>
                            <select name="misc" id="selectedUserMisc" 
                                class="form-control" required>
                                <option disabled selected>Choose one...</option>
                                <option value="0">New Community</option>
                                <option value="1">MISC FBS</option> 
                                <option value="2">MG extension</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" 
                                data-live-search="true" 
                                name="community_id" id="selectedUserCommunity"
                                required>
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
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Users</label>
                            <select name="household_id" id="selectedUserHousehold" 
                            class="form-control" disabled required>
                                <option disabled selected>Choose one...</option>
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Doesn't exist?</label>
                            <button type="button" class="form-control btn btn-success" 
                                data-bs-toggle="modal" data-bs-target="#createNewHousehold">
                                Create Now
                            </button>
                            @include('employee.household.new_create')
                        </fieldset>
                    </div>

                </div>

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Energy System Type</label>
                        <select name="energy_system_type_id" 
                            class="form-control" id="selectedEnergySystemType">
                            <option disabled selected>Choose one...</option>
                            @foreach($energySystemTypes as $energySystemType)
                                <option value="{{$energySystemType->id}}">
                                    {{$energySystemType->name}}
                                </option>
                            @endforeach
                        </select>
                    </fieldset>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Energy System</label>
                        <select name="energy_system_id" id="selectedEnergySystem" 
                            class="form-control" disabled required>
                            <option disabled selected>Choose one...</option>
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


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
   
    $(document).on('change', '#selectedUserCommunity', function () {

        community_id = $(this).val();
        $.ajax({
            url: "household/get_by_community/" +  community_id,
            method: 'GET',  
            success: function(data) {
                $('#selectedUserHousehold').prop('disabled', false);
                $('#selectedUserHousehold').html(data.html);
            }
        });

        energy_type_id= $("#selectedEnergySystemType").val();

        changeEnergySystemType(energy_type_id, community_id);
    });

    $(document).on('change', '#selectedEnergySystemType', function () {

        energy_type_id = $(this).val();

        if(energy_type_id == 1 || energy_type_id == 3) {

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