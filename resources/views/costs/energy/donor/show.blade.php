<style>
    .spanDetails {
        color: blue;
        font-size: 14px;
    }
</style> 
<div id="viewEnergyDonorCostModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">
                    <span id="energyDonorCostModalTitle"></span> Details
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" 
                    aria-label="Close">
                </button>
            </div> 
            <div class="modal-body">
                <div class="col-xl-12">
                    <div class="">
                        <ul class="timeline timeline-dashed mt-4">
                            <li class="timeline-item timeline-item-success mb-4">
                                <span class="timeline-indicator timeline-indicator-success">
                                    <i class="bx bx-shekel"></i>
                                </span>
                                <div class="timeline-event">
                                    <div>
                                        <div class="timeline-header border-bottom mb-3">
                                            <h6 class="mb-0">Donor
                                                <span class="text-success">Cost Details</span>
                                            </h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                                    <i class="bx bx-donate-heart bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Donor</small>
                                                        <h6 class="mb-0" id="donorName"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                                                    <i class="bx bx-calendar-event bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Year</small>
                                                        <h6 class="mb-0" id="year"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-shekel bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Cost from survey file with donor attributions</small>
                                                        <h6 class="mb-0" id="estimatedFund"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                                    <i class="bx bx-group bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1"># of households</small>
                                                        <h6 class="mb-0" id="estimatedHousehold"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-shekel bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Commitment (funds)</small>
                                                        <h6 class="mb-0" id="commitmentFund"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                                    <i class="bx bx-user-check bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Commitment (households)</small>
                                                        <h6 class="mb-0" id="commitmentHousehold"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                                    <i class="bx bx-shekel bx-sm me-3"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Remaining (funds)</small>
                                                        <h6 class="mb-0" id="remainingFund"></h6>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-lg-6">
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                                    <i id="remainingHouseholdIcon"></i>
                                                    <div class="ps-3 border-start">
                                                        <small class="text-muted mb-1">Extra (or remaining) households</small>
                                                        <h6 class="mb-0" id="remainingHousehold"></h6>
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