@extends('layouts/layoutMaster')

@section('title', 'create new community')

@include('layouts.all')

<style>
    label, input{ 
    display: block;
}
label {
    margin-top: 20px;
}
</style>

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">Create </span> New
    <span class="text-muted fw-light">Community </span> 
</h4>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body">
            <form method="POST" id="communityForm" action="{{url('community')}}" 
                enctype="multipart/form-data" >
                @csrf
                <div class="row" style="margin-top:12px">
                    <h5>General Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>English Name</label>
                            <input type="text" name="english_name" 
                            class="form-control" required>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Arabic Name</label>
                            <input type="text" name="arabic_name" class="form-control"
                                required>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'># of Families</label>
                            <input type="number" name="number_of_household" class="form-control"
                                required>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Fallah</label>
                            <select name="is_fallah" class="selectpicker form-control" 
                                id="is_fallah" required data-parsley-required="true">
                                <option disabled selected>Choose one...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <div id="is_fallah_error" style="color: red;"></div>
                        </fieldset> 
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Bedouin</label>
                            <select name="is_bedouin" id="is_bedouin" required
                                class="selectpicker form-control" data-parsley-required="true">
                                <option disabled selected>Choose one...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                        <div id="is_bedouin_error" style="color: red;"></div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Products type</label>
                            <select name="product_type_id[]" id="product_type_id" 
                                class="selectpicker form-control" data-live-search="true"  
                                multiple>
                                <option disabled selected>Choose one...</option>
                                @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cellular Reception?</label>
                            <select name="reception" class="form-control">
                                <option disabled selected>Choose one...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Region</label>
                            <select name="region_id" id="selectedRegion" 
                                class="selectpicker form-control" data-live-search="true" 
                                    required data-parsley-required="true">
                                <option disabled selected>Choose one...</option>
                                @foreach($regions as $region)
                                <option value="{{$region->id}}">
                                    {{$region->english_name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                        <div id="region_id_error" style="color: red;"></div>
                    </div> 
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Sub Region</label>
                            <select name="sub_region_id" id="selectedSubRegions" 
                            class="selectpicker form-control" disabled 
                                required data-parsley-required="true">
                                <option disabled selected>Choose one...</option>
                            </select>
                        </fieldset>
                        <div id="sub_region_id_error" style="color: red;"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Cycle Year</label>
                            <select name="energy_system_cycle_id" id="selectedCycleYear" 
                                class="selectpicker form-control" data-live-search="true" 
                                    required data-parsley-required="true">
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
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Energy Sources</label>
                            <select class="form-control" name="energy_source">
                                <option disabled selected>Choose one...</option>
                                <option value="Grid">Grid</option>
                                <option value="Old Solar System">Old Solar System</option>
                            </select>
                        </fieldset>
                    </div> 
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Recommended system type</label>
                            <select name="recommended_energy_system_id[]" 
                                class="selectpicker form-control" multiple data-live-search="true">
                                <option disabled selected>Choose one...</option>
                                @foreach($energyTypes as $energyType)
                                <option value="{{$energyType->id}}">{{$energyType->name}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Water Sources</label>
                            <select class="selectpicker form-control" multiple data-live-search="true" 
                                name="waters[]">
                                @foreach($waterSources as $waterSource)
                                <option value="{{$waterSource->id}}">
                                    {{$waterSource->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Nearby Towns</label>
                            <select class="selectpicker form-control" multiple data-live-search="true" 
                                name="towns[]" id="nearbyTowns">
                                @foreach($towns as $town)
                                <option value="{{$town->id}}">
                                    {{$town->english_name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Nearby Settlements</label>
                            <select class="selectpicker form-control" multiple data-live-search="true" 
                                name="settlement[]" id="nearbySettlements">
                                @foreach($settlements as $settlement)
                                <option value="{{$settlement->id}}">
                                    {{$settlement->english_name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Public Structures</label>
                            <select class="selectpicker form-control" 
                                multiple data-live-search="true" 
                                name="public_structures[]" id="publicStructures">
                                @foreach($publicCategories as $publicCategorie)
                                <option value="{{$publicCategorie->id}}">
                                    {{$publicCategorie->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Latitude</label>
                            <input type="text" name="latitude" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Longitude</label>
                            <input type="text" name="longitude" class="form-control">
                        </fieldset>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Notes</label>
                            <textarea name="notes" class="form-control" 
                                style="resize:none" cols="20" rows="2"></textarea>
                        </fieldset>
                    </div>
                </div> 
                <hr style="margin-top:20px">
                <div class="row" >
                    <h5>Second Name for community</h5>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Second Name in English</label>
                            <input name="second_name_english" type="text" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Second Name in Arabic</label>
                            <input name="second_name_arabic" type="text" class="form-control">
                        </fieldset>
                    </div> 
                </div> 

                <hr style="margin-top:20px">
                <div class="row" >
                    <h5>Compounds</h5>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                        <table class="table table-bordered" id="dynamicAddRemoveCompoundName">
                            <tr>
                                <th>Compound Name</th>
                                <th>Options</th>
                            </tr>
                            <tr> 
                                <td>
                                    <input type="text" name="addMoreInputFieldsCompoundName[0][subject]" 
                                    placeholder="Enter English Copmound Name" class="target_point form-control" 
                                    data-id="0"/>
                                </td>
                                <td>
                                    <button type="button" name="add" id="addCompoundNameButton" 
                                    class="btn btn-outline-primary">
                                        Add More
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr style="margin-top:20px">
                <div class="row" >
                    <h5>Legal Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Demolition orders/demolitions </label>
                            <select name="demolition" class="form-control">
                                <option disabled selected>Choose one...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>How Many Demolitions?</label>
                            <input type="text" name="demolition_number" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Have demolition orders been executed?</label>
                            <select name="demolition_executed" class="form-control">
                                <option disabled selected>Choose one...</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>When was the last demolition?</label>
                            <input type="date" name="last_demolition" class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Land Status</label>
                            <input type="text" name="land_status" 
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Lawyer</label>
                            <input type="text" name="lawyer" class="form-control">
                        </fieldset>
                    </div>
                </div>
                    
                <div class="row">

                    
                    <!-- <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label' id=""></label>
                            <select class="selectpicker form-control" multiple data-live-search="true" 
                                name="public_structures[]" id="publicStructures">
                                @foreach($publicCategories as $publicCategorie)
                                <option value="{{$publicCategorie->id}}">
                                    {{$publicCategorie->name}}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div> -->

                    <!-- <div class="col-xl-4 col-lg-4 col-md-4 mb-1" 
                        id="schoolShared">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label' id="schoolSharedLabel"
                            style="visiblity:hidden; display:none" >Is School shared?</label>
                            <select name="sharedSchool" id="schoolSharedSelect"
                            style="visiblity:hidden; display:none" class="form-control">
                                <option selected disabled>Choose ...</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </fieldset>
                    </div> -->
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-12 mb-1" id="percentageQuestion1Div">
                        <fieldset class="form-group">
                            <input type="text" name="description" class="form-control"
                                id="percentageInputQuestion1" 
                                style="visiblity:hidden; display:none">
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
   
    $(document).ready(function () {

        $('#communityForm').on('submit', function (event) {

            var regionValue = $('#selectedRegion').val();
            var subRegionValue = $('#selectedSubRegions').val();
            var cycleValue = $('#selectedCycleYear').val();
            var fallahValue = $('#is_fallah').val();
            var bedouinValue = $('#is_bedouin').val();

            if (fallahValue == null) {

                $('#is_fallah_error').html('Please select an option!'); 
                return false;
            } else if (fallahValue != null) {

                $('#is_fallah_error').empty();
            }
            if (bedouinValue == null) {

                $('#is_bedouin_error').html('Please select an option!'); 
                return false;
            } else if (bedouinValue != null) {

                $('#is_bedouin_error').empty();
            }

            if (regionValue == null) {

                $('#region_id_error').html('Please select a region!'); 
                return false;
            } else if (regionValue != null){

                $('#region_id_error').empty();
            }
            if (subRegionValue == null) {

                $('#sub_region_id_error').html('Please select a sub region!'); 
                return false;
            } else if (subRegionValue != null) {

                $('#sub_region_id_error').empty();
            }
            if (cycleValue == null) {

                $('#energy_system_cycle_id_error').html('Please select a cycle year!'); 
                return false;
            } else if (cycleValue != null) {

                $('#energy_system_cycle_id_error').empty();
            }
            
            $(this).addClass('was-validated');  
            $('#region_id_error').empty();
            $('#sub_region_id_error').empty();
            $('#is_fallah_error').empty();
            $('#is_bedouin_error').empty();
            $("#energy_system_cycle_id_error").empty();
            
            $.ajax({ 
                url: "community",
                method: 'POST',
                success: function(data) {
                    window.location.reload();
                }
            }); 
        });
    }); 

   $(document).on('change', '#publicStructures', function () {
        publicStructure = $(this).val();

        if(publicStructure == 1) {
            $("#schoolSharedLabel").css("visibility", "visible");
            $("#schoolSharedLabel").css('display', 'block');
            $("#schoolSharedSelect").css("visibility", "visible");
            $("#schoolSharedSelect").css('display', 'block');
        }
 
    });

    $(document).on('change', '#selectedRegion', function () {
        region_id = $(this).val();
   
        $.ajax({
            url: "/community/get_by_region/" + region_id,
            method: 'GET',
            success: function(data) {
                var select = $('#selectedSubRegions');
                select.prop('disabled', false); 
                select.html(data.html);
                select.selectpicker('refresh');
            }
        });

    });

    $(document).on('change', '#schoolchanges', function () {
        selectValueQuestion1 = $(this).val();

        if(selectValueQuestion1 == "yes") {
           
            $("#percentageInputQuestion1").css("visibility", "visible");
            $("#percentageInputQuestion1").css('display', 'block');
            $("#percentageInputQuestion1").attr("placeholder", "What Grades");
        } else if(selectValueQuestion1 == "no") {

            $("#percentageInputQuestion1").css("visibility", "visible");
            $("#percentageInputQuestion1").css('display', 'block');
            $("#percentageInputQuestion1").attr("placeholder", "What town do children go to school in?");
        }
    });

    var j = 0;
    $("#addCompoundNameButton").click(function () {
        ++j;
        $("#dynamicAddRemoveCompoundName").append('<tr><td><input type="text"' +
            'name="addMoreInputFieldsCompoundName[][subject]" placeholder="Enter Another one"' +
            'class="target_point form-control" data-id="'+ j +'" /></td><td><button type="button"' +
            'class="btn btn-outline-danger remove-input-field-target-points">Delete</button></td>' +
            '</tr>'
        );
    });
    $(document).on('click', '.remove-input-field-target-points', function () {
        $(this).parents('tr').remove();
    });

    
</script>
@endsection