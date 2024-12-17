@extends('layouts/layoutMaster')

@section('title', 'internet user incidents')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">

    @if($internetUser->household_id)
        {{$internetUser->Household->english_name}} 
    @else @if($internetUser->public_structure_id)
        {{$internetUser->PublicStructure->english_name}} 
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
                            @if($internetUser->household_id)
                                {{$internetUser->Household->english_name}} 
                            @else @if($internetUser->public_structure_id)
                                {{$internetUser->PublicStructure->english_name}} 
                            @endif
                            @endif
                                 -  
                                <span class="text-primary">Details</span></h6>
                            <small class="text-muted"></small>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap mb-2">
                            <div>
                                <span>Community</span>
                                <i class="bx bx-right-arrow-alt scaleX-n1-rtl mx-3"></i>
                                <span>{{$community->english_name}}</span>
                            </div>
                            <div>
                                <span>Contract Date</span>
                                <i class="bx bx-right-arrow-alt scaleX-n1-rtl mx-3"></i>
                                <span>{{$internetUser->start_date}}</span>
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
                                    {{$internetIncident->date}}
                                </small>
                            </div>
                            <p>
                                {{$internetIncident->notes}}
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
                                <span class="text-muted">{{$internetIncident->response_date}}</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Order Number</p>
                                <span class="text-muted">{{$internetIncident->order_number}}</span>
                            </div>
                        </div> <br>
                        @if($incident->english_name != "SWO")
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Monetary Losses</p>
                                <span class="text-muted">{{$internetIncident->monetary_losses}} ₪</span>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Equipment Damaged</p>
                                @if(count($internetIncidentEquipments) > 0)
                                    @foreach($internetIncidentEquipments as $internetIncidentEquipment)
                                        <ul>
                                            <li class="text-muted">{{$internetIncidentEquipment->name}}</li>
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
                                    <span class="text-muted">{{$internetIncident->order_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Geolocation Lat</p>
                                    <span class="text-muted">{{$internetIncident->geolocation_lat}}</span>
                                </div>
                            </div> <br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Geolocation Long</p>
                                    <span class="text-muted">{{$internetIncident->geolocation_long}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Date of hearing</p>
                                    <span class="text-muted">{{$internetIncident->hearing_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Building permit request Number</p>
                                    <span class="text-muted">{{$internetIncident->building_permit_request_number}}</span>
                                </div>
                            </div> <br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Building permit request date</p>
                                    <span class="text-muted">{{$internetIncident->building_permit_request_submission_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Illegal Construction Case Number</p>
                                    <span class="text-muted">{{$internetIncident->illegal_construction_case_number}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">District Court Case Number</p>
                                    <span class="text-muted">{{$internetIncident->district_court_case_number}}</span>
                                </div>
                            </div><br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Supreme Court Case Number</p>
                                    <span class="text-muted">{{$internetIncident->supreme_court_case_number}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Description of structure</p>
                                    <span class="text-muted">{{$internetIncident->structure_description}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Case Chronology</p>
                                    <span class="text-muted">{{$internetIncident->case_chronology}}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </li>
          
                <li class="timeline-end-indicator timeline-indicator-success">
                    <i class="bx bx-image"></i>
                </li>
                @if(count($internetIncidentPhotos) > 0)
                    <div class="container">
                        <h5>Network Incident Photos</h5>
                        <div id="carouselInternetIndicators" class="carousel slide" 
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                            @foreach($internetIncidentPhotos as $key => $slider)
                                <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                <img src="{{url('/incidents/internet/'.$slider->slug)}}" 
                                class="d-block w-100" style="max-height:100vh;">
                                </div>
                            @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" 
                                data-bs-target="#carouselInternetIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" 
                                data-bs-target="#carouselInternetIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                @endif
<br>
                <div class="container">
                    <span class="timeline-indicator timeline-indicator-danger">
                        <button type="button" class="btn btn-info" id="editInternetUserIncident"
                            data-id="{{$internetUser->id}}">
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
    $('#editInternetUserIncident').on('click', function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        
        url = url +'/'+ 'edit';
        window.open(url, "_self"); 
    });
</script>
@endsection