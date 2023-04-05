@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Ac')
<style>
    label, input{
    display: block;
}
.dropdown-toggle{
        height: 40px;
        width: 370px !important;
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
            <form method="POST" enctype='multipart/form-data' action="{{url('ac-household')}}">
                @csrf
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>New/Old Community</label>
                            <select name="misc" id="selectedUserMisc" 
                                class="form-control" required>
                                <option disabled selected>Choose one...</option>
                                <option value="1">MISC FBS/MG extension</option> 
                                <option value="0">New Community</option>
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
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
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Users</label>
                            <!-- <select name="household_id" id="selectedUserHousehold" 
                            class="form-control" disabled required>
                                <option disabled selected>Choose one...</option>
                            </select> -->
                            <select class="selectpicker form-control" 
                                multiple data-live-search="true" 
                                name="household_id[]" id="selectedHouseholdMeter"
                                required>
                                @foreach($households as $household)
                                    <option value="{{$household->id}}">
                                        {{$household->english_name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>

                <!-- <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Shared Household?</label>
                            <select name="shared_household-meter" 
                                class="form-control" id="selectedSharedMeter" disabled>
                                <option disabled selected>Choose one...</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Household</label>
                            <select class="selectpicker form-control" 
                                multiple data-live-search="true" 
                                name="household_meter_id[]" id="selectedHouseholdMeter"
                                required>
                            </select>
                        </fieldset>
                    </div>
                </div> -->

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


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


<script>

    // $(document).on('change', '#selectedUserMisc', function () {
    //     misc = $(this).val();
        
    //     $.ajax({
    //         url: "energy-user/get_misc/" + misc,
    //         method: 'GET',
    //         success: function(data) {
    //             $('#selectedUserCommunity').prop('disabled', false);
    //             $('#selectedUserCommunity').html(data.html);
                
    //             $('#selectedSharedMeter').prop('disabled', false);

    //             $(document).on('change', '#selectedUserCommunity', function () {
    //                 community_id = $(this).val();
            
    //                 $.ajax({
    //                     url: "energy-user/get_by_community/" + community_id + "/" + misc,
    //                     method: 'GET',
    //                     success: function(data) {
    //                         $('#selectedUserHousehold').prop('disabled', false);
    //                         $('#selectedUserHousehold').html(data.html);
                            
    //                         $('#selectedSharedMeter').prop('disabled', false);
    //                         $(document).on('change', '#selectedSharedMeter', function () {

    //                             user_id = $("#selectedUserHousehold").val();

    //                             $.ajax({
    //                                 url: "energy-user/shared_household/" + community_id + "/" + user_id,
    //                                 method: 'GET',
    //                                 success: function(data) {
    //                                     var options = '';

    //                                     // for (var i = 0; i < data.html.length; i++) {
    //                                     // options += '<option value="' + data.html[i].id + '">' + data.html[i].english_name + '</option>';
    //                                     // }
    //                                     //$('#selectedHouseholdMeter').prop('disabled', false);
                                        
    //                                     $("#selectedHouseholdMeter").selectpicker('refresh');
    //                                    $("#selectedHouseholdMeter").append(data.html);
    //                                    // $("#selectedHouseholdMeter").selectpicker("refresh");

    //                                     //
    //                                     //$('#selectedHouseholdMeter').append(data.html);
    //                                     //$("#selectedHouseholdMeter").html(options).multiselect('refresh');
    //                                 }
    //                             });
    //                         });
    //                     }
    //                 });
    //             });

    //             // $(document).on('change', '#selectedSharedMeter', function () {

    //             //     user_id = $("#selectedUserHousehold").val();

    //             //     $.ajax({
    //             //         url: "energy-user/shared_household/" + community_id + "/" + user_id,
    //             //         method: 'GET',
    //             //         success: function(data) {
                         
    //             //             $('#selectedHouseholdMeter').prop('disabled', false);
    //             //             $('#selectedHouseholdMeter').append(data.html);
    //             //             $('.selectpicker').selectpicker('refresh');
    //             //         }
    //             //     });
    //             // });
    //         }
    //     });
    // });


    $(document).on('change', '#selectedEnergySystemType', function () {
        energy_type_id = $(this).val();
   
        $.ajax({
            url: "energy-user/get_by_energy_type/" + energy_type_id,
            method: 'GET',
            success: function(data) {
                $('#selectedEnergySystem').prop('disabled', false);
                $('#selectedEnergySystem').html(data.html);
            }
        });
    });

</script>

@endsection


