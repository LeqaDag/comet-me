<div id="updateRegionModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Region</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>English Name</label>
                            <input type="text" class="form-control" id="english_name_region" 
                                placeholder="Enter English Name"> 
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Arabic Name</label> 
                            <input type="text" class="form-control" id="arabic_name_region" 
                                placeholder="Enter Arabic Name"> 
                        </fieldset> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm" 
                    id="saveRegionButton">Save
                </button>
                <button type="button" class="btn btn-default btn-sm" 
                    data-bs-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>