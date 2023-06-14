<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}

.headingLabel {
    font-size:18px;
    font-weight: bold;
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

<div id="updateRepresentativeModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="householdRepresentative modal-title fs-5" id="householdRepresentative">
                    Update Representative	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Household</label>
                            <select name="household_id" id="selectedHousehold" 
                                class="selectedHousehold form-control">
                                <option class="disabledSelectedHousehold" disabled selected id="disabledSelectedHousehold"></option>
                            </select>
                        </fieldset>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Phone Number</label>
                            <input type="text" name="phone_number" id="phoneNumber"
                            class="form-control">
                        </fieldset>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Role</label>
                            <select name="community_role_id" id="communityRole" class="form-control">
                                <option disabled selected id="selectedRole">

                                </option>
                                @foreach($communityRoles as $communityRoles)
                                <option value="{{$communityRoles->id}}">{{$communityRoles->role}}</option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm" 
                    id="saveRepresentativeButton">Save
                </button>
                <button type="button" id="closeRepresentativeUpdate" class="btn btn-default btn-sm" 
                    data-bs-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    $(document).on('change', '#selectedHousehold', function () {
        household_id = $(this).val();
   
        $.ajax({
            url: "household/" + household_id,
            method: 'GET',
            success: function(response) {
                
                $("#phoneNumber").val(response['household'].phone_number);
            }
        });
    });
</script>