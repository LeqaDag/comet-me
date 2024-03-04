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
                    <span class="badge rounded-pill bg-label-danger">MG Systems</span>
                </span>
            </h6>
            
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between 
                    align-items-center ps-0 text-warning">
                    <div>
                        <span>In Progress</span>
                    </div>
                    <div>
                    @if(count($mgIncidents) > 0)
                        <span class="text-dark"> 
                            {{$mgIncidents->where('incident_status_mg_system_id', 16)->count()}}
                        </span>
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
                        <span class="text-dark"> 
                            {{$mgIncidents->where('incident_status_mg_system_id', 13)->count()}}
                        </span>
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
                        <span class="text-dark"> 
                            {{$mgIncidents->where('incident_status_mg_system_id', 15)->count()}}
                        </span>
                    @endif
                    </div>
                </li>
            </ul>
        </div>
        <div class="card-body pb-0">
            <h6 class="card-title mb-0">
                <span class="align-middle">
                    Incidents 
                    <span class="badge rounded-pill bg-label-danger">FBS Users</span>
                </span>
            </h6>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between 
                    align-items-center ps-0 text-warning">
                    <div>
                        <span>Response in progress</span>
                    </div>
                    <div>
                    @if(count($fbsIncidents) > 0)
                        <span class="text-dark"> 
                            {{$fbsIncidents->where('incident_status_small_infrastructure_id', 7)->count()}}
                        </span>
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
                        <span class="text-dark"> 
                            {{$fbsIncidents->where('incident_status_small_infrastructure_id', 11)->count()}}
                        </span>
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
                        <span class="text-dark"> 
                            {{$fbsIncidents->where('incident_status_small_infrastructure_id', 2)->count()}}
                        </span>
                    @endif
                    </div>
                </li>
            </ul>
        </div>
        <div class="card-body pb-0">
            <h6 class="card-title mb-0">
                <span class="align-middle">
                    Incidents 
                    <span class="badge rounded-pill bg-label-danger">Water Holders</span>
                </span>
            </h6>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between 
                    align-items-center ps-0 text-warning">
                    <div>
                        <span>In progress</span>
                    </div>
                    <div>
                    @if(count($waterIncidents) > 0)
                        <span class="text-dark"> 
                            {{$waterIncidents->where('incident_status_id', 5)->count()}}
                        </span>
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
                        <span class="text-dark"> 
                            {{$waterIncidents->where('incident_status_id', 8)->count()}}
                        </span>
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
                        <span class="text-dark"> 
                            {{$waterIncidents->where('incident_status_id', 1)->count()}}
                        </span>
                    @endif
                    </div>
                </li>
            </ul>
        </div>

        <div class="card-body pb-0">
            <div class="d-flex flex-wrap mb-4">
                @foreach($users as $user)
                @if($user->user_type_id == 6)
                <div>
                    <div class="avatar avatar-xs me-2">
                    @if($user->image == "")
        
                    @if($user->gender == "male")
                        <img src='/users/profile/male.jpg' alt="Internet Manager Avatar" 
                            class="rounded-circle">
                    @else
                        <img src='/assets/images/female.png' alt="Internet Manager Avatar" 
                            class="rounded-circle">
                    @endif
                    @else
                        <img src="{{url('users/profile/'.$user->image)}}" alt="Avatar" 
                            class="rounded-circle" />
                    @endif
                    </div>
                </div>
                <span>Assigned this task to <strong>{{$user->name}}</strong></span>
                @endif
                @endforeach
            </div> 
            <h6 class="card-title mb-0">
                <span class="align-middle">
                    Incidents 
                    <span class="badge rounded-pill bg-label-danger">Internet Network</span>
                </span>
            </h6>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between 
                    align-items-center ps-0 text-warning">
                    <div>
                        <span>In progress</span>
                    </div>
                    <div>
                    @if(count($networkIncidents) > 0)
                        <span class="text-dark"> 
                            {{$networkIncidents->where('incident_status_id', 6)->count()}}
                        </span>
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
                        <span class="text-dark"> 
                            {{$networkIncidents->where('incident_status_id', 1)->count()}}
                        </span>
                    @endif
                    </div>
                </li>
            </ul>
        </div>
        <div class="card-body pb-0">
            <h6 class="card-title mb-0">
                <span class="align-middle">
                    Incidents 
                    <span class="badge rounded-pill bg-label-danger">Internet Holders</span>
                </span>
            </h6>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between 
                    align-items-center ps-0 text-warning">
                    <div>
                        <span>In progress</span>
                    </div>
                    <div>
                    @if(count($internetHolderIncidents) > 0)
                        <span class="text-dark"> 
                            {{$internetHolderIncidents->where('internet_incident_status_id', 6)->count()}}
                        </span>
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
                        <span class="text-dark"> 
                            {{$internetHolderIncidents->where('internet_incident_status_id', 1)->count()}}
                        </span>
                    @endif
                    </div>
                </li>
            </ul>
        </div>
        <div class="timeline-event-time">Incidents</div>
    </div>
</li>
