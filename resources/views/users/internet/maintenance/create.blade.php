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
                <form method="POST" enctype='multipart/form-data' 
                    action="{{url('internet-maintenance')}}">
                    @csrf

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Community</label>
                                <select class="selectpicker form-control" 
                                    data-live-search="true" id="selectedInternetCommunity"
                                    name="community_id[]">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('community_id'))
                                    <span class="error">{{ $errors->first('community_id') }}</span>
                                @endif
                            </fieldset>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Internet User</label>
                                <select name="household_id" class="selectpicker form-control" 
                                    id="selectedInternetUser" data-live-search="true" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Internet Public Structure</label>
                                <select class="selectpicker form-control" name="public_structure_id"
                                    id="selectedInternetPublic" data-live-search="true" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance Type</label>
                                <select name="maintenance_type_id" class="form-control" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($maintenanceTypes as $maintenanceType)
                                    <option value="{{$maintenanceType->id}}">
                                        {{$maintenanceType->type}}
                                    </option>
                                    @endforeach
                                </select>
                                    @if ($errors->has('maintenance_type_id'))
                                        <span class="error">{{ $errors->first('maintenance_type_id') }}</span>
                                    @endif
                            </fieldset>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance Status</label>
                                <select name="maintenance_status_id" class="form-control" >
                                    <option disabled selected>Choose one...</option>
                                    @foreach($maintenanceStatuses as $maintenanceStatus)
                                    <option value="{{$maintenanceStatus->id}}">
                                        {{$maintenanceStatus->name}}
                                    </option>
                                    @endforeach
                                </select>
                                    @if ($errors->has('maintenance_status_id'))
                                        <span class="error">{{ $errors->first('maintenance_status_id') }}</span>
                                    @endif
                            </fieldset>
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
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Actions</label>
                                <select name="action_ids[]" class="selectpicker form-control" multiple 
                                    id="selectedInternetActions" data-live-search="true" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Recipient</label>
                                <select name="user_id" class="selectpicker form-control"
                                    data-live-search="true">
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

    $(document).on('change', '#selectedInternetCommunity', function () {
        community_id = $(this).val();

        $('#selectedInternetUser').prop('disabled', false);
        $('#selectedInternetPublic').prop('disabled', false);
        getUserByCommunity(community_id);
        getPublicByCommunity(community_id);
    });

    function getUserByCommunity(community_id) {
   
        $.ajax({
            url: "internet_user/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {

                var select = $('#selectedInternetUser'); 
                select.prop('disabled', false);
    
                select.html(data.html);
                select.selectpicker('refresh');
            }
        }); 
    }

    function getPublicByCommunity(community_id) {
        
        $.ajax({
            url: "internet_public/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {

                var selectPublic = $('#selectedInternetPublic'); 
                selectPublic.prop('disabled', false);
    
                selectPublic.html(data.html);
                selectPublic.selectpicker('refresh');
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

</script>