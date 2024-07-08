<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>
 
<div id="createMaintenanceLogWater" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    Create New Maintenance Log 
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button> 
            </div>
            <div class="modal-body">
                <form method="POST" enctype='multipart/form-data' id="waterMaintenanceForm"
                    action="{{url('water-maintenance')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" 
                                    data-live-search="true" id="selectedUserCommunity"
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
                                        id="selectedWaterHolder" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="all_water_holder_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance Type</label>
                                <select name="maintenance_type_id" class="selectpicker form-control" 
                                    id="maintenanceWaterType" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($maintenanceTypes as $maintenanceType)
                                    <option value="{{$maintenanceType->id}}">
                                        {{$maintenanceType->type}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="maintenance_type_id_error" style="color: red;"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance Status</label>
                                <select name="maintenance_status_id" id="maintenanceWaterStatus"
                                    class="selectpicker form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($maintenanceStatuses as $maintenanceStatus)
                                    <option value="{{$maintenanceStatus->id}}">
                                        {{$maintenanceStatus->name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="maintenance_status_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date Of Call</label>
                                <input type="date" name="date_of_call" class="form-control" required>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Visit Date</label>
                                <input type="date" name="visit_date" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Completed Date</label>
                                <input type="date" name="date_completed" class="form-control">
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance H2O Action</label>
                                <select name="maintenance_h2o_action_id[]" id="selectedWaterActions"
                                    class="selectpicker form-control" multiple
                                    data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($maintenanceH2oActions as $maintenanceH2oAction)
                                    <option value="{{$maintenanceH2oAction->id}}">
                                        {{$maintenanceH2oAction->maintenance_action_h2o}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="maintenance_h2o_action_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Recipient</label>
                                <select name="user_id" class="selectpicker form-control"
                                 id="maintenanceUser"data-live-search="true" >
                                    <option disabled selected>Choose one...</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">
                                        {{$user->name}}
                                    </option>
                                    @endforeach 
                                </select>
                            </fieldset>
                            <div id="user_id_error" style="color: red;"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Performed By</label>
                                <select name="performed_by[]" class="selectpicker form-control" 
                                    data-live-search="true" multiple>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($users as $user)
                                    <option value="{{$user->id}}">
                                        {{$user->name}}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('user_id'))
                                    <span class="error">{{ $errors->first('user_id') }}</span>
                                @endif
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
        $(document).on('change', '#selectedUserCommunity', function () {
            var community_id = $(this).val();
            $('#selectedWaterHolder').empty();
            $('#chooseUserOrPublic').prop('disabled', false);
            $('#chooseUserOrPublic').html('<option disabled selected>Choose one...</option><option value="user">User</option><option value="public">Public Structure</option><option value="system">Water System</option>');
            $('#chooseUserOrPublic').selectpicker('refresh');
        });

        // This event handler handles the change of #chooseUserOrPublic
        $(document).on('change', '#chooseUserOrPublic', function () {

            var community_id = $('#selectedUserCommunity').val();
            var publicUser = $(this).val();
            
            $.ajax({
                url: "water_holder/get_by_community/" + community_id + "/" + publicUser,
                method: 'GET',
                success: function(data) {
                    var select = $('#selectedWaterHolder');
                    select.prop('disabled', false); 
                    select.html(data.html);
                    select.selectpicker('refresh');
                }
            });
        });
    });

    $(document).ready(function() {
        $('#waterMaintenanceForm').on('submit', function (event) {

            var communityValue = $('#selectedUserCommunity').val();
            var userOrPublicValue = $('#chooseUserOrPublic').val();
            var waterValue = $('#selectedWaterHolder').val();
            var maintenanceTypeValue = $('#maintenanceWaterType').val();
            var maintenanceStatusValue = $('#maintenanceWaterStatus').val();
            var actionValue = $('#selectedWaterActions').val();
            var maintenanceUser = $('#maintenanceUser').val();

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

            if (maintenanceTypeValue == null) {

                $('#maintenance_type_id_error').html('Please select a type!'); 
                return false;
            } else if (maintenanceTypeValue != null){

                $('#maintenance_type_id_error').empty();
            }

            if (maintenanceStatusValue == null) {

                $('#maintenance_status_id_error').html('Please select a status!'); 
                return false;
            } else if (maintenanceStatusValue != null){

                $('#maintenance_status_id_error').empty();
            }

            if (!actionValue || actionValue.length === 0) {

                $('#maintenance_h2o_action_id_error').html('Please select at least one action!');
                return false;
            } else {

                $('#maintenance_h2o_action_id_error').empty();
            }

            if (maintenanceUser == null) {

                $('#user_id_error').html('Please select a user!'); 
                return false;
            } else if (maintenanceUser != null){

                $('#user_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#all_water_holder_id_error').empty();  
            $('#public_user_error').empty();
            $('#community_id_error').empty();
            $('#maintenance_type_id_error').empty();
            $('#internet_incident_status_id_error').empty();
            $('#internet_issues_error').empty();
            $('#user_id_error').empty();
            $('#maintenance_h2o_action_id_error').empty();

            this.submit();
        });
    });

</script>