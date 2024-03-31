<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}


</style>


<div id="createWaterUser" class="modal fade" tabindex="-1" aria-hidden="true" 
    aria-labelledby="exampleModalWaterUser">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalWaterUser">
                    Create New Water System Holder	
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="waterHolderForm"
                    action="{{url('water-user')}}">
                    @csrf
                    <div class="row">
                        
                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select name="community_id[]" id="communityChanges" 
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
                                <select name="public_user" id="userPublicSelected" 
                                    class="selectpicker form-control" required>
                                    <option disabled selected>Choose one...</option>
                                    <option value="user">Water User</option> 
                                    <option value="public">Public Structure</option>
                                </select>
                            </fieldset>
                            <div id="public_user_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water System Holder</label>
                                <select name="household_id" id="selectedHousehold" disabled
                                    class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="household_id_error" style="color: red;"></div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Electricity Meter user?</label>
                                <input type="text" name="meter_user" id="meter_user"
                                    class="form-control" disabled>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Grid Access</label>
                                <select name="grid_access" id="selectedGridAccess" 
                                    class="selectpicker form-control">
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div id="system_type_error" style="color: red;"></div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <label class='col-md-12 headingLabel'>H2O System</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of H2O</label>
                                <input type="number" name="number_of_h20" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>H2O Status</label>
                                <select name="h2o_status_id" class="selectpicker form-control"
                                    id="h2oStatusValue">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($h2oStatus as $h2oStatus)
                                    <option value="{{$h2oStatus->id}}">{{$h2oStatus->status}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Number of BSF</label>
                                <input type="number" name="number_of_bsf" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>BSF Status</label>
                                <select name="bsf_status_id" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($bsfStatus as $bsfStatu)
                                    <option value="{{$bsfStatu->id}}">{{$bsfStatu->name}}</option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>H2O Request Date</label>
                                <input type="date" name="h2o_request_date" 
                                    class="form-control">
                            </fieldset>
                        </div>  
                        
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Installation Year</label>
                                <input type="number" name="installation_year" 
                                class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Installation Date</label>
                                <input type="date" name="h2o_installation_date" 
                                    class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <label class='col-md-12 headingLabel'>Grid System</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Request Date</label>
                                <input type="date" name="request_date" 
                                    class="form-control">
                            </fieldset>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Integration Large</label>
                                <input type="number" name="grid_integration_large" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Integration Large Date</label>
                                <input type="date" name="large_date" 
                                    class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Integration Small</label>
                                <input type="number" name="grid_integration_small" 
                                class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Integration Small Date</label>
                                <input type="date" name="small_date" 
                                    class="form-control">
                            </fieldset>
                        </div>
                    </div> 

                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-1">
                            <label class='col-md-12 headingLabel'>Confirmation</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Delivery</label>
                                <select name="is_delivery" class="selectpicker form-control"
                                    id="deliveryGridSystem">
                                    <option disabled selected>Choose one...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Paid</label>
                                <select name="is_paid" class="selectpicker form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                    <option value="NA">NA</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Complete</label>
                                <select name="is_complete" class="selectpicker form-control">
                                    <option disabled selected>Choose one...</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
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
    
    $(document).on('change', '#communityChanges', function () {

        community_id = $(this).val();
   
        $('#userPublicSelected').prop('disabled', false);

        UserOrPublic(community_id);

        $.ajax({
            url: "water-user/get_water_source/" + community_id,
            method: 'GET',
            success: function(data) {
                if(data.val == "Yes") {
                    $('#selectedGridAccess').prop('disabled', false);
                    $('#selectedGridAccess').html(data.html);

                } else if(data.val == "New") {
                    $('#selectedGridAccess').prop('disabled', false);
                    $('#selectedGridAccess').html(data.html);
                }  
            }
        });
    });

    function UserOrPublic(community_id) {

        $(document).on('change', '#userPublicSelected', function () {
            publicUser = $('#userPublicSelected').val();
            
            if(publicUser == "user") {
             
                $.ajax({
                    url: "household/get_by_community/" + community_id,
                    method: 'GET',
                    success: function(data) {

                        var select = $('#selectedHousehold');
                        select.prop('disabled', false); 
                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
                
            } else if(publicUser == "public") {

                $('#selectedWaterHolder').prop('disabled', true);
                $.ajax({
                    url: "public/get_by_community/" + community_id,
                    method: 'GET',
                    success: function(data) {
                        $('#selectedHousehold').prop('disabled', false);
                        $('#selectedHousehold').html(data.html);
                    }
                });
            }
        });
    }


    $(document).on('change', '#selectedHousehold', function () {
        household_id = $(this).val();

        $.ajax({
            url: "energy_user/get_by_household/" + household_id,
            method: 'GET',
            success: function(data) {

                $('#meter_user').val(data.meter_number);
            }
        });

    });

    $(document).ready(function() {
        $('#waterHolderForm').on('submit', function (event) {

            var communityValue = $('#communityChanges').val();
            var userOrPublicValue = $('#userPublicSelected').val();
            var waterValue = $('#selectedHousehold').val();
            var h2oStatusValue = $('#h2oStatusValue').val();
            var gridValue = $('#deliveryGridSystem').val();

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

                $('#household_id_error').html('Please select a holder!'); 
                return false;
            } else if (waterValue != null){

                $('#household_id_error').empty();
            }

            if (h2oStatusValue == null && gridValue == null) {

                $('#system_type_error').html('Please fill out at least one system type!');
                return false;
            } else {

                $('#system_type_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#household_id_error').empty();  
            $('#public_user_error').empty();
            $('#community_id_error').empty();

            this.submit();
        });
    });
</script>