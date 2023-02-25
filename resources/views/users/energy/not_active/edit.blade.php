<style>
    label, input{
    display: block;
}

label {
    margin-top: 20px;
}
</style>

<div id="updateAllEnergyUserModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editUserMeter" name="editUserMeter"
             enctype="multipart/form-data" >
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Energy User</h4>
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Meter Number</label>
                                <input type="text" class="form-control" id="meter_number" 
                                    placeholder="Enter Meter Number"> 
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Daily limit</label> 
                                <input type="text" class="form-control" id="daily_limit" 
                                    placeholder="Enter daily limit"> 
                            </fieldset> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Installation date</label>
                                <input type="date" class="form-control" id="installation_date" 
                                    placeholder="Enter Installation date"> 
                            </fieldset>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Meter Active</label> 
                                <select id='meter_active' class="updateRegionId form-control">
                                    <option id="selectedActive" selected disabled></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select> 
                            </fieldset> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label' for="region_id">Meter Case</label>
                                <select id='meter_case_id' name="meter_case_id " class="form-control">
                                    <option id="selectedMeterCase" disabled selected></option>
                                    @foreach($meterCases as $meterCase)
                                        <option value="{{$meterCase->id}}">
                                            {{$meterCase->meter_case_name_english}}
                                        </option>
                                    @endforeach
                                </select> 
                            </fieldset> 
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <fieldset class="form-group">
                                <label class='col-md-12 control-label'>Vendor Name</label> 
                                <select id='meter_active' class="updateRegionId form-control">
                                    <option id="selectedActive" selected disabled></option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select> 
                            </fieldset> 
                        </div>
                    </div>

                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-sm" 
                        id="btn_save">Save
                    </button>
                    <button type="button" class="btn btn-default btn-sm" 
                        data-bs-dismiss="modal">Close
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
