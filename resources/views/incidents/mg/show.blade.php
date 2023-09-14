<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    } 
</style>
<div id="viewMgIncidentModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="mgIncidentModalTitle"></span> Details
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div> 
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            MG System: 
                            <span class="spanDetails" id="mgSystem">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Community: 
                            <span class="spanDetails" id="communityName">
                               
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Incident Type: 
                            <span class="spanDetails" id="incidentType">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            MG Incident Status: 
                            <span class="spanDetails" id="mgIncidentStatus">
                                
                            </span>
                        </h6>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Date: 
                            <span class="spanDetails" id="incidentDate">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Year: 
                            <span class="spanDetails" id="incidentYear">
                                
                            </span>
                        </h6>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <h6>
                            Notes: 
                            <span class="spanDetails" id="incidentNotes">
                                
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