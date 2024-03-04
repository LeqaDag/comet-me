<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    } 
</style>
<div id="workPlanDetails" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="workPlanModalTitle">
                    <span>Details</span> 
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
                                    <div class="timeline-header border-bottom mb-3">
                                        <h6 class="mb-0" id="actionOwner">
                                            <span class="text-primary">Details</span>
                                        </h6>
                                        <h6 class="mb-0">
                                            Role :  
                                            <span class="text-primary" id="ownerRole"></span>
                                        </h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                                    <i class="bx bx-analyse bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Action Status</small>
                                                        <h6 class="mb-0" id="actionStatus"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-calendar bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Start Date</small>
                                                        <h6 class="mb-0" id="actionDate"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                    <i class="bx bx-note bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Notes</small>
                                                        <h6 class="mb-0" id="actionNotes"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                                    <i class="bx bx-bulb bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Action Priority</small>
                                                        <h6 class="mb-0" id="actionPriority"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-calendar bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">End Date</small>
                                                        <h6 class="mb-0" id="actionDueDate"></h6>
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