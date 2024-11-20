<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>  

<div id="createInternetNetworkIncident" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Add Internet Network Incident
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="networkIncidentForm"
                    action="{{url('incident-network')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" name="community_id"
                                    id="internetSelectedCommuntiy" data-live-search="true" 
                                    required>
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
                                <label class='col-md-12 control-label'>Areas Affected</label>
                                <select name="affected_community_id[]" id="areaAffected"
                                    class="selectpicker form-control" 
                                    multiple data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="affected_community_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Type</label>
                                <select name="incident_id" class="selectpicker form-control" 
                                    id="incidentNetworkType" required>
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
                                <label class='col-md-12 control-label'>Internet Incident Status</label>
                                <select name="internet_incident_status_id" id="incidentNetworkStatus"
                                    class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($internetIncidentStatuses as $internetIncidentStatus)
                                    <option value="{{$internetIncidentStatus->id}}">
                                        {{$internetIncidentStatus->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="internet_incident_status_id_error" style="color: red;"></div>
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
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Households Affected</label>
                                <select name="household_id[]" multiple id="affectedHouseholdsIncident"
                                    class="selectpicker form-control" data-live-search="true" >
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Nature of the Incident</label>
                                <textarea name="notes" class="form-control" 
                                   style="resize:none" cols="20" rows="3"></textarea>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Next Steps</label>
                                <textarea name="next_step" class="form-control" 
                                   style="resize:none" cols="20" rows="3"></textarea>
                            </fieldset>
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
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Monetary Losses</label>
                                <input type="number" name="monetary_losses" class="form-control" step="0.01">
                            </fieldset>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Upload photos</label>
                            <input type="file" name="photos[]"
                                class="btn btn-primary me-2 mb-4 block w-full mt-1 rounded-md"
                                accept="image/png, image/jpeg" multiple/>
                        </fieldset>
                        <p class="mb-0">Allowed JPG, JPEG, GIF or PNG.</p>
                    </div>
                    <div id="selected-photos"></div>
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

    $(document).on('change', '#incidentNetworkType', function () {
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

    $(document).on('change', '#internetSelectedCommuntiy', function () {

        community_id = $(this).val();

        $.ajax({
            url: "incident-internet-user/get_by_community/" +  community_id,
            method: 'GET',
            success: function(data) {
                var select = $('#affectedHouseholdsIncident'); 

                select.prop('disabled', false);
                select.html(data.html);
                select.selectpicker('refresh');
            }
        });
    });

    $(document).ready(function() {

        $('#networkIncidentForm').on('submit', function (event) {

            var communityValue = $('#internetSelectedCommuntiy').val();
            var areaAffectedValue = $('#areaAffected').val();
            var incidentTypeValue = $('#incidentNetworkType').val();
            var incidentStatusValue = $('#incidentNetworkStatus').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            if (!areaAffectedValue || areaAffectedValue.length === 0) {

                $('#affected_community_id_error').html('Please select an area!'); 
                return false;
            } else {

                $('#affected_community_id_error').empty();
            }

            if (incidentTypeValue == null) {

                $('#incident_id_error').html('Please select a type!'); 
                return false;
            } else if (incidentTypeValue != null){

                $('#incident_id_error').empty();
            }

            if (incidentStatusValue == null) {

                $('#internet_incident_status_id_error').html('Please select a status!'); 
                return false;
            } else if (incidentStatusValue != null) {

                $('#internet_incident_status_id_error').empty();
            }


            $(this).addClass('was-validated');  
            $('#affected_community_id_error').empty();
            $('#community_id_error').empty();
            $('#incident_id_error').empty();
            $('#internet_incident_status_id_error').empty();
            this.submit();
        });
    });
</script>