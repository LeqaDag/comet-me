<!-- Action Items for Missing details for MISC households -->
<li class="timeline-item mb-md-4 mb-5 timeline-item-left">
    <span class="timeline-indicator timeline-indicator-danger" data-aos="zoom-in" data-aos-delay="200">
        <i class="bx bx-user"></i>
    </span>
    <div class="timeline-event card p-0" data-aos="fade-right">
        <div class="card-body">
            <div class="d-flex flex-wrap mb-4">
                <h6 class="card-title mb-0">
                    <a data-toggle="collapse" class="text-danger" 
                        href="#MiscHouseholdsTab" 
                        aria-expanded="false" 
                        aria-controls="MiscHouseholdsTab">
                        MISC Households
                    </a>
                </h6> 
            </div>

            <div class="collapse multi-collapse container mb-4" 
                id="MiscHouseholdsTab">
                <p>We've noticed that some essential details are missing for the MISC households.
                </p>
                <ul class="list-unstyled">
                    @if(count($missingPhoneMiscHouseholds) > 0)
                    <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                        <i class="bx bx-phone bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Phone Numbers</small>
                            <h5 class="mb-0">
                                <a type="button"
                                    data-bs-toggle="modal" data-bs-target="#viewMissingMiscPhoneNumber">
                                    {{$missingPhoneMiscHouseholds->count()}}
                                </a>
                            </h5>  
                            @include('actions.admin.internal.household.misc.phone')
                        </div>
                    </li>
                    @endif
                    @if(count($missingAdultMiscHouseholds) > 0)
                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                        <i class="bx bx-group bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Adults</small>
                            <h5 class="mb-0">
                                <a type="button"
                                    data-bs-toggle="modal" data-bs-target="#viewMissingMiscAdult">
                                    {{$missingAdultMiscHouseholds->count()}}
                                </a>
                            </h5>  
                            @include('actions.admin.internal.household.misc.adult')
                        </div>
                    </li>
                    @endif
                    @if(count($missingChildrenMiscHouseholds) > 0)
                    <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                        <i class="bx bx-face bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Children</small>
                            <h5 class="mb-0">
                                <a type="button"
                                    data-bs-toggle="modal" data-bs-target="#viewMissingMiscChildren">
                                    {{$missingChildrenMiscHouseholds->count()}}
                                </a>
                            </h5>  
                            @include('actions.admin.internal.household.misc.children')
                        </div>
                    </li>
                    @endif
                    @if(count($missingMaleMiscHouseholds) > 0)
                    <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                        <i class="bx bx-male bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Male</small>
                            <h5 class="mb-0">
                                <a type="button"
                                    data-bs-toggle="modal" data-bs-target="#viewMissingMiscMale">
                                    {{$missingMaleMiscHouseholds->count()}}
                                </a>
                            </h5>  
                            @include('actions.admin.internal.household.misc.male')
                        </div>
                    </li>
                    @endif
                    @if(count($missingFemaleMiscHouseholds) > 0)
                    <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                        <i class="bx bx-female bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Female</small>
                            <h5 class="mb-0">
                                <a type="button"
                                    data-bs-toggle="modal" data-bs-target="#viewMissingMiscFemale">
                                    {{$missingFemaleMiscHouseholds->count()}}
                                </a>
                            </h5>  
                            @include('actions.admin.internal.household.misc.female')
                        </div>
                    </li>
                    @endif
                </ul>
            </div>

            <hr>
            
            <div class="d-flex flex-wrap mb-4">
                <h6 class="card-title mb-0">
                    <a data-toggle="collapse" class="text-danger" 
                        href="#NewCycleHouseholdsTab" 
                        aria-expanded="false" 
                        aria-controls="NewCycleHouseholdsTab">
                        New Cycle - Households
                    </a>
                </h6> 
            </div>

            <div class="collapse multi-collapse container mb-4" 
                id="NewCycleHouseholdsTab">
                <p>We've noticed that some essential details are missing for the new households.you can 
                <a type="button" title="Export Households with Missing Details"
                    href="action-item/new-household/missing">
                    Export
                </a>
                these missing and fill them out in the platform. 
                </p>
                <ul class="list-unstyled">
                    <!-- @if(count($missingPhoneNewHouseholds) > 0)
                    <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                        <i class="bx bx-phone bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Phone Numbers</small>
                            <h5 class="mb-0">
                                <a type="button"
                                    data-bs-toggle="modal" data-bs-target="#viewMissingNewPhoneNumber">
                                    {{$missingPhoneNewHouseholds->count()}}
                                </a>
                            </h5>  
                            @include('actions.admin.internal.household.new.phone')
                        </div>
                    </li>
                    @endif
                    @if(count($missingAdultNewHouseholds) > 0)
                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                        <i class="bx bx-group bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Adults</small>
                            <h5 class="mb-0">
                                <a type="button"
                                    data-bs-toggle="modal" data-bs-target="#viewMissingNewAdult">
                                    {{$missingAdultNewHouseholds->count()}}
                                </a>
                            </h5>  
                            @include('actions.admin.internal.household.new.adult')
                        </div>
                    </li>
                    @endif
                    @if(count($missingChildrenNewHouseholds) > 0)
                    <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                        <i class="bx bx-face bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Children</small>
                            <h5 class="mb-0">
                                <a type="button"
                                    data-bs-toggle="modal" data-bs-target="#viewMissingNewChildren">
                                    {{$missingChildrenNewHouseholds->count()}}
                                </a>
                            </h5>  
                            @include('actions.admin.internal.household.new.children')
                        </div>
                    </li>
                    @endif
                    @if(count($missingMaleNewHouseholds) > 0)
                    <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                        <i class="bx bx-male bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Male</small>
                            <h5 class="mb-0">
                                <a type="button"
                                    data-bs-toggle="modal" data-bs-target="#viewMissingNewMale">
                                    {{$missingMaleNewHouseholds->count()}}
                                </a>
                            </h5>  
                            @include('actions.admin.internal.household.new.male')
                        </div>
                    </li>
                    @endif
                    @if(count($missingFemaleNewHouseholds) > 0)
                    <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                        <i class="bx bx-female bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Female</small>
                            <h5 class="mb-0">
                                <a type="button"
                                    data-bs-toggle="modal" data-bs-target="#viewMissingNewFemale">
                                    {{$missingFemaleNewHouseholds->count()}}
                                </a>
                            </h5>  
                            @include('actions.admin.internal.household.new.female')
                        </div>
                    </li>
                    @endif -->
                </ul>
            </div>
        </div>
    <div class="timeline-event-time">Households</div>
    </div>
</li> 