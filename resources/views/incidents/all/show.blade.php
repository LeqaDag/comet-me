@extends('layouts/layoutMaster')

@section('title', 'view incidents')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">
    @include('incidents.all.details.incident-holder')
    </span> Incident Information 
</h4>

<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <ul class="timeline timeline-dashed mt-4">
                <li class="timeline-item timeline-item-primary mb-4">
                    <span class="timeline-indicator timeline-indicator-primary">
                        @include('incidents.all.details.icon')
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header border-bottom mb-3">
                            <h6 class="mb-0">
                                @include('incidents.all.details.incident-holder')
                                <span class="text-primary">Details</span>
                            </h6>
                            <h6 class="mb-0">
                                Community :  
                                <span class="text-primary">{{$allIncident->Community->english_name}}</span>
                            </h6>
                        </div>
                        @include('incidents.all.details.general-information')
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
                                    {{$allIncident->date}}
                                </small>
                            </div>
                            <p>
                                {{$allIncident->notes}}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Type</p>
                                <span class="text-muted">{{$allIncident->Incident->english_name}}</span>
                            </div>
                            <div>
                                <p class="mb-0">Statuses</p>
                                <ul>
                                    @if(count($allIncident->incidentStatuses) > 0)
                                        @foreach($allIncident->incidentStatuses as $incidentStatus)
                                        <li class="text-muted">
                                                <span class="text-muted">
                                                        {{$incidentStatus->AllIncidentStatus->status}}
                                                    </span>
                                        </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Response Date</p>
                                <span class="text-muted">{{$allIncident->response_date}}</span>
                            </div>
                        </div> <br>
                        @include('incidents.all.details.equipment-damaged')

                        <br>
                        @if($allIncident->Incident->english_name == "SWO")
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Order Number</p>
                                    <span class="text-muted">{{$allIncident->order_number}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Order Date</p>
                                    <span class="text-muted">{{$allIncident->order_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Geolocation Lat</p>
                                    <span class="text-muted">{{$allIncident->geolocation_lat}}</span>
                                </div>
                            </div> <br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Geolocation Long</p>
                                    <span class="text-muted">{{$allIncident->geolocation_long}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Date of hearing</p>
                                    <span class="text-muted">{{$allIncident->hearing_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Building permit request Number</p>
                                    <span class="text-muted">{{$allIncident->building_permit_request_number}}</span>
                                </div>
                            </div> <br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Building permit request date</p>
                                    <span class="text-muted">{{$allIncident->building_permit_request_submission_date}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Illegal Construction Case Number</p>
                                    <span class="text-muted">{{$allIncident->illegal_construction_case_number}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">District Court Case Number</p>
                                    <span class="text-muted">{{$allIncident->district_court_case_number}}</span>
                                </div>
                            </div><br>
                            <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Supreme Court Case Number</p>
                                    <span class="text-muted">{{$allIncident->supreme_court_case_number}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Description of structure</p>
                                    <span class="text-muted">{{$allIncident->structure_description}}</span>
                                </div>
                                <div class="mb-sm-0 mb-2">
                                    <p class="mb-0">Case Chronology</p>
                                    <span class="text-muted">{{$allIncident->case_chronology}}</span>
                                </div>
                            </div>
                        @endif <br>
                      
                    </div>
                </li>
          
                
                @include('incidents.all.details.photo')
                
            
                <br>

                <div class="container">
                    <span class="timeline-indicator timeline-indicator-danger">
                        <button type="button" class="btn btn-info" id="editMgIncident"
                            data-id="{{$allIncident->id}}">
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