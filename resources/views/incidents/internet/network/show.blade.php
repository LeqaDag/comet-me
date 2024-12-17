@extends('layouts/layoutMaster')

@section('title', 'internet network incidents')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">{{$networkIncident->Community->english_name}} </span> 
    Network Incident Information 
</h4>

@include('incidents.internet.network.area')
@include('incidents.internet.network.household')

<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <ul class="timeline timeline-dashed mt-4">
                <li class="timeline-item timeline-item-primary mb-4">
                    <span class="timeline-indicator timeline-indicator-primary">
                        <i class="bx bx-wifi"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header">
                            <h6 class="mb-0">{{$networkIncident->Community->english_name}}</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0 p-0">
                                <div class="d-flex flex-wrap align-items-center">
                                    <ul class="list-unstyled users-list d-flex align-items-center avatar-group m-0 my-3 me-2">
                                    @if(count($affectedAreas)> 0)
                                        @foreach($affectedAreas as $affectedArea)
                                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Vinnie Mostowy" class="avatar avatar-xs pull-up">
                                            <img class="rounded-circle"
                                             src="{{asset('assets/img/website.png')}}" alt="Avatar" />
                                        </li>
                                        @endforeach
                                        <span>
                                            <span class="text-primary" style="font-size:19px; 
                                                font-weight:bold">
                                                {{count($affectedAreas)}}
                                            </span>
                                            Affected Areas - Communities
                                        </span>
                                    @else
                                        No Affected Areas - Communities
                                    @endif
                                    </ul>
                                </div>
                                @if(count($affectedAreas)> 0)
                                <button type="button" class="btn btn-primary btn-sm my-sm-0 my-3" 
                                    data-bs-toggle="modal" data-bs-target="#affectedCommunitModal">
                                    <i class="bx bx-wifi"></i> View
                                </button>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0 p-0">
                                <div class="d-flex flex-wrap align-items-center">
                                    <ul class="list-unstyled users-list d-flex align-items-center avatar-group m-0 my-3 me-2">
                                    @if(count($affectedHouseholds)> 0)
                                        @foreach($affectedHouseholds as $affectedHousehold)
                                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Vinnie Mostowy" class="avatar avatar-xs pull-up">
                                            <img class="rounded-circle"
                                             src="{{asset('assets/images/user.png')}}" alt="Avatar" />
                                        </li>
                                        @endforeach
                                        <span>
                                            <span class="text-primary" style="font-size:19px; 
                                                font-weight:bold">
                                                {{count($affectedHouseholds)}}
                                            </span>
                                            Affected Households - Contract Holders
                                        </span>
                                    @else
                                        No Affected Households
                                    @endif
                                    </ul>
                                </div>
                                @if(count($affectedHouseholds)> 0)
                                <button type="button" class="btn btn-primary btn-sm my-sm-0 my-3" 
                                    data-bs-toggle="modal" data-bs-target="#affectedHouseholdModal">
                                    <i class="bx bx-user"></i> View
                                </button>
                                @endif
                            </li>
                        </ul>
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
                                    {{$networkIncident->date}}
                                </small>
                            </div>
                            <p>
                                <span style="font-weight:bold">
                                    Nature of the Incident:
                                </span>
                                {{$networkIncident->notes}}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Type</p>
                                <span class="text-muted">{{$incident->english_name}}</span>
                            </div>
                            <div>
                                <p class="mb-0">Status</p>
                                <span class="text-muted">{{$internetStatus->name}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Response Date</p>
                                <span class="text-muted">{{$networkIncident->response_date}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Order Number</p>
                                <span class="text-muted">{{$networkIncident->order_number}}</span>
                            </div>
                        </div> <br>
                        @if($incident->english_name != "SWO")
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Monetary Losses</p>
                                <span class="text-muted">{{$networkIncident->monetary_losses}} ₪</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Equipment Damaged</p>
                                @if(count($networkIncidentEquipments) > 0)
                                    @foreach($networkIncidentEquipments as $networkIncidentEquipment)
                                        <ul>
                                            <li class="text-muted">{{$networkIncidentEquipment->IncidentEquipment->name}}</li>
                                        </ul>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @endif
                        <br>
                        @if($incident->english_name == "SWO")
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Order Date</p>
                                    <span class="text-muted">{{$networkIncident->order_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Geolocation Lat</p>
                                    <span class="text-muted">{{$networkIncident->geolocation_lat}}</span>
                                </div>
                            </div> <br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Geolocation Long</p>
                                    <span class="text-muted">{{$networkIncident->geolocation_long}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Date of hearing</p>
                                    <span class="text-muted">{{$networkIncident->hearing_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Building permit request Number</p>
                                    <span class="text-muted">{{$networkIncident->building_permit_request_number}}</span>
                                </div>
                            </div> <br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Building permit request date</p>
                                    <span class="text-muted">{{$networkIncident->building_permit_request_submission_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Illegal Construction Case Number</p>
                                    <span class="text-muted">{{$networkIncident->illegal_construction_case_number}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">District Court Case Number</p>
                                    <span class="text-muted">{{$networkIncident->district_court_case_number}}</span>
                                </div>
                            </div><br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Supreme Court Case Number</p>
                                    <span class="text-muted">{{$networkIncident->supreme_court_case_number}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Description of structure</p>
                                    <span class="text-muted">{{$networkIncident->structure_description}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Case Chronology</p>
                                    <span class="text-muted">{{$networkIncident->case_chronology}}</span>
                                </div>
                            </div>
                        @endif
                        <br>
                        <p style="margin-top:15px">
                            <span style="font-weight:bold;">
                                Next Step:
                            </span>
                            {{$networkIncident->next_step}}
                        </p>
                    </div>
                </li>
          
                <li class="timeline-end-indicator timeline-indicator-success">
                    <i class="bx bx-image"></i>
                </li>
                @if(count($networkIncidentPhotos) > 0)
                    <div class="container">
                        <h5>Network Incident Photos</h5>
                        <div id="carouselNetworkIndicators" class="carousel slide" 
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                            @foreach($networkIncidentPhotos as $key => $slider)
                                <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                <img src="{{url('/incidents/internet/'.$slider->slug)}}" 
                                class="d-block w-100" style="max-height:100vh;">
                                </div>
                            @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" 
                                data-bs-target="#carouselNetworkIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" 
                                data-bs-target="#carouselNetworkIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                @endif

                <br>
                <div class="container">
                    <span class="timeline-indicator timeline-indicator-danger">
                        <button type="button" class="btn btn-info" id="editNetworkIncident"
                            data-id="{{$networkIncident->id}}">
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
    $('#editNetworkIncident').on('click', function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        
        url = url +'/'+ 'edit';
        window.open(url, "_self"); 
    });
</script>
@endsection