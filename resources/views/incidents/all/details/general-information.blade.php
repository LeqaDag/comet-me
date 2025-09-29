@if($allEnergyIncident)
    
    @if($allEnergyIncident->energy_system_id)

        <div class="row">
            <div class="col-lg-6">
                <ul class="list-unstyled">
                    <li class="d-flex justify-content-start align-items-center text-success mb-3">
                        <i class="bx bx-calendar bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Installation Year</small>
                            <h6 class="mb-0">{{$allEnergyIncident->EnergySystem->installation_year}}</h6>
                        </div>
                    </li>
                    <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                        <i class="bx bx-bulb bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Rated Solar Power (kW)</small>
                            <h6 class="mb-0">{{$allEnergyIncident->EnergySystem->total_rated_power}}</h6>
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
                            <h6 class="mb-0">{{$allEnergyIncident->EnergySystem->upgrade_year1}}</h6>
                        </div>
                    </li>
                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                        <i class="bx bx-analyse bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Upgrade Year 2</small>
                            <h6 class="mb-0">{{$allEnergyIncident->EnergySystem->upgrade_year2}}</h6>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <ul class="list-group list-group-flush">
            {{-- Affected Households --}}
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0 p-0">
                <div class="d-flex flex-wrap align-items-center">
                    <ul class="list-unstyled users-list d-flex align-items-center avatar-group m-0 my-3 me-2">
                        @if($allEnergyIncident->affectedHouseholds->count() > 0)
                            @foreach($allEnergyIncident->affectedHouseholds as $household)
                                <li data-bs-toggle="tooltip" title="Household ID: {{ $household->id }}" class="avatar avatar-xs pull-up">
                                    <img class="rounded-circle" src="{{ asset('assets/images/user.png') }}" alt="Avatar" />
                                </li>
                            @endforeach
                            <span>
                                <span class="text-primary" style="font-size:19px; font-weight:bold">
                                    {{ $allEnergyIncident->affectedHouseholds->count() }}
                                </span>
                                Affected Households - Energy Holders
                            </span>
                        @else
                            No Affected Households
                        @endif
                    </ul>
                </div>
                @if($allEnergyIncident->affectedHouseholds->count() > 0)
                    <button type="button" class="btn btn-primary btn-sm my-sm-0 my-3" 
                        data-bs-toggle="modal" data-bs-target="#affectedEnergyHouseholdModal">
                        <i class="bx bx-user"></i> View
                    </button>
                    @include('incidents.all.details.energy-household')
                @endif
            </li>
        </ul>


    @elseif($allEnergyIncident->all_energy_meter_id)

        <div class="row">
            <div class="col-lg-6">
                <ul class="list-unstyled">
                    @if($allEnergyIncident->AllEnergyMeter->meter_number) 
                    <li class="d-flex justify-content-start align-items-center text-success mb-3">
                        <i class="bx bx-barcode bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Meter Number</small>
                            <h6 class="mb-0">{{$allEnergyIncident->AllEnergyMeter->meter_number}}</h6>
                        </div>
                    </li>
                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                        <i class="bx bx-calendar bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Installation Date</small>
                            <h6 class="mb-0">{{$allEnergyIncident->AllEnergyMeter->installation_date}}</h6>
                        </div>
                    </li>
                    <li class="d-flex justify-content-start align-items-center text-danger mb-3">
                        <i class="bx bx-analyse bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Meter Case</small>
                            @if($allEnergyIncident->AllEnergyMeter->meter_case_id)
                            <h6 class="mb-0">{{$allEnergyIncident->AllEnergyMeter->MeterCase->meter_case_name_english}}</h6>
                            @endif
                        </div>
                    </li>
                    @else
                    <li class="d-flex justify-content-start align-items-center text-info mb-3">
                        <i class="bx bx-user bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Main Holder</small>
                            <h6 class="mb-0">
                                {{ $allEnergyIncident->AllEnergyMeter->sharedHouseholdLink->mainEnergyMeter->Household->english_name ?? 'N/A' }}
                            </h6>
                        </div>
                    </li>
                    @endif
                </ul>
            </div> 
            <div class="col-lg-6">
                <ul class="list-unstyled">
                    <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                        <i class="bx bx-bulb bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Energy System</small>
                            <h6 class="mb-0">{{$allEnergyIncident->AllEnergyMeter->EnergySystem->name}}</h6>
                        </div>
                    </li>
                    <li class="d-flex justify-content-start align-items-center text-primary">
                        <i class="bx bx-circle bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Installation Type</small>
                            <h6 class="mb-0">{{$allEnergyIncident->AllEnergyMeter->InstallationType->type}}</h6>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    @endif

@elseif($allWaterIncident)

    @if($allWaterIncident->water_system_id)
    
        <ul class="list-group list-group-flush">
            {{-- Affected Households --}}
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0 p-0">
                <div class="d-flex flex-wrap align-items-center">
                    <ul class="list-unstyled users-list d-flex align-items-center avatar-group m-0 my-3 me-2">
                        @if($allWaterIncident->affectedHouseholds->count() > 0)
                            @foreach($allWaterIncident->affectedHouseholds as $household)
                                <li data-bs-toggle="tooltip" title="Household ID: {{ $household->id }}" class="avatar avatar-xs pull-up">
                                    <img class="rounded-circle" src="{{ asset('assets/images/user.png') }}" alt="Avatar" />
                                </li>
                            @endforeach
                            <span>
                                <span class="text-primary" style="font-size:19px; font-weight:bold">
                                    {{ $allWaterIncident->affectedHouseholds->count() }}
                                </span>
                                Affected Households - Water Holders
                            </span>
                        @else
                            No Affected Households
                        @endif
                    </ul>
                </div>
                @if($allWaterIncident->affectedHouseholds->count() > 0)
                    <button type="button" class="btn btn-primary btn-sm my-sm-0 my-3" 
                        data-bs-toggle="modal" data-bs-target="#affectedWaterHouseholdModal">
                        <i class="bx bx-user"></i> View
                    </button>
                    @include('incidents.all.details.water-household')
                @endif
            </li>
        </ul>
    @elseif($allWaterIncident->all_water_holder_id)

        <div class="row">
            <div class="col-lg-6">
                <ul class="list-unstyled">
                    <li class="d-flex justify-content-start align-items-center text-success mb-3">
                        <i class="bx bx-water bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">Main Holder</small>
                            <h6 class="mb-0">{{ $allWaterIncident->AllWaterHolder->is_main }}</h6>
                        </div>
                    </li>
                    <li class="d-flex justify-content-start align-items-center text-primary mb-3">
                        <i class="bx bx-droplet bx-sm me-3"></i>
                        <div class="ps-3 border-start">
                            <small class="text-muted mb-1">System Type</small>
                            <h6 class="mb-0">{{ $allWaterIncident->AllWaterHolder->getSystemType() }}</h6>
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
                            <h6 class="mb-0">{{ $allWaterIncident->AllWaterHolder->getInstallationDate() }}</h6>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    @endif

@elseif($allInternetIncident)

    @if($allInternetIncident->community_id)
    
        <ul class="list-group list-group-flush">
            {{-- Affected Communities --}}
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0 p-0">
                <div class="d-flex flex-wrap align-items-center">
                    <ul class="list-unstyled users-list d-flex align-items-center avatar-group m-0 my-3 me-2">
                        @if($allInternetIncident->affectedAreas->count() > 0)
                            @foreach($allInternetIncident->affectedAreas as $area)
                                <li data-bs-toggle="tooltip" title="{{ $area->AffectedCommunity->english_name ?? 'Unknown' }}" 
                                    class="avatar avatar-xs pull-up">
                                    <img class="rounded-circle" src="{{ asset('assets/img/website.png') }}" alt="Avatar" />
                                </li>
                            @endforeach
                            <span>
                                <span class="text-primary" style="font-size:19px; font-weight:bold">
                                    {{ $allInternetIncident->affectedAreas->count() }}
                                </span>
                                Affected Areas - Communities
                            </span>
                        @else
                            No Affected Areas - Communities
                        @endif
                    </ul>
                </div>
                @if($allInternetIncident->affectedAreas->count() > 0)
                    <button type="button" class="btn btn-primary btn-sm my-sm-0 my-3" 
                        data-bs-toggle="modal" data-bs-target="#affectedCommunitModal">
                        <i class="bx bx-wifi"></i> View
                    </button>
                    @include('incidents.all.details.internet-area')
                @endif
            </li>

            {{-- Affected Households --}}
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap border-top-0 p-0">
                <div class="d-flex flex-wrap align-items-center">
                    <ul class="list-unstyled users-list d-flex align-items-center avatar-group m-0 my-3 me-2">
                        @if($allInternetIncident->affectedHouseholds->count() > 0)
                            @foreach($allInternetIncident->affectedHouseholds as $household)
                                <li data-bs-toggle="tooltip" title="Household ID: {{ $household->id }}" class="avatar avatar-xs pull-up">
                                    <img class="rounded-circle" src="{{ asset('assets/images/user.png') }}" alt="Avatar" />
                                </li>
                            @endforeach
                            <span>
                                <span class="text-primary" style="font-size:19px; font-weight:bold">
                                    {{ $allInternetIncident->affectedHouseholds->count() }}
                                </span>
                                Affected Households - Contract Holders
                            </span>
                        @else
                            No Affected Households
                        @endif
                    </ul>
                </div>
                @if($allInternetIncident->affectedHouseholds->count() > 0)
                    <button type="button" class="btn btn-primary btn-sm my-sm-0 my-3" 
                        data-bs-toggle="modal" data-bs-target="#affectedHouseholdModal">
                        <i class="bx bx-user"></i> View
                    </button>
                    @include('incidents.all.details.internet-household')
                @endif
            </li>
        </ul>
    
    @elseif($allInternetIncident->internet_user_id)
 
        @if($allInternetIncident->InternetUser->household_id)
            {{ optional($allInternetIncident->InternetUser->Household)->english_name }}
        @elseif($allInternetIncident->InternetUser->public_structure_id)
            {{ optional($allInternetIncident->InternetUser->PublicStructure)->english_name }}
        @endif
    @endif

@elseif($allCameraIncident)

    <div class="row">
        <div class="col-lg-6">
            <ul class="list-unstyled">
                <li class="d-flex justify-content-start align-items-center text-success mb-3">
                    <i class="bx bx-user bx-sm me-3"></i>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1"># of People</small>
                        <h6 class="mb-0">
                            @if($allCameraIncident->CameraInstallation->community_id)
                            {{$allCameraIncident->CameraInstallation->Community->number_of_people}}
                            @endif
                        </h6>
                    </div>
                </li>
                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                    <i class="bx bx-group bx-sm me-3"></i>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1"># of Households</small>
                        <h6 class="mb-0">
                            @if($allCameraIncident->CameraInstallation->community_id)
                            {{$allCameraIncident->CameraInstallation->Community->number_of_household}}
                            @endif
                        </h6>
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
                        <h6 class="mb-0">
                           
                            @if($allCameraIncident->CameraInstallation )
                                {{ $allCameraIncident->CameraInstallation->date }}
                            @endif
                        </h6>
                    </div>
                </li>
                <li class="d-flex justify-content-start align-items-center text-warning mb-3">
                    <i class="bx bx-camera bx-sm me-3"></i>
                    <div class="ps-3 border-start">
                        <small class="text-muted mb-1"># of Cameras</small>
                        <h6 class="mb-0">
                            @php $totalCamera = 0; @endphp

                            @if(is_array($allCameraIncident->CameraInstallation->cameraCommunityTypes) || $allCameraIncident->CameraInstallation->cameraCommunityTypes instanceof Countable)

                                @foreach($allCameraIncident->CameraInstallation->cameraCommunityTypes as $cameraCommunityType)
                            
                                    @php
                                        $totalCamera += $cameraCommunityType->number ?? 0;
                                    @endphp
                            
                                @endforeach
                            
                            @endif


                            {{$totalCamera}}
                        </h6>
                    </div>
                </li>
            </ul>
        </div>
    </div>
@endif
