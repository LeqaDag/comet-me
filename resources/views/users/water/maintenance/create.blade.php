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
                <form method="POST" enctype='multipart/form-data' 
                    action="{{url('water-maintenance')}}">
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
                                @if ($errors->has('community_id'))
                                    <span class="error">{{ $errors->first('community_id') }}</span>
                                @endif
                            </fieldset>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water User</label>
                                <select name="household_id" class="form-control"
                                        id="selectedWaterUser" disabled>
                                    <option disabled selected>Choose one...</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Structure</label>
                                <select class="form-control" id="selectedWaterPublic"
                                    name="public_structure_id"disabled>
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
                                <label class='col-md-12 control-label'>Date Of Call</label>
                                <input type="date" name="date_of_call" class="form-control" required>
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
                                <label class='col-md-12 control-label'>Maintenance H2O Action</label>
                                <select name="maintenance_h2o_action_id[]" 
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
                            @if ($errors->has('maintenance_h2o_action_id'))
                                <span class="error">{{ $errors->first('maintenance_h2o_action_id') }}</span>
                            @endif
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

        $('#selectedWaterUser').prop('disabled', false);
        $('#selectedWaterPublic').prop('disabled', false);
        getUserByCommunity(community_id);
        getPublicByCommunity(community_id);

    });

    function getUserByCommunity(community_id) {
   
        $.ajax({
            url: "water_user/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                $('#selectedWaterUser').prop('disabled', false);
                $('#selectedWaterUser').html(data.html);
            }
        });
    } 

    function getPublicByCommunity(community_id) {
        
        $.ajax({
            url: "water_public/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                $('#selectedWaterPublic').prop('disabled', false);
                $('#selectedWaterPublic').html(data.html);
            }
        });
    }

</script>