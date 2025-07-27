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
                                data-live-search="true" id="communitySelected" required>
                                <option disabled selected>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="community_id_error" style="color: red;"></div>
                    </div> 
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Compound</label>
                            <select class="selectpicker form-control" name="compound_id" 
                                data-live-search="true" id="compoundSelected" required>
                            </select>
                        </fieldset>
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
                </div>

                <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-8 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="system_name" id="systemInternetName"
                            class="form-control" required>
                        </fieldset>
                    </div>
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

    $(document).on('change', '#communitySelected', function () {

        community_id = $(this).val();
        var communityName = $('#communitySelected option:selected').text();
        $("#systemInternetName").val(communityName + " Internet System");

        $.ajax({
            url: "/community-compound/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {

                $('#compoundSelected').prop('disabled', false);
                $('#compoundSelected').html(data.htmlCompounds);
                $('.selectpicker').selectpicker('refresh');
            }
        });
    });

    $(document).on('change', '#compoundSelected', function () {

        var compoundName = $('#compoundSelected option:selected').text();
        $("#systemInternetName").val(compoundName + " Internet System");
    });

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
</script>
@endsection
