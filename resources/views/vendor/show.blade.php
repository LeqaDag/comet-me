<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>

<div id="vendorDetails" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="vendorModalTitle"></span> Details
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
                                            <h6 class="mb-0">General 
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
                                                        <small class="text-muted mb-1">English Name</small>
                                                        <h6 class="mb-0" id="englishNameVendingPoint"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                                    <i class="bx bx-phone bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Phone Number</small>
                                                        <h6 class="mb-0" id="phoneNumberVendingPoint"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                                    <i class="bx bx-circle bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Vendor Username</small>
                                                        <h6 class="mb-0" id="usernameVendingPoint"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-secondary mb-3">
                                                    <i class="bx bx-map-pin bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Region</small>
                                                        <h6 class="mb-0" id="locationVendingPoint"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-home bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Served Communities</small>
                                                        <h6 class="mb-0" id="communitiesVendingPoint"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-user bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Arabic Name</small>
                                                        <h6 class="mb-0" id="arabicNameVendingPoint"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                    <i class="bx bx-phone bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Additional Phone Number</small>
                                                        <h6 class="mb-0" id="additionalphoneNumberVendingPoint"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                                                    <i class="bx bx-map bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Location</small>
                                                        <h6 class="mb-0" id="regionVendingPoint"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                                    <i class="bx bx-text bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1"> Notes: </small>
                                                        <h6 class="mb-0" id="notesVendingPoint"></h6>
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