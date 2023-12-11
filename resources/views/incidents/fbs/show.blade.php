@extends('layouts/layoutMaster')

@section('title', 'energy user incidents')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">
        @if($energyMeter->household_id)
            {{$energyMeter->Household->english_name}} 
        @else @if($energyMeter->public_structure_id)
            {{$energyMeter->PublicStructure->english_name}} 
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
                            @if($energyMeter->household_id)
                                {{$energyMeter->Household->english_name}} 
                            @else @if($energyMeter->public_structure_id)
                                {{$energyMeter->PublicStructure->english_name}} 
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
                                        <i class="bx bx-barcode bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Meter Number</small>
                                            <h6 class="mb-0">{{$fbsIncident->AllEnergyMeter->meter_number}}</h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                        <i class="bx bx-bulb bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Energy System</small>
                                            <h6 class="mb-0">{{$fbsIncident->AllEnergyMeter->EnergySystem->name}}</h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-primary">
                                        <i class="bx bx-circle bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Installation Type</small>
                                            <h6 class="mb-0">{{$fbsIncident->AllEnergyMeter->InstallationType->type}}</h6>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                        <i class="bx bx-calendar bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Installation Date</small>
                                            <h6 class="mb-0">{{$fbsIncident->AllEnergyMeter->installation_date}}</h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                                        <i class="bx bx-analyse bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Meter Case</small>
                                            <h6 class="mb-0">{{$fbsIncident->AllEnergyMeter->MeterCase->meter_case_name_english}}</h6>
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
                                    {{$fbsIncident->date}}
                                </small>
                            </div>
                            <p>
                                {{$fbsIncident->notes}}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Type</p>
                                <span class="text-muted">{{$incident->english_name}}</span>
                            </div>
                            <div>
                                <p class="mb-0">Status</p>
                                @if(count($fbsStatuses) > 0)
                                    @foreach($fbsStatuses as $fbsStatuse)
                                        <ul>
                                            <li class="text-muted">{{$fbsStatuse->name}}</li>
                                        </ul>
                                    @endforeach
                                @endif
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Response Date</p>
                                <span class="text-muted">{{$fbsIncident->response_date}}</span>
                            </div>
                        </div> <br>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Equipment Damaged</p>
                                @if(count($fbsIncidentEquipments) > 0)
                                    @foreach($fbsIncidentEquipments as $fbsIncidentEquipment)
                                        <ul>
                                            <li class="text-muted">{{$fbsIncidentEquipment->name}}</li>
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
                @if(count($fbsIncidentPhotos) > 0)
                    <div class="container">
                        <h5>FBS Incident Photos</h5>
                        <div id="carouselExampleIndicators" class="carousel slide" 
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                            @foreach($fbsIncidentPhotos as $key => $slider)
                                <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                <img src="{{url('/incidents/energy/'.$slider->slug)}}" 
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
                        <button type="button" class="btn btn-info" id="editFbsIncident"
                            data-id="{{$fbsIncident->id}}">
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
    $('#editFbsIncident').on('click', function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        
        url = url +'/'+ 'edit';
        window.open(url, "_self"); 
    });
</script>
@endsection