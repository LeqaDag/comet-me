@extends('layouts/layoutMaster')

@section('title', 'Action Items')

@include('layouts.all')

@section('content')
<h4 class="py-3 breadcrumb-wrapper mb-2">
    <a class="text-primary">
        <span class="text-muted fw-light">Teams /</span> Action Items
    </a>
</h4>

<div class="container mb-4">
    @if(count($groupedActionItems) > 0)
        @foreach($groupedActionItems as $userId => $userActionItems)
            <div class="user-tasks">
                <div class="d-flex flex-wrap mb-4">
                    <div>
                        <div class="avatar avatar-xs me-2">
                            @if($userActionItems->first()->User->image == "")
                                @if($userActionItems->first()->User->gender == "male")
                                    <img src="{{url('users/profile/male.png')}}" class="rounded-circle">
                                @else
                                    <img src="{{url('users/profile/female.png')}}" class="rounded-circle">
                                @endif
                            @else
                                <img src="{{url('users/profile/'.$userActionItems->first()->User->image)}}" alt="Avatar" class="rounded-circle" />
                            @endif
                        </div>
                    </div> 
                    <a data-toggle="collapse" class="text-dark" 
                        href="#userCollapse{{$userId}}" 
                        aria-expanded="false" 
                        aria-controls="userCollapse{{$userId}}">
                        Action Items for <strong>{{$userActionItems->first()->User->name}}</strong>
                    </a>
                </div>

                <div id="userCollapse{{$userId}}" class="collapse multi-collapse timeline-event p-0 mb-4" 
                    data-aos="fade-right">
                    <div class="pb-0">
                        <table class="dt-advanced-search table table-bordered">
                            <thead>
                                <tr>
                                    <th>Task</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Timeline</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userActionItems as $actionItem)
                                    <tr>
                                        <td>{{$actionItem->task}}</td>
                                        <td contenteditable="true">
                                            @if($actionItem->ActionStatus->id == 1)
                                            <span class='badge rounded-pill bg-label-info'>
                                                {{$actionItem->ActionStatus->status}}
                                            </span>
                                            @else @if($actionItem->ActionStatus->id == 2)
                                            <span class='badge rounded-pill bg-label-warning'>
                                                {{$actionItem->ActionStatus->status}}
                                            </span>
                                            @else @if($actionItem->ActionStatus->id == 3)
                                            <span class='badge rounded-pill bg-label-danger'>
                                                {{$actionItem->ActionStatus->status}}
                                            </span>
                                            @else @if($actionItem->ActionStatus->id == 4)
                                            <span class='badge rounded-pill bg-label-success'>
                                                {{$actionItem->ActionStatus->status}}
                                            </span>
                                            @endif
                                            @endif
                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if($actionItem->ActionPriority->id == 1)
                                            <span class='badge bg-primary'>
                                                {{$actionItem->ActionPriority->name}}
                                            </span>
                                            @else @if($actionItem->ActionPriority->id == 2)
                                            <span class='badge bg-warning text-dark'>
                                                {{$actionItem->ActionPriority->name}}
                                            </span>
                                            @else @if($actionItem->ActionPriority->id == 3)
                                            <span class='badge bg-danger'>
                                                {{$actionItem->ActionPriority->name}}
                                            </span>
                                            @endif
                                            @endif
                                            @endif
                                        </td>
                                        <td>
                                            {{ $actionItem->date }} 
                                            <strong>to </strong>
                                            {{ $actionItem->due_date }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<hr>

<h4 class="py-3 breadcrumb-wrapper mb-2">
    <a data-toggle="collapse" class="text-primary" 
        href="#actionPlanTab" 
        aria-expanded="false" 
        aria-controls="actionPlanTab">
        <span class="text-muted fw-light">Projects /</span> Work Plan Progress
    </a>
</h4>

<div class="container mb-4">
    @php 
        $userTypeId = 3;
        $collapseId = 'energyWorkPlans';
        $includeView = 'actions.admin.installation.ac_dc_process';
        $flag = 1;
    @endphp
    @include('actions.admin.user_tasks')
</div>

<div class="container mb-4">
    @php
        $userTypeId = 4;
        $collapseId = 'maintenanceEnergyWorkPlans';
        $includeView = 'actions.admin.energy.maintenance';
    @endphp
    @include('actions.admin.user_tasks')
</div>

<hr>

<h4 class="py-3 breadcrumb-wrapper mb-2">
    <a data-toggle="collapse" class="text-primary" 
        href="#actionPlanTab" 
        aria-expanded="false" 
        aria-controls="actionPlanTab">
        <span class="text-muted fw-light">Platform /</span> Internal
    </a>
</h4>

<div class="container mb-4">
    @php
        $userTypeId = 6;
        $collapseId = 'internetWorkPlans';
        $includeView = 'actions.admin.internet.index';
    @endphp
    @include('actions.admin.user_tasks')
</div>

<div class="container mb-4">
    <div class="user-tasks">
        <div class="d-flex flex-wrap mb-4">
            @foreach($users as $user)
            @if($user->user_type_id == 1 && $user->name == "Tamar Cohen")
            <div>
                <div class="avatar avatar-xs me-2">
                    @if($user->image == "")
                        @if($user->gender == "male")
                            <img src="{{url('users/profile/male.png')}}" class="rounded-circle">
                        @else
                            <img src="{{url('users/profile/female.png')}}" class="rounded-circle">
                        @endif
                    @else
                        <img src="{{url('users/profile/'.$user->image)}}" alt="Avatar" class="rounded-circle" />
                    @endif
                </div>
            </div> 
            <a data-toggle="collapse" class="text-dark" 
                href="#incidentsDonorPlans" 
                aria-expanded="false" 
                aria-controls="incidentsDonorPlans">
                Assigned this task to <strong>{{$user->name}}</strong>
            </a>
            @endif
            @endforeach
        </div>

        <div id="incidentsDonorPlans" data-aos="fade-right"
            class="collapse multi-collapse timeline-event p-0 mb-4">
            <div class="row overflow-hidden container mb-4" >
                <div class="col-12">
                    <ul class="timeline timeline-center mt-5">
                        <!-- Action Items for incident -->
                        @include('actions.admin.internal.incident')
                        <!-- Action Items for donor -->
                        @include('actions.admin.internal.donor')
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-4">
    <div class="user-tasks">
        <div class="d-flex flex-wrap mb-4">
            @foreach($users as $user)
            @if($user->user_type_id == 1 && $user->name == "Leqa Daghameen")
            <div>
                <div class="avatar avatar-xs me-2">
                    @if($user->image == "")
                        @if($user->gender == "male")
                            <img src="{{url('users/profile/male.png')}}" class="rounded-circle">
                        @else
                            <img src="{{url('users/profile/female.png')}}" class="rounded-circle">
                        @endif
                    @else
                        <img src="{{url('users/profile/'.$user->image)}}" alt="Avatar" class="rounded-circle" />
                    @endif
                </div>
            </div> 
            <a data-toggle="collapse" class="text-dark" 
                href="#internalWorkPlans" 
                aria-expanded="false" 
                aria-controls="internalWorkPlans">
                Assigned this task to <strong>{{$user->name}}</strong>
            </a>
            @endif
            @endforeach
        </div>

        <div id="internalWorkPlans" data-aos="fade-right"
            class="collapse multi-collapse timeline-event p-0 mb-4">
            <div class="row overflow-hidden container mb-4" >
                <div class="col-12">
                    <ul class="timeline timeline-center mt-5">
                        <!-- Action Items for internal -->
                        @include('actions.admin.internal.index')
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row overflow-hidden collapse multi-collapse container mb-4" id="actionPlanTab">
    <div class="col-12">
        <ul class="timeline timeline-center mt-5">
            
            <!-- Action Items for AC/DC/MISC -->
            @include('actions.admin.installation.ac_dc_process')
            <!-- Action Items for maintenance -->
            @include('actions.admin.energy.maintenance')

            <!-- Action Items for internet -->
            @include('actions.admin.internet.index')

            <!-- Action Items for internal -->
            @include('actions.admin.internal.index')
          
            <!-- Action Items for adding energy system for In Progress
            <li class="timeline-item mb-md-4 mb-5 timeline-item-right">
                <span class="timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                    <i class="bx bx-reset"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header border-0 d-flex justify-content-between">
                        <h6 class="card-title mb-0">In Progress</h6>
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex flex-wrap mb-4">
                            @foreach($users as $user)
                            @if($user->user_type_id == 3)
                            <div>
                                <div class="avatar avatar-xs me-2">
                                @if($user->image == "")
                    
                                @if($user->gender == "male")
                                    <img src='/users/profile/male.jpg'
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png'
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
                         <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center ps-0">
                                <div>
                                    <i class="text-success bx bx-home"></i>
                                    <span>AC Communities</span>
                                </div>
                                <div>
                                    <a type="button" data-bs-toggle="modal" 
                                        data-bs-target="#communityAC">
                                        <i class='bx bx-message-alt-detail'></i>
                                    </a>
                                </div>
                                @include('employee.community.service.ac_survey')
                            </li>
                        </ul> 
                        @if(count($inProgressHouseholdsAcCommunity) > 0)
                        <p>You've {{$inProgressHouseholdsAcCommunity->count()}}
                            <a type="button" title="Export AC Households"
                                href="action-item/in-progress-household/export">
                                In-Progress households 
                            </a>
                            Need an AC Installation, Follow up!
                        </p> 
                        @endif
                    </div>
                    <div class="timeline-event-time">In Progress</div>
                </div>
            </li> -->

            <!-- Action Items for adding energy details -->
            <!-- <li class="timeline-item mb-md-4 mb-5 timeline-item-right">
                <span class="timeline-indicator timeline-indicator-warning" data-aos="zoom-in" data-aos-delay="200">
                    <i class="bx bx-bulb"></i>
                </span>
                <div class="timeline-event card p-0" data-aos="fade-right">
                    <div class="card-header border-0 d-flex justify-content-between">
                        <h6 class="card-title mb-0">Energy Users</h6>
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex flex-wrap mb-4">
                            @foreach($users as $user)
                            @if($user->user_type_id == 4)
                            <div>
                                <div class="avatar avatar-xs me-2">
                                @if($user->image == "")
                    
                                @if($user->gender == "male")
                                    <img src='/users/profile/male.jpg'
                                        class="rounded-circle">
                                @else
                                    <img src='/assets/images/female.png'
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
                        <p>
                        @if(count($newEnergyUsers) > 0)
                            <p>You've {{$newEnergyUsers->count()}}
                                <a title="Export AC Households"
                                    href="all-meter" target="_blank">
                                    New Energy Holders
                                </a>
                                need to fill out meter number, daily limit and other details.
                            </p> 
                        @endif
                        </p>
                    </div>
                    <div class="timeline-event-time">Energy Service</div>
                </div>
            </li> -->
        </ul>
    </div>
</div>

<script>
    // View record photos
    $(document).on('click', '.viewMissingEnergyUserDonors', function() {

        community_id = $(this).data("id");

        $.ajax({
            url: "missing/donors/" + community_id,
            type: 'get',
            success: function(response) {
                //$('#missingEnergyUserDonorsModel').modal('toggle');
                $('#missingEnergyUserDonorsModelTitle').html(response.community.english_name);
                $('#missingEnergyUserDonorsContent').find('tbody').html('');
                response.html.forEach(refill_table);
                function refill_table(item, index){
                    $('#missingEnergyUserDonorsContent').find('tbody').append('<tr><td>'+item.holder_name+'</td><td>'+item.name+'</td><td>'+item.status+'</td></tr>');
                }

                $('#missingEnergyUserDonorsModel').modal('show');
            }
        });
    });
</script>
@endsection
