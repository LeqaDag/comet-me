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
                <div class="col-xl-12">
                    <div class="">
                        <ul class="timeline timeline-dashed mt-4">
                            <li class="timeline-item timeline-item-primary mb-4">
                                <span class="timeline-indicator timeline-indicator-primary">
                                    <i class="bx bx-user"></i>
                                </span>
                                <div class="timeline-event">
                                    <div>
                                        <div class="timeline-header border-bottom mb-3">
                                            <h6 class="mb-0">Personal 
                                                <span class="text-danger">Details</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-user bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">User Name</small>
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
                            
                            <li class="timeline-item timeline-item-warning mb-4">
                                <span class="timeline-indicator timeline-indicator-warning">
                                    <i class="bx bx-bulb"></i>
                                </span>
                                <div class="timeline-event">
                                    <div>
                                        <div class="timeline-header border-bottom mb-3">
                                            <h6 class="mb-0">Energy
                                                <span class="text-warning">Details</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-black mb-3">
                                                    <i class="bx bx-barcode-reader bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Meter Active:</small>
                                                        <h6 class="mb-0" id="meterActiveUser"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-barcode bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Meter Case: </small>
                                                        <h6 class="mb-0" id="meterCaseUser"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                                    <i class="bx bx-bulb bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Energy System</small>
                                                        <h6 class="mb-0" id="systemNameUser"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                                    <i class="bx bx-square bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Energy System Type</small>
                                                        <h6 class="mb-0" id="systemTypeUser"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-calendar bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Energy Cycle Year</small>
                                                        <h6 class="mb-0" id="energyCycleYear"></h6>
                                                    </div>
                                                </li>
                                                
                                                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                                    <i class="bx bx-circle bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Installation Type: </small>
                                                        <h6 class="mb-0" id="installationTypeUser"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                                                    <i class="bx bx-hourglass bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Daily Limit:</small>
                                                        <h6 class="mb-0" id="systemLimitUser"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                    <i class="bx bx-calendar-check  bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Installation Date: </small>
                                                        <h6 class="mb-0" id="systemDateUser"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="timeline-item timeline-item-info mb-4">
                                <span class="timeline-indicator timeline-indicator-info">
                                    <i class="bx bx-grid"></i>
                                </span>
                                <div class="timeline-event">
                                    <div>
                                        <div class="timeline-header border-bottom mb-3">
                                            <h6 class="mb-0">Other
                                                <span class="text-info">Details</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-light mb-3">
                                                    <i class="bx bx-crosshair bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Ground Connected</small>
                                                        <h6 class="mb-0" id="systemGroundUser"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-barcode bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Vendor:  </small>
                                                        <h6 class="mb-0" id="vendorDateUser"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-shekel bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Donors:</small>
                                                        <h6 class="mb-0" id="donorsDetails"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                                    <i class="bx bx-text bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1"> Notes: </small>
                                                        <h6 class="mb-0" id="systemNotesUser"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                    <i class="bx bx-error bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Incident: </small>
                                                        <h6 class="mb-0" id="incidentUser"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-light mb-3">
                                                    <i class="bx bx-calendar bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Incident Date: </small>
                                                        <h6 class="mb-0" id="incidentDate"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                                                    <i class="bx bx-group bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Shared Households: </small>
                                                        <h6 class="mb-0" id="sharedHousehold"></h6>
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