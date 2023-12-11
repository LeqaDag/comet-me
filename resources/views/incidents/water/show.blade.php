@extends('layouts/layoutMaster')

@section('title', 'water holder incidents')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">
        @if($waterHolder->household_id)
            {{$waterHolder->Household->english_name}} 
        @else @if($waterHolder->public_structure_id)
            {{$waterHolder->PublicStructure->english_name}} 
        @endif
    @endif
    </span> Incident Information 
</h4>

<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <ul class="timeline timeline-dashed mt-4">
                <li class="timeline-item timeline-item-primary mb-4">
                    <span class="timeline-indicator timeline-indicator-primary">
                        <i class="bx bx-user"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header border-bottom mb-3">
                            <h6 class="mb-0">
                            @if($waterHolder->household_id)
                                {{$waterHolder->Household->english_name}} 
                            @else @if($waterHolder->public_structure_id)
                                {{$waterHolder->PublicStructure->english_name}} 
                            @endif
                            @endif -  
                                <span class="text-primary">Details</span>
                            </h6>
                            <h6 class="mb-0">
                                Community :  
                                <span class="text-primary">{{$community->english_name}}</span>
                            </h6>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                        <i class="bx bx-water bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Main Holder</small>
                                            <h6 class="mb-0">{{$waterHolder->is_main}}</h6>
                                        </div>
                                    </li>
                                    @if(count($h2oUser) > 0 || count($gridUser) > 0)
                                    <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                        <i class="bx bx-droplet bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">System Type</small>
                                            <h6 class="mb-0">
                                            @if(count($h2oUser) > 0)
                                                H2O /
                                            @if(count($gridUser) > 0)
                                                Grid Integration
                                            @endif
                                            @endif
                                            </h6>
                                        </div>
                                    </li>
                                    @endif
                                    @if(count($h2oPublic) > 0 || count($gridPublic) > 0 )
                                    <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                                        <i class="bx bx-droplet bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">System Type</small>
                                            <h6 class="mb-0">
                                            @if(count($h2oPublic) > 0)
                                                H2O /
                                            @if(count($gridPublic) > 0)
                                                Grid Integration
                                            @endif
                                            @endif
                                            </h6>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                @if(count($h2oUser) > 0 || count($gridUser) > 0)
                                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                        <i class="bx bx-calendar bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Installation Date</small>
                                            <h6 class="mb-0">
                                            @if(count($h2oUser) > 0)
                                                {{$h2oUser[0]->h2o_installation_date}}
                                            @if(count($gridUser) > 0)
                                               
                                            @endif
                                            @endif
                                            </h6>
                                        </div>
                                    </li>
                                    @endif
                                    @if(count($h2oPublic) > 0 || count($gridPublic) > 0 )
                                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                        <i class="bx bx-calendar bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Installation Date</small>
                                            <h6 class="mb-0">
                                            @if(count($h2oPublic) > 0)
                                                {{$h2oPublic[0]->h2o_installation_date}}
                                            @if(count($gridPublic) > 0)
                                              
                                            @endif
                                            @endif
                                            </h6>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
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
                                    {{$waterIncident->date}}
                                </small>
                            </div>
                            <p>
                                {{$waterIncident->notes}}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Type</p>
                                <span class="text-muted">{{$incident->english_name}}</span>
                            </div>
                            <div>
                                <p class="mb-0">Status</p>
                                @if(count($waterStatuses) > 0)
                                    @foreach($waterStatuses as $waterStatus)
                                        <ul>
                                            <li class="text-muted">{{$waterStatus->name}}</li>
                                        </ul>
                                    @endforeach
                                @endif
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Response Date</p>
                                <span class="text-muted">{{$waterIncident->response_date}}</span>
                            </div>
                        </div> <br>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Equipment Damaged</p>
                                @if(count($waterIncidentEquipments) > 0)
                                    @foreach($waterIncidentEquipments as $waterIncidentEquipment)
                                        <ul>
                                            <li class="text-muted">{{$waterIncidentEquipment->name}}</li>
                                        </ul>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
          
                <li class="timeline-end-indicator timeline-indicator-success">
                    <i class="bx bx-image"></i>
                </li>
                @if(count($waterIncidentPhotos) > 0)
                    <div class="container">
                        <h5>FBS Incident Photos</h5>
                        <div id="carouselWaterIndicators" class="carousel slide" 
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                            @foreach($waterIncidentPhotos as $key => $slider)
                                <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                <img src="{{url('/incidents/water/'.$slider->slug)}}" 
                                class="d-block w-100" style="max-height:100vh;">
                                </div>
                            @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" 
                                data-bs-target="#carouselWaterIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" 
                                data-bs-target="#carouselWaterIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                @endif
                <br>
                <div class="container">
                    <span class="timeline-indicator timeline-indicator-danger">
                        <button type="button" class="btn btn-info" id="editWaterIncident"
                            data-id="{{$waterIncident->id}}">
                            Go to Edit!
                        </button>
                    </span>
                </div>
            </ul>
        </div>
    </div>
</div>

<script>

    // View record edit
    $('#editWaterIncident').on('click', function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        
        url = url +'/'+ 'edit';
        window.open(url, "_self"); 
    });

</script>
@endsection