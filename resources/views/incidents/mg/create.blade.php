<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
    .dropdown-toggle{
        height: 40px;
        
    }
</style>

<div id="createMgIncident" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Add MG Incident
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="mgIncidentForm"
                    action="{{url('mg-incident')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" 
                                    data-live-search="true" id="communityMgIncident"
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
                                <label class='col-md-12 control-label'>Energy System</label>
                                <select name="energy_system_id" class="selectpicker form-control" 
                                    id="energySystemMgIncident" required disabled>
                                </select>
                            </fieldset>
                            <div id="energy_system_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Type</label>
                                <select name="incident_id" class="selectpicker form-control" 
                                    id="incidentMgType" required>
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
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident MG Status</label>
                                <select name="incident_status_mg_system_id" disabled
                                    class="selectpicker form-control" id="incidentMgStatus" 
                                        data-live-search="true" required>
                                </select>
                            </fieldset>
                            <div id="incident_status_mg_system_id_error" style="color: red;"></div>
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
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label class='col-md-12 control-label'>Households Affected</label>
                            <select name="households[]" multiple id="selectedHouseholdAffected"
                                class="selectpicker form-control" data-live-search="true" >
                                <option disabled selected>Choose one...</option>
                            </select>
                        </div>
                    </div>

                    <div id="swoDiv" style="display:none; visiblity: none">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Order Number</label>
                                    <input type="number" name="order_number" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Order Date</label>
                                    <input type="date" name="order_date" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Lat</label>
                                    <input type="text" name="geolocation_lat" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Geolocation Long</label>
                                    <input type="text" name="geolocation_long" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Date of hearing</label>
                                    <input type="date" name="hearing_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Number</label>
                                    <input type="text" name="building_permit_request_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Building Permit Request Date</label>
                                    <input type="date" name="building_permit_request_submission_date" class="form-control">
                                </fieldset>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>Illegal Construction Case Number</label>
                                    <input type="text" name="illegal_construction_case_number" class="form-control">
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <fieldset class="form-group">
                                    <label class='col-md-12 control-label'>District Court Case Number</label>
                                    <input type="text" name="district_court_case_number" class="form-control">
                                </fieldset>
                            </div>
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
                                    <label class='col-md-12 control-label'>Description of structure</label>
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
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Monetary Losses</label>
                                <input type="number" name="monetary_losses" class="form-control" step="0.01">
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

    $(document).on('change', '#communityMgIncident', function () {
        community_id = $(this).val();

        $.ajax({
            url: "mg-incident/get_system_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
               
                var select = $('#energySystemMgIncident');
                select.prop('disabled', false); 
                select.html(data.html);
                select.selectpicker('refresh');
            }
        });
 
        $.ajax({
            url: "mg-incident/get_household_by_community/" + community_id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var select = $('#selectedHouseholdAffected'); 

                select.html(response.html);
                select.selectpicker('refresh');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $(document).on('change', '#incidentMgType', function () {
        incident_type_id = $(this).val();

        if(incident_type_id == 4)  {

            $("#swoDiv").css("display", "block");
            $("#swoDiv").css("visiblity", "visible");
        } else {
 
            $("#swoDiv").css("display", "none");
            $("#swoDiv").css("visiblity", "none");
        }

        $.ajax({
            url: "mg-incident/get_by_type/" + incident_type_id,
            method: 'GET',
            success: function(data) {
                
                var select = $('#incidentMgStatus');
                select.prop('disabled', false); 
                select.html(data.html);
                select.selectpicker('refresh');
            }
        });
    });

    $(document).ready(function () {

        $('#mgIncidentForm').on('submit', function (event) {

            var communityValue = $('#communityMgIncident').val();
            var energyValue = $('#energySystemMgIncident').val();
            var incidentTypeValue = $('#incidentMgType').val();
            var incidentStatusValue = $('#incidentMgStatus').val();
            //var equipmentValue = $('#equipmentDamaged').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            if (energyValue == null) {

                $('#energy_system_id_error').html('Please select an energy system!'); 
                return false;
            } else if (energyValue != null){

                $('#energy_system_id_error').empty();
            }

            if (incidentTypeValue == null) {

                $('#incident_id_error').html('Please select a type!'); 
                return false;
            } else if (incidentTypeValue != null){

                $('#incident_id_error').empty();
            }

            if (incidentStatusValue == null) {

                $('#incident_status_mg_system_id_error').html('Please select a status!'); 
                return false;
            } else if (incidentStatusValue != null){

                $('#incident_status_mg_system_id_error').empty();
            }

            // if (!equipmentValue || equipmentValue.length === 0) {

            //     $('#incident_equipment_id_error').html('Please select at least one equipment!');
            //     return false;
            // } else {

            //     $('#incident_equipment_id_error').empty();
            // }

            $(this).addClass('was-validated');  
            $('#energy_system_id_error').empty(); 
            $('#community_id_error').empty();
            $('#incident_id_error').empty();
            $('#incident_status_mg_system_id_error').empty();
            //$('#incident_equipment_id_error').empty();

            this.submit();
        });
    });

</script>