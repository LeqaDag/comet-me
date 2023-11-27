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
                <form method="POST" enctype='multipart/form-data' 
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
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Energy System</label>
                                <select name="energy_system_id" class="form-control" 
                                    id="energySystemMgIncident" required disabled>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident Type</label>
                                <select name="incident_id" class="form-control" 
                                    id="incidentMgType" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($incidents as $incident)
                                    <option value="{{$incident->id}}">
                                        {{$incident->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Incident MG Status</label>
                                <select name="incident_status_mg_system_id" disabled
                                    class="form-control" id="incidentMgStatus" required>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date Of Incident</label>
                                <input type="date" name="date" class="form-control">
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
                                    class="selectpicker form-control" data-live-search="true" >
                                    <option disabled selected>Choose one...</option>
                                    @foreach($incidentEquipments as $incidentEquipment)
                                    <option value="{{$incidentEquipment->id}}">
                                        {{$incidentEquipment->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <label class='col-md-12 control-label'>Households Affected</label>
                            <select name="households[]" multiple id="selectedHouseholdAffected"
                                class="selectpicker form-control" data-live-search="true" >
                                <option disabled selected>Choose one...</option>
                            </select>
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
                console.log(data.html);
                $('#energySystemMgIncident').prop('disabled', false);
                $('#energySystemMgIncident').html(data.html);
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

        $.ajax({
            url: "mg-incident/get_by_type/" + incident_type_id,
            method: 'GET',
            success: function(data) {
                console.log(data.html);
                $('#incidentMgStatus').prop('disabled', false);
                $('#incidentMgStatus').html(data.html);
            }
        });
    });
</script>