<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style> 
 
<div id="createMaintenanceLogRefrigerator" class="modal fade" tabindex="-1" aria-hidden="true">
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
                <form method="POST" enctype='multipart/form-data' id="refrigeratorMaintenanceForm"
                    action="{{url('refrigerator-maintenance')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" 
                                    data-live-search="true" id="selectedUserCommunity"
                                    name="community_id[]">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->arabic_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="community_id_error" style="color: red;"></div>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Refrigerator User/ Public</label>
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
                                <label class='col-md-12 control-label'>Refrigerator Holder</label>
                                <select name="holder_id" class="selectpicker form-control" 
                                    id="selectedRefrigeratorHolder" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="holder_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Phone Number</label>
                                <input type="text" name="phone_number" id="householdPhoneNumber"
                                class="form-control"> 
                            </fieldset>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance Type</label>
                                <select name="maintenance_type_id" class="selectpicker form-control" 
                                    id="maintenanceRefrigeratorType" required>
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
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance Status</label>
                                <select name="maintenance_status_id" class="selectpicker form-control" 
                                    id="maintenanceRefrigeratorStatus" required>
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
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date Of Call</label>
                                <input type="date" name="date_of_call" class="form-control" required>
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Visit Date</label>
                                <input type="date" name="visit_date" class="form-control">
                            </fieldset>
                        </div>
                    </div>
                  
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Completed Date</label>
                                <input type="date" name="date_completed" class="form-control">
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance Refrigerator Action</label>
                                <select name="maintenance_refrigerator_action_id[]" multiple
                                    class="selectpicker form-control" data-live-search="true"
                                    id="selectedRefrigeratorActions">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($maintenanceRefrigeratorActions as $maintenanceRefrigeratorAction)
                                    <option value="{{$maintenanceRefrigeratorAction->id}}">
                                        {{$maintenanceRefrigeratorAction->maintenance_action_refrigerator}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="maintenance_refrigerator_action_id_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Recipient</label>
                                <select name="user_id" class="selectpicker form-control"
                                    id="maintenanceUser">
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

    $(document).on('change', '#selectedUserCommunity', function () {

        community_id = $(this).val();
        $('#selectedRefrigeratorHolder').empty();
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
                    url: "refrigerator-user/get_by_community/" + community_id + "/" + publicUser,
                    method: 'GET',
                    success: function(data) {
                        var select = $('#selectedRefrigeratorHolder');
                        select.prop('disabled', false); 
                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
                
            } else if(publicUser == "public") {

                $.ajax({
                    url: "refrigerator-user/get_by_community/" + community_id + "/" + publicUser,
                    method: 'GET',
                    success: function(data) {
                        var select = $('#selectedRefrigeratorHolder');
                        select.prop('disabled', false); 
                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
            }
        });
    } 

    $(document).ready(function() {

        $('#refrigeratorMaintenanceForm').on('submit', function (event) {

            var communityValue = $('#selectedUserCommunity').val();
            var userOrPublicValue = $('#chooseUserOrPublic').val();
            var refrigeratorValue = $('#selectedRefrigeratorHolder').val();
            var maintenanceTypeValue = $('#maintenanceRefrigeratorType').val();
            var maintenanceStatusValue = $('#maintenanceRefrigeratorStatus').val();
            var actionValue = $('#selectedRefrigeratorActions').val();
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

            if (refrigeratorValue == null) {

                $('#holder_id_error').html('Please select a holder!'); 
                return false;
            } else if (refrigeratorValue != null){

                $('#holder_id_error').empty();
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

                $('#maintenance_refrigerator_action_id_error').html('Please select at least one action!');
                return false;
            } else {

                $('#maintenance_refrigerator_action_id_error').empty();
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
            $('#maintenance_refrigerator_action_id_error').empty();

            this.submit();
        });
    });

</script>