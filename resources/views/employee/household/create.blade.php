@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'communities')
@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js')}}"></script>
<!-- Flat Picker -->
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/tables-datatables-advanced.js')}}"></script>
<script type="text/javascript" 
src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" 
src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js" async defer></script>
@endsection
<style>
    label, input {
    display: block;
}

label {
    margin-top: 20px;
}
</style>
@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">Add </span> New Household
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">


            <form method="POST" action="{{url('household')}}" enctype="multipart/form-data" >
                @csrf
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Father/Husband Name</label>
                            <input type="text" name="arabic_name" placeholder="Write in Arabic"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Wife/Mother Name</label>
                            <input type="text" name="women_name_arabic" 
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
                            <input type="text" name="phone_number" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Community</label>
                            <select name="community_id" id="selectedCommunity" class="form-control" >
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
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many male?</label>
                            <input type="number" name="number_of_male" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many female?</label>
                            <input type="number" name="number_of_female" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many adults?</label>
                            <input type="number" name="number_of_adults" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>
                   

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many children under 16?</label>
                            <input type="number" name="number_of_children" 
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many children in school?</label>
                            <input type="number" name="school_students" 
                            class="form-control">
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How many household members in university?</label>
                            <input type="number" name="university_students" 
                            class="form-control">
                        </fieldset>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Demolition order in house?</label>
                            <select name="demolition_order"  class="form-control">
                                <option selected disabled>Choose One...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <input type="text" name="notes" 
                            class="form-control">
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
                            <label class='col-md-12 control-label'>How many structures?</label>
                            <input type="number" name="number_of_structures" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Size of herd</label>
                            <input type="number" name="size_of_herd" 
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
                            <label class='col-md-12 control-label'>How many cisterns?</label>
                            <input type="number" name="number_of_cisterns" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Electricity source</label>
                            <select name="electricity_source" id="electricitySource" class="form-control">
                                <option selected disabled>Choose One...</option>
                                <option value="yes">Old solar system</option>
                                <option value="no">Grid</option>
                                <option value="no">Generator</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Shared?</label>
                            <select name="electricity_source_shared" id="electricitySourceShared"
                                class="form-control" disabled>
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

@endsection

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script>


    $(document).on('change', '#selectedProfession', function () {
        
        selectedValue = $(this).val();

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

    $(document).on('change', '#electricitySource', function () {

        $('#electricitySourceShared').prop('disabled', false);
    });
</script>