@extends('layouts/layoutMaster')

@include('layouts.all')

@section('title', 'create internet holder')

<style>
    label, input {

        display: block;
    }
    .dropdown-toggle {

        height: 40px;
    }
    label {

        margin-top: 20px;
    }
</style>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Internet Contract Holder
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
        <form method="POST" action="{{url('internet-user')}}" enctype="multipart/form-data" id="internetUserForm">
            @csrf
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Community</label>
                        <select class="selectpicker form-control" 
                            data-live-search="true" id="selectedInternetHolderCommunity"
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
                        <label class='col-md-12 control-label'>Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
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
                        <label class='col-md-12 control-label'>New Internet Holder</label>
                        <select name="internet_holder_id" id="selectedHouseholdPublic" disabled
                            class="selectpicker form-control" data-live-search="true">
                            <option disabled selected>Choose one...</option>
                        </select>
                    </fieldset>
                    <div id="internet_holder_id_error" style="color: red;"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Energy Holders (Main/Shared?)</label>
                        <input name="main_energy" class="selectpicker form-control" 
                            id="isMainSelectedHousehold" disabled>
                    </fieldset>
                    <span class="text-info" id="meterNumberSelectedHousehold"></span>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>Internet Contract</label>
                        <input name="" class="selectpicker form-control" 
                            id="internetContractHolder" disabled>
                    </fieldset>
                    <span class="text-info" id="internetContractHolderSpan"></span>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <fieldset class="form-group">
                        <label class='col-md-12 control-label'>System Type</label>
                        <select name="internet_type" 
                            class="selectpicker form-control" data-live-search="true"
                            id="internetSystemType">
                            <option disabled selected>Choose one...</option>
                            @foreach($internetSystemTypes as $internetSystemType) 
                                <option value="{{$internetSystemType->id}}">
                                    {{$internetSystemType->name}}
                                </option>
                            @endforeach
                        </select>
                    </fieldset>
                    <div id="internet_type_error" style="color: red;"></div>
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
   
    var isMainSelectedHousehold = $('#isMainSelectedHousehold');
    var waterSystemSelectedHousehold = $('#waterSystemSelectedHousehold');
    var meterNumberSelectedHousehold = $('#meterNumberSelectedHousehold');
    var internetContractHolder = $('#internetContractHolder');
    var internetContractHolderSpan = $('#internetContractHolderSpan');

    $(document).on('change', '#selectedInternetHolderCommunity', function () {

        community_id = $(this).val();
        $('#meterNumberSelectedHousehold').html(" ");
        $('#waterSelectedHouseholdSpan').html(" ");

        $('#userPublicSelected').prop('disabled', false);

        UserOrPublic(community_id);
    });

    function UserOrPublic(community_id) {

        $(document).on('change', '#userPublicSelected', function () {

            publicUser = $('#userPublicSelected').val();
            
            $.ajax({

                url: "/internet-user/get_by_community/" + community_id + '/' + publicUser,
                method: 'GET',
                success: function(data) {

                    $('#selectedHouseholdPublic').prop('disabled', false);
                    var select = $('#selectedHouseholdPublic'); 
                    select.html(data.html);
                    select.selectpicker('refresh');
                }
            });
        });
    }

    $(document).on('change', '#selectedHouseholdPublic', function () {

        internet_holder_id = $(this).val();
        is_household = $("#userPublicSelected").val();

        $.ajax({
            url: "/internet-user/get_by_household/"+  internet_holder_id+ '/'+ is_household,
            method: 'GET',  
            success: function(data) {

                meterNumberSelectedHousehold.html(" ");
                internetContractHolderSpan.html(" ");

                // 1. Main user/public, has a contract
                // 2. Main user/public, doesn't have a contract
                // 3. Shared user/public, sahred with another (view the main) - has a contract
                // 4. Shared user/public, sahred with another (view the main) - doesn't have a contract
                // 5. Young Holder, has a contract
                // 6. Young Holder, doesn't have a contract
                // 7. User doesn't exisit (Back to the database manager, he could be a young holder or shared)
                // 8. Public doesn't exisit (Back to the database manager, it could be a shared or out of comet)

                if(data.holders == null) { 

                    meterNumberSelectedHousehold.html("Doesn't has a meter!");
                    isMainSelectedHousehold.val("No");
                } else if(data.holders) {

                    if(data.holders["is_main"] == null) {

                        if(data.holders["internet_holder_young"] == 1) isMainSelectedHousehold.val("Young Holder");
                        else if(data.holders["Public"]) isMainSelectedHousehold.val("Out of Comet-me");
                    } else if(data.holders["is_main"] == "Yes") {
                        
                        isMainSelectedHousehold.val("Main Holder");
                        meterNumberSelectedHousehold.html("The meter Number is: "+ data.holders["meter_number"]);
                    } else if(data.mainUser) {

                        isMainSelectedHousehold.val("Shared Holder");
                        meterNumberSelectedHousehold.html("Shared with : "+ data.mainUser["english_name"]);
                    }
                }
          
                if(data.internetDetails == null) internetContractHolder.val("No");
                else internetContractHolder.val("Yes");
            }
        }); 
    });

    $('#internetUserForm').on('submit', function (event) {

        var communityValue = $('#selectedInternetHolderCommunity').val();
        var userOrPublicValue = $('#userPublicSelected').val();
        var requestedHolder = $('#selectedHouseholdPublic').val();
        var systemType = $('#internetSystemType').val();

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

            $('#internet_holder_id_error').html('Please select a holder!'); 
            return false;
        } else if (requestedHolder != null){

            $('#internet_holder_id_error').empty();
        }

        if (systemType == null) {

            $('#internet_type_error').html('Please select a type!'); 
            return false;
        } else if (systemType != null){

            $('#internet_type_error').empty();
        }

        $(this).addClass('was-validated');  
        $('#public_user_error').empty();  
        $('#internet_holder_id_error').empty();
        $('#community_id_error').empty();
        $('#internet_type_error').empty();

        this.submit();
    });
</script>
@endsection