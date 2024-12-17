@extends('layouts/layoutMaster')

@section('title', 'camera incidents')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">
        @if($cameraIncident->community_id)
            {{$cameraIncident->Community->english_name}} 
        @else @if($cameraIncident->repository_id)
            {{$cameraIncident->Repository->name}} 
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
                        <i class="bx bx-home"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header border-bottom mb-3">
                            <h6 class="mb-0">
                            <span class="text-primary">Cameras Installed in </span>
                            @if($cameraIncident->community_id)
                                {{$cameraIncident->Community->english_name}} 
                            @else @if($cameraIncident->repository_id)
                                {{$cameraIncident->Repository->name}} 
                            @endif
                            @endif 
                            </h6>
                            <h6 class="mb-0">
                            <span class="text-primary">Installation Date : </span>
                                @if($cameraIncident->community_id)
                                    <span class="text-muted">{{$cameraCommunity->date}}</span>
                                @else @if($cameraIncident->repository_id)
                                    <span class="text-muted">{{$cameraRepository->date}}</span>
                                @endif
                                @endif
                            </h6>
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
                                    {{$cameraIncident->date}}
                                </small>
                            </div>
                            <p>
                                {{$cameraIncident->notes}}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Type</p>
                                <span class="text-muted">{{$incident->english_name}}</span>
                            </div>
                            <div>
                                <p class="mb-0">Status</p>
                                <span class="text-muted">{{$cameraStatus->name}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Response Date</p>
                                <span class="text-muted">{{$cameraIncident->response_date}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Order Number</p>
                                <span class="text-muted">{{$cameraIncident->order_number}}</span>
                            </div>
                        </div> <br>
                        @if($incident->english_name != "SWO")
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Monetary Losses</p>
                                <span class="text-muted">{{$cameraIncident->monetary_losses}} ₪</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Equipment Damaged</p>
                                @if(count($cameraIncidentEquipments) > 0)
                                    @foreach($cameraIncidentEquipments as $cameraIncidentEquipment)
                                        <ul>
                                            <li class="text-muted">{{$cameraIncidentEquipment->name}}</li>
                                        </ul>
                                    @endforeach
                                @endif
                            </div>
                        </div> <br>
                        @endif
                        @if($incident->english_name == "SWO")
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Order Date</p>
                                    <span class="text-muted">{{$cameraIncident->order_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Geolocation Lat</p>
                                    <span class="text-muted">{{$cameraIncident->geolocation_lat}}</span>
                                </div>
                            </div> <br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Geolocation Long</p>
                                    <span class="text-muted">{{$cameraIncident->geolocation_long}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Date of hearing</p>
                                    <span class="text-muted">{{$cameraIncident->hearing_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Building permit request Number</p>
                                    <span class="text-muted">{{$cameraIncident->building_permit_request_number}}</span>
                                </div>
                            </div> <br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Building permit request date</p>
                                    <span class="text-muted">{{$cameraIncident->building_permit_request_submission_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Illegal Construction Case Number</p>
                                    <span class="text-muted">{{$cameraIncident->illegal_construction_case_number}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">District Court Case Number</p>
                                    <span class="text-muted">{{$cameraIncident->district_court_case_number}}</span>
                                </div>
                            </div><br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Supreme Court Case Number</p>
                                    <span class="text-muted">{{$cameraIncident->supreme_court_case_number}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Description of structure</p>
                                    <span class="text-muted">{{$cameraIncident->structure_description}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Case Chronology</p>
                                    <span class="text-muted">{{$cameraIncident->case_chronology}}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </li>
          
                <li class="timeline-end-indicator timeline-indicator-success">
                    <i class="bx bx-image"></i>
                </li>
                @if(count($cameraIncidentPhotos) > 0)
                    <div class="container">
                        <h5>Camera Incident Photos</h5>
                        <div id="carouselExampleIndicators" class="carousel slide" 
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                            @foreach($cameraIncidentPhotos as $key => $slider)
                                <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                <img src="{{url('/incidents/camera/'.$slider->slug)}}" 
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

                <br>
                <div class="container">
                    <span class="timeline-indicator timeline-indicator-danger">
                        <button type="button" class="btn btn-info" id="editCameraIncident"
                            data-id="{{$cameraIncident->id}}">
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
    $('#editCameraIncident').on('click', function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        
        url = url +'/'+ 'edit';
        window.open(url, "_self"); 
    });
</script>
@endsection