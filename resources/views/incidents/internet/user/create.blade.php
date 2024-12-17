<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>  


<div id="createInternetUserIncident" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Add Internet User Incident
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" id="internetIncidentForm"
                    action="{{url('incident-internet-user')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control"  name="community_id"
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
                                <label class='col-md-12 control-label'>Internet User/ Public</label>
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
                                <label class='col-md-12 control-label'>Internet User</label>
                                <select name="internet_user_id" class="selectpicker form-control" 
                                    id="internetUserSelected" disabled>
                                </select>
                            </fieldset>
                            <div id="internet_user_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Type</label>
                                <select name="incident_id" class="selectpicker form-control" 
                                    id="incidentInternetType" required>
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
                                <label class='col-md-12 control-label'>Internet Incident Status</label>
                                <select name="internet_incident_status_id" class="selectpicker form-control"
                                    id="incidentInternetStatus" data-live-search="true"
                                    class="selectpicker form-control">
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
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date Of Incident</label>
                                <input type="date" name="date" class="form-control" required>
                            </fieldset>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Response Date</label>
                                <input type="date" name="response_date" class="form-control">
                            </fieldset>
                        </div>
                        <div id="equipmentDamagedDiv" class="col-xl-6 col-lg-6 col-md-6">
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

    $(document).on('change', '#incidentInternetType', function () {
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
        $('#internetUserSelected').empty();
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
                    url: "incident-internet-user/get_by_community/" + community_id + "/" + publicUser,
                    method: 'GET',
                    success: function(data) {
                        var select = $('#internetUserSelected');
                        select.prop('disabled', false); 
                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
                
            } else if(publicUser == "public") {

                $.ajax({
                    url: "incident-internet-user/get_by_community/" + community_id + "/" + publicUser,
                    method: 'GET',
                    success: function(data) {
                        var select = $('#internetUserSelected');
                        select.prop('disabled', false); 
                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
            }
        });
    }

    function readURL(input) {
        if (input.files && input.files.length > 0) {
            var previewContainer = $('#selected-photos');
            previewContainer.innerHTML = ''; // Clear previous selections

            for (var i = 0; i < input.files.length; i++) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px'; // Set your preferred width
                    previewContainer.appendChild(img);
                };

                reader.readAsDataURL(input.files[i]);
            }
        }
    }

    document.querySelector('input[name="photos[]"]').addEventListener('change', function () {
        readURL(this);
    });
 
    $(document).ready(function() {
        $('#internetIncidentForm').on('submit', function (event) {

            var communityValue = $('#internetSelectedCommuntiy').val();
            var userOrPublicValue = $('#chooseUserOrPublic').val();
            var internetValue = $('#internetUserSelected').val();
            var incidentTypeValue = $('#incidentInternetType').val();
            var incidentStatusValue = $('#incidentInternetStatus').val();

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

            if (internetValue == null) {

                $('#internet_user_id_error').html('Please select a holder!'); 
                return false;
            } else if (internetValue != null){

                $('#internet_user_id_error').empty();
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
            } else if (incidentStatusValue != null){

                $('#internet_incident_status_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#internet_user_id_error').empty();  
            $('#public_user_error').empty();
            $('#community_id_error').empty();
            $('#incident_id_error').empty();
            $('#internet_incident_status_id_error').empty();

            this.submit();
        });
    });

</script>