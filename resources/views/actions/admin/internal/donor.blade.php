<!-- Action Items for adding missing donors for the community and users -->
<li class="timeline-item mb-md-4 mb-5 timeline-item-left">
    <span class="timeline-indicator timeline-indicator-info" data-aos="zoom-in" data-aos-delay="200">
    <i class="bx bx-shekel"></i>
    </span>

    <div class="timeline-event card p-0" data-aos="fade-right">
        <div class="card-body">
            <div class="d-flex flex-wrap mb-4">
                <h5 class="card-title mb-0">
                    <a data-toggle="collapse" class="text-warning" 
                        href="#EnergyDonorsTab" 
                        aria-expanded="false" 
                        aria-controls="EnergyDonorsTab">
                        Energy Donors 
                    </a>
                </h5> 
            </div>
            
            <div class="collapse multi-collapse container mb-4" 
                id="EnergyDonorsTab">

                @if(count($missingCommunityDonors) > 0)
                    <div class="p0">
                        <h6 class="card-title mb-0">
                            <span class="align-middle">
                                <span class="badge rounded-pill bg-label-warning">
                                    For Communities
                                </span>
                            </span>
                        </h6>
                    </div>
                    <div class="mb-4">
                        <p>Add the 
                            <a class="btn btn-warning btn-sm" type="button" 
                                href="/donor" target="_blank">
                                <span> Donors </span> 
                            </a>
                            for these communities: 
                        </p>
                        <ul class="list-group list-group-numbered">
                        @foreach($missingCommunityDonors as $missingCommunityDonor)
                            <li class="d-flex list-group-item list-group-item-warning"
                                style="margin-top:9px">
                                <?php
                                    $missingEnergyUserDonors = DB::table('all_energy_meters')
                                    ->join('households', 'all_energy_meters.household_id', 
                                        'households.id')
                                    ->leftJoin('all_energy_meter_donors', function ($join) {
                                        $join->on('all_energy_meters.id', 
                                            'all_energy_meter_donors.community_id')
                                            ->where('all_energy_meter_donors.is_archived', 0);
                                    })
                                    ->whereNull('all_energy_meter_donors.all_energy_meter_id')
                                    ->where('all_energy_meters.community_id', 
                                        $missingCommunityDonor->id)
                                    ->select('households.english_name', 
                                        'households.id')
                                    ->get();
                                ?>
                                <span> 
                                    <a type="button" class="viewMissingEnergyUserDonors"
                                        data-id="{{$missingCommunityDonor->id}}">
                                        {{$missingCommunityDonor->english_name}} 
                                        / {{$missingEnergyUserDonors->count()}}
                                    </a>
                                </span>   
                                @include('actions.admin.missing_donor_household')
                            </li>
                        @endforeach
                        </ul>
                    </div>
                @else
                    <p>Nothing to do!</p>
                @endif
                    
                
                @if(count($missingUserEnergDonors) > 0)
                    <div>
                        <h6 class="card-title mb-0">
                            <span class="align-middle">
                                <span class="badge rounded-pill bg-label-warning">
                                    For Holders
                                </span>
                            </span>
                        </h6>
                    </div>
                    <div class="mb-4">
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#missingUserEnergDonors">
                            You've got 
                            <span class="text-warning">
                                {{$missingUserEnergDonors->count()}} Energy Users
                            </span>   
                            that missing the donor, check them and fill them out!
                        </a>
                        @include('actions.admin.donors.energy_user')
                    </div>
                @endif

                @if(count($missingEnergyPublicDonors) > 0)
                    <div>
                        <h6 class="card-title mb-0">
                            <span class="align-middle">
                                <span class="badge rounded-pill bg-label-warning">
                                    For Public Structures
                                </span>
                            </span>
                        </h6>
                    </div>
                    <div class="mb-4">
                        <a type="button" data-bs-toggle="modal"  
                            data-bs-target="#missingEnergyPublicDonors">
                            You've got 
                            <span class="text-warning">
                                {{$missingEnergyPublicDonors->count()}} Public Structures
                            </span>   
                            that missing the donor, check them and fill them out!
                        </a>
                        @include('actions.admin.donors.energy_public')
                    </div>
                @endif
            </div>

            <hr>
            <div class="d-flex flex-wrap mb-4">
                <h5 class="card-title mb-0">
                    <a data-toggle="collapse" class="text-info" 
                        href="#WaterDonorsTab" 
                        aria-expanded="false" 
                        aria-controls="WaterDonorsTab">
                        Water Donors 
                    </a>
                </h5>
            </div>
            
            <div class="collapse multi-collapse container mb-4" 
                id="WaterDonorsTab">
                @if(count($missingUserWaterDonors) > 0)
                    <div>
                        <h6 class="card-title mb-0">
                            <span class="align-middle">
                                <span class="badge rounded-pill bg-label-info">
                                    For Holders
                                </span>
                            </span>
                        </h6>
                    </div>
                    <div class="mb-4">
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#missingUserWaterDonors">
                            You've got 
                            <span class="text-info">
                                {{$missingUserWaterDonors->count()}} Water Users
                            </span>   
                            that missing the donor, check them and fill them out!
                        </a>
                        @include('actions.admin.donors.water_user')
                    </div>
                @endif
                @if(count($missingPublicWaterDonors) > 0)
                    <div>
                        <h6 class="card-title mb-0">
                            <span class="align-middle">
                                <span class="badge rounded-pill bg-label-info">
                                    For Public Structures
                                </span>
                            </span>
                        </h6>
                    </div>
                    <a type="button" data-bs-toggle="modal" 
                        data-bs-target="#missingPublicWaterDonors">
                        You've got 
                        <span class="text-info">
                            {{$missingPublicWaterDonors->count()}} Public Structures
                        </span>   
                        that missing the donor, check them and fill them out!
                    </a>
                    @include('actions.admin.donors.water_public')
                @endif
            </div>

            <hr>
            <div class="d-flex flex-wrap mb-4">
                <h5 class="card-title mb-0">
                    <span class="align-middle">
                    <a data-toggle="collapse" class="text-success" 
                        href="#InternetDonorsTab" 
                        aria-expanded="false" 
                        aria-controls="InternetDonorsTab">
                        Internet Donors 
                    </a>
                </h5>
            </div>
            
            <div class="collapse multi-collapse container mb-4" 
                id="InternetDonorsTab">
                @if(count($missingUserInternetDonors) > 0)
                    <div>
                        <h6 class="card-title mb-0">
                            <span class="align-middle">
                                <span class="badge rounded-pill bg-label-success">
                                    For Holders
                                </span>
                            </span>
                        </h6>
                    </div>
                    <div class="mb-4">
                        <a type="button" data-bs-toggle="modal" 
                            data-bs-target="#missingUserInternetDonors">
                            You've got 
                            <span class="text-success">
                                {{$missingUserInternetDonors->count()}} Internet Users
                            </span>   
                            that missing the donor, check them and fill them out!
                        </a>
                        @include('actions.admin.donors.internet_user')
                    </div>
                @endif

                @if(count($missingPublicInternetDonors) > 0)
                    <div>
                        <h6 class="card-title mb-0">
                            <span class="align-middle">
                                <span class="badge rounded-pill bg-label-success">
                                    For Public Structures
                                </span>
                            </span>
                        </h6>
                    </div>
                    <a type="button" data-bs-toggle="modal" 
                        data-bs-target="#missingPublicInternetDonors">
                        You've got 
                        <span class="text-success">
                            {{$missingPublicInternetDonors->count()}} Public Structures
                        </span>   
                        that missing the donor, check them and fill them out!
                    </a>
                    @include('actions.admin.donors.internet_public')
                @endif
            </div>
        </div>
        <div class="timeline-event-time">Donors</div>
    </div>
</li>