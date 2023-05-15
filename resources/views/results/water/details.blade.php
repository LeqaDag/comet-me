<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>
<div id="viewWaterResultModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="WaterUserModalTitle"></span> Details
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <h5>General Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            User Name: 
                            <span class="spanDetails" id="englishNameUser">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Community: 
                            <span class="spanDetails" id="communityUser">
                               
                            </span>
                        </h6>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h5>Result Details</h5>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Date: 
                            <span class="spanDetails" id="dateH2oResult">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Year: 
                            <span class="spanDetails" id="yearH2oResult">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            CFU: 
                            <span class="spanDetails" id="cfuResult">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            FCI: 
                            <span class="spanDetails" id="fciResult">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            EC: 
                            <span class="spanDetails" id="ecResult">
                              
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            PH: 
                            <span class="spanDetails" id="phResult">
                               
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