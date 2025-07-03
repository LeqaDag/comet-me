@extends('layouts/layoutMaster')

@section('title', 'create energy system')

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
  <span class="text-muted fw-light">Add </span> New Energy System
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" action="{{url('energy-system')}}" id="energySystemForm"
                enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <h6>General Details</h6> 
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select class="selectpicker form-control" name="community_id" 
                                data-live-search="true" id="communitySelected"
                                >
                                <option disabled selected>Choose one...</option>
                                @foreach($communities as $community)
                                <option value="{{$community->id}}">{{$community->english_name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy System Type</label>
                            <select name="energy_system_type_id" class="selectpicker form-control"
                                data-live-search="true" id="energySystemTypeSelected">
                                <option disabled selected>Choose one...</option>
                                @foreach($energyTypes as $energyType)
                                    <option value="{{$energyType->id}}">
                                        {{$energyType->name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="energy_system_type_id_error" style="color: red;"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Name</label>
                            <input type="text" name="name" required
                            class="form-control">
                        </fieldset>
                    </div> 
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Installation Year</label>
                            <input type="number" name="installation_year" required
                            class="form-control">
                        </fieldset> 
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cycle Year</label>
                            <select name="energy_system_cycle_id" class="selectpicker form-control"
                                data-live-search="true">
                                <option disabled selected>Choose one...</option>
                                @foreach($energyCycles as $energyCycle)
                                    <option value="{{$energyCycle->id}}">
                                        {{$energyCycle->name}}
                                    </option>
                                @endforeach
                            </select>
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

        $('#energySystemForm').on('submit', function (event) {

            var energyTypeValue = $('#energySystemTypeSelected').val();

            if (energyTypeValue == null) {

                $('#energy_system_type_id_error').html('Please select a type!'); 
                return false;
            } else  if (energyTypeValue != null) {

                $('#energy_system_type_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#energy_system_type_id_error').empty();

            this.submit();
        });
    });

</script>
@endsection

