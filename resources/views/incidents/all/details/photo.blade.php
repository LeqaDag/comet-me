@if($allEnergyIncident && count($allEnergyIncident->photos) > 0)
    <div class="container my-4">
        <h5>Energy Incident Photos</h5>
        <div id="carouselEnergyIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($allEnergyIncident->photos as $key => $slider)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        @php
                            $path = $allEnergyIncident->EnergySystem 
                                    ? url('/incidents/mg/'.$slider->slug) 
                                    : url('/incidents/energy/'.$slider->slug);
                        @endphp
                        <img src="{{ $path }}" class="d-block w-100 img-fluid rounded" style="max-height:500px; object-fit:cover;">
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselEnergyIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselEnergyIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
@endif

@if($allWaterIncident && count($allWaterIncident->photos) > 0)
    <div class="container my-4">
        <h5>Water Incident Photos</h5>
        <div id="carouselWaterIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($allWaterIncident->photos as $key => $slider)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ url('/incidents/water/'.$slider->slug) }}" class="d-block w-100 img-fluid rounded" style="max-height:500px; object-fit:cover;">
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselWaterIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselWaterIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
@endif

@if($allInternetIncident && count($allInternetIncident->photos) > 0)
    <div class="container my-4">
        <h5>Internet Incident Photos</h5>
        <div id="carouselInternetIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($allInternetIncident->photos as $key => $slider)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ url('/incidents/internet/'.$slider->slug) }}" class="d-block w-100 img-fluid rounded" style="max-height:500px; object-fit:cover;">
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselInternetIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselInternetIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
@endif

@if($allCameraIncident && count($allCameraIncident->photos) > 0)
    <div class="container my-4">
        <h5>Camera Incident Photos</h5>
        <div id="carouselCameraIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($allCameraIncident->photos as $key => $slider)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                        <img src="{{ url('/incidents/camera/'.$slider->slug) }}" class="d-block w-100 img-fluid rounded" style="max-height:500px; object-fit:cover;">
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCameraIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselCameraIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
@endif
