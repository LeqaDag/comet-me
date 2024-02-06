@extends('layouts/layoutMaster')

@section('title', 'Action Items')

@include('layouts.all')

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-2">
  <span class="text-muted fw-light">Action Items /</span> To do List
</h4>

<div class="row overflow-hidden">
    <div class="col-12">
        <ul class="timeline timeline-center mt-5">
            <!-- Action Items for Missing details for the communities -->
            <li class="timeline-item mb-md-4 mb-5">
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
                            @include('actions.community.missing_representatives')
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
                            @include('actions.community.water_service')
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
                            @include('actions.community.water_service_year')
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
                            @include('actions.community.internet_service')
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
                            @include('actions.community.internet_service_year')
                            @endif
                        </ul>

                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <p class="text-muted mb-2">Who are in charge?</p>
                                
                                <ul class="list-unstyled users-list d-flex align-items-center avatar-group">
                                @foreach($users as $user)
                                @if($user->user_type_id == 1)
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" 
                                    data-bs-placement="top" title="{{$user->name}}" 
                                    class="avatar avatar-xs pull-up">
                                   
                                @if($user->image == "")
                                
                                @if($user->gender == "male")
                                    <img src='/users/profile/male.jpg' title="{{$user->name}}" 
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png' title="{{$user->name}}" 
                                        class="rounded-circle">
                                @endif
                                @else
                                    <img src="{{url('users/profile/'.$user->image)}}" title="{{$user->name}}" 
                                        class="rounded-circle" />
                                @endif
                                </li>
                                @endif
                                @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                <div class="timeline-event-time">Communities</div>
                </div>
            </li>

            <!-- Action Items for Missing details for the households -->
            <li class="timeline-item mb-md-4 mb-5">
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
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <p class="text-muted mb-2">Who are in charge?</p>
                                
                                <ul class="list-unstyled users-list d-flex align-items-center avatar-group">
                                @foreach($users as $user)
                                @if($user->user_type_id == 1)
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" 
                                    data-bs-placement="top" title="{{$user->name}}" 
                                    class="avatar avatar-xs pull-up">
                                   
                                @if($user->image == "")
                                
                                @if($user->gender == "male")
                                    <img src='/users/profile/male.jpg' title="{{$user->name}}" 
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png' title="{{$user->name}}" 
                                        class="rounded-circle">
                                @endif
                                @else
                                    <img src="{{url('users/profile/'.$user->image)}}" title="{{$user->name}}" 
                                        class="rounded-circle" />
                                @endif
                                </li>
                                @endif
                                @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                <div class="timeline-event-time">Households</div>
                </div>
            </li> 

            <!-- Action Items for adding energy system for AC Survey -->
            <li class="timeline-item mb-md-4 mb-5 timeline-item-left">
                <span class="timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                    <i class="bx bx-building"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header border-0 d-flex justify-content-between">
                        <h6 class="card-title mb-0">Schools</h6>
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex flex-wrap mb-4">
                            @foreach($users as $user)
                            @if($user->name == "Leqa Daghameen")
                            <div>
                                <div class="avatar avatar-xs me-2">
                                @if($user->image == "")
                    
                                @if($user->gender == "male")
                                    <img src='/users/profile/male.jpg'
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png'
                                        class="rounded-circle">
                                @endif
                                @else
                                    <img src="{{url('users/profile/'.$user->image)}}" alt="Avatar" 
                                        class="rounded-circle" />
                                @endif
                                </div>
                            </div>
                            <span>Assigned this task to <strong>{{$user->name}}</strong></span>
                            @endif 
                            @endforeach
                        </div>
                        @if(count($missingSchoolDetails) > 0)
                        <p>You've {{$missingSchoolDetails->count()}}
                      
                            <a type="button" data-bs-toggle="modal" title="View Schools"
                                data-bs-target="#missingSchoolDetails" class="text-primary">
                                Schools
                            </a>
                            that missing the details, check them and fill them out!
                        </p> 
                        @include('actions.public.school')
                        @endif
                    </div>
                    <div class="timeline-event-time">Structures</div>
                </div>
            </li>

            <!-- Action Items for adding energy system for initial Survey -->
            <li class="timeline-item">
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
                        <div class="d-flex flex-wrap mb-4">
                            @foreach($users as $user)
                            @if($user->name == "Leqa Daghameen")
                            <div>
                                <div class="avatar avatar-xs me-2">
                                @if($user->image == "")
                    
                                @if($user->gender == "male")
                                    <img src='/users/profile/male.jpg' alt="Internet Manager Avatar" 
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png' alt="Internet Manager Avatar" 
                                        class="rounded-circle">
                                @endif
                                @else
                                    <img src="{{url('users/profile/'.$user->image)}}" alt="Avatar" 
                                        class="rounded-circle" />
                                @endif
                                </div>
                            </div>
                            <span>Assigned this task to <strong>{{$user->name}}</strong></span>
                            @endif
                            @endforeach
                        </div>
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

            <!-- Action Items for adding energy system for AC Survey -->
            <li class="timeline-item mb-md-4 mb-5 timeline-item-left">
                <span class="timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                    <i class="bx bx-task"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header border-0 d-flex justify-content-between">
                        <h6 class="card-title mb-0">AC Households</h6>
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex flex-wrap mb-4">
                            @foreach($users as $user)
                            @if($user->user_type_id == 3)
                            <div>
                                <div class="avatar avatar-xs me-2">
                                @if($user->image == "")
                    
                                @if($user->gender == "male")
                                    <img src='/users/profile/male.jpg'
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png'
                                        class="rounded-circle">
                                @endif
                                @else
                                    <img src="{{url('users/profile/'.$user->image)}}" alt="Avatar" 
                                        class="rounded-circle" />
                                @endif
                                </div>
                            </div>
                            <span>Assigned this task to <strong>{{$user->name}}</strong></span>
                            @endif 
                            @endforeach
                        </div>
                        <!-- <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                <div>
                                    <i class="text-success bx bx-home"></i>
                                    <span>AC Communities</span>
                                </div>
                                <div>
                                    <a type="button" data-bs-toggle="modal" 
                                        data-bs-target="#communityAC">
                                        <i class='bx bx-message-alt-detail'></i>
                                    </a>
                                </div>
                                @include('employee.community.service.ac_survey')
                            </li>
                        </ul> -->
                        @if(count($acHouseholds) > 0)
                        <p>You've {{$acHouseholds->count()}}
                            <a type="button" title="Export AC Households"
                                href="action-item/ac-household/export">
                                AC households 
                            </a>
                            which are not related to AC Survey "AC Community"
                            ,Need to be checked
                        </p> 
                        @endif
                    </div>
                    <div class="timeline-event-time">AC Survey</div>
                </div>
            </li>

            <!-- Action Items for adding energy system for In Progress -->
            <li class="timeline-item mb-md-4 mb-5 timeline-item-right">
                <span class="timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                    <i class="bx bx-task"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header border-0 d-flex justify-content-between">
                        <h6 class="card-title mb-0">AC Households</h6>
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex flex-wrap mb-4">
                            @foreach($users as $user)
                            @if($user->user_type_id == 3)
                            <div>
                                <div class="avatar avatar-xs me-2">
                                @if($user->image == "")
                    
                                @if($user->gender == "male")
                                    <img src='/users/profile/male.jpg'
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png'
                                        class="rounded-circle">
                                @endif
                                @else
                                    <img src="{{url('users/profile/'.$user->image)}}" alt="Avatar" 
                                        class="rounded-circle" />
                                @endif
                                </div>
                            </div>
                            <span>Assigned this task to <strong>{{$user->name}}</strong></span>
                            @endif 
                            @endforeach
                        </div>
                        <!-- <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                <div>
                                    <i class="text-success bx bx-home"></i>
                                    <span>AC Communities</span>
                                </div>
                                <div>
                                    <a type="button" data-bs-toggle="modal" 
                                        data-bs-target="#communityAC">
                                        <i class='bx bx-message-alt-detail'></i>
                                    </a>
                                </div>
                                @include('employee.community.service.ac_survey')
                            </li>
                        </ul> -->
                        @if(count($acHouseholds) > 0)
                        <p>You've {{$acHouseholds->count()}}
                            <a type="button" title="Export AC Households"
                                href="action-item/ac-household/export">
                                AC households 
                            </a>
                            which are not related to AC Survey "AC Community"
                            ,Need to be checked
                        </p> 
                        @endif
                    </div>
                    <div class="timeline-event-time">AC Survey</div>
                </div>
            </li>

            <!-- Action Items for adding energy details -->
            <li class="timeline-item mb-md-4 mb-5 timeline-item-left">
                <span class="timeline-indicator timeline-indicator-warning" data-aos="zoom-in" data-aos-delay="200">
                    <i class="bx bx-bulb"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header border-0 d-flex justify-content-between">
                        <h6 class="card-title mb-0">Energy Users</h6>
                    </div>
                    <div class="card-body pb-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between 
                                align-items-center ps-0">
                                <div class="d-flex flex-wrap mb-4">
                                    <i class="text-success bx bx-home"></i>
                                    <span>MISC FBS or New Community</span>
                                    <p>
                                    @if(count($newCommunityFbs) > 0)
                                        <p>You've {{$newCommunityFbs->count()}}
                                            <a title="Export AC Households"
                                                href="all-meter" target="_blank">
                                                New Energy Holders
                                            </a>
                                            need to fill out meter number, daily limit and other details.
                                        </p> 
                                    @endif
                                    </p>
                                </div> 
                                <div class="mb-4">
                                    @foreach($users as $user)
                                    @if($user->user_type_id == 3)
                                    <div>
                                        <div class="avatar avatar-xs me-2">
                                        @if($user->image == "")
                            
                                        @if($user->gender == "male")
                                            <img src='/users/profile/male.jpg'
                                                class="rounded-circle">
                                        @else
                                            <img src='/assets/images/female.png'
                                                class="rounded-circle">
                                        @endif
                                        @else
                                            <img src="{{url('users/profile/'.$user->image)}}" alt="Avatar" 
                                                class="rounded-circle" />
                                        @endif
                                        </div>
                                    </div>
                                    <span>Assigned this task to <strong>{{$user->name}}</strong>
                                    </span>
                                    @endif 
                                    @endforeach
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                <div>
                                    <i class="text-success bx bx-grid"></i>
                                    <span>MG Extension</span>
                                    <p>
                                    @if(count($newEnergyHolders) > 0)
                                        <p>You've {{$newEnergyHolders->count()}}
                                            <a title="Export AC Households"
                                                href="all-meter" target="_blank">
                                                New Energy Holders
                                            </a>
                                            need to fill out meter number, daily limit and other details.
                                        </p> 
                                    @endif
                                    </p>
                                </div>
                            </li>
                        </ul>
                       

                        <div class="d-flex flex-wrap mb-4">
                            @foreach($users as $user)
                            @if($user->user_type_id == 12)
                            <div>
                                <div class="avatar avatar-xs me-2">
                                @if($user->image == "")
                    
                                @if($user->gender == "male")
                                    <img src='/users/profile/male.jpg'
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png'
                                        class="rounded-circle">
                                @endif
                                @else
                                    <img src="{{url('users/profile/'.$user->image)}}" alt="Avatar" 
                                        class="rounded-circle" />
                                @endif
                                </div>
                            </div>
                            <span>Assigned this task to <strong>{{$user->name}}</strong></span>
                            @endif 
                            @endforeach
                        </div>
                    </div>
                    <div class="timeline-event-time">Energy Service</div>
                </div>
            </li>

            <!-- Action Items for adding english name for the internet contract holders -->
            <li class="timeline-item mb-md-4 mb-5 timeline-item-right">
                <span class="timeline-indicator timeline-indicator-success" data-aos="zoom-in" data-aos-delay="200">
                    <i class="bx bx-wifi"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-left">
                    <h6 class="card-header">Internet Users</h6>
                    <div class="card-body">
                        <div class="d-flex flex-wrap mb-4">
                            @if($internetManager)
                            <div>
                                <div class="avatar avatar-xs me-2">
                                @if($internetManager->image == "")
                    
                                @if($internetManager->gender == "male")
                                    <img src='/users/profile/male.jpg' alt="Internet Manager Avatar" 
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png' alt="Internet Manager Avatar" 
                                        class="rounded-circle">
                                @endif
                                @else
                                    <img src="{{url('users/profile/'.$internetManager->image)}}" alt="Avatar" 
                                        class="rounded-circle" />
                                @endif
                                </div>
                            </div>
                            <span>Assigned this task to <strong>{{$internetManager->name}}</strong></span>
                            @endif
                        </div>
                        <div class="mb-4">
                            <h6>Contract Holders</h6>
                            @if(count($internetUsers) == $internetDataApi[0]["total_contracts"])
                                <span>All is well!</span>
                            @else 
                            @if(count($internetUsers) < $internetDataApi[0]["total_contracts"])
                                <p>Go to the 
                                    <a href="/internet-user" target="_blank">
                                    internet service page
                                    </a>
                                    and click on “Get Latest Internet Holders”
                                </p>
                            @else
                                <p>We've in the database 
                                    {{$internetUsers->count()}}
                                    contracts, while from the API we get 
                                    {{$internetDataApi[0]["total_contracts"]}}
                                    ,Please check them! 
                                </p>
                            @endif
                            @endif
                        </div>
                        <hr>
                        <div class="mb-4">
                            <h6>Internet Young Holders</h6>
                            @if(count($youngHolders) > 0)
                            <p>Add English Name for: </p>
                            @foreach($youngHolders as $youngHolder)
                            <ul class="list-unstyled">
                                <li class="d-flex" style="margin-top:9px">
                                    <a class="btn btn-warning btn-sm" type="button" 
                                        href="/household/{{$youngHolder->id}}/edit" target="_blank">
                                        <span> {{$youngHolder->arabic_name}} </span>   
                                            Go To Edit 
                                    </a>
                                </li>
                            </ul>
                            @endforeach
                            @else
                            <span>No Internet Young Holders</span>
                            @endif

                        </div>
                    </div>
                    <div class="timeline-event-time">Internet Users</div>
                </div>
            </li>

            <!-- Action Items for adding internet system for the new communities -->
            <li class="timeline-item mb-md-4 mb-5 timeline-item-right">
                <span class="timeline-indicator timeline-indicator-success" data-aos="zoom-in" data-aos-delay="200">
                    <i class="bx bx-wifi"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-left">
                    <h6 class="card-header">Internet Systems</h6>
                    <div class="card-body">
                        <div class="d-flex flex-wrap mb-4">
                            @if($communitiesNotInSystems)
                            <div>
                                <div class="avatar avatar-xs me-2">
                                @if($internetManager->image == "")
                    
                                @if($internetManager->gender == "male")
                                    <img src='/users/profile/male.jpg' alt="Internet Manager Avatar" 
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png' alt="Internet Manager Avatar" 
                                        class="rounded-circle">
                                @endif
                                @else
                                    <img src="{{url('users/profile/'.$internetManager->image)}}" alt="Avatar" 
                                        class="rounded-circle" />
                                @endif
                                </div>
                            </div>
                            <span>Assigned this task to <strong>{{$internetManager->name}}</strong></span>
                            @endif
                        </div>
                        @if(count($communitiesNotInSystems) > 0)
                        <p>Add the 
                            <a class="btn btn-success btn-sm" type="button" 
                                href="/internet-system" target="_blank">
                                <span> internet system </span> 
                            </a>
                            for these communities: 
                        </p>
                        @foreach($communitiesNotInSystems as $communitiesNotInSystem)
                        <ul class="list-group">
                            <li class="d-flex list-group-item" style="margin-top:9px">
                                <span> {{$communitiesNotInSystem->english_name}} </span>   
                            </li>
                        </ul>
                        @endforeach
                        @endif
                    </div>
                    <div class="timeline-event-time">Internet System</div>
                </div>
            </li>

            <!-- Action Items for adding missing donors for the community and users -->
            <li class="timeline-item mb-md-4 mb-5 timeline-item-left">
                <span class="timeline-indicator timeline-indicator-info" data-aos="zoom-in" data-aos-delay="200">
                <i class="bx bx-shekel"></i>
                </span>

                <div class="timeline-event card p-0" data-aos="fade-right">
                    <h6 class="card-header">Donors</h6>
                    <div class="card-body">
                        <div class="d-flex flex-wrap mb-4">
                            @foreach($users as $user)
                            @if($user->name == "Tamar Cohen")
                            <div>
                                <div class="avatar avatar-xs me-2">
                                @if($user->image == "")
                    
                                @if($user->gender == "male")
                                    <img src='/users/profile/male.jpg' alt="Internet Manager Avatar" 
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png' alt="Internet Manager Avatar" 
                                        class="rounded-circle">
                                @endif
                                @else
                                    <img src="{{url('users/profile/'.$user->image)}}" alt="Avatar" 
                                        class="rounded-circle" />
                                @endif
                                </div>
                            </div>
                            <span>Assigned this task to <strong>{{$user->name}}</strong></span>
                            @endif
                            @endforeach
                        </div> 
                        @if(count($missingCommunityDonors) > 0)
                            <p>Add the 
                                <a class="btn btn-info btn-sm" type="button" 
                                    href="/donor" target="_blank">
                                    <span> Donors </span> 
                                </a>
                                for these communities: 
                            </p>
                            <ul class="list-group list-group-numbered">
                            @foreach($missingCommunityDonors as $missingCommunityDonor)
                                <li class="d-flex list-group-item list-group-item-info"
                                    style="margin-top:9px">
                                    <?php
                                        $missingEnergyUserDonors = DB::table('all_energy_meters')
                                        ->join('households', 'all_energy_meters.household_id', 
                                            'households.id')
                                        ->leftJoin('all_energy_meter_donors', function ($join) {
                                            $join->on('all_energy_meters.id', 
                                                'all_energy_meter_donors.community_id')
                                                ->where('all_energy_meter_donors.is_archived', 0);
                                        })
                                        ->whereNull('all_energy_meter_donors.all_energy_meter_id')
                                        ->where('all_energy_meters.community_id', 
                                            $missingCommunityDonor->id)
                                        ->select('households.english_name', 
                                            'households.id')
                                        ->get();
                                    ?>
                                    <span> 
                                        <a type="button" class="viewMissingEnergyUserDonors"
                                            data-id="{{$missingCommunityDonor->id}}">
                                            {{$missingCommunityDonor->english_name}} 
                                            / {{$missingEnergyUserDonors->count()}}
                                        </a>
                                    </span>   
                                    @include('actions.missing_donor_household')
                                </li>
                            @endforeach
                            </ul>
                        @else
                            <p>Nothing to do!</p>
                        @endif
                    </div>
                    <div class="timeline-event-time">Donors</div>
                </div>
            </li>
        </ul>
    </div>
</div>

<script>

    // View record photos
    $(document).on('click', '.viewMissingEnergyUserDonors', function() {

        community_id = $(this).data("id");

        $.ajax({
            url: "missing/donors/" + community_id,
            type: 'get',
            success: function(response) {
                //$('#missingEnergyUserDonorsModel').modal('toggle');
                $('#missingEnergyUserDonorsModelTitle').html(response.community.english_name);
                $('#missingEnergyUserDonorsContent').find('tbody').html('');
                response.html.forEach(refill_table);
                function refill_table(item, index){
                    $('#missingEnergyUserDonorsContent').find('tbody').append('<tr><td>'+item.holder_name+'</td><td>'+item.name+'</td></tr>');
                }

                $('#missingEnergyUserDonorsModel').modal('show');
            }
        });
    });

    
</script>
@endsection
