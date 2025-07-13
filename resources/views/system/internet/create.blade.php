@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'internet system')

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
  <span class="text-muted fw-light">Add </span> New Internet System
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{url('internet-system')}}" id="internetSystemForm"
                enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <h6>General Details</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" name="community_id" 
                                data-live-search="true" id="communitySelected"
                                required>
                                <option disabled selected>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="community_id_error" style="color: red;"></div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Internet System Type</label>
                            <select name="internet_system_type_id[]" class="selectpicker form-control"
                                data-live-search="true" multiple id="internetSystemTypeSelected">
                                <option disabled selected>Choose one...</option>
                                @foreach($internetSystemTypes as $internetSystemType)
                                    <option value="{{$internetSystemType->id}}">
                                        {{$internetSystemType->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="internet_system_type_id_error" style="color: red;"></div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="system_name" 
                            class="form-control" required>
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Start Year</label>
                            <input type="number" name="start_year" 
                            class="form-control" required>
                        </fieldset>
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="2">
                            </textarea>
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

    $(document).ready(function() {

        $('#internetSystemForm').on('submit', function (event) {

            var communityValue = $('#communitySelected').val();
            var internetTypeValue = $('#internetSystemTypeSelected').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            if (!internetTypeValue || internetTypeValue.length === 0) {

                $('#internet_system_type_id_error').html('Please select at least one type!'); 
                return false;
            } else {

                $('#internet_system_type_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#internet_system_type_id_error').empty();
            $('#community_id_error').empty();

            this.submit();
        });
    });


    var router_counter = 0;
    var switch_counter = 0;
    var controller_counter = 0;
    var ap_counter = 0;
    var ap_lite_counter = 0;
    var ptp_counter = 0;
    var uisp_counter = 0;

    // Routers
    $(document).on('click', '#addRemoveRouterButton', function () {

        ++router_counter;
        $("#addRemoveRouter").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ router_counter +'" name="router_units[][subject]"></td>' +
            '<td><button type="button"' +
            'class="btn btn-outline-danger removeRouter">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeRouter', function () {
        $(this).parents('tr').remove();
    });

    // Switchs
    $(document).on('click', '#addRemoveSwitchButton', function () {

        ++switch_counter;
        $("#addRemoveSwitch").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ switch_counter +'"' +
            'name="switch_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeSwitch">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeSwitch', function () {
        $(this).parents('tr').remove();
    });
   
    // Controllers
    $(document).on('click', '#addRemoveControllerButton', function () {

        ++controller_counter;
        $("#addRemoveController").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ controller_counter +'"' +
            'name="controller_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeController">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeController', function () {
        $(this).parents('tr').remove();
    });
    
    // AP
    $(document).on('click', '#addRemoveApButton', function () {

        ++ap_counter;
        $("#addRemoveAp").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ ap_counter +'"' +
            'name="ap_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeAp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeAp', function () {
        $(this).parents('tr').remove();
    });
    
    // AP Lite
    $(document).on('click', '#addRemoveApLiteButton', function () {

        ++ap_lite_counter;
        $("#addRemoveApLite").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ ap_lite_counter +'"' +
            'name="ap_lite_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeAp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeAp', function () {
        $(this).parents('tr').remove();
    });
    
    // PTP
    $(document).on('click', '#addRemovePtpButton', function () {

        ++ptp_counter;
        $("#addRemovePtp").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ ptp_counter +'"' +
            'name="ptp_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removePtp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removePtp', function () {
        $(this).parents('tr').remove();
    });
    
    // UISP
    $(document).on('click', '#addRemoveUispButton', function () {

        ++uisp_counter;
        $("#addRemoveUisp").append('<tr><td></td>' +
            '<td><input class="form-control" data-id="'+ uisp_counter +'"' +
            'name="uisp_units[][subject]"></td><td><button type="button"' +
            'class="btn btn-outline-danger removeUisp">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.removeUisp', function () {
        $(this).parents('tr').remove();
    });

</script>
@endsection
