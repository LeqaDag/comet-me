<!-- Action Items for Missing details for the communities -->
<li class="timeline-item mb-md-4 mb-5 timeline-item-right">
    <span class="timeline-indicator timeline-indicator-success" data-aos="zoom-in" data-aos-delay="200">
    <i class="bx bx-home"></i>
    </span>
    <div class="timeline-event card p-0" data-aos="fade-right">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="card-title mb-0">Missing Community Details</h6>
        </div>
        <div class="card-body">
            <p>We've noticed that some essential details are missing in the following.
                To ensure accuracy and completeness, click on the icons to see them 
            </p>
            <ul class="list-unstyled">
                @if(count($missingCommunityRepresentatives) > 0)
                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                    <a type="button" data-bs-toggle="modal"
                        title="view communities that missing representatives"
                        data-bs-target="#missingRepresentativesInCommunity">
                        <i class="bx bx-user bx-sm me-3"></i>
                    </a>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1">
                            <a href="/representative" target="_blank" title="click here">
                                Community Representatives
                            </a>
                        </small>
                        <h5 class="mb-0">{{$missingCommunityRepresentatives->count()}}</h5>
                    </div>
                </li>
                @include('actions.admin.community.missing_representatives')
                @endif
                @if(count($communityWaterService) > 0)
                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                    <a type="button" data-bs-toggle="modal"
                        title="view communities that missing representatives"
                        data-bs-target="#missingYesInWaterServiceForCommunity">
                        <i class="bx bx-droplet bx-sm me-3"></i>
                    </a>
                    
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1">
                            <a href="/community" target="_blank" title="click here">
                            Need to Update Water Service to "Yes"
                            </a>
                        </small>
                        <h5 class="mb-0">{{$communityWaterService->count()}}</h5>
                    </div>
                </li>
                @include('actions.admin.community.water_service')
                @endif
                @if(count($communityWaterServiceYear) > 0)
                <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                    <a type="button" data-bs-toggle="modal"
                        title="view communities that missing water year"
                        data-bs-target="#missingYearInWaterServiceForCommunity">
                        <i class="bx bx-calendar bx-sm me-3"></i>
                    </a>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1">
                            <a href="/community" target="_blank" title="click here">
                            Missing Water Year
                            </a>
                        </small>
                        <h5 class="mb-0">{{$communityWaterServiceYear->count()}}</h5>
                    </div>
                </li>
                @include('actions.admin.community.water_service_year')
                @endif
                @if(count($communityInternetService) > 0)
                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                    <a type="button" data-bs-toggle="modal"
                        title="view communities that missing internet year"
                        data-bs-target="#missingYesInInternetServiceForCommunity">
                        <i class="bx bx-wifi bx-sm me-3"></i>
                    </a>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1">
                            <a href="/community" target="_blank" title="click here">
                                Need to Update Internet Service to "Yes"
                            </a>
                        </small>
                        <h5 class="mb-0">{{$communityInternetService->count()}}</h5>
                    </div>
                </li>
                @include('actions.admin.community.internet_service')
                @endif
                @if(count($communityInternetServiceYear) > 0)
                <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                    <a type="button" data-bs-toggle="modal"
                        title="view communities that missing internet year"
                        data-bs-target="#missingYearInInternetServiceForCommunity">
                        <i class="bx bx-calendar bx-sm me-3"></i>
                    </a>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1">
                            <a href="/community" target="_blank" title="click here">
                                Missing Internet Year
                            </a>
                        </small>
                        <h5 class="mb-0">{{$communityInternetServiceYear->count()}}</h5>
                    </div>
                </li>
                @include('actions.admin.community.internet_service_year')
                @endif
            </ul>
        </div>
    <div class="timeline-event-time">Communities</div>
    </div>
</li>

<!-- Action Items for Missing details for the households -->
<li class="timeline-item mb-md-4 mb-5 timeline-item-left">
    <span class="timeline-indicator timeline-indicator-danger" data-aos="zoom-in" data-aos-delay="200">
    <i class="bx bx-user"></i>
    </span>
    <div class="timeline-event card p-0" data-aos="fade-right">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h6 class="card-title mb-0">Missing Household Details</h6>
        </div>
        <div class="card-body">
            <p>We've noticed that some essential details are missing in the following.
                To ensure accuracy and completeness, you can 
                <a type="button" title="Export Households with Missing Details"
                    href="action-item/household/missing">
                    Export
                </a>
                these missing and fill them out. 
            </p>
            <ul class="list-unstyled">
                <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                    <i class="bx bx-phone bx-sm me-3"></i>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1">Phone Numbers</small>
                        <h5 class="mb-0">{{$missingPhoneNumbers}}</h5>
                    </div>
                </li>
                <li class="d-flex justify-content-start align-items-center text-info mb-3">
                    <i class="bx bx-group bx-sm me-3"></i>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1">Adults</small>
                        <h5 class="mb-0">{{$missingAdultNumbers}}</h5>
                    </div>
                </li>
                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                    <i class="bx bx-face bx-sm me-3"></i>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1">Children</small>
                        <h5 class="mb-0">{{$missingChildrenNumbers}}</h5>
                    </div>
                </li>
                <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                    <i class="bx bx-male bx-sm me-3"></i>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1">Male</small>
                        <h5 class="mb-0">{{$missingMaleNumbers}}</h5>
                    </div>
                </li>
                <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                    <i class="bx bx-female bx-sm me-3"></i>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1">Female</small>
                        <h5 class="mb-0">{{$missingFemaleNumbers}}</h5>
                    </div>
                </li>
            </ul>
        </div>
    <div class="timeline-event-time">Households</div>
    </div>
</li> 

<!-- Action Items for Public Structures -->
<li class="timeline-item mb-md-4 mb-5 timeline-item-right">
    <span class="timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
        <i class="bx bx-building"></i>
    </span>
    <div class="timeline-event card p-0" data-aos="fade-right">
        <div class="card-header border-0 d-flex justify-content-between">
            <h6 class="card-title mb-0">Schools</h6>
        </div>
        <div class="card-body pb-0">
            @if(count($missingSchoolDetails) > 0)
            <p>You've {{$missingSchoolDetails->count()}}
            
                <a type="button" data-bs-toggle="modal" title="View Schools"
                    data-bs-target="#missingSchoolDetails" class="text-primary">
                    Schools
                </a>
                that missing the details, check them and fill them out!
            </p> 
            @include('actions.admin.public.school')
            @endif
        </div>
        <div class="timeline-event-time">Structures</div>
    </div>
</li>

<!-- Action Items for adding energy system for initial Survey -->
<li class="timeline-item timeline-item-left" >
    <span class="timeline-indicator timeline-indicator-info" data-aos="zoom-in" data-aos-delay="200">
    <i class="bx bx-edit-alt"></i>
    </span>
    <div class="timeline-event card p-0" data-aos="fade-right">
        <div class="card-header border-0 d-flex justify-content-between">
            <h6 class="card-title mb-0">
                <span class="align-middle">
                Missing Energy System Name
                </span>
            </h6>
        </div>
        @if(count($missingEnergySystems) > 0)
        <div class="card-body pb-3 pt-0">
            <p>
            We've observed that several initial and AC-survey communities have 
            suggested energy system types, yet they lack the corresponding system names. 
            It's essential to 
            <a type="button" title="Add Energy System Names"
                href="energy-system/create" target="_blank">
                Add the names 
            </a>
            associated with each of these communities: 
            </p>
            @foreach($missingEnergySystems as $missingEnergySystem)
            <ul class="list-group">
                <li class="d-flex list-group-item" style="margin-top:9px">
                    <span> {{$missingEnergySystem->english_name}}  / 
                        {{$missingEnergySystem->name}}
                    </span>   
                </li>
            </ul>
            @endforeach
        </div>
        @endif
        <div class="timeline-event-time">Initial Survey</div>
    </div>
</li>


