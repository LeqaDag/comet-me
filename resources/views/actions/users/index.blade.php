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
                    
                        @include('actions.users.action')
                    </div>

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
                            href="#userCollapsePlatfrom{{$userId}}" 
                            aria-expanded="false" 
                            aria-controls="userCollapsePlatfrom{{$userId}}">
                            Your <strong>Platform Tasks</strong>
                        </a>
                    </div>

                    <div id="userCollapsePlatfrom{{$userId}}" class="collapse multi-collapse timeline-event p-0 mb-4" 
                        data-aos="fade-right">
                    
                       
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
@include('actions.users.show')

<script>
    var table;
    function DataTableContent() {

        table = $('.data-table-action-items').DataTable({
            
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('action-item-user.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.status_filter = $('#filterByStatus').val();
                    d.priority_filter = $('#filterByPriority').val();
                    d.start_date_filter = $('#filterByStartDate').val();
                    d.end_date_filter = $('#filterByEndDate').val();
                }
            },
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [1,2,3,4,5] // Column index which needs to export
                    }
                },
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: [0,5] // Column index which needs to export
                    }
                },
                {
                    extend: 'excel',
                }
            ],
            columns: [
                {data: 'task', name: 'task'},
                {data: 'statusLabel'},
                {data: 'priorityLabel'},
                {data: 'action'}
            ]
            
        });
    }

    $(function () {

        DataTableContent();
        
        $('#filterByStatus').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByPriority').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByStartDate').on('input', function() {
            table.ajax.reload(); 
        });
        $('#filterByEndDate').on('input', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-action-items')) {
                $('.data-table-action-items').DataTable().destroy();
            }
            $('#filterByStartDate').val(' ');
            $('#filterByEndDate').val(' ');

            DataTableContent();
        });

        // View record details
        $('#actionItemUserTable').on('click', '.detailsUserActionItemButton',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'work-plan/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    $('#actionItemUserModalTitle').html(" ");

                    $('#actionOwner').html(" ");
                    $('#ownerRole').html(" ");
                    $('#actionStatus').html(" ");
                    $('#actionPriority').html(" ");
                    $('#actionDate').html(" ");
                    $('#actionDueDate').html(" ");
                    $('#actionNotes').html(" ");

                    $('#actionItemUserModalTitle').html(response['actionItem'].task);
                    $('#actionOwner').html(response['user'].name);
                    $('#ownerRole').html(response['userType'].name);
                    $('#actionStatus').html(response['status'].status);
                    $('#actionPriority').html(response['priority'].name);
                    $('#actionDate').html(response['actionItem'].date);
                    $('#actionDueDate').html(response['actionItem'].due_date);
                    $('#actionNotes').html(response['actionItem'].notes);
                }
            });
        });

        // View record photos
        $('#actionItemUserTable').on('click', '.updateUserActionItem',function() {
            var id = $(this).data('id');
            var url = window.location.href; 
           
            url = url +'/'+ id +'/edit';
            window.open(url, "_self"); 
        });

        // Delete record
        $('#actionItemUserTable').on('click', '.deleteUserActionItem',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this Action Item?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {

                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteUserActionItem') }}",
                        type: 'get',
                        data: {id: id},
                        success: function(response) {
                            if(response.success == 1) {

                                Swal.fire({
                                    icon: 'success',
                                    title: response.msg,
                                    showDenyButton: false,
                                    showCancelButton: false,
                                    confirmButtonText: 'Okay!'
                                }).then((result) => {
                                    $('#actionItemUserTable').DataTable().draw();
                                });
                            } else {

                                alert("Invalid ID.");
                            }
                        }
                    });
                } else if (result.isDenied) {

                    Swal.fire('Changes are not saved', '', 'info')
                }
            });
        });
    });


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

        $('.action-date-from').change(function () {

            var actionItemId = $(this).data('action-item-id');
            var dateFrom = $(this).val();

            $.ajax({
                type: 'POST',
                url: 'action-item/update-action-date-from',
                data: {
                    actionItemId: actionItemId,
                    dateFrom: dateFrom,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'The Date updated successfully!',
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

        $('.action-due-date').change(function () {

            var actionItemId = $(this).data('action-item-id');
            var dateTo = $(this).val();

            $.ajax({
                type: 'POST',
                url: 'action-item/update-action-date-to',
                data: {
                    actionItemId: actionItemId,
                    dateTo: dateTo,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'The Due Date updated successfully!',
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
