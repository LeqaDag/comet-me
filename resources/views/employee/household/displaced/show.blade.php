@extends('layouts/layoutMaster')

@section('title', 'displaced Household')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">
        {{$displacedHousehold->Household->english_name}} 
    </span> Information 
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
                            {{$displacedHousehold->Household->english_name}} 
                            -  
                                <span class="text-primary">Details</span>
                            </h6>
                            <h6 class="mb-0">
                                <span class="text-primary"></span>
                            </h6>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                        <i class="bx bx-home bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Old Community</small>
                                            <h6 class="mb-0">{{$displacedHousehold->OldCommunity->english_name}}</h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                        <i class="bx bx-barcode bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Old Meter Number</small>
                                            <h6 class="mb-0">{{$displacedHousehold->old_meter_number}}</h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                        <i class="bx bx-bulb bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Old Energy System</small>
                                            <h6 class="mb-0">{{$displacedHousehold->OldEnergySystem->name}}</h6>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                        <i class="bx bx-home bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">New Community</small>
                                            <h6 class="mb-0">
                                                @if($displacedHousehold->new_community_id)
                                                {{$displacedHousehold->NewCommunity->english_name}}
                                                @endif
                                            </h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                        <i class="bx bx-barcode bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">New Meter Number</small>
                                            <h6 class="mb-0">{{$displacedHousehold->new_meter_number}}</h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-success">
                                        <i class="bx bx-bulb bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">New Energy System</small>
                                            <h6 class="mb-0">
                                                @if($displacedHousehold->new_energy_system_id)
                                                {{$displacedHousehold->NewEnergySystem->name}}
                                                @endif
                                            </h6>
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
                                <h6 class="mb-0">Displacement - <span class="text-danger">Details</span></h6>
                                <small class="text-muted">
                                    <span class="text-danger"></span>
                                    
                                </small>
                            </div>
                            <p>
                            {{$displacedHousehold->notes}}
                            </p>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Date of Displacement</p>
                                <span class="text-muted">
                                    {{$displacedHousehold->displacement_date}}
                                </span>
                            </div>
                            <div>
                                <p class="mb-0">Area</p>
                                    {{$displacedHousehold->area}}
                            </div>
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">System Retrieved</p>
                                <span class="text-muted">
                                    {{$displacedHousehold->system_retrieved}}
                                </span>
                            </div>
                        </div> <br>
                        <div class="d-flex justify-content-between flex-wrap flex-sm-row flex-column text-center">
                            <div class="mb-sm-0 mb-2">
                                <p class="mb-0">Shared Households</p>
                                @if(count($sharedHouseholds) > 0)
                                    @foreach($sharedHouseholds as $sharedHousehold)
                                        <ul>
                                            <li class="text-muted">{{$sharedHousehold->english_name}}</li>
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
          
                <br>
                <div class="container">
                    <span class="timeline-indicator timeline-indicator-danger">
                        <button type="button" class="btn btn-info" id="editDisplacedHousehold"
                            data-id="{{$displacedHousehold->id}}">
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
    $('#editDisplacedHousehold').on('click', function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        
        url = url +'/'+ 'edit';
        window.open(url, "_self"); 
    });

</script>


@endsection