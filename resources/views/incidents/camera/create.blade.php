<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>  

<div id="createCameraIncident" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Add Camera Incident
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="cameraIncidentForm"
                    action="{{url('incident-camera')}}">
                    @csrf

                    <div class="row" style="margin-top:12px">
                        <h6>Select Community or Repository</h6>
                    </div>
                    <div id="community_id_error" style="color: red;"></div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community - Installed -</label>
                                <select class="selectpicker form-control" name="community_id"
                                    id="cameraSelectedCommuntiy" data-live-search="true" 
                                    required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($installedCommunityCameras as $community)
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
                                <label class='col-md-12 control-label'>Repository - Installed -</label>
                                <select class="selectpicker form-control" id="RepositoryCamera"
                                    data-live-search="true" name="repository_id" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($installedRepositoryCameras as $repository)
                                    <option value="{{$repository->id}}">
                                        {{$repository->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Type</label>
                                <select name="incident_id" class="selectpicker form-control" 
                                    id="incidentCameraType" required>
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
                                <label class='col-md-12 control-label'>Incident Status</label>
                                <select name="internet_incident_status_id" id="incidentCameraStatus"
                                    class="selectpicker form-control" >
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

    $(document).ready(function() {

        $('#cameraIncidentForm').on('submit', function (event) {

            var communityValue = $('#cameraSelectedCommuntiy').val();
            var repositoryValue = $('#RepositoryCamera').val();
            var incidentTypeValue = $('#incidentCameraType').val();
            var incidentStatusValue = $('#incidentCameraStatus').val();
            var equipmentValue = $('#equipmentDamaged').val();

            if (communityValue == null && repositoryValue == null) {

                $('#community_id_error').html('Please select a community or repository!');
                return false;
            } else {

                $('#community_id_error').empty();
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

            if (!equipmentValue || equipmentValue.length === 0) {

                $('#incident_equipment_id_error').html('Please select at least one equipment!');
                return false;
            } else {

                $('#incident_equipment_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#community_id_error').empty();
            $('#incident_id_error').empty();
            $('#internet_incident_status_id_error').empty();
            $('#incident_equipment_id_error').empty();

            this.submit();
        });
    });
</script>