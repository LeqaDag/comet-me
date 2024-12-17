@extends('layouts/layoutMaster')

@section('title', 'mg incidents')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">
        {{$energySystem->name}} 
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
                            {{$energySystem->name}}  -  
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
                                        <i class="bx bx-calendar bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Installation Year</small>
                                            <h6 class="mb-0">{{$energySystem->installation_year}}</h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                        <i class="bx bx-bulb bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Rated Solar Power (kW)</small>
                                            <h6 class="mb-0">{{$energySystem->total_rated_power}}</h6>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-start align-items-center text-primary">
                                        <i class="bx bx-circle bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Upgrade Year 1</small>
                                            <h6 class="mb-0">{{$energySystem->upgrade_year1}}</h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                        <i class="bx bx-analyse bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Upgrade Year 2</small>
                                            <h6 class="mb-0">{{$energySystem->upgrade_year2}}</h6>
                                        </div>
                                    </li>
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
                                    {{$mgIncident->date}}
                                </small>
                            </div>
                            <p>
                                {{$mgIncident->notes}}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Type</p>
                                <span class="text-muted">{{$incident->english_name}}</span>
                            </div>
                            <div>
                                <p class="mb-0">Status</p>
                               <span class="text-muted">{{$mgStatus->name}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Response Date</p>
                                <span class="text-muted">{{$mgIncident->response_date}}</span>
                            </div>
                        </div> <br>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Order Number</p>
                                <span class="text-muted">{{$mgIncident->order_number}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Monetary Losses</p>
                                <span class="text-muted">{{$mgIncident->monetary_losses}} ₪</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Equipment Damaged</p>
                                @if(count($mgIncidentEquipments) > 0)
                                    @foreach($mgIncidentEquipments as $mgIncidentEquipment)
                                        <ul>
                                            <li class="text-muted">{{$mgIncidentEquipment->name}}</li>
                                        </ul>
                                    @endforeach
                                @endif
                            </div>
                        </div><br>
                        @if($incident->english_name == "SWO")
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Order Date</p>
                                <span class="text-muted">{{$mgIncident->order_date}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Geolocation Lat</p>
                                <span class="text-muted">{{$mgIncident->geolocation_lat}}</span>
                            </div>
                        </div> <br>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Geolocation Long</p>
                                <span class="text-muted">{{$mgIncident->geolocation_long}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Date of hearing</p>
                                <span class="text-muted">{{$mgIncident->hearing_date}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Building permit request Number</p>
                                <span class="text-muted">{{$mgIncident->building_permit_request_number}}</span>
                            </div>
                        </div> <br>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Building permit request date</p>
                                <span class="text-muted">{{$mgIncident->building_permit_request_submission_date}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Illegal Construction Case Number</p>
                                <span class="text-muted">{{$mgIncident->illegal_construction_case_number}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">District Court Case Number</p>
                                <span class="text-muted">{{$mgIncident->district_court_case_number}}</span>
                            </div>
                        </div><br>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Supreme Court Case Number</p>
                                <span class="text-muted">{{$mgIncident->supreme_court_case_number}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Description of structure</p>
                                <span class="text-muted">{{$mgIncident->structure_description}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Case Chronology</p>
                                <span class="text-muted">{{$mgIncident->case_chronology}}</span>
                            </div>
                        </div>
                        @endif <br>
                        <div class="mb-sm-0 mb-2">
                            <p class="mb-0">Households Affected</p>
                            @if(count($mgAffectedHouseholds) > 0)
                                @foreach($mgAffectedHouseholds as $mgAffectedHousehold)
                                    <ul>
                                        <li class="text-muted">{{$mgAffectedHousehold->english_name}}</li>
                                    </ul>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </li>
          
                <li class="timeline-end-indicator timeline-indicator-success">
                    <i class="bx bx-image"></i>
                </li>
                @if(count($mgIncidentPhotos) > 0)
                    <div class="container">
                        <h5>MG Incident Photos</h5>
                        <div id="carouselMgIndicators" class="carousel slide" 
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                            @foreach($mgIncidentPhotos as $key => $slider)
                                <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                <img src="{{url('/incidents/mg/'.$slider->slug)}}" 
                                class="d-block w-100" style="max-height:100vh;">
                                </div>
                            @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" 
                                data-bs-target="#carouselMgIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" 
                                data-bs-target="#carouselMgIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                @endif
                <br>

                <div class="container">
                    <span class="timeline-indicator timeline-indicator-danger">
                        <button type="button" class="btn btn-info" id="editMgIncident"
                            data-id="{{$mgIncident->id}}">
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
    $('#editMgIncident').on('click', function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        
        url = url +'/'+ 'edit';
        window.open(url, "_self"); 
    });

</script>
@endsection