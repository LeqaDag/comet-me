<style>
    label, input {
        display: block;
    }

    label, table {
        margin-top: 20px;
    }
</style>  

<div id="createFbsIncident" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Add FBS Incident
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="fbsIncidentForm"
                    action="{{url('fbs-incident')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" id="fbsSelectedCommuntiy"
                                    data-live-search="true" name="community_id" required>
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
                                <label class='col-md-12 control-label'>Energy User/ Public</label>
                                <select id="chooseUserOrPublic" class="selectpicker form-control" 
                                    name="public_user" data-live-search="true" disabled>
                                </select>
                            </fieldset>
                            <div id="public_user_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy Holder</label>
                                <select name="energy_user_id" class="selectpicker form-control" data-live-search="true"
                                    id="energyUserSelectedFbs" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="energy_user_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Type</label>
                                <select name="incident_id" class="selectpicker form-control" 
                                    required id="incidentFbsType">
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
                                <label class='col-md-12 control-label'>Incident FBS Status</label>
                                <select name="incident_status_small_infrastructure_id[]" 
                                    class="selectpicker form-control" data-live-search="true"
                                    multiple id="incidentFbsStatus">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($fbsIncidents as $fbsIncident)
                                    <option value="{{$fbsIncident->id}}">
                                        {{$fbsIncident->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="incident_status_small_infrastructure_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Equipment Damaged</label>
                                <select name="incident_equipment_id[]" multiple id="equipmentDamaged"
                                    class="selectpicker form-control" data-live-search="true" >
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
                                <label class='col-md-12 control-label'>Losses Energy (ILS)</label>
                                <input type="number" name="losses_energy" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Losses Water (ILS)</label>
                                <input type="number" name="losses_water" class="form-control">
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

$(document).ready(function() {

    $(document).on('change', '#fbsSelectedCommuntiy', function () {

        $('#energyUserSelectedFbs').empty();
        community_id = $(this).val();
        $('#chooseUserOrPublic').prop('disabled', false);
        $('#chooseUserOrPublic').html('<option disabled selected>Choose one...</option><option value="user">User</option><option value="public">Public Structure</option>');
        $('#chooseUserOrPublic').selectpicker('refresh');
        UserOrPublic(community_id);
    });

    function UserOrPublic(community_id) {
     
        $(document).on('change', '#chooseUserOrPublic', function () {
            publicUser = $('#chooseUserOrPublic').val();
            
            if(publicUser == "user") {
            
                $.ajax({
                    url: "energy_user/get_by_community/" +  community_id,
                    method: 'GET',
                    success: function(data) {
                        var select = $('#energyUserSelectedFbs');
                        select.prop('disabled', false); 
                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
                
            } else if(publicUser == "public") {

                $.ajax({
                    url: "energy_public/get_by_community/" + community_id,
                    method: 'GET',
                    success: function(data) {
                        var select = $('#energyUserSelectedFbs');
                        select.prop('disabled', false); 
                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
            }
        });
    }

    $('#fbsIncidentForm').on('submit', function (event) {

        var communityValue = $('#fbsSelectedCommuntiy').val();
        var userOrPublicValue = $('#chooseUserOrPublic').val();
        var energyValue = $('#energyUserSelectedFbs').val();
        var incidentTypeValue = $('#incidentFbsType').val();
        var incidentStatusValue = $('#incidentFbsStatus').val();
        var equipmentValue = $('#equipmentDamaged').val();

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

        if (energyValue == null) {

            $('#energy_user_id_error').html('Please select a holder!'); 
            return false;
        } else if (energyValue != null){

            $('#energy_user_id_error').empty();
        }

        if (incidentTypeValue == null) {

            $('#incident_id_error').html('Please select a type!'); 
            return false;
        } else if (incidentTypeValue != null){

            $('#incident_id_error').empty();
        }

        if (!incidentStatusValue || incidentStatusValue.length === 0) {

            $('#incident_status_small_infrastructure_id_error').html('Please select a status!'); 
            return false;
        } else {

            $('#incident_status_small_infrastructure_id_error').empty();
        }

        if (!equipmentValue || equipmentValue.length === 0) {

            $('#incident_equipment_id_error').html('Please select at least one equipment!');
            return false;
        } else {

            $('#incident_equipment_id_error').empty();
        }

        $(this).addClass('was-validated');  
        $('#energy_user_id_error').empty();  
        $('#public_user_error').empty();
        $('#community_id_error').empty();
        $('#incident_id_error').empty();
        $('#incident_status_small_infrastructure_id_error').empty();
        $('#incident_equipment_id_error').empty();

        this.submit();
        });

    });

</script>