@extends('layouts/layoutMaster')

@section('title', 'Action Items')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-2">
    <a class="text-primary">
        <span class="text-muted fw-light">
            Hi
            {{Auth::guard('user')->user()->name}} 
        /</span> Your Action Items
    </a>
</h4>

<div class="container mb-4">
    @if(count($groupedActionItems) > 0)
        @foreach($groupedActionItems as $userId => $userActionItems)
            @php
                $currentUserTypeId = Auth::guard('user')->user()->user_type_id;
                $userTypeId = $userActionItems->first()->User->user_type_id;
            @endphp

            @if(($currentUserTypeId == 3 && $userTypeId == 3) || 
                ($currentUserTypeId == 4 && $userTypeId == 4) || 
                ($currentUserTypeId == 6 && $userTypeId == 6) )
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
                            Your <strong>Action Items</strong>
                        </a>
                    </div>

                    <div id="userCollapse{{$userId}}" class="collapse multi-collapse timeline-event p-0 mb-4" 
                        data-aos="fade-right">
                        <h5>
                            <button type="button" class="btn btn-success btn-sm" 
                                data-bs-toggle="modal" data-bs-target="#createUserActionItem">
                                <i class="bx bx-plus"></i>
                            </button>
                            @include('actions.users.create_task')
                        </h5>
                        <div class="pb-0">
                            <table id="actionItemTable" class="dt-advanced-search table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Timeline</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody id="actionItemsBody">
                                    @foreach($userActionItems as $actionItem)
                                        <tr id="actionItemRow_{{ $actionItem->id }}">
                                            <td>{{$actionItem->task}}</td>
                                            <td style="width:200px">
                                                <select name="action_status_id" class="selectpicker form-control action-status" 
                                                    data-live-search="true" data-action-item-id="{{$actionItem->id}}"
                                                    >
                                                    <option disabled selected>{{$actionItem->ActionStatus->status}}</option>
                                                    @foreach($actionStatuses as $actionStatus)
                                                        <option value="{{$actionStatus->id}}">
                                                            {{$actionStatus->status}}
                                                        </option>
                                                    @endforeach
                                                </select>
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
                                            <td>
                                                <input type="text" value="{{ $actionItem->notes }}"
                                                    class="action-notes form-control" 
                                                    data-action-item-id="{{$actionItem->id}}" >
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pb-0">
                            @php
                                $userTypeId = Auth::guard('user')->user()->user_type_id;
                            @endphp

                            @if($userTypeId == 3) 
                            <div class="container mb-4">
                                @php
                                    $userTypeId = 3;
                                    $collapseId = 'energyWorkPlans';
                                    $includeView = 'actions.admin.installation.ac_dc_process';
                                    $flag = 0;
                                @endphp
                                @include('actions.admin.user_tasks')
                            </div>
                            @else @if($userTypeId == 4) 
                            <div class="container mb-4">
                                @php
                                    $userTypeId = 4;
                                    $collapseId = 'maintenanceEnergyWorkPlans';
                                    $includeView = 'actions.admin.energy.maintenance';
                                    $flag = 0;
                                @endphp
                                @include('actions.admin.user_tasks')
                            </div>
                            @else @if($userTypeId == 6) 
                            <div class="container mb-4">
                                @php
                                    $userTypeId = 6;
                                    $collapseId = 'internetWorkPlans';
                                    $includeView = 'actions.admin.internet.index';
                                    $flag = 0;
                                @endphp
                                @include('actions.admin.user_tasks')
                            </div>
                            @endif
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>



<script>
    $(document).ready(function () {

        $('.action-status').change(function () {

            var actionItemId = $(this).data('action-item-id');
            var newStatusId = $(this).val();

            // Send the update to the server using AJAX
            $.ajax({
                type: 'POST',
                url: 'action-item/update-action-status', // Change this URL to your actual route
                data: {
                    actionItemId: actionItemId,
                    newStatusId: newStatusId,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {

                    if(response.status != 4) {
                        Swal.fire({
                            icon: 'success',
                            title: 'The status updated successfully!',
                            showDenyButton: false,
                            showCancelButton: false,
                            confirmButtonText: 'Okay!'
                        }).then((result) => {
                            
                        }); 
                    } else if(response.status == 4) {

                        Swal.fire({
                            title: "Great Job!",
                            width: 600,
                            padding: "3em",
                            color: "#716add",
                            html: `
                                <div style="
                                    background: rgba(0,0,123,0.4) url('/images/well.gif') left top repeat;
                                    width: 100%;
                                    height: 100%;
                                    position: fixed;
                                    top: 0;
                                    left: 0;
                                    z-index: 9999;
                                "></div>
                                </div>
                            `,
                        });

                        $('#actionItemRow_' + actionItemId).remove();
                    }
                },
                error: function (error) {
                    // Handle error, if needed
                    console.error(error);
                }
            });
        });

        $('.action-notes').change(function () {

            var actionItemId = $(this).data('action-item-id');
            var newNotes = $(this).val();

            // Send the update to the server using AJAX
            $.ajax({
                type: 'POST',
                url: 'action-item/update-action-note', // Change this URL to your actual route
                data: {
                    actionItemId: actionItemId,
                    newNotes: newNotes,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'The notes updated successfully!',
                        showDenyButton: false,
                        showCancelButton: false,
                        confirmButtonText: 'Okay!'
                    }).then((result) => {
                        
                    });
                },
                error: function (error) {
                    // Handle error, if needed
                    console.error(error);
                }
            });
        });
    });
</script>

@endsection
