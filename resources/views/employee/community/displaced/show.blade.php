@extends('layouts/layoutMaster')

@section('title', 'displaced community details')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">
        @if($newCommunity[0]->new_community)
            <del>{{$community->english_name}}</del>
            / <b>{{$newCommunity[0]->new_community}}</b>
        @else

            {{$community->english_name}}
        @endif
    </span>  Information 
</h4>
 
<div class="row overflow-hidden">
    <div class="col-12">
        <ul class="timeline timeline-center mt-5">
            <li class="timeline-item mb-md-4 mb-5">
                <span class="timeline-indicator timeline-indicator-primary" 
                    data-aos="zoom-in" data-aos-delay="200">
                    <i class="bx bx-home"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="card-title mb-0 text-danger">
                            {{$community->english_name}}
                        </h6>
                        <div class="meta">
                            <span class="badge rounded-pill bg-label-danger">
                                {{$community->arabic_name}}
                            </span>
                        </div>
                        @if($newCommunity[0]->new_community)
                        <h6 class="card-title mb-0">
                            {{$newCommunity[0]->new_community}}
                        </h6>
                        <div class="meta">
                            <span class="badge rounded-pill bg-label-primary">
                                {{$newCommunity[0]->new_arabic_community}}
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                        <i class="bx bx-map bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Old Region</small>
                                            <h6 class="mb-0">{{$community->Region->english_name}}</h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                        <i class="bx bx-map-pin bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Old Sub Region</small>
                                            <h6 class="mb-0">{{$community->SubRegion->english_name}}</h6>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                        <i class="bx bx-map bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">New Region</small>
                                            @if($newSubRegion)
                                          
                                            <h6 class="mb-0">{{$newSubRegion->SubRegion->Region->english_name}}</h6>
                                            @endif
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                        <i class="bx bx-map-pin bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">New Sub Region</small>
                                            @if($newSubRegion)
                                            <h6 class="mb-0">{{$newSubRegion->SubRegion->english_name}}</h6>
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div> <hr>
                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="list-unstyled">
                                        <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                            <i class="bx bx-group bx-sm me-3"></i>
                                            <div class="ps-3 border-start">
                                                <small class="text-muted mb-1"># of Displaced People</small>
                                                <h6 class="mb-0">{{$displacedPeople}}</h6>
                                            </div>
                                        </li>
                                        <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                            <i class="bx bx-user-pin bx-sm me-3"></i>
                                            <div class="ps-3 border-start">
                                                <small class="text-muted mb-1"># of Displaced Households</small>
                                                <h6 class="mb-0">{{$displacedHousehold}}</h6>
                                            </div>
                                        </li>
                                        <li class="d-flex justify-content-start align-items-center text-dark mb-3">
                                            <i class="bx bx-user bx-sm me-3"></i>
                                            <div class="ps-3 border-start">
                                                <small class="text-muted mb-1">Fallah</small>
                                                <h6 class="mb-0">{{$community->is_fallah}}</h6>
                                            </div>
                                        </li>
                                        <li class="d-flex justify-content-start align-items-center 
                                            text-danger mb-3">
                                            <i class="bx bx-book-reader bx-sm me-3"></i>
                                            <div class="ps-3 border-start">
                                                <small class="text-muted mb-1">Bedouin</small>
                                                <h6 class="mb-0">{{$community->is_bedouin}}</h6>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-event-time">General </div>
                </div>
            </li>

            @if($newCommunity[0]->new_community)
            <li class="timeline-item mb-md-4 mb-5">
                <span class="timeline-indicator timeline-indicator-dark" 
                    data-aos="zoom-in" data-aos-delay="200">
                    <i class="bx bx-home"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h6 class="card-title mb-0">
                            {{$community->english_name}}
                        </h6>
                        <div class="meta">
                            <span class="badge rounded-pill bg-label-primary">
                                {{$community->arabic_name}}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-start align-items-center 
                                        text-success mb-3">
                                        <i class="bx bx-rename bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Second Name</small>
                                            <h6 class="mb-0">
                                                @if($secondName)
                                                {{$secondName->english_name}}
                                                @endif
                                            </h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                        <i class="bx bx-user-voice bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Community Representative</small>
                                            @if(count($communityRepresentative)>0)
                                                @foreach($communityRepresentative as $representative)
                                                <ul>
                                                    <li>
                                                        <span class="text-dark" style="font-size:12px">
                                                            {{$representative->english_name}} 
                                                            / {{$representative->role}}
                                                        </span>
                                                    </li>
                                                </ul>
                                                @endforeach
                                            @endif
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center 
                                        text-primary mb-3">
                                        <i class="bx bx-building bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Public Structures</small>
                                            @if(count($publicStructures)>0)
                                                @foreach($publicStructures as $publicStructure)
                                                <ul>
                                                    <li>
                                                        <span class="text-dark" style="font-size:12px">
                                                            {{$publicStructure->english_name}}
                                                        </span>
                                                    </li>
                                                </ul>
                                                @endforeach
                                            @endif
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center 
                                        text-warning mb-3">
                                        <i class="bx bx-area bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Compounds</small>
                                            @if(count($compounds)>0)
                                                @foreach($compounds as $compound)
                                                <ul>
                                                    <li>
                                                        <span class="text-dark" style="font-size:12px">
                                                            <a type="button" data-bs-toggle="modal"
                                                                title="view households"
                                                                data-bs-target="#compoundHouseholds{{$compound->id}}">
                                                                {{$compound->english_name}}
                                                            </a>
                                                            @include('employee.community.compound_households')
                                                        </span>
                                                    </li>
                                                </ul>
                                                @endforeach
                                            @endif
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center 
                                        text-dark mb-3">
                                        <i class="bx bx-map-alt bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Nearby Towns</small>
                                            @if(count($nearbyTowns)>0)
                                                @foreach($nearbyTowns as $nearbyTown)
                                                <ul>
                                                    <li>
                                                        <span class="text-dark" style="font-size:12px">
                                                            {{$nearbyTown->english_name}}
                                                        </span>
                                                    </li>
                                                </ul>
                                                @endforeach
                                            @endif
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center 
                                        text-danger mb-3">
                                        <i class="bx bx-building-house bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Nearby Settlements</small>
                                            @if(count($nearbySettlements)>0)
                                                @foreach($nearbySettlements as $nearbySettlement)
                                                <ul>
                                                    <li>
                                                        <span class="text-dark" style="font-size:12px">
                                                            {{$nearbySettlement->english_name}}
                                                        </span>
                                                    </li>
                                                </ul>
                                                @endforeach
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-event-time">Other </div>
                </div>
            </li>
            <li class="timeline-item mb-md-4 mb-5">
                <span class="timeline-indicator timeline-indicator-danger" data-aos="zoom-in" data-aos-delay="200">
                <i class="bx bx-server"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-left">
                <div class="card-header border-0 d-flex justify-content-between">
                    <h6 class="card-title mb-0">
                        <span class="align-middle">
                            {{$community->english_name}} - 
                            <span class="badge rounded-pill bg-label-danger">Services</span>
                        </span>
                    </h6>
                </div>
                <div class="card-body pb-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between 
                            align-items-center ps-0 text-warning">
                            <div>
                                <i class="bx bx-bulb"></i>
                                <span>Energy Service</span>
                            </div>
                            <div>
                                {{$community->energy_service}}
                                @if($community->energy_service_beginning_year)
                                    <span class="text-dark">
                                        ({{$community->energy_service_beginning_year}})
                                    </span>
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between 
                            align-items-center ps-0 text-info">
                            <div>
                                <i class="bx bx-droplet"></i>
                                <span>Water Service</span>
                            </div>
                            <div>
                                {{$community->water_service}}
                                @if($community->water_service_beginning_year)
                                    <span class="text-dark">
                                        ({{$community->water_service_beginning_year}})
                                    </span>
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between 
                            align-items-center ps-0 text-success">
                            <div>
                                <i class="bx bx-wifi"></i>
                                <span>Internet Service</span>
                            </div>
                            <div>
                                {{$community->internet_service}}
                                @if($community->internet_service_beginning_year)
                                    <span class="text-dark">
                                        ({{$community->internet_service_beginning_year}})
                                    </span>
                                @endif
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="timeline-event-time">Services </div>
                </div>
            </li>
            <li class="timeline-item mb-md-4 mb-5">
                <span class="timeline-indicator timeline-indicator-warning" data-aos="zoom-in" data-aos-delay="200">
                <i class="bx bx-bulb"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header border-0 d-flex justify-content-between">
                        <h6 class="card-title mb-0">Energy Service</h6>
                        <span class="text-muted">
                            @if($community->energy_service_beginning_year)
                                Start at {{$community->energy_service_beginning_year}}
                            @endif
                        </span>
                    </div>
                    <div class="card-body pb-0">
                        <div class="hours mb-2">
                            <i class="bx bx-barcode"></i>
                            <span>Total Meters</span>
                            <i class="bx bx-transfer mx-2"></i>
                            <span>{{$totalMeters}}</span>
                        </div>
                        <div class="hours mb-2">
                            <i class="bx bx-grid"></i>
                            <span>Energy Source</span>
                            <i class="bx bx-transfer mx-2"></i>
                            <span>{{$community->energy_source}}</span>
                        </div>
                    </div>
                    <div class="timeline-event-time">Energy</div>
                </div>
            </li>
            <li class="timeline-item mb-md-4 mb-5">
                <span class="timeline-indicator timeline-indicator-info" data-aos="zoom-in" data-aos-delay="200">
                <i class="bx bx-droplet"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header border-0 d-flex justify-content-between">
                        <h6 class="card-title mb-0">Water Service</h6>
                        <span class="text-muted">
                            @if($community->water_service_beginning_year)
                                Start at {{$community->water_service_beginning_year}}
                            @endif
                        </span>
                    </div>
                    <div class="card-body pb-0">
                        <div class="hours mb-2">
                            <i class="bx bx-grid-alt"></i>
                            <span>Water Sources</span>
                            <i class="bx bx-transfer mx-2"></i>
                            @if(count($communityWaterSources) > 0)
                            @foreach($communityWaterSources as $communityWaterSource)
                            <span>
                                {{$communityWaterSource->name}}, 
                            </span>
                            @endforeach
                            @endif
                        </div>
                        <div class="hours mb-2">
                            <i class="bx bx-water"></i>
                            <span>Total H2O Holders</span>
                            <i class="bx bx-transfer mx-2"></i>
                            <span>{{$totalWaterHolders}}</span>
                        </div>
                        <div class="hours mb-2">
                            <i class="bx bx-grid"></i>
                            <span>Total Grid Large</span>
                            <i class="bx bx-transfer mx-2"></i>
                            <span>{{$gridLarge->sum}}</span>
                        </div>
                        <div class="hours mb-2">
                            <i class="bx bx-grid-small"></i>
                            <span>Total Grid Small</span>
                            <i class="bx bx-transfer mx-2"></i>
                            <span>{{$gridSmall->sum}}</span>
                        </div>
                    </div>
                    <div class="timeline-event-time">Water</div>
                </div>
            </li>
            <li class="timeline-item mb-md-4 mb-5">
                <span class="timeline-indicator timeline-indicator-success" data-aos="zoom-in" data-aos-delay="200">
                <i class="bx bx-wifi"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header border-0 d-flex justify-content-between">
                        <h6 class="card-title mb-0">Internet Service</h6>
                        <span class="text-muted">
                            @if($community->internet_service_beginning_year)
                                Start at {{$community->internet_service_beginning_year}}
                            @endif
                        </span>
                    </div>
                    <div class="card-body pb-0">
                        <div class="hours mb-2">
                            <i class="bx bx-phone"></i>
                            <span>Reception</span>
                            <i class="bx bx-transfer mx-2"></i>
                            <span>{{$community->reception}}</span>
                        </div>
                        <div class="hours mb-2">
                            <i class="bx bx-wifi"></i>
                            <span>Total Contract Holders</span>
                            <i class="bx bx-transfer mx-2"></i>
                            <span>{{$internetHolders}}</span>
                        </div>
                        <div class="hours mb-2">
                            <i class="bx bx-check-circle"></i>
                            <span>Total Active Holders</span>
                            <i class="bx bx-transfer mx-2"></i>
                            <span></span>
                        </div>
                        <div class="hours mb-2">
                            <i class="bx bx-error-circle"></i>
                            <span>Total Inactive Holders</span>
                            <i class="bx bx-transfer mx-2"></i>
                            <span></span>
                        </div>
                    </div>
                    <div class="timeline-event-time">Internet</div>
                </div>
            </li>
            <li class="timeline-item">
                <span class="timeline-indicator timeline-indicator-info" data-aos="zoom-in" 
                    data-aos-delay="200">
                    <i class="bx bx-dollar"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header border-0 d-flex justify-content-between">
                        <h6 class="card-title mb-0">
                            <span class="align-middle">Services Donors</span>
                        </h6>
                    </div>
                    <div class="card-body pb-3 pt-0">
                        <div class="d-flex flex-wrap flex-sm-row flex-column">
                            <div class="mb-sm-0 mb-3 me-5">
                                <p class="text-muted mb-2">
                                    <i class="bx bx-bulb"></i>
                                    Energy Donors
                                </p>
                                @if(count($energyDonors) > 0)
                                <div class="d-flex align-items-center">
                                    @foreach($energyDonors as $energyDonor)
                                    <ul class="me-2">
                                        <li class="avatar-initial rounded-circle 
                                            bg-label-warning">
                                            {{$energyDonor->donor_name}}
                                        </li>
                                    </ul>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-wrap flex-sm-row flex-column">
                            <div class="mb-sm-0 mb-3 me-5">
                                <p class="text-muted mb-2">
                                    <i class="bx bx-droplet"></i>
                                    Water Donors
                                </p>
                                @if(count($waterDonors) > 0)
                                <div class="d-flex align-items-center">
                                    @foreach($waterDonors as $waterDonor)
                                    <ul class="me-2">
                                        <li class="avatar-initial rounded-circle 
                                            bg-label-info">
                                            {{$waterDonor->donor_name}}
                                        </li>
                                    </ul>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-wrap flex-sm-row flex-column">
                            <div class="mb-sm-0 mb-3 me-5">
                                <p class="text-muted mb-2">
                                    <i class="bx bx-wifi"></i>
                                    Internet Donors
                                </p>
                                @if(count($internetDonors) > 0)
                                <div class="d-flex align-items-center">
                                    @foreach($internetDonors as $internetDonor)
                                    <ul class="me-2">
                                        <li class="avatar-initial rounded-circle 
                                            bg-label-success">
                                            {{$internetDonor->donor_name}}
                                        </li>
                                    </ul>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="timeline-event-time">Donors</div>
                </div>
            </li>
            @endif
        </ul>
    </div>
  
    @if(count($photos) > 0)
        <div class="container" style="margin-top:20px">
            <h5>{{$community->english_name}} Photos</h5>
            <div id="carouselExampleIndicators" class="carousel slide" 
                data-bs-ride="carousel">
                <div class="carousel-inner">
                @foreach($photos as $key => $slider)
                    <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                    <img src="{{url('/communities/images/'.$slider->slug)}}" 
                    class="d-block w-100" style="max-height:100vh;">
                    </div>
                @endforeach
                </div>
                <button class="carousel-control-prev" type="button" 
                    data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" 
                    data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    @endif
</div>

@endsection