@extends('layouts/layoutMaster')

@section('title', 'water holder')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">
    @if($allWaterHolder->household_id)
            {{$allWaterHolder->Household->english_name}} 
        @else @if($allWaterHolder->public_structure_id)
            {{$allWaterHolder->PublicStructure->english_name}} 
        @endif
    @endif
    </span> Information 
</h4>

<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <ul class="timeline timeline-dashed mt-4">
                <li class="timeline-item timeline-item-primary mb-4">
                    <span class="timeline-indicator timeline-indicator-primary">
                    @if($allWaterHolder->household_id)
                        <i class="bx bx-user"></i>
                    @else @if($allWaterHolder->public_structure_id)
                        <i class="bx bx-building"></i>
                    @endif
                    @endif
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header border-bottom mb-3">
                            <h6 class="mb-0">
                            @if($allWaterHolder->household_id)
                                {{$allWaterHolder->Household->english_name}} 
                            @else @if($allWaterHolder->public_structure_id)
                                {{$allWaterHolder->PublicStructure->english_name}} 
                            @endif
                            @endif -  
                                <span class="text-primary">Details</span>
                            </h6>
                            <h6 class="mb-0">
                                Community :  
                                <span class="text-primary">{{$community->english_name}}</span>
                            </h6>
                        </div>
                        @if($allWaterHolder->household_id)
                        <div class="row">
                            <h6 class="text-primary">General Details</h6>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 d-flex justify-content-between flex-wrap mb-2">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <i class='bx bx-male'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1"># of Male</p>
                                            <small class="text-muted">
                                                {{$allWaterHolder->Household->number_of_male}}
                                            </small>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <i class='bx bx-female'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1"># of Female</p>
                                            <small class="text-muted">
                                                {{$allWaterHolder->Household->number_of_female}}
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-6 d-flex justify-content-between flex-wrap mb-2">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <i class='bx bx-male'></i><i class='bx bx-female'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1"># of Adults</p>
                                            <small class="text-muted">
                                                {{$allWaterHolder->Household->number_of_adults}}
                                            </small>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <i class='bx bx-face'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1"># of Children</p>
                                            <small class="text-muted">
                                                {{$allWaterHolder->Household->number_of_children}}
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div> <hr>
                        @endif

                       @if(count($energyUser) > 0)
                        <div class="row">
                            <h6 class="text-primary">Energy Service Details</h6>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-4 d-flex justify-content-between flex-wrap">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <i class='bx bx-circle'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Main Holder</p>
                                            <small class="text-muted">
                                                {{$energyUser[0]->is_main}}
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4 d-flex justify-content-between flex-wrap">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <i class='bx bx-calendar'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Energy Date</p>
                                            <small class="text-muted">
                                                {{$energyUser[0]->installation_date}}
                                            </small>
                                        </div>
                                    </li> 
                                </ul>
                            </div>
                            <div class="col-lg-4 d-flex justify-content-between flex-wrap">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                <i class='bx bx-barcode'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Meter Number</p>
                                            <small class="text-muted">
                                                {{$energyUser[0]->meter_number}}
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                </li>
                
                <li class="timeline-item timeline-item-info mb-4">
                    <span class="timeline-indicator timeline-indicator-info">
                        <i class="bx bx-water"></i>
                    </span>
                    <div class="timeline-event">
                        <div>
                            <div class="timeline-header border-bottom mb-3">
                                <h6 class="mb-0">Water - <span class="text-info">Systems</span></h6>
                                <small class="text-muted">Main Holder : 
                                    <span class="text-info">{{$allWaterHolder->is_main}}</span>
                                </small>
                            </div>
                            @if($networkUser)
                            <div class="row">
                                <div class="col-lg-6 d-flex justify-content-between flex-wrap">
                                    <ul class="p-0 m-0">
                                        <li class="d-flex mb-4">
                                            <div class="avatar avatar-sm flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded-circle bg-label-info">
                                                    <i class='bx bx-water'></i>
                                                </span>
                                            </div>
                                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-2">
                                                <p class="mb-0 lh-1">Network Holder</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @endif
                            @if($allWaterHolder->is_main == 'No')
                            <div class="row">
                                <div class="col-lg-6 d-flex justify-content-between flex-wrap">
                                    <ul class="p-0 m-0">
                                        <li class="d-flex mb-4">
                                            <div class="avatar avatar-sm flex-shrink-0 me-3">
                                                <span class="avatar-initial rounded-circle bg-label-info">
                                                    <i class='bx bx-user'></i>
                                                </span>
                                            </div>
                                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                            <div class="me-2">
                                                <p class="mb-0 lh-1">Main User</p>
                                                <small class="text-muted">
                                                    @if($mainUser)
                                                    {{$mainUser->user_english_name}}
                                                    @else @if($mainGridUser)
                                                    {{$mainGridUser->grid_user_english_name}}
                                                    @else @if($mainH2oPublic)
                                                    {{$mainH2oPublic->H2oPublicStructure->public_structure_name}}
                                                    @endif
                                                    @endif
                                                    @endif
                                                </small>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if($h2oUser)
                        <div class="row">
                            <h6><i class="bx bx-water text-info"></i> Old H2O Details</h6>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-4 d-flex justify-content-between flex-wrap mb-2">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-calendar'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Request Date</p>
                                            <small class="text-muted">
                                            {{$h2oUser->h2o_request_date}}
                                            </small>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-calendar-week'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Installation Year</p>
                                            <small class="text-muted">
                                            {{$h2oUser->installation_year}}
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4 d-flex justify-content-between flex-wrap mb-2">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-info-circle'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Number of H2O</p>
                                            <small class="text-muted">
                                            {{$h2oUser->number_of_h20}}
                                            </small>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-circle'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">H2O Status</p>
                                            <small class="text-muted">
                                            @if($h2oStatus)
                                            {{$h2oStatus->status}}
                                            @endif
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4 d-flex justify-content-between flex-wrap mb-2">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-info-circle'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Number of BSF</p>
                                            <small class="text-muted">
                                            {{$h2oUser->number_of_bsf}}
                                            </small>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-cloud-snow'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">BSF Status</p>
                                            <small class="text-muted">
                                            @if($bsfStatus)
                                            {{$bsfStatus->name}}
                                            @endif
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div> 
                        <hr>
                        @endif


                        @if($gridUser)
                        <div class="row">
                            <h6><i class="bx bx-droplet text-info"></i> Grid Integration Details</h6>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 d-flex justify-content-between flex-wrap mb-2">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-calendar'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Request Date</p>
                                            <small class="text-muted">
                                            {{$gridUser->request_date}}
                                            </small>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-expand'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Number of Grid Large</p>
                                            <small class="text-muted">
                                            {{$gridUser->grid_integration_large}}
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4 d-flex justify-content-between flex-wrap mb-2">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-collapse'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Number of Grid Small</p>
                                            <small class="text-muted">
                                            {{$gridUser->grid_integration_small}}
                                            </small>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-calendar'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Grid Large Date</p>
                                            <small class="text-muted">
                                            {{$gridUser->large_date}}
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4 d-flex justify-content-between flex-wrap mb-2">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-calendar'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Grid Small Date</p>
                                            <small class="text-muted">
                                            {{$gridUser->small_date}}
                                            </small>
                                        </div>
                                    </li>
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-exit'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Delivery</p>
                                            <small class="text-muted">
                                            {{$gridUser->is_delivery}}
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4 d-flex justify-content-between flex-wrap mb-2">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-shekel'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Paid</p>
                                            <small class="text-muted">
                                            {{$gridUser->is_paid}}
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-4 d-flex justify-content-between flex-wrap mb-2">
                                <ul class="p-0 m-0">
                                    <li class="d-flex mb-4">
                                        <div class="avatar avatar-sm flex-shrink-0 me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">
                                                <i class='bx bx-check'></i>
                                            </span>
                                        </div>
                                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="me-2">
                                            <p class="mb-0 lh-1">Complete</p>
                                            <small class="text-muted">
                                            {{$gridUser->is_complete}}
                                            </small>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div> <hr>
                        @endif

                        @if(count($sharedH2oUsers) > 0)
                        <div class="row">
                            <h6><i class="bx bx-user text-info"></i> Shared H2O Users</h6>
                        </div>
                        @foreach($sharedH2oUsers as $sharedH2oUser)
                            <ul>
                                <li class="text-muted">
                                    {{$sharedH2oUser->Household->english_name}}
                                </li>
                            </ul>
                        @endforeach
                            <hr>
                        @endif

                        @if(count($sharedH2oPublics) > 0)
                        <div class="row">
                            <h6><i class="bx bx-building text-info"></i> Shared H2O Public Structures</h6>
                        </div>
                        @foreach($sharedH2oPublics as $sharedH2oPublic)
                            <ul>
                                <li class="text-muted">
                                    {{$sharedH2oPublic->PublicStructure->english_name}}
                                </li>
                            </ul>
                        @endforeach
                            <hr>
                        @endif

                        @if(count($sharedGridUsers) > 0)
                        <div class="row">
                            <h6><i class="bx bx-user text-info"></i> Shared Grid Users</h6>
                        </div>
                        @foreach($sharedGridUsers as $sharedGridUser)
                            <ul>
                                <li class="text-muted">
                                    {{$sharedGridUser->Household->english_name}}
                                </li>
                            </ul>
                        @endforeach
                            <hr>
                        @endif

                        @if(count($allWaterHolderDonors) > 0)
                        <div class="row">
                            <h6><i class="bx bx-shekel text-info"></i> Donors</h6>
                        </div>
                        @foreach($allWaterHolderDonors as $allWaterHolderDonor)
                            <ul>
                                <li class="text-muted">
                                {{$allWaterHolderDonor->donor_name}}
                                </li>
                            </ul>
                        @endforeach
                        @endif

                    </div>
                </li>

                @if(count($waterIncident) > 0)
                <li class="timeline-item timeline-item-danger mb-4">
                    <span class="timeline-indicator timeline-indicator-danger">
                        <i class="bx bx-error"></i>
                    </span>
                    <div class="timeline-event">
                        <div>
                            <div class="timeline-header border-bottom mb-3">
                                <h6 class="mb-0">Incident - <span class="text-danger">Details</span></h6>
                                <small class="text-muted">
                                    <span class="text-danger">Date of Incident:</span>
                                    {{$waterIncident[0]->incident_date}}
                                </small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Type</p>
                                <span class="text-muted">
                                {{$waterIncident[0]->incident}}
                                </span>
                            </div>
                            <div>
                                <p class="mb-0">Status</p>
                                <span class="text-muted">
                                {{$waterIncident[0]->incident_status}}
                                </span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Response Date</p>
                                <span class="text-muted">
                                {{$waterIncident[0]->response_date}}
                                </span>
                            </div>
                        </div> <br>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                             
                            </div>
                        </div>
                    </div>
                </li>
                @endif
                <li class="timeline-end-indicator timeline-indicator-success">
                    <i class="bx bx-image"></i>
                </li>

          
            </ul>
        </div>
    </div>
</div>
@endsection