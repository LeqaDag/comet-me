<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>

<div id="viewEnergySafetyModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="energyUserModalTitle"></span> Details
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Meter Holder: 
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
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Meter Case: 
                            <span class="spanDetails" id="meterCaseUser">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            System Type: 
                            <span class="spanDetails" id="systemTypeUser">
                               
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            X 0/Phase 0: 
                            <span class="spanDetails" id="meterXphase0">
                              
                            </span>
                        </h6> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            X 0.5/Phase 180: 
                            <span class="spanDetails" id="meterXphase1">
                              
                            </span>
                        </h6> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            X 1/Phase 0: 
                            <span class="spanDetails" id="meterX1phase0">
                              
                            </span>
                        </h6> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            X 1/Phase 180: 
                            <span class="spanDetails" id="meterX1phase1">
                              
                            </span>
                        </h6> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            X 5/Phase 0:
                            <span class="spanDetails" id="meterX5phase0">
                              
                            </span>
                        </h6> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            X 5/Phase 180: 
                            <span class="spanDetails" id="meterX5phase1">
                              
                            </span>
                        </h6> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            PH-Loop: 
                            <span class="spanDetails" id="meterPhLoop">
                              
                            </span>
                        </h6> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            N-Loop: 
                            <span class="spanDetails" id="meterNLoop">
                              
                            </span>
                        </h6> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Visit Date: 
                            <span class="spanDetails" id="systemVisitDate">
                               
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Connected Ground: 
                            <span class="spanDetails" id="groundConnected">
                              
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                            Notes: 
                            <span class="spanDetails" id="systemNotesUser">
                              
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>