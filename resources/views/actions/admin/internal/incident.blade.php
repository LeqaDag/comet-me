<!-- Action Items for Incidents -->
<li class="timeline-item mb-md-4 mb-5 timeline-item-right">
    <span class="timeline-indicator timeline-indicator-danger" 
        data-aos="zoom-in" data-aos-delay="200">
        <i class="bx bx-error-alt"></i>
    </span>
    <div class="timeline-event card p-0" data-aos="fade-left">
        <div class="card-body pb-0">
            <h6 class="card-title mb-0">
                <span class="align-middle">
                    Incidents 
                    <a data-toggle="collapse" class="text-dark" 
                        href="#mgIncidentStatusDetails" 
                        aria-expanded="false" 
                        aria-controls="mgIncidentStatusDetails">
                        <span class="badge rounded-pill bg-label-danger">MG Systems</span>
                    </a>
                </span>
            </h6>
            <div class="row overflow-hidden collapse multi-collapse container" id="mgIncidentStatusDetails">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>In Progress</span>
                        </div>
                        <div>
                        @if(count($mgIncidents) > 0)
                            @php
                                $modalIncidentDetailsId = 'mgIncidentInProgress';
                                $incidentStatus = 16;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$mgIncidents->where('incident_status_mg_system_id', 16)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.mg_system_details')
                        @endif
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>System Not Repaired</span>
                        </div>
                        <div>
                        @if(count($mgIncidents) > 0)
                            @php
                                $modalIncidentDetailsId = 'mgIncidentSystemNotRepaired';
                                $incidentStatus = 13;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$mgIncidents->where('incident_status_mg_system_id', 13)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.mg_system_details')
                        @endif
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>System Not Replaced</span>
                        </div>
                        <div>
                        @if(count($mgIncidents) > 0)
                            @php
                                $modalIncidentDetailsId = 'mgIncidentSystemNotReplaced';
                                $incidentStatus = 15;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$mgIncidents->where('incident_status_mg_system_id', 15)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.mg_system_details')
                        @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <hr>

        <div class="card-body pb-0">
            <h6 class="card-title mb-0">
                <span class="align-middle">
                    Incidents 
                    <a data-toggle="collapse" class="text-dark" 
                        href="#fbsIncidentStatusDetails" 
                        aria-expanded="false" 
                        aria-controls="fbsIncidentStatusDetails">
                        <span class="badge rounded-pill bg-label-danger">FBS Users</span>
                    </a>
                </span>
            </h6>
            <div class="row overflow-hidden collapse multi-collapse container" id="fbsIncidentStatusDetails">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>Response in progress</span>
                        </div>
                        <div>
                        @if(count($fbsIncidents) > 0)
                            @php
                                $modalFbsIncidentDetailsId = 'fbsIncidentInProgress';
                                $incidentStatus = 7;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalFbsIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$fbsIncidents->where('incident_status_small_infrastructure_id', 7)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.fbs_user_details')
                        @endif
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>Not Repaired</span>
                        </div>
                        <div>
                        @if(count($fbsIncidents) > 0)
                            @php
                                $modalFbsIncidentDetailsId = 'fbsIncidentNotRepaired';
                                $incidentStatus = 11;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalFbsIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$fbsIncidents->where('incident_status_small_infrastructure_id', 11)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.fbs_user_details')
                        @endif
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>Not Retrieved</span>
                        </div>
                        <div>
                        @if(count($fbsIncidents) > 0)
                            @php
                                $modalFbsIncidentDetailsId = 'fbsIncidentNotRetrieved';
                                $incidentStatus = 2;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalFbsIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$fbsIncidents->where('incident_status_small_infrastructure_id', 2)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.fbs_user_details')
                        @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <hr>
        <div class="card-body pb-0">
            <h6 class="card-title mb-0">
                <span class="align-middle">
                    Incidents 
                    <a data-toggle="collapse" class="text-dark" 
                        href="#waterIncidentStatusDetails" 
                        aria-expanded="false" 
                        aria-controls="waterIncidentStatusDetails">
                        <span class="badge rounded-pill bg-label-danger">Water Holders</span>
                    </a>
                </span>
            </h6>
            <div class="row overflow-hidden collapse multi-collapse container" id="waterIncidentStatusDetails">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>In progress</span>
                        </div>
                        <div>
                        @if(count($waterIncidents) > 0)
                            @php
                                $modalWaterIncidentDetailsId = 'waterIncidentNotInProgress';
                                $incidentStatus = 5;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalWaterIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$waterIncidents->where('incident_status_id', 5)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.water_holder_details')
                        @endif
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>Not Repaired</span>
                        </div>
                        <div>
                        @if(count($waterIncidents) > 0)
                            @php
                                $modalWaterIncidentDetailsId = 'waterIncidentNotRepaired';
                                $incidentStatus = 8;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalWaterIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$waterIncidents->where('incident_status_id', 8)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.water_holder_details')
                        @endif
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>Not Retrieved</span>
                        </div>
                        <div>
                        @if(count($waterIncidents) > 0)
                            @php
                                $modalWaterIncidentDetailsId = 'waterIncidentNotRetrieved';
                                $incidentStatus = 1;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalWaterIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$waterIncidents->where('incident_status_id', 1)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.water_holder_details')
                        @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="card-body pb-0">
            <h6 class="card-title mb-0">
                <span class="align-middle">
                    Incidents 
                    <a data-toggle="collapse" class="text-dark" 
                        href="#internetIncidentStatusDetails" 
                        aria-expanded="false" 
                        aria-controls="internetIncidentStatusDetails">
                        <span class="badge rounded-pill bg-label-danger">Internet Network</span>
                    </a>
                </span>
            </h6>
            <div class="row overflow-hidden collapse multi-collapse container" id="internetIncidentStatusDetails">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>In progress</span>
                        </div>
                        <div> 
                        @if(count($networkIncidents) > 0)
                            @php
                                $modalNetworkIncidentDetailsId = 'networkIncidentInProgress';
                                $incidentStatus = 6;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalNetworkIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$networkIncidents->where('internet_incident_status_id', 6)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.network_details')
                        @endif
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>Not Retrieved</span>
                        </div>
                        <div>
                        @if(count($networkIncidents) > 0)
                            @php
                                $modalNetworkIncidentDetailsId = 'networkIncidentNotRetrieved';
                                $incidentStatus = 1;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalNetworkIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$networkIncidents->where('internet_incident_status_id', 1)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.network_details')
                        @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <hr>
        <div class="card-body pb-0">
            <h6 class="card-title mb-0">
                <span class="align-middle">
                    Incidents 
                    <a data-toggle="collapse" class="text-dark" 
                        href="#internetHolderIncidentStatusDetails" 
                        aria-expanded="false" 
                        aria-controls="internetHolderIncidentStatusDetails">
                        <span class="badge rounded-pill bg-label-danger">Internet Holders</span>
                    </a>
                </span>
            </h6>
            <div class="row overflow-hidden collapse multi-collapse container" id="internetHolderIncidentStatusDetails">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>In progress</span>
                        </div>
                        <div>
                        @if(count($internetHolderIncidents) > 0)
                            @php
                                $modalInternetHolderkIncidentDetailsId = 'internetHolderIncidentInProgress';
                                $incidentStatus = 6;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalInternetHolderkIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$internetHolderIncidents->where('internet_incident_status_id', 6)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.internet_holder_details')
                        @endif
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between 
                        align-items-center ps-0 text-warning">
                        <div>
                            <span>Not Retrieved</span>
                        </div>
                        <div>
                        @if(count($internetHolderIncidents) > 0)
                            @php
                                $modalInternetHolderkIncidentDetailsId = 'internetHolderIncidentNotRetrieved';
                                $incidentStatus = 1;
                            @endphp
                            <p>
                                <a type="button" data-bs-toggle="modal" title="View Incidents"
                                    data-bs-target="#{{$modalInternetHolderkIncidentDetailsId}}" class="btn btn-outline-warning">
                                    
                                    {{$internetHolderIncidents->where('internet_incident_status_id', 1)->count()}}
                                </a>
                            </p> 
                            @include('actions.admin.internal.incident.internet_holder_details')
                        @endif
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        <br>
        <div class="timeline-event-time">Incidents</div>
    </div>
</li>
