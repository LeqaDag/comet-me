<style>
    label, input {
    display: block;
}

label, table {
    margin-top: 20px;
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>

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
                                    multiple data-live-search="true" 
                                    name="community_id[]" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($communities as $community)
                                    <option value="{{$community->id}}">
                                        {{$community->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Water User</label>
                                <select name="household_id[]" class="selectpicker form-control" 
                                    multiple data-live-search="true" required >
                                    <option disabled selected>Choose one...</option>
                                    @foreach($households as $household)
                                    <option value="{{$household->id}}">
                                        {{$household->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Public Structure</label>
                                <select class="selectpicker form-control" 
                                    multiple data-live-search="true" 
                                    name="public_structure_id[]" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($publics as $public)
                                    <option value="{{$public->id}}">
                                        {{$public->english_name}}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div> 
                    </div>
                    
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Date Of Call</label>
                                <input type="date" name="date_of_call" class="form-control">
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
                                <label class='col-md-12 control-label'>Maintenance Type</label>
                                <select name="maintenance_type_id" class="form-control" required>
                                    <option disabled selected>Choose one...</option>
                                    @foreach($maintenanceTypes as $maintenanceType)
                                    <option value="{{$maintenanceType->id}}">
                                        {{$maintenanceType->type}}
                                    </option>
                                    @endforeach
                                </select>
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
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Maintenance H2O Action</label>
                                <select name="maintenance_h2o_action_id" class="form-control">
                                    <option disabled selected>Choose one...</option>
                                    @foreach($maintenanceH2oActions as $maintenanceH2oAction)
                                    <option value="{{$maintenanceH2oAction->id}}">
                                        {{$maintenanceH2oAction->maintenance_action_h2o}}
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

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


<script>

    $(document).on('change', '#selectedUserCommunity', function () {
        community_id = $(this).val();
   
        $.ajax({
            url: "energy-user/get_by_community/" + community_id,
            method: 'GET',
            success: function(data) {
                $('#selectedUserHousehold').prop('disabled', false);
                $('#selectedUserHousehold').html(data.html);
                
                $('#selectedSharedMeter').prop('disabled', false);
                $(document).on('change', '#selectedSharedMeter', function () {

                    user_id = $("#selectedUserHousehold").val();

                    $.ajax({
                        url: "energy-user/shared_household/" + community_id + "/" + user_id,
                        method: 'GET',
                        success: function(data) {
                         
                            $('#selectedHouseholdMeter').prop('disabled', false);
                            $('#selectedHouseholdMeter').append(data.html);
                            $('.selectpicker').selectpicker('refresh');
                        }
                    });
                });
            }
        });
    });

    $(document).on('change', '#selectedEnergySystemType', function () {
        energy_type_id = $(this).val();
   
        $.ajax({
            url: "energy-user/get_by_energy_type/" + energy_type_id,
            method: 'GET',
            success: function(data) {
                $('#selectedEnergySystem').prop('disabled', false);
                $('#selectedEnergySystem').html(data.html);
            }
        });
    });

</script>