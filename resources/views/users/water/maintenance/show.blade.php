<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>
<div id="viewWaterMaintenanceModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="WaterModalTitle"></span> Details
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
                                                    <i id="holderIcon"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Agent</small>
                                                        <h6 class="mb-0" id="englishNameUser"></h6>
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
                                                        <h6 class="mb-0" id="communityUser"></h6>
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
                                                    <i class="bx bx-calendar bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Call Date</small>
                                                        <h6 class="mb-0" id="callDate"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-calendar-week bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Visit Date</small>
                                                        <h6 class="mb-0" id="visitDate"></h6>
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
                                                    <i class="bx bx-check bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Actions</small>
                                                        <h6 class="mb-0" id="maintenanceAction"></h6>
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
                                                        <small class="text-muted mb-1">Recipient</small>
                                                        <h6 class="mb-0" id="userReceipent"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                    <i class="bx bx-analyse bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Performed By</small>
                                                        <h6 class="mb-0" id="maintenancePerformedBy"></h6>
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