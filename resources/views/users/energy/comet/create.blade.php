<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>
<div id="createMeterPublic" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Comet Meter
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="elecCometMeterForm"
                    action="{{url('comet-meter')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>New/Old Community</label>
                                <select name="installation_type_id" id="selectedUserMisc"
                                    data-live-search="true" class="selectpicker form-control" 
                                    data-parsley-required="true" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($installationTypes as $installationType)
                                        <option value="{{$installationType->id}}">
                                            {{$installationType->type}}
                                        </option>
                                    @endforeach
                                </select> 
                            </fieldset>
                            <div id="misc_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id" id="selectedPublicCommunity" 
                                    class="selectpicker form-control" 
                                    data-live-search="true" required>
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
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Structure</label>
                                <select name="public_structure_id" id="selectedPublicStructure" 
                                    class="selectpicker form-control" disabled required
                                    data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="public_structure_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy System Type</label>
                                <select name="energy_system_type_id" data-live-search="true"
                                    class="selectpicker form-control" id="selectedEnergySystemType">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($energySystemTypes as $energySystemType)
                                        <option value="{{$energySystemType->id}}">
                                            {{$energySystemType->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="energy_system_type_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy System</label>
                                <select name="energy_system_id" id="selectedEnergySystemPublic"
                                    class="selectpicker form-control" disabled 
                                    data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($energySystems as $energySystem)
                                        <option value="{{$energySystem->id}}">
                                            {{$energySystem->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="energy_system_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Meter Case</label>
                                <select name="meter_case_id" class="selectpicker form-control"  
                                    data-live-search="true" id="meterCaseValue">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($meters as $meter)
                                        <option value="{{$meter->id}}">
                                            {{$meter->meter_case_name_english}}
                                        </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="meter_case_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Meter Number</label>
                                <input type="number" name="meter_number" class="form-control" required>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Daily Limit</label>
                                <input type="text" name="daily_limit" class="form-control"required>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Installation Date</label>
                                <input type="date" name="installation_date" class="form-control"required>
                            </fieldset>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).on('change', '#selectedPublicCommunity', function () {
        community_id = $(this).val();
   
        $.ajax({
            url: "energy-public/get_by_community/" + community_id + "/" + 1,
            method: 'GET',
            success: function(data) {

                var select = $('#selectedPublicStructure'); 
                select.prop('disabled', false);
                select.html(data.html);
                select.selectpicker('refresh');
                
                $('#selectedSharedMeter').prop('disabled', false);
                $(document).on('change', '#selectedSharedMeter', function () {

                    user_id = $("#selectedPublicStructure").val();

                    $.ajax({
                        url: "energy-user/shared_household/" + community_id + "/" + user_id,
                        method: 'GET',
                        success: function(data) {
                         
                            $('#selectedHouseholdMeter').prop('disabled', false);
                            $('#selectedHouseholdMeter').append(data.html);
                            $('.selectpicker').selectpicker('refresh');
                        }
                    });
                });
            }
        });

        energy_type_id= $("#selectedEnergySystemType").val();

        changeEnergySystemType(energy_type_id, community_id);
    });
 
    $(document).on('change', '#selectedEnergySystemType', function () {
        energy_type_id = $(this).val();

        if(energy_type_id == 1 || energy_type_id == 3) {

            community_id = $("#selectedPublicCommunity").val();
        } else {

            community_id = 0;
        }

        changeEnergySystemType(energy_type_id, community_id);
    });

    function changeEnergySystemType(energy_type_id, community_id) {

        $.ajax({
            url: "energy_public/get_by_energy_type/" + community_id + "/" + energy_type_id,
            method: 'GET',
            success: function(data) {

                var select = $('#selectedEnergySystemPublic'); 
                select.prop('disabled', false);
                select.html(data.html);
                select.selectpicker('refresh');
            }
        });
    }

    $(document).ready(function () {

        $('#elecCometMeterForm').on('submit', function (event) {

            var miscValue = $('#selectedUserMisc').val();
            var communityValue = $('#selectedPublicCommunity').val();
            var publicValue = $('#selectedPublicStructure').val();
            var energyTypeValue = $('#selectedEnergySystemType').val();
            var energyValue = $('#selectedEnergySystemPublic').val();
            var meterCaseValue = $('#meterCaseValue').val();

            if (miscValue == null) {

                $('#misc_error').html('Please select an option!'); 
                return false;
            } else if (miscValue != null){

                $('#misc_error').empty();
            }

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            if (!publicValue || publicValue.length === 0) {

                $('#public_structure_id_error').html('Please select a public structure!');
                return false;
            } else {

                $('#public_structure_id_error').empty();
            }

            if (energyTypeValue == null) {

                $('#energy_system_type_id_error').html('Please select a Energy System Type!'); 
                return false;
            } else if (energyTypeValue != null){

                $('#energy_system_type_id_error').empty();
            } 

            if (energyValue == null) {

                $('#energy_system_id_error').html('Please select an Energy System!'); 
                return false;
            } else if (energyValue != null){

                $('#energy_system_id_error').empty();
            }

            if (meterCaseValue == null) {

                $('#meter_case_id_error').html('Please select a case!'); 
                return false;
            } else if (meterCaseValue != null){

                $('#meter_case_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#misc_error').empty(); 
            $('#community_id_error').empty();
            $('#public_structure_id_error').empty();
            $('#energy_system_type_id_error').empty();
            $('#energy_system_id_error').empty();
            $('#meter_case_id_error').empty();

            this.submit();
        });
    });

</script>