@extends('layouts/layoutMaster')
@include('layouts.all')
@section('title', 'create water request')

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

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Request Water Holder
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
        <form method="POST" action="{{url('water-request')}}" enctype="multipart/form-data" id="requestedWaterForm">
            @csrf
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Community</label>
                        <select class="selectpicker form-control" 
                            data-live-search="true" id="selectedWaterRequestCommunity"
                            name="community_id" required>
                            <option disabled selected>Choose one...</option>
                            @foreach($communities as $community)
                            <option value="{{$community->id}}">
                                {{$community->english_name}}
                            </option> 
                            @endforeach
                        </select>
                    </fieldset>
                    <div id="community_id_error" style="color: red;"></div>
                </div> 
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Request Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </fieldset>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>User/Public Structure</label>
                        <select name="public_user" id="userPublicSelected" 
                            class="selectpicker form-control" required>
                            <option disabled selected>Choose one...</option>
                            <option value="user">Household</option> 
                            <option value="public">Public Structure</option>
                        </select>
                    </fieldset>
                    <div id="public_user_error" style="color: red;"></div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Water System Holder</label>
                        <select name="household_public_id" id="selectedHouseholdPublic" disabled
                            class="selectpicker form-control" data-live-search="true">
                            <option disabled selected>Choose one...</option>
                        </select>
                    </fieldset>
                    <div id="household_public_id_error" style="color: red;"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Energy Holders (Main/Shared?)</label>
                        <input name="main_energy" class="selectpicker form-control" 
                            id="isMainSelectedHousehold" disabled>
                    </fieldset>
                    <span class="error" id="meterNumberSelectedHousehold"></span>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Water System?</label>
                        <input name="water_system" class="selectpicker form-control" 
                            id="waterSystemSelectedHousehold" disabled>
                    </fieldset>
                    <span class="error" id="waterSelectedHouseholdSpan"></span>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Status of request</label>
                        <select name="water_request_status_id" 
                            class="selectpicker form-control" data-live-search="true"
                            id="statusOfRequest">
                            <option disabled selected>Choose one...</option>
                            @foreach($requestStatuses as $requestStatus) 
                                <option value="{{$requestStatus->id}}">
                                    {{$requestStatus->name}}
                                </option>
                            @endforeach
                        </select>
                    </fieldset>
                    <div id="water_request_status_id_error" style="color: red;"></div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Recommended Water System Type</label>
                        <select name="water_system_type_id" id="recommendedWaterType"
                            class="selectpicker form-control" data-live-search="true" >
                            <option disabled selected>Choose one...</option>
                            @foreach($waterSystemTypes as $waterSystemType)
                            <option value="{{$waterSystemType->id}}">
                                {{$waterSystemType->type}}
                            </option>
                            @endforeach
                        </select>
                    </fieldset>
                    <div id="water_system_type_id_error" style="color: red;"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Referred by</label>
                        <textarea name="referred_by" class="form-control" 
                            style="resize:none" cols="20" rows="3"></textarea>
                    </fieldset>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
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
   
    var isMainSelectedHousehold = $('#isMainSelectedHousehold');
    var waterSystemSelectedHousehold = $('#waterSystemSelectedHousehold');

    $(document).on('change', '#selectedWaterRequestCommunity', function () {

        community_id = $(this).val();
        $('#meterNumberSelectedHousehold').html(" ");
        $('#waterSelectedHouseholdSpan').html(" ");

        $('#userPublicSelected').prop('disabled', false);

        UserOrPublic(community_id);

    });

    function UserOrPublic(community_id) {

        $(document).on('change', '#userPublicSelected', function () {
            publicUser = $('#userPublicSelected').val();
            
            if(publicUser == "user") {
            
                $.ajax({
                    url: "/water-request/get_by_community/" + community_id,
                    method: 'GET',
                    success: function(data) {

                        $('#selectedHouseholdPublic').prop('disabled', false);

                        var select = $('#selectedHouseholdPublic'); 

                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
                
            } else if(publicUser == "public") {

                $('#selectedHouseholdPublic').prop('disabled', true);
                $.ajax({
                    url: "/public/get_by_community/" + community_id,
                    method: 'GET',
                    success: function(data) {

                        $('#selectedHouseholdPublic').prop('disabled', false);
                        var select = $('#selectedHouseholdPublic'); 

                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
            }
        });
    }

    $(document).on('change', '#selectedHouseholdPublic', function () {

        household_public_id = $(this).val();
        is_household = $("#userPublicSelected").val();

        $.ajax({
            url: "/water-request/get_by_household/"+  household_public_id+ '/'+ is_household,
            method: 'GET',  
            success: function(data) {

                $('#meterNumberSelectedHousehold').html(" ");
                $('#waterSelectedHouseholdSpan').html(" ");

                if(data.energyUser == null) {

                    $('#meterNumberSelectedHousehold').html("He is not an energy user!");
                    isMainSelectedHousehold.val("No");
                }
                else {

                    isMainSelectedHousehold.val(data.energyUser["is_main"]);
                    $('#meterNumberSelectedHousehold').html("The meter Number is: "+ data.energyUser["meter_number"]);
                }
          
                if(data.waterDetails == null) waterSystemSelectedHousehold.val("No");
                else {

                    waterSystemSelectedHousehold.val("Yes");
                    $('#waterSelectedHouseholdSpan').html("To see his water system's details click : <a target='_blank' href='/all-water/"+ data.waterDetails["id"] +"'>here </a>");
                }
            }
        }); 
    });

    $('#requestedWaterForm').on('submit', function (event) {

        var communityValue = $('#selectedWaterRequestCommunity').val();
        var userOrPublicValue = $('#userPublicSelected').val();
        var requestedHolder = $('#selectedHouseholdPublic').val();
        var statusOfRequest = $('#statusOfRequest').val();
        var recommendedWaterType = $('#recommendedWaterType').val();

        if (communityValue == null) {

            $('#community_id_error').html('Please select a community!'); 
            return false;
        } else if (communityValue != null){

            $('#community_id_error').empty();
        }

        if (userOrPublicValue == null) {

            $('#public_user_error').html('Please select an option!'); 
            return false;
        } else if (userOrPublicValue != null){

            $('#public_user_error').empty();
        }

        if (requestedHolder == null) {

            $('#household_public_id_error').html('Please select a holder!'); 
            return false;
        } else if (requestedHolder != null){

            $('#household_public_id_error').empty();
        }

        if (statusOfRequest == null) {

            $('#water_request_status_id_error').html('Please select a status!'); 
            return false;
        } else if (statusOfRequest != null){

            $('#water_request_status_id_error').empty();
        }

        if (recommendedWaterType == null) {

            $('#water_system_type_id_error').html('Please select a type!'); 
            return false;
        } else if (recommendedWaterType != null){

            $('#water_system_type_id_error').empty();
        }

        $(this).addClass('was-validated');  
        $('#public_user_error').empty();  
        $('#household_public_id_error').empty();
        $('#community_id_error').empty();
        $('#water_request_status_id_error').empty();
        $('#water_system_type_id_error').empty();

        this.submit();
    });
</script>
@endsection