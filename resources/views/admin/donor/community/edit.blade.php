<div id="updateDonorCommunityModal" class="modal fade" tabindex="-1" aria-hidden="true" 
        role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update 
                    <span id="communityName"></span> 
                    Donor for
                    <span id="communityService"></span>
                </h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <input type="hidden" name="service_id" id="serviceId">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Donor</label>
                            <select id='donor_id' class="form-control">
                                <option id="communityDonor"selected></option>
                                @foreach($donors as $donor)
                                    <option value="{{$donor->id}}">
                                        {{$donor->donor_name}}
                                    </option>
                                @endforeach
                            </select>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm" 
                    id="saveDonorCommunityButton">Save
                </button>
                <button type="button" id="closeDonorCommunityUpdate" class="btn btn-default btn-sm" 
                    data-bs-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>