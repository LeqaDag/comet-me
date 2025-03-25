<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style> 
<div id="viewAllWorkshopModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="allWorkshopModalTitle"></span> Details
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
                                            <h6 class="mb-0">Workshop 
                                                <span class="text-danger">Details</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                                    <i class="bx bx-home bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Community</small>
                                                        <h6 class="mb-0" id="workshopCommunity"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-calendar-week bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Date</small>
                                                        <h6 class="mb-0" id="workshopDate"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-user-check bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Lead By</small>
                                                        <h6 class="mb-0" id="workshopLeadBy"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                    <i class="bx bx-female bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1"># of Female</small>
                                                        <h6 class="mb-0" id="workshopFemale"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-group bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Co-Trainers</small>
                                                        <h6 class="mb-0" id="workshopCoTrainers"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                                    <i class="bx bx-note bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Notes</small>
                                                        <h6 class="mb-0" id="workshopNotes"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-bookmark bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Workshop Type</small>
                                                        <h6 class="mb-0" id="workshopType"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                    <i class="bx bx-time bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1"># of Hours</small>
                                                        <h6 class="mb-0" id="workshopHours"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-male bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1"># of Male</small>
                                                        <h6 class="mb-0" id="workshopMale"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                                    <i class="bx bx-user bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1"># of Adult</small>
                                                        <h6 class="mb-0" id="workshopAdult"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-user-circle bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Lawyer</small>
                                                        <h6 class="mb-0" id="workshopLawyer"></h6>
                                                    </div>
                                                </li>
                                                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                                    <i class="bx bx-note bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Stories</small>
                                                        <h6 class="mb-0" id="workshopStories"></h6>
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