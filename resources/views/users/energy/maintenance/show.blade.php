<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>
<div id="viewEnergyMaintenanceModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="energyModalTitle"></span> Details
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Agent: 
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
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Recipient: 
                            <span class="spanDetails" id="userReceipent">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Type: 
                            <span class="spanDetails" id="maintenanceType">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Call Date: 
                            <span class="spanDetails" id="callDate">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Completed Date: 
                            <span class="spanDetails" id="completedDate">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Maintenance Status: 
                            <span class="spanDetails" id="maintenanceStatus">
                              
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Maintenance Action: 
                            <span class="spanDetails" id="maintenanceAction">
                                
                            </span>
                        </h6>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12">
                        <h6>
                            Notes: 
                            <span class="spanDetails" id="maintenanceNotes">
                                
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