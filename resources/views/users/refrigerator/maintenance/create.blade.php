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
                <form method="POST" enctype='multipart/form-data' 
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
                                @if ($errors->has('community_id'))
                                    <span class="error">{{ $errors->first('community_id') }}</span>
                                @endif
                            </fieldset>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Refrigerator User</label>
                                <select name="household_id" class="form-control" 
                                    id="selectedRefrigeratorUser" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Phone Number</label>
                                <input type="text" name="phone_number" id="householdPhoneNumber"
                                class="form-control"> 
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Structure</label>
                                <select class=" form-control" name="public_structure_id"
                                    id="selectedPublic" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div> 
                    </div>
                    
                    <div class="row">
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
                                    class="selectpicker form-control" data-live-search="true" >
                                    <option disabled selected>Choose one...</option>
                                    @foreach($maintenanceRefrigeratorActions as $maintenanceRefrigeratorAction)
                                    <option value="{{$maintenanceRefrigeratorAction->id}}">
                                        {{$maintenanceRefrigeratorAction->maintenance_action_refrigerator}}
                                    </option>
                                    @endforeach
                                    @if ($errors->has('maintenance_status_id'))
                                        <span class="error">{{ $errors->first('maintenance_status_id') }}</span>
                                    @endif
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 mb-1">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Recipient</label>
                                <select name="user_id" class="form-control">
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

    $(document).on('change', '#selectedUserCommunity', function () {
        community_id = $(this).val();

        $('#selectedRefrigeratorUser').prop('disabled', false);
        $('#selectedPublic').prop('disabled', false);
        getUserByCommunity(community_id);
        getPublicByCommunity(community_id);
    });

    function getUserByCommunity(community_id) {
   
        $.ajax({
            url: "refrigerator-user/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {

                $('#selectedRefrigeratorUser').prop('disabled', false);
                $('#selectedRefrigeratorUser').html(data.html);
            }
        });
    }

    function getPublicByCommunity(community_id) {
        
        $.ajax({
            url: "refrigerator-public/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {

                $('#selectedPublic').prop('disabled', false);
                $('#selectedPublic').html(data.html);
            }
        });
    }

</script>