<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>

<div id="viewPublicStructureModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="publicStructureModalTitle"></span> Details
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            English Name: 
                            <span class="spanDetails" id="englishNamePublic">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Arabic Name: 
                            <span class="spanDetails" id="arabicNamePublic">
                                
                            </span>
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Community: 
                            <span class="spanDetails" id="communityName">
                               
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Notes: 
                            <span class="spanDetails" id="publicNotes">
                                
                            </span>
                        </h6>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="closeDetailsModel" type="button" 
                        class="closeDetailsModel btn btn-secondary" 
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>