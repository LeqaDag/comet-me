<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}


</style>

<div id="createSafetyEnergyCheck" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Meter Safety Check	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="safteyChecksForm"
                    action="{{url('energy-safety')}}">
                    @csrf
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id[]" id="communitySafetyCheck" 
                                    class="selectpicker form-control" 
                                    data-live-search="true" >
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">{{$community->english_name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="community_id_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>User/Public Structure</label>
                                <select name="public_user" id="userPublicSelectedSaftey" 
                                    class="selectpicker form-control" disabled required>
                                    <option disabled selected>Choose one...</option>
                                    <option value="user">Energy User</option> 
                                    <option value="public">Public Structure</option>
                                </select>
                            </fieldset>
                            <div id="public_user_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Meter Holder</label>
                                <select name="holder_id" id="selectedHolder" required
                                    class="selectpicker form-control" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="holder_id_error" style="color: red;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Meter Number</label>
                                <input type="text" name="meter_user" id="meter_holder"
                                    class="form-control" disabled>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Meter Case</label>
                                <select name='meter_case_id' id="meter_case_id" class="form-control">
                                    <option disabled selected
                                        id="meterCaseOption"></option>
                                    @foreach($meterCases as $meterCase)
                                        <option value="{{$meterCase->id}}">
                                            {{$meterCase->meter_case_name_english}}
                                        </option>
                                    @endforeach
                                </select> 
                            </fieldset> 
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Visit Date</label>
                                <input type="date" name="visit_date" 
                                    class="form-control" required>
                            </fieldset>
                        </div>  
                    </div>
                    <div class="row" id="groundConnectedDiv" style="display:none">
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Ground Connected</label> 
                                <select name='ground_connected' id="groundConnectedSelect"
                                    class="form-control">
                                </select> 
                            </fieldset> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <label class='col-md-12 headingLabel'>Residual Current Device (RCD)</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>X 0.5/Phase 0</label>
                                <input type="text" name="rcd_x_phase0" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>X 0.5/Phase 180</label>
                                <input type="text" name="rcd_x_phase1" 
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>X 1/Phase 0</label>
                                <input type="text" name="rcd_x1_phase0" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>X 1/Phase 180</label>
                                <input type="text" name="rcd_x1_phase1" 
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>X 5/Phase 0</label>
                                <input type="text" name="rcd_x5_phase0" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>X 5/Phase 180</label>
                                <input type="text" name="rcd_x5_phase1" 
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <label class='col-md-12 headingLabel'>Loop</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>PH-Loop</label>
                                <input type="text" name="ph_loop" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>N-Loop</label>
                                <input type="text" name="n_loop" 
                                class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Notes</label>
                                <textarea name="notes" class="form-control" 
                                    style="resize:none" cols="20" rows="2">
                                </textarea>
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
    
    $(document).on('change', '#communitySafetyCheck', function () {

        community_id = $(this).val();
   
        $('#selectedHolder').empty();
        $('#userPublicSelectedSaftey').prop('disabled', false);
        $('#userPublicSelectedSaftey').html('<option disabled selected>Choose one...</option><option value="user">User</option><option value="public">Public Structure</option>');
        $('#userPublicSelectedSaftey').selectpicker('refresh');
        UserOrPublic(community_id);
    });

    function UserOrPublic(community_id) {

        $(document).on('change', '#userPublicSelectedSaftey', function () {
            publicUser = $('#userPublicSelectedSaftey').val();
            
            if(publicUser == "user") {
            
                $.ajax({
                    url: "energy_user/get_by_community/" + community_id,
                    method: 'GET',
                    success: function(data) {

                        var select = $('#selectedHolder');
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
                
                        var select = $('#selectedHolder');
                        select.prop('disabled', false); 
                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
            }
        });
    }

    $(document).on('change', '#selectedHolder', function () {
        holder_id = $(this).val();

        publicUser = $('#userPublicSelectedSaftey').val();
       
        GroundConnected(holder_id, publicUser);
    });

    function GroundConnected(holder_id, publicUser) {

        $.ajax({
            url: "energy_safety/info/" + holder_id + "/" + publicUser,
            method: 'GET',
            success: function(data) {

                $('#meter_holder').val(data.meter_number);
                // $('#meterCaseOption').html(data.meter_case);
                // $('#meterCaseOption').val(data.meter_case);

                if(data.ground_connected == 0) $('#groundConnectedDiv').hide();
                else {

                    $('#groundConnectedDiv').show();

                    if(data.ground_connected == "Yes") {

                        $('#groundConnectedSelect').append('<option value="Yes"disabled selected>Yes</option><option value="No">No</option>');
                    } else if(data.ground_connected == "No") {
                        
                        $('#groundConnectedSelect').append('<option value="No" disabled selected>No</option><option value="Yes">Yes</option>');
                    }
                }
            }
        });
    }

    $(document).ready(function() {

        $('#safteyChecksForm').on('submit', function (event) {

            var communityValue = $('#communitySafetyCheck').val();
            var userOrPublicValue = $('#userPublicSelectedSaftey').val();
            var holderValue = $('#selectedHolder').val();

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

            if (holderValue == null) {

                $('#holder_id_error').html('Please select a holder!'); 
                return false;
            } else if (holderValue != null){

                $('#holder_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#holder_id_error').empty();  
            $('#public_user_error').empty();
            $('#community_id_error').empty();

            this.submit();
        });
    });

</script>