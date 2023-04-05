<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>
<div id="viewEnergyUserModal" class="modal fade" tabindex="-1" aria-hidden="true">
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
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Meter Active: 
                            <span class="spanDetails" id="meterActiveUser">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Meter Case: 
                            <span class="spanDetails" id="meterCaseUser">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            System Name: 
                            <span class="spanDetails" id="systemNameUser">
                              
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
                            Daily Limit: 
                            <span class="spanDetails" id="systemLimitUser">
                              
                            </span>
                        </h6> 
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Installation Date: 
                            <span class="spanDetails" id="systemDateUser">
                               
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Vendor: 
                            <span class="spanDetails" id="vendorDateUser">
                               
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Notes: 
                            <span class="spanDetails" id="systemNotesUser">
                              
                            </span>
                        </h6>
                    </div>
                </div> 
<!-- 
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Household Meters: 
                            <div class="spanDetails" id="householdMeters">
                              
                            </div>
                        </h6>
                    </div>
                </div> -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>