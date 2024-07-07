
<div id="createOtherCommunity" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="workPlanModalTitle">
                    <span>Create new community</span> 
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="">
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="row" style="margin-top:12px">
                                <h5>General Details</h5>
                            </div>
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>English Name</label>
                                        <input type="text" name="other_nglish_name" 
                                        class="form-control" id="other_nglish_name" required>
                                    </fieldset>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Arabic Name</label>
                                        <input type="text" name="other_arabic_name" class="form-control"
                                            required id="other_arabic_name">
                                    </fieldset>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'># of Families</label>
                                        <input type="number" name="other_number_of_household" class="form-control"
                                            required id="other_number_of_household">
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
                                <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Cellular Reception?</label>
                                        <select name="reception" id="reception" class="form-control">
                                            <option disabled selected>Choose one...</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row">
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
                            </div>
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Recommended system type</label>
                                        <select name="recommended_energy_system_id[]" 
                                            class="selectpicker form-control" id="recommended_energy_system_id" multiple data-live-search="true">
                                            <option disabled selected>Choose one...</option>
                                            @foreach($energyTypes as $energyType)
                                            <option value="{{$energyType->id}}">{{$energyType->name}}</option>
                                            @endforeach
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Latitude</label>
                                        <input type="text" name="latitude" id="latitude" class="form-control">
                                    </fieldset>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                                    <fieldset class="form-group">
                                        <label class='col-md-12 control-label'>Longitude</label>
                                        <input type="text" name="longitude" id="longitude" class="form-control">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px">
                                <div class="col-xl-4 col-lg-4 col-md-4">
                                    <button id="newOtherCommunityButton" class="btn btn-primary">
                                        Save changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

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

    $(document).ready(function () {

        $('#newOtherCommunityButton').on('click', function (event) {
            event.preventDefault();

            var englishName = $('#other_nglish_name').val();
            var arabicName = $('#other_arabic_name').val();
            var numberOfHousehold = $('#other_number_of_household').val();
            var reception = $('#reception').val();
            var latitude = $('#latitude').val();
            var longitude = $('#longitude').val();
            var regionValue = $('#selectedRegion').val();
            var subRegionValue = $('#selectedSubRegions').val();
            var cycleValue = $('#selectedCycleYear').val();
            var fallahValue = $('#is_fallah').val();
            var bedouinValue = $('#is_bedouin').val();
            var recommended_energy_system_id = $('#recommended_energy_system_id').val();

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
                url: "/other-community/new",
                method: 'get',
                data:{
                    _token: $("#csrf").val(),
                    englishName : englishName,
                    arabicName : arabicName,
                    numberOfHousehold : numberOfHousehold,
                    reception: reception,
                    latitude: latitude,
                    longitude: longitude,
                    regionValue: regionValue,
                    subRegionValue: subRegionValue,
                    cycleValue: cycleValue,
                    fallahValue: fallahValue,
                    bedouinValue: bedouinValue,
                    recommended_energy_system_id: recommended_energy_system_id,
                },
                success: function(data) {
                    
                    var select = $('#selectedCommunity');
                    select.prop('disabled', false); 
                    select.html(data.html);
                    select.selectpicker('refresh');
                    $('#createOtherCommunity').modal('hide');
                }
            }); 
        });
    }); 
</script>

