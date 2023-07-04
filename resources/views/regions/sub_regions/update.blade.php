<div id="updateSubRegionModal"class="modal fade" tabindex="-1" aria-hidden="true" 
        role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Sub-Region</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'for="name" >English Name</label>
                            <input type="text" class="form-control" id="english_name" 
                                placeholder="Enter English Name"> 
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'for="aname" >Arabic Name</label> 
                            <input type="text" class="form-control" id="arabic_name" 
                                placeholder="Enter Arabic Name"> 
                        </fieldset> 
                    </div>
                </div>
                <br>
                <!-- <input type="text" id="selectedRegionValue" hidden>
                <fieldset class="form-group">
                    <label class='col-md-12 control-label' for="region_id">Region</label>
                    <select id='updateRegionId' class="updateRegionId form-control">
                        <option id="selectedRegion" selected></option>
                    </select> 
                </fieldset>  -->
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm" 
                    id="btnSaveSubRegion">Save
                </button>
                <button type="button" class="btn btn-default btn-sm" 
                    data-bs-dismiss="modal" id="closeSubRegionUpdate">Close
                </button>
            </div>
        </div>

    </div>
</div>
