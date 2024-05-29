<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>

<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style>
<div id="viewPublicStructureModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="publicStructureModalTitle"></span> Details
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
                                    <i class="bx bx-building"></i>
                                </span>
                                <div class="timeline-event">
                                    <div>
                                        <div class="timeline-header border-bottom mb-3">
                                            <h6 class="mb-0">Public Structure
                                                <span class="text-primary">Details</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-list-minus bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Public English Name</small>
                                                        <h6 class="mb-0" id="englishNamePublic"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                                    <i class="bx bx-filter bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Public Arabic Name</small>
                                                        <h6 class="mb-0" id="arabicNamePublic"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-home bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Community</small>
                                                        <h6 class="mb-0" id="communityName"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-home bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Compound</small>
                                                        <h6 class="mb-0" id="compoundName"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                    <i class="bx bx-chat bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Notes</small>
                                                        <h6 class="mb-0" id="publicNotes"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row" id="kindergartenDetails" style="visibility:none; display:none">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <ul class="list-unstyled">
                                                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                        <i class="bx bx-group bx-sm me-3"></i>
                                                        <div class="ps-3 border-start">
                                                            <small class="text-muted mb-1">Number of Kindergarten students</small>
                                                            <h6 class="mb-0" id="totalKindergartenStudents"></h6>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <ul class="list-unstyled">
                                                    <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                        <i class="bx bx-male bx-sm me-3"></i>
                                                        <div class="ps-3 border-start">
                                                            <small class="text-muted mb-1">Number of Kindergarten Boys</small>
                                                            <h6 class="mb-0" id="kindergartenBoys"></h6>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <ul class="list-unstyled">
                                                    <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                        <i class="bx bx-female bx-sm me-3"></i>
                                                        <div class="ps-3 border-start">
                                                            <small class="text-muted mb-1">Number of Kindergarten Girls</small>
                                                            <h6 class="mb-0" id="kindergartenGirls"></h6>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="schoolDetails" style="visibility:none; display:none">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <ul class="list-unstyled">
                                                    <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                                                        <i class="bx bx-group bx-sm me-3"></i>
                                                        <div class="ps-3 border-start">
                                                            <small class="text-muted mb-1">Number of School students</small>
                                                            <h6 class="mb-0" id="totalSchoolStudents"></h6>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <ul class="list-unstyled">
                                                    <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                                        <i class="bx bx-male bx-sm me-3"></i>
                                                        <div class="ps-3 border-start">
                                                            <small class="text-muted mb-1">Number of School Boys</small>
                                                            <h6 class="mb-0" id="schoolBoys"></h6>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <ul class="list-unstyled">
                                                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                        <i class="bx bx-female bx-sm me-3"></i>
                                                        <div class="ps-3 border-start">
                                                            <small class="text-muted mb-1">Number of School Girls</small>
                                                            <h6 class="mb-0" id="schoolGirls"></h6>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <ul class="list-unstyled">
                                                    <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                        <i class="bx bx-log-in bx-sm me-3"></i>
                                                        <div class="ps-3 border-start">
                                                            <small class="text-muted mb-1">Grade from</small>
                                                            <h6 class="mb-0" id="gradeFrom"></h6>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-6">
                                                <ul class="list-unstyled">
                                                    <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                        <i class="bx bx-log-out bx-sm me-3"></i>
                                                        <div class="ps-3 border-start">
                                                            <small class="text-muted mb-1">Grade to</small>
                                                            <h6 class="mb-0" id="gradeTo"></h6>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
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
