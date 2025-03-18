<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style> 
<div id="viewAllMaintenanceModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="allMaintenanceModalTitle"></span> Details
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="">
                        <ul class="timeline timeline-dashed mt-4">
                            <li class="timeline-item timeline-item-primary mb-4">
                                <span class="timeline-indicator timeline-indicator-primary">
                                    <i class="bx bx-circle"></i>
                                </span>
                                <div class="timeline-event">
                                    <div>
                                        <div class="timeline-header border-bottom mb-3">
                                            <h6 class="mb-0">Agent 
                                                <span class="text-danger">Details</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i id="agentIcon"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">English Name</small>
                                                        <h6 class="mb-0" id="englishNameAgent"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                    <i class="bx bx-phone bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Phone Number</small>
                                                        <h6 class="mb-0" id="phoneNumberAgent"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                                    <i class="bx bx-home bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Community</small>
                                                        <h6 class="mb-0" id="communityAgent"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                            <li class="timeline-item timeline-item-danger mb-4">
                                <span class="timeline-indicator timeline-indicator-danger">
                                    <i class="bx bx-chat"></i>
                                </span>
                                <div class="timeline-event">
                                    <div>
                                        <div class="timeline-header border-bottom mb-3">
                                            <h6 class="mb-0">Maintenance
                                                <span class="text-danger">Details</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                                    <i id="departamentIcon"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Departament</small>
                                                        <h6 class="mb-0" id="departamentMaintenance"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-barcode bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Energy Meter Number</small>
                                                        <h6 class="mb-0" id="meterNumberAgent"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-calendar-week bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Start Date</small>
                                                        <h6 class="mb-0" id="startDateMaintenance"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-calendar-check bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Completed Date</small>
                                                        <h6 class="mb-0" id="completedDate"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                                                    <i class="bx bx-analyse bx-sm me-3"></i>
                                                    <div class="ps-3 border-start" >
                                                        <small class="text-muted mb-1">Last Run Hours</small>
                                                        <h6 class="mb-0" id="lastHourGenerator"></h6> <br>
                                                        <small class="text-muted mb-1">Run Hours</small>
                                                        <h6 class="mb-0" id="runHourGenerator"></h6> <br>
                                                        <small class="text-muted mb-1">Run Hours to perform maintenance</small>
                                                        <h6 class="mb-0" id="runPerformedHourGenerator"></h6> <br>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                                    <i class="bx bx-map bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Maintenance Type</small>
                                                        <h6 class="mb-0" id="maintenanceType"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-light mb-3">
                                                    <i class="bx bx-crosshair bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Maintenance Status</small>
                                                        <h6 class="mb-0" id="maintenanceStatus"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                                                    <i class="bx bx-male bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Assigned to</small>
                                                        <h6 class="mb-0" id="userReceipent"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                                    <i class="bx bx-note bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Notes</small>
                                                        <h6 class="mb-0" id="maintenanceNotes"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-lg-12">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                                                    <i class="bx bx-check bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Actions</small>
                                                        <h6 class="mb-0" id="maintenanceAction"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
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