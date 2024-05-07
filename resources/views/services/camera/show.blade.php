@extends('layouts/layoutMaster')

@section('title', 'community camera')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
    <span class="text-muted fw-light">
        @if($cameraCommunity->community_id)

            {{$cameraCommunity->Community->english_name}} 
        @else @if($cameraCommunity->repository_id)

            {{$cameraCommunity->Repository->name}} 
        @endif
        @endif
    </span> Information 
</h4>

<div class="col-xl-12">
    <div class="card">
        <div class="card-body">
            <ul class="timeline timeline-dashed mt-4">
            @if($cameraCommunity->community_id)
                <li class="timeline-item timeline-item-primary mb-4">
                    <span class="timeline-indicator timeline-indicator-primary">
                        <i class="bx bx-home"></i>
                    </span>
                    <div class="timeline-event">
                        <div class="timeline-header border-bottom mb-3">
                            <h6 class="mb-0">
                                {{$cameraCommunity->Community->english_name}} -  
                                <span class="text-primary">Details</span>
                            </h6>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-start align-items-center text-success mb-3">
                                        <i class="bx bx-user bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1"># of People</small>
                                            <h6 class="mb-0">
                                                @if($cameraCommunity->community_id)
                                                {{$cameraCommunity->Community->number_of_people}}
                                                @endif
                                            </h6>
                                        </div>
                                    </li>
                                    <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                                        <i class="bx bx-group bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1"># of Households</small>
                                            <h6 class="mb-0">
                                                @if($cameraCommunity->community_id)
                                                {{$cameraCommunity->Community->number_of_household}}
                                                @endif
                                            </h6>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                                        <i class="bx bx-male bx-sm me-3"></i>
                                        <div class="ps-3 border-start">
                                            <small class="text-muted mb-1">Responsible Household</small>
                                            <h6 class="mb-0">
                                                @if($cameraCommunity->household_id)
                                                {{$cameraCommunity->Household->english_name}}
                                                @endif
                                            </h6>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            @endif 
                <li class="timeline-item timeline-item-info mb-4">
                    <span class="timeline-indicator timeline-indicator-info">
                        <i class="bx bx-error"></i>
                    </span>
                    <div class="timeline-event">
                        <div>
                            <div class="timeline-header border-bottom mb-3">
                                <h6 class="mb-0">Technical - <span class="text-info">Details</span></h6>
                                <small class="text-muted">
                                    <span class="text-info">Date of Installation:</span>
                                    {{$cameraCommunity->date}}
                                </small>
                            </div>
                            <p>
                                {{$cameraCommunity->notes}}
                            </p>
                        </div>
                        <div class="ps-3 border-start">
                            <i class="text-info bx bx-camera"></i>
                            <small class="text-dark mb-1">Camera Types</small>
                            @if(count($cameraCommunityTypes)>0)
                                @foreach($cameraCommunityTypes as $cameraCommunityType)
                                <ul>
                                    <li>
                                        <span class="text-muted" style="font-size:12px">
                                            {{$cameraCommunityType->Camera->model}}
                                        </span> / 
                                        <span class="text-info" style="font-size:12px">
                                            {{$cameraCommunityType->number}}
                                        </span>
                                    </li>
                                </ul>
                                @endforeach
                            @endif
                        </div> <hr>
                        
                        <div class="ps-3 border-start">
                            <i class="text-info bx bx-barcode"></i>
                            <small class="text-dark mb-1">NVR Types</small>
                            @if(count($nvrCommunityTypes)>0)
                                @foreach($nvrCommunityTypes as $nvrCommunityType)
                                <ul>
                                    <li>
                                        <span class="text-muted" style="font-size:12px">
                                            {{$nvrCommunityType->NvrCamera->model}}
                                        </span> / 
                                        <span class="text-info" style="font-size:12px">
                                            {{$nvrCommunityType->number}}
                                        </span> (
                                        <span class="text-info" style="font-size:12px">
                                            {{$nvrCommunityType->ip_address}}
                                        </span> )
                                    </li>
                                </ul>
                                @endforeach
                            @endif
                        </div>

                    </div>
                </li>
          
                <li class="timeline-end-indicator timeline-indicator-success">
                    <i class="bx bx-image"></i>
                </li>
                @if(count($cameraPhotos) > 0)
                    <div class="container">
                        <h5>Community-Camera Photos</h5>
                        <div id="carouselExampleIndicators" class="carousel slide" 
                            data-bs-ride="carousel">
                            <div class="carousel-inner">
                            @foreach($cameraPhotos as $key => $slider)
                                <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                <img src="{{url('/cameras/community/'.$slider->slug)}}" 
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
                        <button type="button" class="btn btn-info" id="editCameraCommunity"
                            data-id="{{$cameraCommunity->id}}">
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
    $('#editCameraCommunity').on('click', function() {
        var id = $(this).data('id');
        var url = window.location.href; 
        
        url = url +'/'+ 'edit';
        window.open(url, "_self"); 
    });
</script>
@endsection