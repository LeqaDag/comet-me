<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
} 
</style>  

<div id="createMaintenanceLogElectricity" class="modal fade" tabindex="-1" aria-hidden="true">
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
                <form method="POST" enctype='multipart/form-data' id="energyMaintenanceForm"
                    action="{{url('energy-maintenance')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" 
                                    data-live-search="true" id="selectedUserCommunity"
                                    name="community_id[]" required>
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
                                <label class='col-md-12 control-label'>MG system/ User / Turbine?</label>
                                <select name="flag" class="form-control"
                                    id="mgSystemOrFbsOrTurbine" disabled>
                                    <option selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="flag_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Agent</label>
                                <select name="agent_id" class="selectpicker form-control" 
                                    id="selectedEnergyHolder" data-live-search="true" disabled
                                    required>
                                </select>
                            </fieldset>
                            <div id="agent_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance Type</label>
                                <select name="maintenance_type_id" class="selectpicker form-control" 
                                    required id="maintenanceEnergyType">
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
                                <select name="maintenance_status_id"  class="selectpicker form-control" 
                                    required id="maintenanceEnergyStatus">
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
                                <label class='col-md-12 control-label'>Issues</label>
                                <select name="energy_issues" data-live-search="true"  
                                    id="energyMaintenanceIssue" 
                                    class="selectpicker form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($energyIssues as $energyIssue)
                                    <option value="{{$energyIssue->id}}">
                                        {{$energyIssue->arabic_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="energy_issues_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance Electricity Action</label>
                                <select id="selectedEnergyActions" name="action_ids[]" multiple
                                    class="selectpicker form-control" data-live-search="true">
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                            <div id="action_ids_error" style="color: red;"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Recipient</label>
                                <select name="user_id" class="selectpicker form-control"
                                    data-live-search="true" id="maintenanceUser">
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
        $('#selectedEnergyHolder').empty();
        $('#mgSystemOrFbsOrTurbine').prop('disabled', false);
        $('#mgSystemOrFbsOrTurbine').html('<option disabled selected>Choose one...</option><option value="system">MG System</option><option value="fbs_user">FBS User</option><option value="mg_user">MG User</option><option value="fbs_public">FBS Public</option><option value="mg_public">MG Public</option><option value="turbine">Turbine</option>');
        $('#mgSystemOrFbsOrTurbine').selectpicker('refresh');
        UserOrSystemOrTurbine(community_id);
    });

    function UserOrSystemOrTurbine(community_id) {

        $(document).on('change', '#mgSystemOrFbsOrTurbine', function () {

            systemUserTurbine = $('#mgSystemOrFbsOrTurbine').val();
             
            $.ajax({
                url: "energy-maintenance/get_holder/" + systemUserTurbine + "/" + community_id,
                method: 'GET',
                success: function(data) {

                    var select = $('#selectedEnergyHolder');
                    select.prop('disabled', false); 
                    select.html(data.html);
                    select.selectpicker('refresh');
                }
            });
        });
    }

    $(document).on('change', '#energyMaintenanceIssue', function () {
        
        issue_id = $(this).val();
        var selectIssue = $('#selectedEnergyActions'); 

        $.ajax({
            url: "energy-maintenance/get_actions/" + issue_id,
            method: 'GET',
            success: function(data) {

                selectIssue.prop('disabled', false);

                selectIssue.html(data.html);
                selectIssue.selectpicker('refresh');
            }
        });
    });

    $(document).ready(function() {

        $('#energyMaintenanceForm').on('submit', function (event) {

            var communityValue = $('#selectedUserCommunity').val();
            var userOrPublicValue = $('#mgSystemOrFbsOrTurbine').val();
            var holderValue = $('#selectedEnergyHolder').val();
            var maintenanceTypeValue = $('#maintenanceEnergyType').val();
            var maintenanceStatusValue = $('#maintenanceEnergyStatus').val();
            var maintenanceIssue = $('#energyMaintenanceIssue').val();
            var actionValue = $('#selectedEnergyActions').val();
            var maintenanceUser = $('#maintenanceUser').val();

            if (communityValue == null) {

                $('#community_id_error').html('Please select a community!'); 
                return false;
            } else if (communityValue != null){

                $('#community_id_error').empty();
            }

            if (userOrPublicValue == null) {

                $('#flag_error').html('Please select an option!'); 
                return false;
            } else if (userOrPublicValue != null){

                $('#flag_error').empty();
            }

            if (holderValue == null) {

                $('#agent_id_error').html('Please select a holder!'); 
                return false;
            } else if (holderValue != null){

                $('#agent_id_error').empty();
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

            if (maintenanceIssue == null) {

                $('#energy_issues_error').html('Please select an issue!'); 
                return false;
            } else if (maintenanceIssue != null){

                $('#energy_issues_error').empty();
            }

            if (!actionValue || actionValue.length === 0) {

                $('#action_ids_error').html('Please select at least one action!');
                return false;
            } else {

                $('#action_ids_error').empty();
            }

            if (maintenanceUser == null) {

                $('#user_id_error').html('Please select a user!'); 
                return false;
            } else if (maintenanceUser != null){

                $('#user_id_error').empty();
            }

            $(this).addClass('was-validated');  
            $('#agent_id_error').empty();  
            $('#flag_error').empty();
            $('#community_id_error').empty();
            $('#maintenance_type_id_error').empty();
            $('#maintenance_status_id_error').empty();
            $('#energy_issues_error').empty();
            $('#user_id_error').empty();
            $('#action_ids_error').empty();

            this.submit();
        });
    });
</script>