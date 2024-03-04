<!-- Action Items for AC-->
<li class="timeline-item mb-md-4 mb-5 timeline-item-left">
    <span class="timeline-indicator timeline-indicator-warning" data-aos="zoom-in" data-aos-delay="200">
        <i class="bx bx-bulb"></i>
    </span>
    <div class="timeline-event card p-0" data-aos="fade-right">
        <div class="card-header border-0 d-flex justify-content-between">
            <h5 class="card-title mb-0">New Community/Compound</h5>
        </div>
        <div class="card-body pb-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-warning" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-alarm-exclamation"></i>
                        <a data-toggle="collapse" class="text-warning" 
                            href="#notYetStartedACSurveyTab" 
                            aria-expanded="false" 
                            aria-controls="notYetStartedACSurveyTab">
                            AC Survey Not Yet Started 
                        </a>
                    </div>
                </li>
            </ul>
            <div class="collapse multi-collapse container mb-4" 
                id="notYetStartedACSurveyTab">
                @if(count($notStartedACSurveyCommunities) > 0)
                <p>You've got {{$notStartedACSurveyCommunities->count()}} initial communities 
                    that need a visit to kickstart the survey process.
                    <br>
                    <span class="text-warning">
                    If you've already visited the community, please enter the survey details into the platform
                    </span>
                </p> 
                @foreach($notStartedACSurveyCommunities as $notStartedACSurveyCommunity)
                    <ul class="list-group">
                        <li class="d-flex list-group-item" style="margin-top:9px">
                            <span> {{$notStartedACSurveyCommunity->english_name}}  / 
                                {{$notStartedACSurveyCommunity->number_of_household}}
                            </span>   
                        </li>
                    </ul>
                    @endforeach
                @endif
                @if(count($queryCompounds) > 0)
                <p>You've got {{$queryCompounds->count()}} initial compounds 
                    that need a visit to kickstart the survey process.
                </p> 
                @foreach($queryCompounds as $queryCompound)
                    <ul class="list-group">
                        <li class="d-flex list-group-item" style="margin-top:9px">
                            <span> {{$queryCompound->english_name}}  / 
                            </span>   
                        </li>
                    </ul>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Not Yet Completed AC Installation -->
        <div class="card-body pb-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-danger" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-grid"></i>
                        <a data-toggle="collapse" class="text-danger" 
                            href="#notYetCompletedACInstallationTab" 
                            aria-expanded="false" 
                            aria-controls="notYetCompletedACInstallationTab">
                            AC Installation Not Yet Completed
                        </a>
                    </div>
                </li>
            </ul>
            <div class="collapse multi-collapse container mb-4" 
                id="notYetCompletedACInstallationTab">
                @if($totalNotStartedAC > 0)
                    <p>You've got {{$totalNotStartedAC}} communities 
                        / Compounds
                        that need to complete the AC installation process.
                    </p>  
                    @if(count($notStartedACInstallationCommunities) > 0)
                    @foreach($notStartedACInstallationCommunities as $notStartedACInstallationCommunity)
                    <ul class="list-group">
                        <li class="d-flex list-group-item" style="margin-top:9px">
                            <a  type="button" data-bs-toggle="modal"
                                data-bs-target="#NotCompletedHouseholdsCommunity{{$notStartedACInstallationCommunity->id}}">
                                <span> {{$notStartedACInstallationCommunity->english_name}}  -
                                    {{$notStartedACInstallationCommunity->number}} 
                                    <span class="text-info">/ 
                                    {{$notStartedACInstallationCommunity->number_of_households}}
                                    </span>
                                </span> 
                            </a>  
                        </li>
                    </ul>
                    @include('actions.admin.AC.not_completed_community_household')
                    @endforeach
                    @endif
                    @if(count($notStartedACInstallationCompounds) > 0)
                    @foreach($notStartedACInstallationCompounds as $queryCompound)
                    <ul class="list-group">
                        <li class="d-flex list-group-item" style="margin-top:9px">
                            <a  type="button" data-bs-toggle="modal"
                                data-bs-target="#NotCompletedHouseholdsCompound{{$queryCompound->id}}">
                                <span> {{$queryCompound->english_name}}  -
                                    {{$queryCompound->number}} 
                                    <span class="text-info">/ 
                                    {{$queryCompound->number_of_households}}
                                    </span>
                                </span> 
                            </a>  
                        </li>
                    </ul>
                    @include('actions.admin.AC.not_completed_compound_household')
                    @endforeach
                    @endif
                @endif
            </div>
        </div>

        <!-- Not Yet Completed Electricity Room-->
        <div class="card-body pb-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-primary" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-pulse"></i>
                        <a data-toggle="collapse" class="text-primary" 
                            href="#notYetCompletedElectricityRoomTab" 
                            aria-expanded="false" 
                            aria-controls="notYetCompletedElectricityRoomTab">
                            Electricity Room Not Yet Completed 
                        </a>
                    </div>
                </li>
            </ul>
            <div class="collapse multi-collapse container mb-4" 
                id="notYetCompletedElectricityRoomTab">

                @if(count($communitiesElecticityRoomMissing) > 0 ||
                    count($compoundsElecticityRoomMissing) > 0)
                    <p>You've got {{$communitiesElecticityRoomMissing->count()
                            + $compoundsElecticityRoomMissing->count()}} SMG/MG 
                        communities or compounds that need 
                        to complete the electricity room.
                    </p> 
                    @if(count($compoundsElecticityRoomMissing) > 0)
                        @foreach($compoundsElecticityRoomMissing as $compoundsElecticityRoom)
                        <ul class="list-group">
                            <li class="d-flex list-group-item" style="margin-top:9px">
                                <a type="button" data-bs-toggle="modal" 
                                    data-bs-target="#updateElectricityGridCompound{{$compoundsElecticityRoom->id}}">
                                    <span>{{$compoundsElecticityRoom->compound}}</span>   
                                </a> 
                            </li>
                        </ul>
                        @include('actions.admin.AC.room_compound')
                        @endforeach
                    @endif
                    @if(count($communitiesElecticityRoomMissing) > 0)
                        @foreach($communitiesElecticityRoomMissing as $communitiesElecticityRoom)
                        <ul class="list-group">
                            <li class="d-flex list-group-item" style="margin-top:9px">
                                <a type="button" data-bs-toggle="modal" 
                                    data-bs-target="#updateElectricityGrid{{$communitiesElecticityRoom->id}}">
                                    <span>{{$communitiesElecticityRoom->community}}</span>   
                                </a> 
                            </li>
                        </ul>
                        @include('actions.admin.AC.room_community')
                        @endforeach
                    @endif
                @endif
            </div>
        </div>

        <!-- Not Yet Completed Grid-->
        <div class="card-body pb-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-info" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-grid-alt"></i>
                        <a data-toggle="collapse" class="text-info" 
                            href="#notYetCompletedGridTab" 
                            aria-expanded="false" 
                            aria-controls="notYetCompletedGridTab">
                            Grid Not Yet Completed
                        </a>
                    </div>
                </li>
            </ul>
            <div class="collapse multi-collapse container mb-4" 
                id="notYetCompletedGridTab">
                @if(count($communitiesGridMissing) > 0 ||
                    count($compoundsGridMissing) > 0)
                <p>You've got {{$communitiesGridMissing->count()
                        + $compoundsGridMissing->count()}} SMG/MG 
                    communities or compounds that need 
                    to complete the grid.
                </p> 
                    @if(count($compoundsGridMissing) > 0)
                        @foreach($compoundsGridMissing as $compoundsGrid)
                        <ul class="list-group">
                            <li class="d-flex list-group-item" style="margin-top:9px">
                                <a type="button" data-bs-toggle="modal" 
                                    data-bs-target="#updateGridCompound{{$compoundsGrid->id}}">
                                    <span>{{$compoundsGrid->compound}}</span>   
                                </a> 
                            </li>
                        </ul>
                        @include('actions.admin.AC.grid_compound')
                        @endforeach
                    @endif
                    @if(count($communitiesGridMissing) > 0)
                        @foreach($communitiesGridMissing as $communitiesGrid)
                        <ul class="list-group">
                            <li class="d-flex list-group-item" style="margin-top:9px">
                                <a type="button" data-bs-toggle="modal" 
                                    data-bs-target="#updateGridCommunity{{$communitiesGrid->id}}">
                                    <span>{{$communitiesGrid->community}}</span>   
                                </a> 
                            </li>
                        </ul>
                        @include('actions.admin.AC.grid_community')
                        @endforeach
                    @endif
                @endif
            </div>
        </div>

        <!-- Not Yet Completed DC Installations -->
        <div class="card-body pb-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-dark" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-barcode"></i>
                        <a data-toggle="collapse" class="text-dark" 
                            href="#notYetCompletedDCInstallationTab" 
                            aria-expanded="false" 
                            aria-controls="notYetCompletedDCInstallationTab">
                            DC installations Not Yet Completed 
                        </a>
                    </div>
                </li>
            </ul>

            <div class="collapse multi-collapse container mb-4" 
                id="notYetCompletedDCInstallationTab">
                <!-- @if(count($communitiesFbsNotDCInstallations) > 0)
                    You've got 
                    <span class="text-danger">
                        {{$communitiesFbsNotDCInstallations->count()}} FBS
                    </span>   
                    communities that completed AC installations but didn't 
                    complete the DC installation process.
                @foreach($communitiesFbsNotDCInstallations as $communitiesFbs)
                    <ul class="list-group">
                        <li class="d-flex list-group-item" style="margin-top:9px">
                            <span> {{$communitiesFbs->community}}  - 
                                {{$communitiesFbs->number_of_holders}}
                            </span>   
                        </li>
                    </ul>
                @endforeach
                @endif -->

                @if(count($communitiesFbsNotDCInstallations) > 0)
                    You've got 
                    <span class="text-danger">
                        {{$holdersFbsNotDCInstallations->count()}} 
                        holders 
                    </span>  in
                    <span class="text-danger"> 
                        {{$communitiesFbsNotDCInstallations->count()}}
                        FBS communities 
                        </span> 
                        that need to complete the DC installation process.
                @foreach($communitiesFbsNotDCInstallations as $holdersFbs)
                    <ul class="list-group">
                        <li class="d-flex list-group-item" style="margin-top:9px">
                            <a type="button" data-bs-toggle="modal" 
                                data-bs-target="#communitiesFbsNotDCInstallations{{$holdersFbs->id}}">
                                <span> {{$holdersFbs->community}}  - 
                                    {{$holdersFbs->number_of_holders}}
                                </span>   
                            </a>
                        </li>
                    </ul>
                    @include('actions.admin.DC.fbs')
                @endforeach
                @endif
                <br>

                @if(count($communitiesMgSmgNotDCInstallations) > 0)
                    You've got 
                    <span class="text-danger">
                        {{$holdersMgSmgNotDCInstallations->count()}} 
                        holders 
                    </span> in
                    <span class="text-danger"> 
                        {{$communitiesMgSmgNotDCInstallations->count()}}
                        MG/SMG communities 
                    </span> 
                    that need to complete the DC installation process.
                @foreach($communitiesMgSmgNotDCInstallations as $holdersMgSmg)
                    <ul class="list-group">
                        <li class="d-flex list-group-item" style="margin-top:9px">
                            <a type="button" data-bs-toggle="modal" 
                                data-bs-target="#communitiesMgSmgNotDCInstallations{{$holdersMgSmg->id}}">
                                <span> {{$holdersMgSmg->community}}  - 
                                    {{$holdersMgSmg->number_of_holders}}
                                </span>   
                            </a>
                        </li>
                    </ul>
                    @include('actions.admin.DC.grid')
                @endforeach
                @endif

                <!-- @if(count($communitiesMgSmgNotDCInstallations) > 0)
                <a type="button" data-bs-toggle="modal" 
                    data-bs-target="#communitiesMgSmgNotDCInstallations">
                    You've got 
                    <span class="text-danger">
                        {{$communitiesMgSmgNotDCInstallations->count()}} MG/SMG
                    </span>   
                    communities that completed AC installations but didn't 
                    complete the DC installation process.
                </a>
                @endif -->
            </div>
            <!-- 
            <p>
            You've got XX communities or compounds where you haven't 
            completed activating the meters.
            </p> -->
        </div>

        <!-- MISC FBS -->
        <div class="card-body pb-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-primary" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-data"></i>
                        <a data-toggle="collapse" class="text-primary" 
                            href="#miscFbsTab" 
                            aria-expanded="false" 
                            aria-controls="miscFbsTab">
                            MISC FBS -- "Requested Systems"
                        </a>
                    </div>
                </li>
            </ul>
            <div class="collapse multi-collapse container mb-4" 
                id="miscFbsTab">
                @if(count($miscRequested) > 0)
                <p>You've got {{$miscRequested->count()}} MISC systems 
                    that need to begin the installation process.
                </p> 
                @endif 
            </div>
        </div>

        <!-- <div class="card-body pb-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-info" style="font-weight:bold; font-size:16px">
                        <i class="bx bx-group"></i>
                        <span >AC Completed </span>
                    </div>
                </li>
            </ul>
            @if(count($acHouseholds) > 0)
            <p>You've {{$acHouseholds->count()}}
                <a type="button" title="Export AC Households"
                    href="action-item/ac-household/export">
                    AC households 
                </a>
                which are not related to AC Survey "AC Community"
                ,Need to be checked
            </p> 
            @endif
        </div> -->

        <div class="card-body pb-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                    <div class="text-light" style="font-weight:bold; font-size:16px">
                        <form method="POST" enctype='multipart/form-data' 
                            action="{{ route('energy-request.export') }}">
                            @csrf
                            <button class="" type="submit">
                                <i class='fa-solid fa-file-excel'></i>
                                Export Energy Installation Progress Report 
                            </button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
        <div class="timeline-event-time">AC/DC Process</div>
    </div>
</li>