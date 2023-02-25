<style>
    .spanDetails {
        color: blue;
        font-size: 17px;
    }
</style>
<div id="donorEnergyUserModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span class="spanDetails" id="energyDonorModalTitle"></span>
                    <span >Donor Details</span> 
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Donor Name</label>
                            <select class="form-control" name="donor_id" id="donorsEnergyUser">
                                
                            </select>
                        </fieldset>
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
        </div>
    </div>
</div>