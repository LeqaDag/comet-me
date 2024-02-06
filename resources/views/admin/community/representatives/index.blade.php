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
    <div class="card my-2 mx-auto" >
        <div class="card-body">
            <div class="card-header">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Filter By Region</label>
                            <select name="region_id" class="selectpicker form-control" 
                                data-live-search="true" id="filterByRegion">
                                <option disabled selected>Choose one...</option>
                                @foreach($regions as $region)
                                    <option value="{{$region->id}}">{{$region->english_name}}</option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Filter By Sub Region</label>
                            <select name="sub_region_id" class="selectpicker form-control" 
                                data-live-search="true" id="filterBySubRegion">
                                <option disabled selected>Choose one...</option>
                                @foreach($subregions as $subRegion)
                                    <option value="{{$subRegion->id}}">{{$subRegion->english_name}}</option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Filter By Status</label>
                            <select name="status_id" class="selectpicker form-control" 
                                data-live-search="true" id="filterByStatus">
                                <option disabled selected>Choose one...</option>
                                @foreach($communityStatuses as $communityStatus)
                                    <option value="{{$communityStatus->id}}">{{$communityStatus->name}}</option>
                                @endforeach
                            </select> 
                        </fieldset>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-3">
                        <fieldset class="form-group">
                            <label class='col-md-12 control-label'>Clear All Filters</label>
                            <button class="btn btn-dark" id="clearFiltersButton">
                                <i class='fa-solid fa-eraser'></i>
                                Clear Filters
                            </button>
                        </fieldset>
                    </div>
                </div>
            </div>

            @if(Auth::guard('user')->user()->user_type_id == 1 ||
                Auth::guard('user')->user()->user_type_id == 2  )
            <div>
                <button type="button" class="btn btn-success" 
                    data-bs-toggle="modal" data-bs-target="#createCommunityRepresentative">
                    Create New Community Representative	
                </button>
                @include('admin.community.representatives.create')
            </div>
            @endif
            <table id="communityRepresentativesTable" 
                class="table table-striped data-table-community-representatives my-2">
                <thead>
                    <tr>
                        <th class="text-center">Representative</th>
                        <th class="text-center">Community</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Region</th>
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

    var table;
    function DataTableContent() {

        table = $('.data-table-community-representatives').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('representative.index') }}",
                data: function (d) {
                    d.search = $('input[type="search"]').val();
                    d.filter = $('#filterByRegion').val();
                    d.second_filter = $('#filterBySubRegion').val();
                    d.third_filter = $('#filterByStatus').val();
                }
            },
            columns: [
                {data: 'household', name: 'household'},
                {data: 'english_name', name: 'english_name'},
                {data: 'status_name', name: 'status_name'},
                {data: 'name', name: 'name'},
                {data: 'role', name: 'role'},
                {data: 'action'}
            ]
        });
    }

    $(function () {

        DataTableContent();
        
        $('#filterByRegion').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterBySubRegion').on('change', function() {
            table.ajax.reload(); 
        });
        $('#filterByStatus').on('change', function() {
            table.ajax.reload(); 
        });

        // Clear Filter
        $('#clearFiltersButton').on('click', function() {

            $('.selectpicker').prop('selectedIndex', 0);
            $('.selectpicker').selectpicker('refresh');
            if ($.fn.DataTable.isDataTable('.data-table-community-representatives')) {
                $('.data-table-community-representatives').DataTable().destroy();
            }
            DataTableContent();
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
