<style>
    label, table {
        margin-top: 20px;
    }
</style>
<div id="updateSettingModal" class="modal fade" tabindex="-1" aria-hidden="true" 
        role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Setting</h4>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button> 
            </div>
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Program</label>
                            <input type="text" class="form-control" id="program"> 
                        </fieldset>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Link</label>
                            <input type="text" class="form-control" id="link"> 
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>English Description</label> 
                            <textarea class="form-control" id="englishDescription" 
                                style="resize:none" rows="8">
                            </textarea>
                        </fieldset> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Arabic Description</label> 
                            <textarea class="form-control" id="arabicDescription" 
                                style="resize:none" rows="8">
                            </textarea>
                        </fieldset> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-sm" 
                    id="saveSettingButton">Save
                </button>
                <button type="button" id="closeSettingUpdate" class="btn btn-default btn-sm" 
                    data-bs-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>