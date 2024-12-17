<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style> 

<div id="createWaterIncident" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Add Water Incident
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div> 
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="waterIncidentForm"
                    action="{{url('water-incident')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" 
                                    data-live-search="true" id="selectedCommunityWater"
                                    name="community_id[]" required>
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
                                <label class='col-md-12 control-label'>Water User/ Public/ System</label>
                                <select id="chooseUserOrPublic" class="selectpicker form-control" 
                                    name="public_user" disabled>
                                </select>
                            </fieldset>
                            <div id="public_user_error" style="color: red;"></div>
                        </div>
                    </div> 
 
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water Holder</label>
                                <select name="all_water_holder_id" class="selectpicker form-control" 
                                    id="waterHolderSelected" required disabled>
                                </select>
                            </fieldset>
                            <div id="all_water_holder_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Type</label>
                                <select name="incident_id" class="selectpicker form-control" 
                                    id="incidentWaterType" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($incidents as $incident)
                                    <option value="{{$incident->id}}">
                                        {{$incident->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="incident_id_error" style="color: red;"></div>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date Of Incident</label>
                                <input type="date" name="date" class="form-control" required>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Response Date</label>
                                <input type="date" name="response_date" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Status</label>
                                <select name="incident_status_id[]" multiple id="incidentWaterStatus"
                                    class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($incidentStatuses as $incidentStatus)
                                    <option value="{{$incidentStatus->id}}">
                                        {{$incidentStatus->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="incident_status_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6" id="equipmentDamagedDiv">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Equipment Damaged</label>
                                <select name="incident_equipment_id[]" multiple
                                    class="selectpicker form-control" data-live-search="true"
                                    id="equipmentDamaged">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($incidentEquipments as $incidentEquipment)
                                    <option value="{{$incidentEquipment->id}}">
                                        {{$incidentEquipment->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="incident_equipment_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Order Number</label>
                                <input type="number" name="order_number" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Monetary Losses</label>
                                <input type="number" name="monetary_losses" class="form-control" step="0.01">
                            </fieldset>
                        </div>
                    </div>

                    <div id="swoDiv" style="display:none; visiblity: none">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Order Date</label>
                                    <input type="date" name="order_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Lat</label>
                                    <input type="text" name="geolocation_lat" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Long</label>
                                    <input type="text" name="geolocation_long" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Date of hearing</label>
                                    <input type="date" name="hearing_date" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Number</label>
                                    <input type="text" name="building_permit_request_number" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Date</label>
                                    <input type="date" name="building_permit_request_submission_date" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Illegal Construction Case Number</label>
                                    <input type="text" name="illegal_construction_case_number" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>District Court Case Number</label>
                                    <input type="text" name="district_court_case_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Supreme Court Case Number</label>
                                    <input type="text" name="supreme_court_case_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Description of Structure</label>
                                    <textarea name="structure_description" class="form-control" 
                                        style="resize:none" cols="20" rows="3"></textarea>
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Case Chronology</label>
                                    <textarea name="case_chronology" class="form-control" 
                                        style="resize:none" cols="20" rows="3"></textarea>
                                </fieldset>
                            </div>
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

                    <div class="row">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upload photos</label>
                            <input type="file" name="photos[]"
                                class="btn btn-primary me-2 mb-4 block w-full mt-1 rounded-md"
                                accept="image/png, image/jpeg, image/jpg, image/gif" multiple/>
                        </fieldset>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
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

    $(document).ready(function() {
        // This event handler handles the change of #selectedCommunityWater
        $(document).on('change', '#selectedCommunityWater', function () {
            var community_id = $(this).val();
            $('#waterHolderSelected').empty();
            $('#chooseUserOrPublic').prop('disabled', false);
            $('#chooseUserOrPublic').html('<option disabled selected>Choose one...</option><option value="user">User</option><option value="public">Public Structure</option><option value="system">Water System</option>');
            $('#chooseUserOrPublic').selectpicker('refresh');
        });

        // This event handler handles the change of #chooseUserOrPublic
        $(document).on('change', '#chooseUserOrPublic', function () {
            var community_id = $('#selectedCommunityWater').val();
            var publicUser = $(this).val();
            
            $.ajax({
                url: "water_holder/get_by_community/" + community_id + "/" + publicUser,
                method: 'GET',
                success: function(data) {
                    var select = $('#waterHolderSelected');
                    select.prop('disabled', false); 
                    select.html(data.html);
                    select.selectpicker('refresh');
                }
            });
        });
    });

    $(document).on('change', '#incidentWaterType', function () {
        incident_type_id = $(this).val();

        if(incident_type_id == 4)  {

            $("#swoDiv").css("display", "block");
            $("#swoDiv").css("visiblity", "visible");

            $('#equipmentDamagedDiv').css("display", "none");
            $('#equipmentDamagedDiv').css("visiblity", "none");
        } else {

            $("#swoDiv").css("display", "none");
            $("#swoDiv").css("visiblity", "none");

            $('#equipmentDamagedDiv').css("display", "block");
            $('#equipmentDamagedDiv').css("visiblity", "visible");
        }
    });

    $(document).ready(function() {
        $('#waterIncidentForm').on('submit', function (event) {

            var communityValue = $('#selectedCommunityWater').val();
            var userOrPublicValue = $('#chooseUserOrPublic').val();
            var waterValue = $('#waterHolderSelected').val();
            var incidentTypeValue = $('#incidentWaterType').val();
            var incidentStatusValue = $('#incidentWaterStatus').val();

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

            if (waterValue == null) {

                $('#all_water_holder_id_error').html('Please select a holder!'); 
                return false;
            } else if (waterValue != null){

                $('#all_water_holder_id_error').empty();
            }

            if (incidentTypeValue == null) {

                $('#incident_id_error').html('Please select a type!'); 
                return false;
            } else if (incidentTypeValue != null){

                $('#incident_id_error').empty();
            }

            if (!incidentStatusValue || incidentStatusValue.length === 0) {

                $('#incident_status_id_error').html('Please select a status!'); 
                return false;
            } else {

                $('#incident_status_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#all_water_holder_id_error').empty();  
            $('#public_user_error').empty();
            $('#community_id_error').empty();
            $('#incident_id_error').empty();
            $('#incident_status_id_error').empty();

            this.submit();
        });
    });
</script>