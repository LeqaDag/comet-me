@php
  $pricingModal = true;
@endphp

@extends('layouts/layoutMaster')

@section('title', 'community representatives')

@include('layouts.all')

@section('content')

<h4 class="py-3 breadcrumb-wrapper mb-4">
  <span class="text-muted fw-light">All </span> community representatives
</h4>

@if(session()->has('message'))
    <div class="row">
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    </div>
@endif

@include('admin.community.representatives.details')
@include('admin.community.representatives.edit')

<div class="container">
    <div class="card my-2">
        <div class="card-body">
            <div class="card-header">
               
            </div>

            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createCommunityRepresentative">
                    Create New Community Representative	
                </button>
                @include('admin.community.representatives.create')
            </div>
            <table id="communityRepresentativesTable" 
                class="table table-striped data-table-community-representatives my-2">
                <thead>
                    <tr>
                        <th class="text-center">Community</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Representative</th>
                        <th class="text-center">Role</th>
                        <th class="text-center">Options</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        var table = $('.data-table-community-representatives').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('representative.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                }
            },
            columns: [
                {data: 'english_name', name: 'english_name'},
                {data: 'status_name', name: 'status_name'},
                {data: 'name', name: 'name'},
                {data: 'household', name: 'household'},
                {data: 'role', name: 'role'},
                {data: 'action'}
            ]
        });

        // View record details
        $('#communityRepresentativesTable').on('click', '.detailsRepresentativeButton',function() {
            var id = $(this).data('id');
        
            // AJAX request
            $.ajax({
                url: 'representative/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    $('#communityRepresentativeModalTitle').html(" ");
                    $('#communityRepresentative').html(" ");
                    $('#arabicNameCommunity').html(" ");
                    $('#englishNameRegion').html(" ");

                    $('#householdRepresentative').html(" ");
                    $('#householdPhone').html(" ");
                    $('#roleRepresentative').html(" ");
                    $('#statusCommunity').html(" ");

                    $('#communityRepresentativeModalTitle').html(response.response['household'].english_name);
                    $('#communityRepresentative').html(response.response['community'].english_name);
                    $('#householdRepresentative').html(response.response['household'].english_name);
                    $('#householdPhone').html(response.response['household'].phone_number);
                    $('#englishNameRegion').html(response.response['region'].english_name);
                    $('#roleRepresentative').html(response.response['role'].role);
                    $('#statusCommunity').html(response.response['status'].name);
                }
            });
        });

        // Update record
        $('#communityRepresentativesTable').on('click', '.updateRepresentative', function() {
            id = $(this).data('id');

            // AJAX request
            $.ajax({
                url: 'representative/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    $(".householdRepresentative").html(response.response['household'].english_name);
                    
                    $('#selectedRole').html(response.response['role'].role);
                    $('.selectedHousehold').append(response.html);
                }
            });
        });

        var phone = 0, household_id = 0, community_role_id = 0;

        $('#saveRepresentativeButton').on('click', function() {
                        
            phone = $('#phoneNumber').val();
            community_role_id = $('#communityRole').val();
            household_id = $('#selectedHousehold').val();

            console.log(phone);
            console.log(community_role_id);
            console.log(household_id);
            
            $.ajax({
                url: 'representative/edit_representative/' + id,
                type: 'get',
                data: {
                    id: id,
                    phone: phone,
                    community_role_id: community_role_id,
                    household_id: household_id
                }, 
                dataType: 'json',
                success: function(response) {

                    $('#updateRepresentativeModal').modal('toggle');
                    $('#closeRepresentativeUpdate').click ();

                    if(response == 1) {
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Representative Updated Successfully!',
                            showDenyButton: false,
                            showCancelButton: false,
                            confirmButtonText: 'Okay!'
                        }).then((result) => {

                            $('#communityRepresentativesTable').DataTable().draw();
                        });
                    }
                }
            });
        });

        // View record update page
        $('#communityRepresentativesTable').on('click', '.updateCommunity', function() {
            var id = $(this).data('id');
            var url = window.location.href; 
            url = url +'/'+ id +'/edit';
            
            // AJAX request
            $.ajax({
                url: 'community/' + id + '/editpage',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    window.open(url, "_self"); 
                }
            });
        });

        // delete community
        $('#communityRepresentativesTable').on('click', '.deleteRepresentative',function() {
            var id = $(this).data('id');

            Swal.fire({
                icon: 'warning',
                title: 'Are you sure you want to delete this community representative?',
                showDenyButton: true,
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('deleteCommunityRepresentative') }}",
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
                                    $('#communityRepresentativesTable').DataTable().draw();
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
</script>
@endsection
