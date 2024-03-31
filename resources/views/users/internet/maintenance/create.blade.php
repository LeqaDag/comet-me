<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style> 

<div id="createMaintenanceLogInternet" class="modal fade" tabindex="-1" aria-hidden="true">
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
                <form method="POST" enctype='multipart/form-data' id="internetMaintenanceForm"
                    action="{{url('internet-maintenance')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" 
                                    data-live-search="true" id="selectedInternetCommunity"
                                    name="community_id">
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
                                <label class='col-md-12 control-label'>Internet Holder</label>
                                <select name="internet_user_id" class="selectpicker form-control" 
                                    id="selectedInternetUser" data-live-search="true" disabled>
                                </select>
                            </fieldset>
                            <div id="internet_user_id_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance Type</label>
                                <select name="maintenance_type_id" class="selectpicker form-control" 
                                    required id="maintenanceInternetType">
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
                                <select name="maintenance_status_id" class="selectpicker form-control" 
                                    required id="maintenanceInternetStatus">
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
                                <select name="internet_issues" data-live-search="true"  
                                    id="internetMaintenanceIssue" 
                                    class="selectpicker form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($internetIssues as $internetIssue)
                                    <option value="{{$internetIssue->id}}">
                                        {{$internetIssue->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                            <div id="internet_issues_error" style="color: red;"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Actions</label>
                                <select name="action_ids[]" class="selectpicker form-control" multiple 
                                    id="selectedInternetActions" data-live-search="true" disabled>
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

    $(document).on('change', '#selectedInternetCommunity', function () {

        community_id = $(this).val();
        $('#selectedInternetUser').empty();
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
                        
                        var select = $('#selectedInternetUser');
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
                        var select = $('#selectedInternetUser');
                        select.prop('disabled', false); 
                        select.html(data.html);
                        select.selectpicker('refresh');
                    }
                });
            }
        });
    }

    $(document).on('change', '#internetMaintenanceIssue', function () {
        
        issue_id = $(this).val();
        var selectIssue = $('#selectedInternetActions'); 

        $.ajax({
            url: "internet-maintenance/get_actions/" + issue_id,
            method: 'GET',
            success: function(data) {

                
                selectIssue.prop('disabled', false);
    
                selectIssue.html(data.html);
                selectIssue.selectpicker('refresh');
            }
        });
    });

    $(document).ready(function() {

        $('#internetMaintenanceForm').on('submit', function (event) {

            var communityValue = $('#selectedInternetCommunity').val();
            var userOrPublicValue = $('#chooseUserOrPublic').val();
            var internetValue = $('#selectedInternetUser').val();
            var maintenanceTypeValue = $('#maintenanceInternetType').val();
            var maintenanceStatusValue = $('#maintenanceInternetStatus').val();
            var maintenanceIssue = $('#internetMaintenanceIssue').val();
            var actionValue = $('#selectedInternetActions').val();
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

            if (internetValue == null) {

                $('#internet_user_id_error').html('Please select a holder!'); 
                return false;
            } else if (internetValue != null){

                $('#internet_user_id_error').empty();
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

                $('#internet_issues_error').html('Please select an issue!'); 
                return false;
            } else if (maintenanceIssue != null){

                $('#internet_issues_error').empty();
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
            $('#internet_user_id_error').empty();  
            $('#public_user_error').empty();
            $('#community_id_error').empty();
            $('#maintenance_type_id_error').empty();
            $('#internet_incident_status_id_error').empty();
            $('#internet_issues_error').empty();
            $('#user_id_error').empty();
            $('#action_ids_error').empty();

            this.submit();
        });
    });
</script>